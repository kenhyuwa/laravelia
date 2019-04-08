@extends(__v() . '.layouts.app')

@section('content')
<div id="app" class="login-box">
    <div class="login-logo">
        <a href="/">{{ 'Installation' }}</a>
    </div>
    <div class="login-box-body">
        <form action="{{ route('installation.store') }}" method="post">
            @csrf
            <div class="form-group has-feedback{{ $errors->has('name') ? ' has-error' : '' }}">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name" autofocus="true" autocomplete="off">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @if ($errors->has('name'))
                    <span class="help-block" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" autocomplete="off">
                <span class="fa fa-envelope form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="help-block" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control" name="password" placeholder="Password">
                <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                        <span class="sr-only">60% Complete (warning)</span>
                    </div>
                </div>
                <span class="fa fa-key form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi Password">
                <span class="fa fa-key form-control-feedback"></span>
                @if ($errors->has('password_confirmation'))
                    <span class="help-block" role="alert">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat primary-btn">Install</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function () {
        let Installation = $('form');
            Installation.find('#name').focus();
        let validators = {
            name: {
                validators: {
                    notEmpty: {},
                    stringLength:{
                        min:3,
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {},
                    regexp:{
                        regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                        message: 'The value is not a valid E-Mail address'
                    },
                    stringLength:{
                        max:50,
                    },
                }
            },
            password: {
                validators: {
                    notEmpty: {},
                    regexp:{
                        regexp: '^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$',
                        message: 'The password must contains lowercase, uppercase, digit, special character and minimum 8 character'
                    }
                }
            },
            password_confirmation: {
                validators: {
                    notEmpty: {},
                    identical: {
                        field: 'password',
                    }
                }
            },
        };
        Installation.callFormValidation(validators)
            .on('success.form.fv', function(e) {
            e.preventDefault();
            Installation.parents('#app').waitMeShow();
            var $form = $(e.target),
                fv    = $form.data('formValidation');
            axios.post($form.attr('action'), $form.serialize())
            .then((response) => {
                window.location.href = '/login';
            })
            .catch((error) => {
                Installation.parents('#app').waitMeHide();
                fv.resetForm(true);
            });
        });
    });
</script>
@endsection