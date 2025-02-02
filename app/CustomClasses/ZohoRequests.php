<?php

namespace App\CustomClasses;
use GuzzleHttp\Client;
use App\Http\Controllers\UtilityController;
use App\TimeEntry;
use App\User;
use App\Task;
use App\Project;
use Auth;

/**
 * This class is for dealing with Zoho's API'.
 *
 * @author Elias Falconi
 */
class ZohoRequests {

    /**Array for holding Zoho tasks. */
    private $allTasks = array();
    private $taskAndUsers = array();
    private $projectAndUsers = array();
    private $taskIDs = array();

    /**
     * Function for getting Zoho tasks, then saves it to database.
     * Calls zohoTasksViaKali.
     */
    public function updateTasksTable(){

        $this->zohoTasksViaKali();
        $everyTask = Task::all();

        foreach($this->allTasks as $t){
            array_push($this->taskIDs, $t['id']);

            $task = Task::where('zoho_id', $t['id'])->first();
            $project_id = Project::where('zoho_id', $t['projectID'])->pluck('id')->first();
            if($project_id === null){
                continue;
            }
            if($task === null){
                $newTask = new Task();
                $newTask->zoho_id = $t['id'];
                $newTask->name = $t['name'];
                $newTask->zoho_project_id = $t['projectID'];
                $newTask->project_id = $project_id;

                $newTask->save();
            }else{
                $task->zoho_id = $t['id'];
                $task->name = $t['name'];
                $task->zoho_project_id = $t['projectID'];

                $task->save();
            }
        }

        // foreach($everyTask as $task){
        //   if(!in_array($task->zoho_id, $this->taskIDs)){
        //     $task->users()->detach();
        //     $task->delete();
        //   }else{}
        // }
    }

    /**
     * Functions for getting zoho tasks via kali app.
     */
    private function zohoTasksViaKali(){

        $page = 1;
        $client = new Client(['base_uri' => env('BASE_URI_KALI')]);
        $users = User::all();

        while(true){

            $response = $client->request('GET', '?Completed=IS+NULL&Page='.$page.'&app_key='.urlencode(env('APP_KEY')));
            $tasksJSON = json_decode($response->getBody());

            if(empty($tasksJSON->Tasks)){
                break;
            }else{}

            if($tasksJSON == null){
                break;
            }

            foreach($tasksJSON as $t){
                if($t == null){
                    break;
                }

                foreach($t as $task){

                    $ownerIDs = array();

                    foreach($task->Owners as $owner){
                        if(property_exists($owner, 'ZohoUserID')){
                            array_push($ownerIDs, $owner->ZohoUserID);

                            foreach($users as $user){
                                if($user->zoho_id == $owner->ZohoUserID){
                                    array_push($this->projectAndUsers[$owner->ZohoUserID], $task->ZohoProjectID);
                                    $this->projectAndUsers[$owner->ZohoUserID] = array_unique($this->projectAndUsers[$owner->ZohoUserID]);
                                }
                            }
                        }
                    }
                    $this->allTasks[] = array(
                        'id' => $task->ZohoTaskID,
                        'name' => $task->Name,
                        'projectID' => $task->ZohoProjectID
                    );
                    $this->taskAndUsers[$task->ZohoTaskID] = $ownerIDs;
                }
            }
            $page++;
        }
    }

    /**
     * Function performs an api call to Zoho to retrieve
     * all users. It then updates the database.
     */
    public function updateUsersTable(){

        $client = new Client(['base_uri' => env('BASE_URI')]);
        $response = $client->request('GET', 'portal/kyledavidgroup/users/?AUTHTOKEN='.env('AUTH_TOKEN').'&RESULT=TRUE');
        $users = json_decode($response->getBody());

        foreach($users as $u){
            foreach($u as $user){
                if($user->role == 'admin'){
                    $this->projectAndUsers[(string) $user->id] = array();
                    $userDB = User::where('zoho_id', $user->id)->first();

                    if($userDB === null){
                        $newUser = new User();
                        $newUser->email = $user->email;
                        $newUser->password = bcrypt('123654');
                        $newUser->name = $user->name;
                        $newUser->zoho_id = $user->id;
                        $newUser->save();
                    }else{
                        $userDB->name = $user->name;
                        $userDB->email = $user->email;
                        $userDB->save();
                    }
                }
            }
        }
    }

    /**
     * Function calls Zoho's projects api, and then updates
     * the database.
     */
    public function updateProjectsTable(){
        $client = new Client(['base_uri' => env('BASE_URI')]);
        $i = 0;
        $index = 1;
        $notNull = true;
        $projects = [];

        while($notNull == true){
            $response = $client->request('GET', 'portal/kyledavidgroup/projects/?AUTHTOKEN='.env('AUTH_TOKEN').'&RESULT=TRUE&index='.$index);
            $projectsJSON = json_decode($response->getBody());

            if($projectsJSON == null){
                $notNull = false;
                continue;
            }
            foreach($projectsJSON as $project){
                if($project == null){
                    $notNull = false;
                    continue;
                }
                foreach($project as $p){
                    if($p->status === "active"){
                        $projects[$i] = array();
                        $projects[$i]['zoho_id'] = $p->id_string;
                        $projects[$i]['name'] = $p->name;
                        $i++;
                    }

                }
            }
            $index = $index + 100;
        }

        for($j = 0; $j < $i; $j++){

            $project = Project::where('zoho_id', $projects[$j]['zoho_id'])->first();

            if($project === null){
                $newProject = new Project();
                $newProject->zoho_id = $projects[$j]['zoho_id'];
                $newProject->name = $projects[$j]['name'];

                $newProject->save();
            }else{
                $project->name = $projects[$j]['name'];

                $project->save();
            }

        }
    }

    /**
     * This function ties together the users and tasks tables,
     * using the fact that they have a many to many relationship.
     */
    public function updateTaskUserTable(){
        foreach($this->taskAndUsers as $taskID => $owners){

            $task = Task::where('zoho_id', $taskID)->first();
            if($task == null){
                continue;
            }
            $ownerArray = array();
            foreach($owners as $ownerID){
                $ownerArray[] = $ownerID;
            }
            $owners = User::whereIn('zoho_id', $ownerArray)->select('id')->get();

            $ownerIDs = array();
            foreach($owners as $owner){
                $ownerIDs[] = $owner->id;
            }

            $task->users()->sync($ownerIDs);
        }
    }

    /**
     * This function ties together the users and projects tables,
     * using the fact that they have a many to many relationship.
     */
    public function updateProjectUserTable(){
        foreach($this->projectAndUsers as $ownerID => $projects){

            $owner = User::where('zoho_id', $ownerID)->first();
            if($owner == null){
                continue;
            }
            $projectArray = array();
            foreach($projects as $projectID){
                $projectArray[] = $projectID;
            }

            $projects = Project::whereIn('zoho_id', $projectArray)->select('id')->get();

            $projectIDs = array();
            foreach($projects as $project){
                $projectIDs[] = $project->id;
            }

            $owner->projects()->sync($projectIDs);
        }
    }

    /**
     * This function posts time entries to zoho.
     */
    public function zohoPost($date, $userID, $billable, $total, $description, $projectID, $taskID, $projectName, $taskName){
        $authToken = Auth::user()->authtoken;
        $client = new Client(['base_uri' => env('BASE_URI')]);

        if(strpos($taskName, 'TICKET:') !== false){
          $urlEnd = 'portal/kyledavidgroup/projects/'.$projectID.'/bugs'.'/'.$taskID.'/logs'.'/';
        }else{
          $urlEnd = 'portal/kyledavidgroup/projects/'.$projectID.'/tasks'.'/'.$taskID.'/logs'.'/';
        }
        
        $response = $client->request('POST', $urlEnd, [
          'form_params' => [
                  'AUTHTOKEN' => $authToken,
                  'date' => $date,
                  'owner' => $userID,
                  'bill_status' => $billable,
                  'hours' => $total,
                  'notes' => $description,
          ]
        ]);

        $code = $response->getStatusCode();

        if($code == 201){
          $timeEntries = TimeEntry::where('project_name', 'LIKE', '%'.$projectName.'%')->where('task', 'LIKE', '%'.$taskName.'%')->where('user_id', Auth::user()->id)->get();

          foreach($timeEntries as $timeEntry){
            $timeEntry->delete();
          }
        }
    }

    /**
     * This function updates tasks per project specified.
     * @param integer
     */
     public function updateTasksPerProject($projectID){
       ini_set('max_execution_time', 100000);
       $user = Auth::user();
       $users = User::all();
       $client = new Client(['base_uri' => env('BASE_URI')]);
       $indexT = 1;
       $indexS = 1;

       $this->updateUsersTable();

       while(true){
         try{
             $response = $client->request('GET', 'portal/kyledavidgroup/projects/'.$projectID.'/tasks/?AUTHTOKEN='.env('AUTH_TOKEN').'&RESULT=TRUE&index='.$indexT);
         }catch(\Exception $e){
             break;
         }

         $tasksJSON = json_decode($response->getBody());

         if($tasksJSON == null){
             break;
         }
         foreach($tasksJSON as $t){
             if($t == null){
                 break;
             }
             foreach($t as $task){

                 if($task == null){
                     break;
                 }
                 if($task->status->name === "Open"){

                     $ownerIDs = array();

                     foreach($task->details->owners as $owner){
                         if(property_exists($owner, 'id')){
                             array_push($ownerIDs, $owner->id);
                             foreach($users as $user){
                                 if($user->zoho_id == $owner->id){
                                     array_push($this->projectAndUsers[$owner->id], $task->id_string);
                                     $this->projectAndUsers[$owner->id] = array_unique($this->projectAndUsers[$owner->id]);
                                 }
                             }
                         }
                     }

                     $this->allTasks[] = array(
                         'id' => $task->id_string,
                         'name' => $task->name,
                         'projectID' => $projectID,
                         'ownerIDs' => $ownerIDs,
                     );

                     $this->taskAndUsers[$task->id_string] = $ownerIDs;

                     if($task->subtasks === true){

                         $subtasksURL = $task->link->subtask->url;

                         while(true){

                             try{
                                 $response = $client->request('GET', $subtasksURL . '?AUTHTOKEN='.$user->authtoken.'&RESULT=TRUE&index='.$indexS);
                             }catch(\Exception $e){
                                 break;
                             }

                             $subtasksJSON = json_decode($response->getBody());

                             if($subtasksJSON == null){
                                 break;
                             }

                             foreach($subtasksJSON as $s){
                                 if($s == null){
                                     break;
                                 }
                                 foreach($s as $subtask){
                                     if($subtask == null){
                                         break;
                                     }

                                     if($subtask->status->name === "Open"){
                                         $ownerIDs = array();

                                         foreach($subtask->details->owners as $owner){
                                             if(property_exists($owner, 'id')){
                                                 array_push($ownerIDs, $owner->id);
                                                 foreach($users as $user){
                                                     if($user->zoho_id == $owner->id){
                                                         array_push($this->projectAndUsers[$owner->id], $task->id_string);
                                                         $this->projectAndUsers[$owner->id] = array_unique($this->projectAndUsers[$owner->id]);
                                                     }
                                                 }
                                             }

                                         }

                                         $this->allTasks[] = array(
                                             'id' => $subtask->id_string,
                                             'name' => $subtask->name,
                                             'projectID' => $projectID,
                                             'ownerIDs' => $ownerIDs,
                                         );

                                         $this->taskAndUsers[$subtask->id_string] = $ownerIDs;
                                     }
                                 }
                             }
                             $indexS = $indexS + 100;
                         }
                     }
                 }
             }
         }
         $indexT = $indexT + 100;
       }

       //For Bugs
       $indexT = 1;
       while(true){
         try{
             $response = $client->request('GET', 'portal/kyledavidgroup/projects/'.$projectID.'/bugs/?AUTHTOKEN='.env('AUTH_TOKEN').'&RESULT=TRUE&index='.$indexT);
         }catch(\Exception $e){
             break;
         }

         $bugsJSON = json_decode($response->getBody());

         if($bugsJSON == null){
             break;
         }
         foreach($bugsJSON as $b){
           if($b == null){
               break;
           }
           foreach($b as $bug){

             if($bug == null){
                 break;
             }
             if($bug->closed === false){
               $assigneeID = array();
               if(property_exists($bug, 'assignee_id')){
                 array_push($assigneeID, $bug->assignee_id);
                 foreach($users as $user){
                     if($user->zoho_id == $bug->assignee_id){
                         array_push($this->projectAndUsers[$bug->assignee_id], $bug->id_string);
                         $this->projectAndUsers[$bug->assignee_id] = array_unique($this->projectAndUsers[$bug->assignee_id]);
                     }
                 }
               }

               $this->allTasks[] = array(
                   'id' => $bug->id_string,
                   'name' => 'TICKET: '.$bug->title,
                   'projectID' => $projectID,
                   'ownerIDs' => $assigneeID,
               );

               $this->taskAndUsers[$bug->id_string] = $assigneeID;
             }
           }
         }
         $indexT = $indexT + 100;
       }

       foreach($this->allTasks as $t){
         array_push($this->taskIDs, $t['id']);

         $task = Task::where('zoho_id', $t['id'])->first();
         $project_id = Project::where('zoho_id', $t['projectID'])->pluck('id')->first();
         if($project_id === null){
             continue;
         }
         if($task === null){
             $newTask = new Task();
             $newTask->zoho_id = $t['id'];
             $newTask->name = $t['name'];
             $newTask->zoho_project_id = $t['projectID'];
             $newTask->project_id = $project_id;

             $newTask->save();
         }else{
             $task->zoho_id = $t['id'];
             $task->name = $t['name'];
             $task->zoho_project_id = $t['projectID'];

             $task->save();
         }
       }

       $this->updateTaskUserTable();
     }
}
