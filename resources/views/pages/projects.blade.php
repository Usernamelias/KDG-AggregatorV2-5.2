@extends('master')

@section('title')
    Projects | {{ env('APP_NAME')}}
@endsection

@section('content')
  
  <div class="container-fluid projectsContainer">
    <div class="col-sm-8 tables">      
      @if($searchTerm != null)
        @include('tables.searchResults')
      @else
        @include('tables.activeProjects')
        @include('tables.inactiveProjects')  
      @endif
    </div>
  </div>
  
@endsection


@push('body')
<script src="/js/projectsPage.js"></script>
<script type="text/javascript">
  $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $('[data-project-enabled]').click(function (e) {
          var enabled;
          var projectID = $(this).data('project-id');
          var CSRF_TOKEN = '{{csrf_token()}}';

          if($(this).prop('checked') == true){
              enabled = 'true';
          }else{
              enabled = 'false';
          }            

          $.ajax({

              type:'POST',
              url:'/projects',
              data: {
                  enabled:enabled,
                  projectID:projectID,
                  _token: CSRF_TOKEN
              },
              success: function(data){
                  //alert(data.success);
              },
              error: function(data){
                  //alert(data.error);
              }

          });

      });
</script>
@endpush