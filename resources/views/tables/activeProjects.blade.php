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
        <th class="toggleColumn"></th>
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
            <td>    
                <i class="fas fa-spinner fa-spin"></i>
                <i class="fas fa-check" data-check-id="{{ $project->zoho_id }}"></i>  
                <button class="updateTasksButton btn btn-default btn-sm" data-project-zoho-id="{{ $project->zoho_id }}">Update Tasks</button>
            </td>
        </tr>
        @endforeach
    </table>
</div>

@push('body')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.updateTasksButton').click(function (e) {

            $(this).fadeToggle().hide()
            $(this).parent().find('.fa-spinner').css("display","inline-block");
            var projectID = $(this).data('project-zoho-id');
            var CSRF_TOKEN = '{{csrf_token()}}';      

            $.ajax({

                type:'GET',
                url:'/projects/update-tasks',
                data: {
                    projectID:projectID,
                    _token: CSRF_TOKEN
                },
                success: function(data){
                    $('.fa-spinner').hide();
                    $('i[data-check-id="' + projectID +'"]').css("display","inline-block");
                },
                error: function(data){
                    //alert(data.error);
                }

            });

        });
    </script>
@endpush