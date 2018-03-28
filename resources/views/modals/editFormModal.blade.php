<div class="modal fade" id="editModal" data-edit="editModal" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h2 class="editTimeEntryHeader">Edit a Time Entry</h2>
            </div>
            <div class="modal-body">
              <form method='POST' id="editEntryForm" enctype="multipart/form-data" action='/work-done/edit_entry' novalidate>
                {{ csrf_field() }}

                <input type='hidden' name='edit_id' value="{{ old('edit_id') }}">  

                <div class="form-group {{ $errors->has('description2') ? ' has-error' : '' }}">
                  <label for="description2">DESCRIPTION OF WORK</label>
                  <textarea name="description2" class="form-control input-lg description" rows="5" placeholder="Say what you worked on..." autofocus="autofocus">{{ old('description2') }}</textarea>
                  @if($errors->get('description2'))
                    <ul class="error-list">
                      @foreach($errors->get('description2') as $error)
                        <li class="error">{{ $error }}</li>
                      @endforeach
                    </ul>
                  @endif
                </div>

                <div class="form-group {{ $errors->has('project_name2') ? ' has-error' : '' }}">
                  <label for="project">PROJECT</label>
                  <select class="form-control input-lg project search-select combobox" id="combobox2" name="project_name2" required>
                    <option value="" hidden selected class="selectPlaceholder">Pick a Project</option>
                    @foreach($activeProjects as $project)
                      <option data-id="{{ $project->zoho_id }}" value='{{ $project->name }}' {{ (old('project_name2') == $project->name) ? 'SELECTED' : ''}}>
                        {{ $project->name }}
                      </option>
                    @endforeach
                  </select>
                  @if($errors->get('project_name2'))
                    <ul class="error-list">
                      @foreach($errors->get('project_name2') as $error)
                        <li class="error">{{ $error }}</li>
                      @endforeach
                    </ul>
                  @endif
                </div>

                <div class="form-group {{ $errors->has('task2') ? ' has-error' : '' }} taskSelectField" id="taskSelectField">
                  <label for="task">TASK/TICKET</label>
                    <select class="form-control input-lg task search-select" name="task2" required data-old='{{ old("task2") }}'>
                      <option value="" hidden selected class="selectPlaceholder">Pick a Task/Ticket</option>
                    </select>
                    @if($errors->get('task2'))
                      <ul class="error-list">
                        @foreach($errors->get('task2') as $error)
                          <li class="error">{{ $error }}</li>
                        @endforeach
                      </ul>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('writein2') ? ' has-error' : '' }} taskTextField" id="taskTextField">
                  <label for="writein2">TASK/TICKET</label>
                  <input type="text"  class="form-control input-lg" name="writein2" placeholder="Enter your task." value='{{ old('writein2') }}'>
                  @if($errors->get('writein2'))
                    <ul class="error-list">
                      @foreach($errors->get('writein2') as $error)
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
                  <div class="form-group col-xs-6 {{ $errors->has('start_time2') ? ' has-error' : '' }}">
                    <label for="start_time2">START TIME</label>
                    <div class="input-group clockpicker" data-placement="right" data-align="top" data-autoclose="true">
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                      </span>
                      <input type="time" name="start_time2" id='start_time_modal' class="form-control time timepicker input-lg start" value="{{ old('start_time2') }}" placeholder="9:00 AM">
                    </div>
                    @if($errors->get('start_time2'))
                      <ul class="error-list">
                        @foreach($errors->get('start_time2') as $error)
                          <li class="error">{{ $error }}</li>
                        @endforeach
                      </ul>
                    @endif
                  </div>
                  
                  <div class="form-group col-xs-6 {{ $errors->has('end_time2') ? ' has-error' : '' }}">
                    <label for="end_time2">END TIME</label>
                    <div class="input-group clockpicker" data-placement="right" data-align="top" data-autoclose="true">
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                      </span>
                      <input type="time" name="end_time2" class="form-control time timepicker input-lg end" value="{{ old('end_time2') }}" placeholder="5:30 PM">
                    </div>
                    @if($errors->get('end_time2'))
                      <ul class="error-list">
                        @foreach($errors->get('end_time2') as $error)
                          <li class="error">{{ $error }}</li>
                        @endforeach
                      </ul>
                    @endif
                  </div>

                </div>

                <div class="form-group durationTextField {{ $errors->has('duration2') ? ' has-error' : '' }} durationTextField" id="durationTextField">
                  <label for="duration2">DURATION</label>
                  <input type="text" id="duration2" class="form-control input-lg duration" name="duration2" placeholder="Enter duration."  value='{{ old("duration2") }}'>               
                </div>
                @if($errors->get('duration2'))
                  <ul class="error-list">
                    @foreach($errors->get('duration2') as $error)
                      <li class="error">{{ $error }}</li>
                    @endforeach
                  </ul>
                @endif

                <div class="enterWriteInOption">
                <button type="button" class="clearTimes" id="clearTimes2">Clear times</button>
                  <span class="enterDuration writeins" id="enterDuration">Enter duration instead</span>
                  <span class="enterStartEndTime writeins" id="enterStartEndTime">Enter start and end time instead</span>
                  
                </div>

                <div class="row">
                  <div class="form-group col-sm-6">
                    <input type="radio" id="billable2" name="billable2" class="billable" value="1" {{ old('billable2')=="1" ? 'checked='.'"'.'checked'.'"' : '' }}>
                    <label for="billable2">Billable Work</label>
                  </div>

                  <div class="form-group col-sm-6">
                    <input type="radio" id="nonbillable2" name="billable2" class="nonbillable" value="0" {{ old('billable2')=="0" ? 'checked='.'"'.'checked'.'"' : '' }}>
                    <label for="nonbillable2">Non-Billable Work</label>
                  </div>
                </div>

                @if($errors->get('billable2'))
                  <ul class="error-list">
                    @foreach($errors->get('billable2') as $error)
                      <li class="error">{{ $error }}</li>
                    @endforeach
                  </ul>
                @endif
              </div>
                
            
            <div class="modal-footer">
            <div class="form-group col-sm-6 cancelButtonContainer">
                  <button class="form-control input-lg btn cancelEdit" id="cancelEdit" data-dismiss="modal">CANCEL</button>
                </div>
        
                <div class="form-group col-sm-6">
                  <input type="submit" class="form-control input-lg btn" value="SAVE">
                </div>

              </form>   
            </div>
        </div>
    </div>
</div>    
    
@push('body')
  <script src="/js/editForm.js"></script>
  <script type="text/javascript">
    @if (count($errors->get('description2')) > 0 || count($errors->get('project_name2')) > 0 ||
      count($errors->get('task2')) > 0 || count($errors->get('start_time2')) > 0 ||
      count($errors->get('end_time2')) > 0 || count($errors->get('duration2')) > 0 ||
      count($errors->get('billable2')) > 0 || count($errors->get('writein2')) > 0)
        $('#editModal').modal('show');
    @endif
  </script>
@endpush 