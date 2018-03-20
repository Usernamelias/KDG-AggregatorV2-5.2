@extends('master')

@section('title')
    Work Done | KDG Aggregator
@endsection

@push('head')
  <link href="/css/mdtimepicker.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
  @include('modals.editFormModal')    
    <div class="container-fluid tablesContainer">
      @include('forms.addEntryForm')  
      <div class="col-sm-8 col-sm-pull-4 tables">

        <h4 id="selectDate">Select Date:</h4>
        <form method="GET" action='/work-done' id='dateForm' class='form-inline'>
          <div id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
              <input class="form-control input-lg" type="text" name='entryDate' id='entryDate' value='{{ old("entryDate") }}' placeholder="mm/dd/yyyy" readonly />
              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
          </div>
          <button type="submit" class='btn btn-default btn-lg dateSubmit' form="dateForm">View</button>
        </form>
        @include('modals.deleteEntryModal')
        @include('tables.todaysEntries')
        @include('tables.aggregatedEntries')     
      </div>
        
    </div>
@endsection

@push('body')
  <script src="/js/mdtimepicker.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
  <script src="/js/entryForms.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){   
      $('.search-select').select2({
        width: '100%',
      });

      $("#datepicker").datepicker({ 
            autoclose: true, 
            todayHighlight: true,
      }).datepicker(new Date());
  });
  </script>

<!-- Task population for entry forms -->
  <script type="text/javascript">
    $(document).ready(function() {
      var allTasks = {!! json_encode($allTasks) !!};
      $(".combobox").bind('change',function() {
        var $parent = $(this).closest('form');
        $parent.find('.taskSelectField select').children('option:not(:first)').remove();
        
        var projectID = $(this).find(':selected').data('id');
       
        var taskNameArray = [];

        allTasks.forEach(function(task) {
          if(task['zoho_project_id'] == projectID || task['zoho_project_id'] === projectID){
            taskNameArray.push(task['name']);   
          }
        });

        var option = '';

        for(let i = 0; i < taskNameArray.length; i++){
          option += '<option value=' + "'" + taskNameArray[i].replace(/(["])/g, "&quot;") + "'" + '>' + taskNameArray[i] + '</option>';
        }

        $parent.find('.taskSelectField select').append(option);

        var oldValue = $parent.find('.taskSelectField select').attr('data-old');
       
        if(oldValue){
          $parent.find('.taskSelectField select').val(oldValue);
        }
      }).trigger('change');
    });
  </script>
<!-- End 'Task population for entry forms' --> 
@endpush