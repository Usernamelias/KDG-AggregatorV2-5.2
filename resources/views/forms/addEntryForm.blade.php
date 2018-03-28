<div class="col-sm-4 col-sm-push-8 addEntrySection">
  <h2 id="addEntryHeading">Add a Time Entry</h2>
  <form method='POST' id="addEntryForm" enctype="multipart/form-data" action='/work-done/add' novalidate>
    {{ csrf_field() }}

    <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
      <label for="description">DESCRIPTION OF WORK</label>
      <textarea name="description" class="form-control input-lg description" rows="5" placeholder="Say what you worked on..." autofocus="autofocus">{{ old('description') }}</textarea>
      @if($errors->get('description'))
        <ul>
          @foreach($errors->get('description') as $error)
            <li class="error">{{ $error }}</li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="form-group {{ $errors->has('project_name') ? ' has-error' : '' }}">
      <label for="project">PROJECT</label>
      <select class="form-control input-lg project search-select combobox" id="combobox" name="project_name" required>
        <option value="" hidden selected class="selectPlaceholder">Pick a Project</option>
        @foreach($allProjects as $project)
          <option data-id="{{ $project->zoho_id }}" value='{{ $project["name"] }}' {{ (old('project_name') == $project['name']) ? 'SELECTED' : ''}}>
            {{ $project['name'] }}
          </option>
        @endforeach
      </select>
      @if($errors->get('project_name'))
        <ul>
          @foreach($errors->get('project_name') as $error)
            <li class="error">{{ $error }}</li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="form-group {{ $errors->has('task') ? ' has-error' : '' }} taskSelectField" id="taskSelectField">
      <label for="task">TASK/TICKET</label>
        <select class="form-control input-lg task search-select" name="task" required data-old='{{ old("task") }}'>
          <option value="" hidden selected class="selectPlaceholder">Pick a Task/Ticket</option>
        </select>
        @if($errors->get('task'))
          <ul>
            @foreach($errors->get('task') as $error)
              <li class="error">{{ $error }}</li>
            @endforeach
          </ul>
        @endif
    </div>

    <div class="form-group {{ $errors->has('writein') ? ' has-error' : '' }} taskTextField" id="taskTextField">
      <label for="writein">TASK/TICKET</label>
      <input type="text"  class="form-control input-lg" name="writein" placeholder="Enter your task." value='{{ old('writein') }}'>
      @if($errors->get('writein'))
        <ul>
          @foreach($errors->get('writein') as $error)
            <li class="error">{{ $error }}</li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="enterWriteInOption">
      <span class="writeins writeInTask" id="writeInTask">Write-in</span>
      <span class="selectTask writeins" id="selectTask">Select Task</span>
    </div>

    
    <div class="row startEndTime" id="startEndTime">
    
        <div class="form-group col-xs-6 {{ $errors->has('start_time') ? ' has-error' : '' }}">
          <label for="start_time">START TIME</label>
          <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
          <span class="input-group-addon">
		          <span class="glyphicon glyphicon-time"></span>
	          </span>
            <input type="time" name="start_time" class="form-control time timepicker input-lg start" value="{{ old('start_time') }}" placeholder="9:00 AM">
            
          </div>
          @if($errors->get('start_time'))
            <ul>
              @foreach($errors->get('start_time') as $error)
                <li class="error">{{ $error }}</li>
              @endforeach
            </ul>
          @endif
        </div>
      

  
      <div class="form-group col-xs-6 {{ $errors->has('end_time') ? ' has-error' : '' }}">
        <label for="end_time">END TIME</label>
        <div class="input-group clockpicker" data-placement="right" data-align="top" data-autoclose="true">
        <span class="input-group-addon">
		          <span class="glyphicon glyphicon-time"></span>
	          </span>
          <input type="time" name="end_time" class="form-control time timepicker input-lg end" value="{{ old('end_time') }}" placeholder="5:30 PM">
        </div>
        @if($errors->get('end_time'))
          <ul>
            @foreach($errors->get('end_time') as $error)
              <li class="error">{{ $error }}</li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
        
    <div class="form-group {{ $errors->has('duration') ? ' has-error' : '' }} durationTextField" id="durationTextField">
      <label for="duration">DURATION</label>
      <input type="text" id="duration" class="form-control input-lg duration" name="duration" placeholder="Enter duration."  value='{{ old('duration') }}'>        
    </div>
    @if($errors->get('duration'))
      <ul>
        @foreach($errors->get('duration') as $error)
          <li class="error">{{ $error }}</li>
        @endforeach
      </ul>
    @endif
            
    <div class="enterWriteInOption">
    <button type="button" class="clearTimes" id="clearTimes">Clear times</button>
      <span class="enterDuration writeins" id="enterDuration">Enter duration instead</span>
      <span class="enterStartEndTime writeins" id="enterStartEndTime">Switch back to start and end time</span>
    </div>
  
    <div class="row">
      <div class="form-group col-sm-6 form-group {{ $errors->has('billable') ? ' has-error' : '' }}">
        <input type="radio" id="billable" name="billable" class="billable" value="1" {{ old('billable')=="1" ? 'checked='.'"'.'checked'.'"' : '' }}>
        <label for="billable">Billable Work</label>
      </div>

      <div class="form-group col-sm-6 {{ $errors->has('billable') ? ' has-error' : '' }}">
        <input type="radio" id="nonbillable" name="billable" class="nonbillable" value="0" {{ old('billable')=="0" ? 'checked='.'"'.'checked'.'"' : '' }}>
        <label for="nonbillable">Non-Billable Work</label>
      </div>
    </div>
    
    @if($errors->get('billable'))
      <ul>
        @foreach($errors->get('billable') as $error)
          <li class="error">{{ $error }}</li>
        @endforeach
      </ul>
    @endif

    <div class="form-group col-sm-6">
      <input type="submit" class="form-control input-lg btn" value="ADD ENTRY">
    </div>

    <div class="form-group col-sm-6 clearButtonContainer">
      <input class="form-control input-lg btn clear-button" value="CLEAR">
    </div>

  </form>
</div>

@push('body')
  <script src="/js/addEntryForm.js"></script>
@endpush