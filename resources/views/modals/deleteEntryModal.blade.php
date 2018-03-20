<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
              <h3>Are you sure you want to delete this entry?</h3>
            </div>
            <div class="modal-body">
              <form method='POST' action='/work-done/delete_entry'>

                {{ csrf_field() }}

                <input type='hidden' id='id' name='id'>              

              <div class="form-group col-sm-6">        
                  <button type="button" id="doNotDelete" class="form-control input-lg btn" data-dismiss="modal">No</button>
                </div>
                <div class="form-group col-sm-6">        
                  <input type='submit' value='Yes' class='btn input-lg btn-primary'>
                </div>                                                        
              </form>
                
                
            </div>
            <div class="modal-footer">
              
            </div>
        </div>
    </div>
</div>

@push('body')
  <script src="/js/deleteEntry.js"></script>
@endpush