<div class="searchAndTitleContainer row">
    
        <div class="col-md-5 col-md-push-7">
            @include('forms.searchForm')
        </div>
        <div class="col-md-7 col-md-pull-5">
            <h2 class="tableHeadings" id="projectsHeadings1">Your Enabled Projects</h2>
        </div>
    
</div>

<div class="table-responsive">
    <table class="table activeTable table-hover">
        <tr class="headerRow">
        <th class="projectColumn">PROJECT</th>
        <th class="toggleColumn">ENABLE/DISABLE</th>
        </tr>
        @foreach($enabledProjects as $project)
        <tr>
            <td class="projectColumn">{{ $project->name }}</td>
            <td class="toggleColumn">
            <label class="switch">
                <input type="checkbox" 
                data-project-enabled='{{ $project->zoho_id }}'
                data-project-id='{{ $project->id}}'
                checked>
                <span class="slider round"></span>
            </label>
            </td>
        </tr>
        @endforeach
    </table>
</div>