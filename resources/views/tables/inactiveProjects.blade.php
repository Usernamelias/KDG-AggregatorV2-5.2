<h2 class="tableHeadings" id="projectsHeadings2">Your Disabled Projects</h2>
<div class="table-responsive">
    <table class="table inactiveTable table-hover">
    <tr class="headerRow">
        <th class="projectColumn">PROJECT</th>
        <th class="toggleColumn">ENABLE/DISABLE</th>
    </tr>

    @foreach($disabledProjects as $project)
        <tr>
            <td class="projectColumn">{{ $project->name }}</td>
            <td class="toggleColumn">
            <label class="switch">
                <input type="checkbox" 
                data-project-enabled='{{ $project->zoho_id }}'
                data-project-id='{{ $project->id}}'
                >
                <span class="slider round"></span>
            </label>
            </td>
        </tr>
    @endforeach

    </table>
</div>