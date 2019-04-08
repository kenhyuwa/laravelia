@extends(__v() . '.layouts.app')

@section('content')
<div id="app" class="login-box">
    <div class="login-logo">
        <a href="/">{{ 'Konfigurasi' }}</a>
    </div>
    <div class="login-box-body">
        <form class="config" action="{{ route('configuration.store') }}" method="post">
            @csrf
            <div class="form-group has-feedback{{ $errors->has('host') ? ' has-error' : '' }}">
                <input id="host" type="text" class="form-control" name="host" placeholder="DB host" readonly="true" value="{{ env('DB_HOST') }}">
                <span class="fa fa-server form-control-feedback"></span>
                @if ($errors->has('host'))
                    <span class="help-block" role="alert">
                        <strong>{{ $errors->first('host') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback{{ $errors->has('username') ? ' has-error' : '' }}">
                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="DB username" autocomplete="off">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @if ($errors->has('username'))
                    <span class="help-block" role="alert">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control" name="password" placeholder="DB password">
                <span class="fa fa-key form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div id="select2" class="form-group has-feedback{{ $errors->has('database') ? ' has-error' : '' }}">
                @if ($errors->has('database'))
                    <span class="help-block" role="alert">
                        <strong>{{ $errors->first('database') }}</strong>
                    </span>
                @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat primary-btn">Lanjutkan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    function eaching(data){
        let e = '<select id="database" class="form-control select2" name="database" data-placeholder="DB name">';
        e += '<option value=""></option>';
        for(let i = 0; i < data.length; i++){
            e += '<option value="'+data[i]+'">'+data[i]+'</option>';
        }
        e += '</select>';
        return e;                
    }
    $(function () {
        const dbValidator = {
            validators:{
                notEmpty:{}
            }
        };
        const validators = {
            username: {
                validators: {
                    notEmpty: {},
                }
            },
            password: {
                validators: {}
            }
        };
        let Config = $('form');
            Config.find('#username').focus();
            Config.find('#database').parent().hide();
        Config.callFormValidation(validators)
        .on('success.form.fv', function(e) {
            e.preventDefault();
            Config.parents('#app').waitMeShow();
            let $form = $(e.target),
                fv    = $form.data('formValidation');
            if($form.hasClass('config')){
                axios.post($form.attr('action'), $form.serialize())
                .then((r) => {
                    if(r.status == 200){
                        $('#select2').empty();
                        $('#select2').html(
                            eaching(r.data.data)
                        );
                        $('.select2').callSelect2();
                        $('#select2').parent().show();
                        $form.removeClass('config');
                        Config.parents('#app').waitMeHide();
                        fv.addField('database', dbValidator);
                    }
                });
            }else{
                axios.post($form.attr('action'), $form.serialize())
                .then((r) => {
                    window.location.href = '/installation';
                    console.log(r);
                }).catch((e) => {
                    Config.parents('#app').waitMeHide();
                    fv.resetForm(true);
                    throw new Error(e);
                });
            }
        });
    });
</script>
@endsection