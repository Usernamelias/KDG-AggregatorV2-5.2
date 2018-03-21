<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Project;
use Auth;

/**
 * Class for handling the projects page.
 * 
 * @author Elias Falconi
 */
class ProjectController extends Controller
{
    /**
     * Display projects page with appropriate variables
     * @param Request
     * @return view
     */
    public function showProjectsPage(Request $request){

        $searchTerm = strtolower($request->input('searchTerm', null));

        $enabledProjects = Project::whereHas('tasks', function($q){ $q->whereHas('users', function($p){
            $p->where('zoho_id', Auth::user()->zoho_id);});})->where('enabled', '!=', '0')->orderBy('name')->get(); 

        $searchResults = Project::with(['tasks' => function($q){ $q->whereHas('users', function($p){
                            $p->where('zoho_id', Auth::user()->zoho_id);});}])->where('name', 'LIKE', '%'.$searchTerm.'%')->orderBy('name')->get();
        
        $disabledProjects = Project::whereHas('tasks', function($q){ $q->whereHas('users', function($p){
            $p->where('zoho_id', Auth::user()->zoho_id);});})->where('enabled', '!=', '1')->orderBy('name')->get(); 

        
        return view('pages.projects')->with([
            'enabledProjects' => $enabledProjects,
            'disabledProjects' => $disabledProjects,
            'searchResults' => $searchResults,
            'searchTerm' => $searchTerm,
            'bodyClass' => 'projectsBody'
        ]);    
    }

    /**
     * Function to enable or disable projects, which would affect which projects are displayed in the project drop-down on work-done page.
     * @param Request
     */
    public function projectEnabledDisabled(Request $request){
        $project = Project::find($request->projectID);

        if($request->enabled == 'true'){
            if($project->enabled == 1){}
            else{
                $project->enabled = 1;
            }
        }else{
            if($project->enabled == 0){}
            else{
                $project->enabled = 0;
            }           
        }

        $project->save();

    }
}

