<div class="searchAndTitleContainer">
<div class="row">
        <div class="col-md-5 col-md-push-7">
            @include('forms.searchForm')
        </div>
        <div class="col-md-7 col-md-pull-5">
            <h2 class="tableHeadings" id="projectsHeadings1">Search Results</h2>
        </div>
    </div>
</div>

@if(count($searchResults) == 0)
    <div class="container nothing">
        No matches found.
    </div>
@else
    <div class="table-responsive">
        <table class="table activeTable table-hover">
            <tr class="headerRow">
            <th class="projectColumn">PROJECT</th>
            <th class="toggleColumn taskColumn">Active Tasks</th>
            </tr>
            @foreach($searchResults as $project)
                <tr>
                <td class="projectColumn">{{ $project->name }}</td>
                <td class="toggleColumn taskColumn">
                    @if(count($project->tasks) == 0)
                        <div class="noActiveTasks">No active tasks.</div>
                    @else
                        <ul>
                            @foreach($project->tasks as $task)
                                <li>{{ $task->name }}</li>
                            @endforeach
                        </ul>  
                    @endif              
                </td>
                </tr>
            @endforeach
        </table>
    </div>
@endif