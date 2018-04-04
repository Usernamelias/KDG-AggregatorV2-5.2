<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use App\TimeEntry;
use App\Project;
use App\Task;
use Carbon\Carbon;
use App\Http\Controllers\UtilityController;
use App\CustomClasses\ZohoRequests;
use GuzzleHttp\Client;

/**
 * This class is for the creation, deletion, or editing of a time entry.
 *
 * @author Elias Falconi
 */
class TimeEntryController extends Controller
{
  private $request;

  /**
   * Method to show the workDonePage view.
   * @return view
   */
  public function showWorkDonePage(Request $request){
    UtilityController::startSession();

    $singleEntriesTableHeadline = "Today's Entries as Entered";
    $aggregatedEntriesTableHeadline = "Today's Entries Aggregated by Project";
    $entryDate = $request->input('entryDate', null);
    $userZohoID = Auth::user()->zoho_id;

    $allProjects = Project::whereHas('usersProjectsEnabled', function($p){
      $p->where('zoho_id', Auth::user()->zoho_id);})->orderBy('name')->get();

    $activeProjects = Project::whereHas('tasks', function($q){ $q->whereHas('users', function($p){
                    $p->where('zoho_id', Auth::user()->zoho_id);});})->orderBy('name')->get();

    $allTasks = Task::whereHas('users', function($q){ $q->where('zoho_id', Auth::user()->zoho_id);
                })->orderBy('name')->get();
    $aggregatedTotals = 0;
    $durationTotal = 0;

    if($entryDate == null || $request->entryDate == Carbon::now('America/New_York')->format('m-d-Y')){
      if($entryDate != null){
        $entryDate = Carbon::createFromFormat('m-d-Y', $entryDate)->format('Y-m-d');
      }else{
        $entryDate = Carbon::now('America/New_York')->format('Y-m-d');
      }
    }else{
      $entryDate = Carbon::createFromFormat('m-d-Y', $entryDate)->format('Y-m-d');
      $singleEntriesTableHeadline = "Entries Entered on ".$request->entryDate;
      $aggregatedEntriesTableHeadline = "Entries Aggregated by Project";
    }

    $allEntries = TimeEntry::whereDate('created_at', '=', $entryDate)->where('user_id', '=', Auth::user()->id)->orderBy('start_time', 'DESC')->get();
    $aggregatedEntries = TimeEntry::selectRaw('sum(time_entries.duration) as total, group_concat(time_entries.description separator "; ") as concatDescription, time_entries.project_name, time_entries.task, time_entries.billable, time_entries.user_id')
    ->whereDate('created_at', '=', $entryDate)
    ->where('user_id', '=', Auth::user()->id)
    ->groupBy('time_entries.project_name', 'time_entries.task', 'time_entries.billable')
    ->get();

    $entryDate = Carbon::createFromFormat('Y-m-d', $entryDate)->format('m-d-Y');

    for($i = 0; $i < sizeof($allEntries); $i++){
      $durationTotal = $durationTotal + $allEntries[$i]['duration'];
    }

    for($i = 0; $i < sizeof($aggregatedEntries); $i++){
      $aggregatedTotals = $aggregatedTotals + $aggregatedEntries[$i]['total'];
    }

    foreach($aggregatedEntries as $aggregatedEntry){
      $aggregatedEntry['total'] = UtilityController::makeDurationDisplayReady($aggregatedEntry['total']);
    }

    foreach($allEntries as $allEntry){
      $allEntry['duration'] = UtilityController::makeDurationDisplayReady($allEntry['duration']);
    }

    return view('pages.workDone')->with([
      'allEntries' => $allEntries,
      'aggregatedEntries' => $aggregatedEntries,
      'aggregatedTotals' => UtilityController::makeDurationDisplayReady($aggregatedTotals),
      'durationTotal' => UtilityController::makeDurationDisplayReady($durationTotal),
      'allProjects' => $allProjects,
      'allTasks' => $allTasks,
      'singleEntriesTableHeadline' => $singleEntriesTableHeadline,
      'aggregatedEntriesTableHeadline' => $aggregatedEntriesTableHeadline,
      'activeProjects' => $activeProjects,
      'entryDate' => $entryDate
    ]);
  }

  /**
   * Save new time entry after submission.
   * @param Request object
   * @return redirect
   */
  public function saveTimeEntry(Request $request){
    $this->setRequest($request);
    $userID = Auth::user()->id;
    $total = 0;
    $totalWithCurrentEntry = 0;
    //Establishing rules for validation
    $rules = [
      'project_name' => 'required|max:75',
      'task' => 'required_without:writein',
      'writein' => 'required_without:task|max:75',
      'end_time' => 'required_without:duration',
      'duration' => array('required_without_all:start_time,end_time'),
      'billable' => 'required|boolean',
      'description' => 'required',
    ];

    //If duration is not null, push another rule for duration that only allows
    //a certain string of characters. Regex taken from Zoho.
    if($request->duration !== null){
      $rules['duration'] = array('regex:/^\d{0,2}[\:\.]{1}\d{1,4}$|^\d{1,2}$/');
    }

    /**
     * If the start_time and end_time fields are not null but duration is, then reformat start
     * and end time, and include a validation rule for start_time. If start_time, end_time,
     * and duration are all not null, then include a OneOrTheOther rule for duration. If either
     * start_time or end_time is null, but the other isn't, then include a rule for start_time.
     * If none of these ifs apply, then include a validation rule for start_time.
     */
    if($request->start_time != null && $request->end_time != null && $request->duration == null){
      $request->start_time = $request->start_time;
      $request->end_time = $request->end_time;
      $rules['start_time'] = 'required_without:duration|before:end_time';
    }elseif($request->start_time != null && $request->end_time != null && $request->duration !== null){
      $rules['duration'] = 'one_or_the_other';
    }elseif(($request->start_time != null && $request->end_time == null) || ($request->start_time == null && $request->end_time != null)){
      $rules['start_time'] = 'required_without:duration|before:end_time';
    }else{
      $rules['start_time'] = 'required_without:duration';
    }

    //custom messages for failed validation
    $messages = [
      'start_time.required_without' => 'Required if not entering duration.',
      'end_time.required_without' => 'Required if not entering duration.',
      'task.required_without' => 'Required if not writing in task.',
      'writein.required_without' => 'Required if not selecting a task.',
      'duration.required_without_all' => 'Duration is required if not entering start and end time.',
      'start_time.before' => 'The start time must be before the end time.',
    ];

    //passing my rules and messages over to the validator
    $this->validate(request(), $rules, $messages);

    /**Beginning of new time entry creation in TimeEntry table */
    $timeEntry = new TimeEntry();
    $timeEntry->project_name = $request->project_name;

    if($request->task != null && $request->writein == null){
      $timeEntry->task = $request->task;
    }
    elseif($request->task == null && $request->writein != null){
      $timeEntry->task = $request->writein;
    }
    elseif($request->task != null && $request->writein != null){
      $timeEntry->task = $request->task;
    }

    if($request->start_time != null && $request->end_time != null && $request->duration == null){
      $timeEntry->start_time = $request->start_time;
      $timeEntry->end_time = $request->end_time;
      $timeEntry->duration = round((strtotime($request->end_time) - strtotime($request->start_time)) / 60,2);
    }else{
      $timeEntry->duration = UtilityController::durationToTotalMinutes($request->duration);
      $timeEntry->start_time = null;
      $timeEntry->end_time = null;
    }

    $timeEntry->billable = $request->billable;
    $timeEntry->description = $request->description;
    $timeEntry->user_id = $userID;

    $timeEntry->save();
    /**End of new time entry creation in TimeEntry table */

    return redirect('/work-done');
  }

  /**
   * Delete time entry from table
   * @param Request object
   * @return redirect
   */
  public function deleteTimeEntry(){

    $timeEntry = TimeEntry::find(request()->get('id'));
    $timeEntry->delete();

    return redirect('/work-done');
  }

  /**
   * Edit time entry in table. Similar to saveTimeEntry method.
   * @param Request object
   * @return redirect
   */
  public function editTimeEntry(Request $request){
    $this->setRequest($request);
    $rules = [
      'project_name2' => 'required|max:75',
      'task2' => 'required_without:writein2',
      'writein2' => 'required_without:task2|max:75',
      'end_time2' => 'required_without:duration2',
      'duration2' => array('required_without_all:start_time2,end_time2'),
      'billable2' => 'required|boolean',
      'description2' => 'required',
    ];

    //If duration is not null, push another rule for duration that only allows
    //a certain string of characters. Regex taken from Zoho.
    if($request->duration2 !== null){
      $rules['duration2'] = array('regex:/^\d{0,2}[\:\.]{1}\d{1,4}$|^\d{1,2}$/');
    }

    /**
     * If the start_time and end_time fields are not null but duration is, then reformat start
     * and end time, and include a validation rule for start_time. If start_time, end_time,
     * and duration are all not null, then include a OneOrTheOther rule for duration. If either
     * start_time or end_time is null, but the other isn't, then include a rule for start_time.
     * If none of these ifs apply, then include a validation rule for start_time.
     */
    if($request->start_time2 != null && $request->end_time2 != null && $request->duration2 == null){
      $request->start_time2 = $request->start_time2;
      $request->end_time2 = $request->end_time2;
      $rules['start_time2'] = 'required_without:duration2|before:end_time2';
    }elseif($request->start_time2 != null && $request->end_time2 != null && $request->duration2 !== null){
      $rules['duration2'] = 'one_or_the_other';
    }elseif(($request->start_time2 != null && $request->end_time2 == null) || ($request->start_time2 == null && $request->end_time2 != null)){
      $rules['start_time2'] = 'required_without:duration2|before:end_time2';
    }else{
      $rules['start_time2'] = 'required_without:duration2';
    }

    $messages = [
      'start_time2.required_without' => 'Required if not entering duration.',
      'end_time2.required_without' => 'Required if not entering duration.',
      'task2.required_without' => 'Required if not writing in task.',
      'writein2.required_without' => 'Required if not selecting a task.',
      'duration2.required_without_all' => 'Duration is required if not entering start and end time.',
      'start_time2.before' => 'The start time must be before the end time.',
      'description2.required' => 'Description is required.',
      'billable2.required' => 'Billable is required.',
      'project_name2.required' => 'The project name is required.',
      'duration2.regex' => 'The duration format is invalid.',
    ];

    $this->validate(request(), $rules, $messages);

    $timeEntry = TimeEntry::find($request->edit_id);

    $timeEntry->project_name = $request->project_name2;

    if($request->task2 != null && $request->writein2 == null){
      $timeEntry->task = $request->task2;
    }
    elseif($request->task2 == null && $request->writein2 != null){
      $timeEntry->task = $request->writein2;
    }
    elseif($request->task2 != null && $request->writein2 != null){
      $timeEntry->task = $request->writein2;
    }

    if($request->start_time2 != null && $request->end_time2 != null){
      $timeEntry->start_time = $request->start_time2;
      $timeEntry->end_time = $request->end_time2;
      $timeEntry->duration = round((strtotime($request->end_time2) - strtotime($request->start_time2)) / 60,2);
    }else{
      $timeEntry->duration = UtilityController::durationToTotalMinutes($request->duration2);
      $timeEntry->start_time = null;
      $timeEntry->end_time = null;
    }

    $timeEntry->billable = $request->billable2;
    $timeEntry->description = $request->description2;

    $timeEntry->save();

    return redirect('/work-done')->withInput();
  }

  /**
   * Method for syncing time entries.
   *
   * @param Request object
   * @return Response only for debugging purposes.
   */
  public function sync(Request $request){
    $r = request()->all();
    $date = $request->entryDate;
    $userID = auth()->user()->zoho_id;
    $zohoPost = new ZohoRequests();

    if(sizeof($r) == 3){
      foreach($r['aggregatedEntries'] as $request){
        $projects = Project::where('name', 'LIKE', '%'.$request['project_name'].'%')->with('tasks')->get();

        if($request['billable'] == 1){
          $billable = "Billable";
        }else{
          $billable = "Non Billable";
        }

        foreach ($projects as $project){
          if($project->name == $request['project_name']){
            $projectName = $request['project_name'];
            $projectID = $project->zoho_id;
            foreach($project->tasks as $task){
              if($task->name == $request['task']){
                $taskName = $request['task'];
                $taskID = $task->zoho_id;
              }
            }
          }
        }
        $total = $request['total'];
        $description = $request['concatDescription'];

        $zohoPost->zohoPost($date, $userID, $billable, $total, $description, $projectID, $taskID, $projectName, $taskName);

      }
    }else{
      $projects = Project::where('name', 'LIKE', '%'.$request->project_name.'%')->with('tasks')->get();

      if($request->billable == 1){
        $billable = "Billable";
      }else{
        $billable = "Non Billable";
      }

      foreach ($projects as $project){
        if($project->name == $request->project_name){
          $projectName = $request->project_name;
          $projectID = $project->zoho_id;
          foreach($project->tasks as $task){
            if($task->name == $request->task){
              $taskName = $request->task;
              $taskID = $task->zoho_id;
            }
          }
        }
      }

      $total = $request->total;
      $description = $request->concatDescription;

      $zohoPost->zohoPost($date, $userID, $billable, $total, $description, $projectID, $taskID, $projectName, $taskName);

    }

  }

  public function getRequest(){
    return $this->request;
  }

  function setRequest($request){
    $this->request = $request;
  }
}
