@extends(__v() . '.layouts.app')

@section('content')
<section class="content">
    {{ callout_info('Setting application & connection.') }}
    <div class="row">
        <div class="col-md-5">
            <div class="row">
                {{-- @if(auth()->user()->canStoreApplication()) --}}
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 can-focus">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab" aria-expanded="false">{{ str_title('application') }}</a>
                                    </li>
                                    @if(auth()->user()->canStoreApplication())
                                    <li>
                                        <a href="#tab_2" data-toggle="tab" aria-expanded="false">{{ str_title('database') }}</a>
                                    </li>
                                    <li>
                                        <a href="#tab_3" data-toggle="tab" aria-expanded="false">{{ str_title('environment') }}</a>
                                    </li>
                                    @endif
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <form id="appUpdate" class="form-horizontal" action="{{ route('application.store') }}" method="POST">
                                            @csrf
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="appName" class="col-sm-4 control-label">APP NAME</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control focused" id="appName" placeholder="APP NAME" name="appName" value="{{ __config(true)['APP_NAME'] }}" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="appLocale" class="col-sm-4 control-label">APP LANGUAGE</label>
                                                    <div class="col-sm-8">
                                                        <select id="appLocale" class="form-control select2" name="appLocale" data-placeholder="APP LANGUAGE"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="appPrefix" class="col-sm-4 control-label">APP PREFIX</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control focused" id="appPrefix" placeholder="APP PREFIX" name="appPrefix" value="{{ __config(true)['APP_PREFIX'] }}" autocomplete="off">
                                                    </div>
                                                </div>
                                                @if(auth()->user()->canStoreApplication())
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-info btn-flat pull-right">Save</button>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                    @if(auth()->user()->canStoreApplication())
                                    <div class="tab-pane" id="tab_2">
                                        <form id="databaseUpdate" class="form-horizontal" action="{{ route('application.store') }}" method="POST">
                                            @csrf
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="appDatabase" class="col-sm-4 control-label">DB NAME</label>
                                                    <div class="col-sm-8">
                                                        <select id="appDatabase" class="form-control callSelect2" name="appDatabase" data-placeholder="APP NAME">
                                                            <option value=""></option>
                                                            @foreach ($databases as $v)
                                                            <option value="{{ $v }}" {{ $v == env('DB_DATABASE') ? 'selected' : '' }}>{{ $v }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="dbUsername" class="col-sm-4 control-label">DB USERNAME</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control focused" id="dbUsername" placeholder="DB USERNAME" name="dbUsername" value="{{ env('DB_USERNAME') }}" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="dbPassword" class="col-sm-4 control-label">DB PASSWORD</label>
                                                    <div class="col-sm-8">
                                                        <input type="password" class="form-control focused" id="dbPassword" placeholder="DB PASSWORD" name="dbPassword" value="{{ env('DB_PASSWORD') }}" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-info btn-flat pull-right">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="tab_3">
                                        <form id="appEnvironment" class="form-horizontal" action="{{ route('application.store') }}" method="POST">
                                            @csrf
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="appEnv" class="col-sm-4 control-label">APP ENV</label>
                                                    <div class="col-sm-8">
                                                        <div class="btn-group" data-toggle="buttons">
                                                            <label class="btn btn-{{ env('APP_ENV') == 'local' ? 'info' : 'default' }} btn-flat btn-sm btn-checkbox appEnv">
                                                                <input type="radio" name="appEnv" value="local" checked="{{ env('APP_ENV') == 'local' ? 'true' : 'false' }}"> Local
                                                            </label>
                                                            <label class="btn btn-{{ env('APP_ENV') == 'staging' ? 'info' : 'default' }} btn-flat btn-sm btn-checkbox appEnv">
                                                                <input type="radio" name="appEnv" value="staging" checked="{{ env('APP_ENV') == 'staging' ? 'true' : 'false' }}">Staging
                                                            </label>
                                                            <label class="btn btn-{{ env('APP_ENV') == 'production' ? 'info' : 'default' }} btn-flat btn-sm btn-checkbox appEnv">
                                                                <input type="radio" name="appEnv" value="production" checked="{{ env('APP_ENV') == 'production' ? 'true' : 'false' }}"> Production
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="appDebug" class="col-sm-4 control-label">APP DEBUG</label>
                                                    <div class="col-sm-8">
                                                        <div class="btn-group" data-toggle="buttons">
                                                            <label class="btn btn-{{ env('APP_DEBUG') == true ? 'info' : 'default' }} btn-flat btn-sm btn-checkbox appDebug">
                                                                <input type="radio" name="appDebug" value="true" checked="{{ env('APP_DEBUG') == true ? 'true' : 'false' }}"> True
                                                            </label>
                                                            <label class="btn btn-{{ env('APP_DEBUG') == false ? 'info' : 'default' }} btn-flat btn-sm btn-checkbox appDebug">
                                                                <input type="radio" name="appDebug" value="false" checked="{{ env('APP_DEBUG') == false ? 'true' : 'false' }}">False
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-info btn-flat pull-right">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                                {{ box_footer() }}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @endif --}}
                {{-- @if(auth()->user()->canStoreApplication()) --}}
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 can-focus">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{ str_title('stroke setting') }}</h3>
                                    <div class="box-tools pull-right">
                                        {{-- <button type="button" class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#createDatabase">Create Database</button> --}}
                                        {{ box_collapse('collapse') }}
                                        {{ box_remove('remove') }}
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="col-md-12">
                                        <form id="stroke" class="form-horizontal" action="{{ route('application.store') }}" method="POST">
                                            @csrf
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="counter_prefix" class="col-sm-12 control-label text-left">FORMAT NO. TRANSAKSI</label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-right focused" id="counter_prefix" placeholder="FORMAT NO. TRANSAKSI" name="counter_prefix" value="{{ $prefix->prefix }}" autocomplete="off" style="padding-right: 10px;letter-spacing: 1.5px;">
                                                            <input type="hidden" name="counter_id" value="{{ $prefix->id }}">
                                                            <span class="input-group-addon text-bold" style="letter-spacing: 1.5px;">
                                                                {{ "/".date('Ymd')."/".roman((intVal(date('Y')) - 2018) + 1)."/{$prefix->counter}" }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(auth()->user()->canStoreApplication())
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-info btn-flat pull-right">Save</button>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                {{ box_footer() }}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @endif --}}
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 can-focus">
                            <div class="box {{ auth()->user()->canStoreApplication() ? 'collapsed-box' : '' }}">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{ str_title('printer setting') }}</h3>
                                    <div class="box-tools pull-right">
                                        {{-- <button type="button" class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#createDatabase">Create Database</button> --}}
                                        {{ box_collapse('collapse') }}
                                        {{ box_remove('remove') }}
                                    </div>
                                </div>
                                <div class="box-body" style="{{ auth()->user()->canStoreApplication() ? 'display: none;' : '' }}">
                                    <div class="col-md-12">
                                        <form id="printer" class="form-horizontal" action="{{ route('application.own') }}" method="POST">
                                            @csrf
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="printer_key" class="col-sm-4 control-label">PRINTER KEY</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control focused" id="printer_key" placeholder="PRINTER KEY" name="printer_key" value="{{ env('PRINTER_KEY') }}" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="printer_port" class="col-sm-4 control-label">PRINTER PORT</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control focused" id="printer_port" placeholder="PRINTER PORT" name="printer_port" value="{{ env('PRINTER_PORT') }}" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-info btn-flat pull-right">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                {{ box_footer() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-12 can-focus">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ str_title('theme setting') }}</h3>
                            <div class="box-tools pull-right">
                                {{ box_collapse('collapse') }}
                                {{ box_remove('remove') }}
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="col-md-12">
                                @foreach (config('litepie')['themes'] as $v)
                                <div class="box box-solid">
                                    <div class="box-body select-theme">
                                        <h4>
                                        {{ strtoupper('Theme ' . $v['version']) }}
                                        </h4>
                                        <div class="media">
                                            <div class="lightgallery media-left">
                                                <a href="{{ asset($v['version'] . '/img/' . $v['images']) }}" class="ad-click-event">
                                                    <img src="{{ asset($v['version'] . '/img/' . $v['images']) }}" alt="{{ $v['name'] }}" class="media-object">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <div class="clearfix">
                                                    @if($v['status'])
                                                    <p class="pull-right">
                                                        @if (__v() == $v['version'])
                                                        <button type="button" {{ tooltip('AKTIV') }} class="btn btn-success btn-xs btn-flat" disabled>
                                                        AKTIV
                                                        </button>
                                                        @else
                                                        <a {{ tooltip('NON AKTIV') }} data-version="{{ $v['version'] }}" class="btn btn-danger btn-xs btn-flat version">
                                                            NON AKTIV
                                                        </a>
                                                        @endif
                                                    </p>
                                                    @endif
                                                    <h4 class="text-center mt-0">{{ $v['name'] }}</h4>
                                                    <p class="text-center">{{ $v['description'] }}</p>
                                                </div>
                                                <form id="theme-form-{{ $v['version'] }}" action="{{ route('application.own') }}" method="POST" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" name="version" value="{{ $v['version'] }}">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        {{ box_footer() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@component(__v() . '.components.modal', [
    'action' => route('application.create'),
    'method' => '',
    'target' => 'createDatabase',
    'type' => '',
    'title' => 'CREATE DATABASE',
    'class' => '',
    'footer' => true
    ])
    <div class="form-group">
        <label for="createDB" class="col-sm-3 control-label">DB Name</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="createDB" placeholder="DB Name" name="createDB" autocomplete="off">
        </div>
    </div>
@endcomponent
@endsection

@section('js')
<script>
    $(function(){
        @if(auth()->user()->canStoreApplication())
        let message = '';
        let differentDB = '{{ env("DB_DATABASE") }}';
        let selectedDB = '{{ env("DB_DATABASE") }}';
        let Form = $('#databaseUpdate');
        Form.find('#appDatabase').on('change', function(){
            differentDB = Form.find('#appDatabase').find(':selected').text();
            if(differentDB != selectedDB){
                message = 'You must login again after move database is successfully.';
            }else{
                message = '';
            }
        });
        let dbValidatorFields = {
            appDatabase: {
                validators: {
                    notEmpty: {}
                }
            },
        };
        Form.callFormValidation(dbValidatorFields)
        .on('success.form.fv', function(e) {
            e.preventDefault();
            let $form = $(e.target),
                fv    = $form.data('formValidation');
            Form.sweetAlert(message)
            .then((aggre) => {
                if (aggre) {
                    Form.parents('.box').waitMeShow();
                    fv.defaultSubmit();
                }
                fv.disableSubmitButtons(false);
            });
        });

        let FormModal = $('#createDatabaseModal');
        let validatorModalFields = {
            createDB:{
                validators:{
                    notEmpty:{}
                }
            }
        };
        FormModal.callFormValidation(validatorModalFields)
        .on('success.form.fv', function(e) {
            e.preventDefault();
            let $form = $(e.target),
                fv    = $form.data('formValidation');
            FormModal.sweetAlert('').then((aggre) => {
                if (aggre) {
                    FormModal.find('.modal-content').waitMeShow();
                    fv.defaultSubmit();
                }
                fv.disableSubmitButtons(false);
            });
        });

        let AppForm = $('#appUpdate');
        let appValidatorFields = {
            appName: {
                validators: {
                    notEmpty: {},
                }
            },
            appLocale: {
                validators: {
                    notEmpty: {}
                }
            },
            appPrefix:{
                validators:{
                    regexp:{
                        regexp:'^[a-z0-9]+(?:-[a-z0-9]+)*$',
                        message: 'example: admin-panel'
                    }
                }
            }
        };
        AppForm.callFormValidation(appValidatorFields)
        .on('success.form.fv', function(e) {
            e.preventDefault();
            let $form = $(e.target),
                fv    = $form.data('formValidation');
            AppForm.sweetAlert('').then((aggre) => {
                if (aggre) {
                    AppForm.parents('.box').waitMeShow();
                    fv.defaultSubmit();
                }
                fv.disableSubmitButtons(false);
            });
        });
        $('#appLocale').callSelect2({
            ajax: true,
            url: `${window.App.APP_ROUTE}/lang`,
            modal: false
        });
        $('#appLocale').setValueSelect2({
            @php
                $lang = [
                    "en" => [
                        "id" => "Indonesian",
                        "en" => "English"
                    ],
                    "id" => [
                        "id" => "Indonesia",
                        "en" => "Inggris"
                    ],
                ];
            @endphp
            id: '{{ $lang[app()->getLocale()][app()->getLocale()] }}',
            text: '{{ $lang[app()->getLocale()][app()->getLocale()] }}'
        });

        let StrokeFrom = $('#stroke');
        let validatorStrokeFromFields = {
            counter_prefix:{
                validators:{
                    notEmpty:{}
                }
            },
        };
        StrokeFrom.callFormValidation(validatorStrokeFromFields)
        .on('keyup', '#counter_prefix', function(e){
            $(this).val(e.target.value.toUpperCase());
        })
        .on('success.form.fv', function(e) {
            e.preventDefault();
            let $form = $(e.target),
                fv    = $form.data('formValidation');
            StrokeFrom.sweetAlert('').then((aggre) => {
                if (aggre) {
                    StrokeFrom.parents('.box').waitMeShow();
                    fv.defaultSubmit();
                }
                fv.disableSubmitButtons(false);
            });
        });
        $(document).on({
            change: function(){
                $(this).addClass('btn-info').siblings().removeClass('btn-info').addClass('btn-default');
            }
        }, '.appEnv');
        $(document).on({
            change: function(){
                $(this).addClass('btn-info').siblings().removeClass('btn-info').addClass('btn-default');
            }
        }, '.appDebug');
        let EnvForm = $('#appEnvironment');
        let envValidatorFields = {
            appEnv: {
                validators: {
                    notEmpty: {},
                }
            },
            appDebug: {
                validators: {
                    notEmpty: {}
                }
            },
        };
        EnvForm.callFormValidation(appValidatorFields)
        .on('success.form.fv', function(e) {
            e.preventDefault();
            let $form = $(e.target),
                fv    = $form.data('formValidation');
            EnvForm.sweetAlert('').then((aggre) => {
                if (aggre) {
                    EnvForm.parents('.box').waitMeShow();
                    fv.defaultSubmit();
                }
                fv.disableSubmitButtons(false);
            });
        });
        @endif

        let PrinterFrom = $('#printer');
        let validatorPrinterFromFields = {
            printer_key:{
                validators:{
                    notEmpty:{}
                }
            },
            printer_port:{
                validators:{
                    notEmpty:{}
                }
            },
        };
        PrinterFrom.callFormValidation(validatorPrinterFromFields)
        .on('success.form.fv', function(e) {
            e.preventDefault();
            let $form = $(e.target),
                fv    = $form.data('formValidation');
            PrinterFrom.sweetAlert('').then((aggre) => {
                if (aggre) {
                    PrinterFrom.parents('.box').waitMeShow();
                    fv.defaultSubmit();
                }
                fv.disableSubmitButtons(false);
            });
        });
        $('.version').on('click', function(event){
            event.preventDefault();
            const version = $(this).data('version');
            $('#theme-form-'+version).sweetAlert('You will change the theme').then((aggree) => {
                if(aggree){
                    $('#theme-form-'+version).submit();
                }
            });
        });
    });
</script>
@endsection