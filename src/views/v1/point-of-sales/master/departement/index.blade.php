@extends(__v() . '.layouts.app')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12 can-focus">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ str_title() }}</h3>
                    <div class="box-tools pull-right">
                        {{ box_collapse('collapse') }}
                        {{ box_remove('remove') }}
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-md-12"> 
                        <table id="departement" class="table table-hover dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{ __('global.table.code') }}</th>
                                    <th>{{ __('global.table.name') }}</th>
                                    <th>{{ __('global.table.discount') }}</th>
                                    <th>{{ __('global.table.created') }}</th>
                                    <th>{{ __('global.table.updated') }}</th>
                                    <th>{{ __('global.table.action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                {{ box_footer() }}
            </div>
        </div>
    </div>
</section>
@if (auth()->user()->canCreateDepartement() || auth()->user()->canUpdateDepartement())
@component(__v() . '.components.modal', [
    'action' => route('departement.store'),
    'method' => 'POST',
    'target' => 'departements',
    'type' => '',
    'title' => 'create departement',
    'class' => '',
    'footer' => true
    ])
    <div class="form-group">
        <label for="code" class="col-sm-4 control-label">Departement Code</label>
        <div class="col-sm-3">
            <p class="input-no-border mb-0 mt-5 text-bold" id="code"></p>
            <input type="hidden" name="departement_code" id="departement_code">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-4 control-label">Departement Name</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="name" placeholder="Departement Name" name="departement_name" autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label for="discount" class="col-sm-4 control-label">Discount <small>(Percentase)</small></label>
        <div class="col-sm-8">
            <input type="text" class="js-range-slider" id="discount" name="departement_discount" value="" />
        </div>
    </div>
@endcomponent
@endif
@endsection
@section('js')
<script>
    const Table = $('#departement').callDatatables(
        [
            { data: 'id', name: 'id', orderable: true, searchable: false, width: '3%' },
            { data: 'code', name: 'departement_code', orderable: true, searchable: true },
            { data: 'name', name: 'departement_name', orderable: true, searchable: true },
            { data: 'discount', name: 'departement_discount', orderable: true, searchable: true },
            { data: 'created_by', name: 'createdBy.name', orderable: true, searchable: true },
            { data: 'updated_by', name: 'updatedBy.name', orderable: true, searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        [
            {
                responsivePriority: 0,
                className: 'text-center', 'targets': [ 0, -1, 1 ],
            }
        ], 1, 'asc'
    );
    @if (auth()->user()->canCreateDepartement() || auth()->user()->canUpdateDepartement())
    let id = '';
    const DepartementForm = $('#departementsModal');
    const validators = {
        departement_name: {
            validators: {
                notEmpty: {},
                stringLength:{
                    min:3,
                },
                remote: {
                    url: `${window.App.APP_ROUTE}/create`,
                    data: function(validator, $field, value) {
                        return {
                            id: id,
                            name: validator.getFieldElements('departement_name').val(),
                        };
                    },
                    message: 'Departement Name sudah digunakan.',
                    type: 'GET'
                }
            }
        },
        departement_discount: {
            validators: {
                numeric: {},
                between: {
                    min: 0,
                    max: 100
                },
            }
        },
    };
    DepartementForm.callFormValidation(validators)
    .on('focus', '#name', function(){
        if($(this).val().length > 0) $(this).select();
    })
    .on('success.form.fv', function(e) {
        e.preventDefault();
        DepartementForm.find('.modal-content').waitMeShow();
        const $form = $(e.target),
            fv    = $form.data('formValidation');
        const Axios = axios.post($form.attr('action'), $form.serialize());
            Axios.then((response) => {
                DepartementForm.find('.modal').modal('hide');
                successResponse(response.data);
                Table.ajax.reload();
            });
            Axios.catch((error) => {
                failedResponse(error);
                DepartementForm.find('.modal').modal('hide');
            });
    });
    DepartementForm.find('.modal').on('hidden.bs.modal', function(){
        DepartementForm.find('.modal-title').html('CREATE DEPARTEMENT'); id = '';
    });
    @endif
    $(document).on('click', '.new-departement', function(e){
        e.preventDefault();
        @if (auth()->user()->canCreateDepartement())
            DepartementForm.attr('action', `/departement`);
            DepartementForm.find('.modal').modal('show');
            const Axios = axios.get('/code-departement');
                Axios.then((response) => {
                    DepartementForm.find('[name="_method"]').val('POST');
                    DepartementForm.find('#code').text(response.data.data);
                    DepartementForm.find('#departement_code').val(response.data.data);
                    DepartementForm.find('#name').val('').focus();
                    DepartementForm.find('.js-range-slider').ionSliderPercent();
                });
                Axios.catch((error) => {
                    failedResponse(error);
                    DepartementForm.find('.modal').modal('hide');
                });
        @endif
    });
    $(document).on('click', '._edit', function(e){
        e.preventDefault();
        @if (auth()->user()->canUpdateDepartement())
        DepartementForm.find('.modal-title').html('EDIT DEPARTEMENT');
        DepartementForm.attr('action', `/departement/${$(this).data('id')}`);
        DepartementForm.find('.modal').modal('show');
        const Axios = axios.get(`/departement/${$(this).data('id')}/edit`);
            Axios.then((response) => {
                id = $(this).data('id');
                DepartementForm.find('[name="_method"]').val('PUT');
                DepartementForm.find('#code').text(response.data.data.departement_code);
                DepartementForm.find('#departement_code').val(response.data.data.departement_code);
                DepartementForm.find('#name').val(response.data.data.departement_name).focus();
                DepartementForm.find('.js-range-slider').ionSliderPercent(function(data){},response.data.data.departement_discount, response.data.data.departement_discount);
            });
            Axios.catch((error) => {
                failedResponse(error);
                DepartementForm.find('.modal').modal('hide');
            });
        @endif
    });
    $(document).on('click', '._destroy', function(e){
        e.preventDefault();
        @if (auth()->user()->canDestroyDepartement())
        const _this = $(this);
        _this.sweetAlert().then((aggre) => {
            if (aggre) {
                $('.content').find('.box').waitMeShow();
                const Axios = _this.destroy(`departement/${_this.data('id')}`);
                Axios.then((response) => {
                    successResponse(response.data);
                    Table.ajax.reload();
                    setTimeout(() => $('.content').find('.box').waitMeHide(), 1000);
                });
                Axios.catch((error) => {
                    $('.content').find('.box').waitMeHide();
                    failedResponse(error);
                });
            }else{
                swal(Label.sweetTextCancel, {
                    icon: 'error',
                });
            }
        });
        @endif
    });
</script>
@endsection