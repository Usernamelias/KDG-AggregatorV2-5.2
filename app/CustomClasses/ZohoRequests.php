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
 * This class is for retrieving Zoho data.
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

        foreach($this->allTasks as $t){
            
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
        $urlEnd = 'portal/kyledavidgroup/projects/'.$projectID.'/tasks'.'/'.$taskID.'/logs'.'/';
       
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
          $timeEntries = TimeEntry::where('project_name', 'LIKE', '%'.$projectName.'%')->where('task', 'LIKE', '%'.$taskName.'%')->get();
    
          foreach($timeEntries as $timeEntry){
            $timeEntry->delete();
          }
        }
    }
}