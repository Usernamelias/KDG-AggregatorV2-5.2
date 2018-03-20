<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
        <div class="passwordResetSuccess alert alert-success" role="alert">
           You've successfully reset your password!
        </div>
        <div class="passwordResetError alert alert-danger" role="alert">
            Error! Something went wrong.
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Enter Your New Password</h4></div>

            <div class="panel-body">
                <form class="form-horizontal" method="POST" id="passwordResetForm" novalidate>
                    {{ csrf_field() }}                      

                    <div data-password-error="passwordError" class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label">Password</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password" required>

                            <span class="text-danger">
                                <strong id="password-error"></strong>
                            </span>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary resetPasswordSubmit">
                                Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-2"></div>
</div>

@push('body')
<script type="text/javascript">

    $('.resetPasswordSubmit').click(function (e) {
        e.preventDefault();
        $('.passwordResetSuccess').hide();
        $('.passwordResetError').hide();

        var passwordResetForm = $('#passwordResetForm');
        var formData = passwordResetForm.serialize();
        $( '#password-error' ).html( "" );

        $.ajax({

            type:'POST',
            url:'/settings',
            data: formData,
            success: function(data){
                //alert(data.success);
 
                if(data.errors) { 
                    $('[data-password-error]').addClass("has-error");
                    $('.passwordResetError').show();
                    if(data.errors.password){
                        $( '#password-error' ).html( data.errors.password[0] );
                    }  
                }
                if(data.success) {
                    $('.passwordResetSuccess').show();
                }
            },
            error: function(data){
                //alert(data.error);
                $('.passwordResetError').show();
            }
        });
        return false;
    });
</script>
@endpush
