<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
        <div class="authtokenSuccess authtokenSuccess1 alert alert-success" role="alert">
           You've successfully updated your auth token!
        </div>
        <div class="authtokenSuccess authtokenSuccess2 alert alert-success" role="alert">
            Success! You can now post time entries. You will be redirected shortly.
            <div>
                <i class="fas fa-spinner fa-spin"></i>
            </div>    
        </div>
        <div class="authtokenFailure alert alert-danger" role="alert">
            Error! Something went wrong.
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Enter Your Auth Token</h4></div>

            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="/authtoken" novalidate>
                    {{ csrf_field() }}
                    
                    <div class="{{ $errors->has('authtoken') ? ' has-error' : '' }}">
                        <div class="input-group">

                            <input id="authtoken" type="text" class="form-control" name="authtoken" value="{{ old('authtoken') }}" placeholder="Enter auth token here." required autofocus>

                            <span class="input-group-btn">                     
                                <button type="submit" class="btn btn-primary authtokenSubmit">
                                    Submit
                                </button>
                            </span>
                        </div>
                    </div>

                    @if ($errors->has('authtoken'))
                        <span class="help-block">
                            <strong>{{ $errors->first('authtoken') }}</strong>
                        </span>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-2"></div>
</div>

@push('body')
<script type="text/javascript">
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.authtokenSubmit').click(function (e) {
        e.preventDefault();
        $('.authtokenSuccess').hide();
        $('.authtokenFailure').hide();

        var authtoken = $('#authtoken').val();
        var CSRF_TOKEN = '{{csrf_token()}}';

        $.ajax({

            type:'POST',
            url:'/authtoken',
            data: {
                authtoken:authtoken,
                _token: CSRF_TOKEN
            },
            success: function(data){
                //alert(data.success);
                
                @if(\Request::is('authtoken'))
                    $('.authtokenSuccess2').show();

                    window.setTimeout(function(){
                        window.location.href = '/work-done';
                    },3000);
                @else
                    $('.authtokenSuccess1').show();
                @endif
            },
            error: function(data){
                //alert(data.failure);
                $('.authtokenFailure').show();
            }
        });

    });
</script>
@endpush