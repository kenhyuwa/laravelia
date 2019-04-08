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
                        <table id="brand" class="table table-hover dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Logo</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Discount</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th>Action</th>
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
@if (auth()->user()->canCreateBrands() || auth()->user()->canUpdateBrands())
@component(__v() . '.components.modal', [
    'action' => route('brands.store'),
    'method' => 'POST',
    'target' => 'brands',
    'type' => '',
    'title' => 'create brand',
    'class' => '',
    'footer' => true
    ])
    <div class="form-group">
        <label for="code" class="col-sm-4 control-label">Brand Code</label>
        <div class="col-sm-3">
            <p class="input-no-border mb-0 mt-5 text-bold" id="code"></p>
            <input type="hidden" name="brand_code" id="brand_code">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-4 control-label">Brand Name</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="name" placeholder="Brand Name" name="brand_name" autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <label for="discount" class="col-sm-4 control-label">Discount <small>(Percentase)</small></label>
        <div class="col-sm-8">
            <input type="text" class="js-range-slider" id="discount" name="brand_discount" value="" />
        </div>
    </div>
    <div class="form-group">
        <label for="brand_logo" class="col-sm-4 control-label">{{ _('Brand Logo') }}</label>
        <div class="col-sm-8">
            <div class="pic-lg add_brand_logo">
                <div class="viewPix-foto" onclick="javascript:addImages('brand_logo')">
                    <div class='plus-foto plus_brand_logo'>
                        <i class='fa fa-picture-o'></i>
                    </div>
                </div>
            </div>
            <div class="pic-lg preview_brand_logo">
                <div class="viewPix-foto">
                    <img id="img_brand_logo" src="" class='' alt='' />
                    <div class="delete_img" id="del_foto" onclick="javascript:removeImages('brand_logo','150px')"><i class="fa fa-close"></i></div>
                </div>
            </div>
            <input type="file" accept="image/*" name="brand_logo" id="brand_logo" style="opacity: 0.0;width:1px" OnChange="javascript:updloadImages(this.id)">
            <input type="hidden" name="remove_brand_logo" id="remove_brand_logo"/>
        </div>
    </div>
@endcomponent
@endif
@endsection
@section('js')
<script>
    const Table = $('#brand').callDatatables(
        [
            { data: 'id', name: 'id', orderable: true, searchable: false, width: '3%' },
            { data: 'logo', name: 'logo', orderable: false, searchable: false },
            { data: 'code', name: 'brand_code', orderable: true, searchable: true },
            { data: 'name', name: 'brand_name', orderable: true, searchable: true },
            { data: 'discount', name: 'brand_discount', orderable: true, searchable: true },
            { data: 'created_by', name: 'createdBy.name', orderable: true, searchable: true },
            { data: 'updated_by', name: 'updatedBy.name', orderable: true, searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        [
            {
                responsivePriority: 0,
                className: 'text-center', 'targets': [ 0, -1, 1 ],
            }
        ], 2, 'asc'
    );
    @if (auth()->user()->canCreateBrands() || auth()->user()->canUpdateBrands())
    let id = '';
    const BrandForm = $('#brandsModal');
    const validators = {
        brand_name: {
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
                            name: validator.getFieldElements('brand_name').val(),
                        };
                    },
                    message: 'Bsrand Name sudah digunakan.',
                    type: 'GET'
                }
            }
        },
        brand_discount: {
            validators: {
                numeric: {},
                between: {
                    min: 0,
                    max: 100
                },
            }
        },
        brand_logo: {
            validators: {
                file: {
                    extension: 'jpeg,jpg,png',
                    type: 'image/jpeg,image/png',
                    maxSize: 2097152,   // 2048 * 1024
                    message: 'The selected file is not valid'
                }
            }
        },
    };
    BrandForm.callFormValidation(validators)
    .on('focus', '#name', function(){
        if($(this).val().length > 0) $(this).select();
    })
    .on('success.form.fv', function(e) {
        e.preventDefault();
        BrandForm.find('.modal-content').waitMeShow();
        const Text = BrandForm.find('.modal-title').text();
        const $form = $(e.target),
            formData = new FormData(),
            params   = $form.serializeArray(),
            files    = $form.find('[name="brand_logo"]')[0].files,
            fv    = $form.data('formValidation');
            $.each(files, function(i, file) {
                formData.append('brand_logo', file);
            });
            $.each(params, function(i, val) {
                formData.append(val.name, val.value);
            });
        const Axios = axios.post($form.attr('action'), formData);
            Axios.then((response) => {
                BrandForm.find('.modal').modal('hide');
                successResponse(response.data);
                Table.ajax.reload();
            });
            Axios.catch((error) => {
                failedResponse(error);
                BrandForm.find('.modal').modal('hide');
            });
    });
    BrandForm.find('.modal').on('hidden.bs.modal', function(){
        BrandForm.find('.modal-title').html('CREATE BRAND'); id = '';
    });
    @endif
    $(document).on('click', '.new-brands', function(e){
        e.preventDefault();
        @if (auth()->user()->canCreateBrands())
            BrandForm.attr('action', '/brands');
            BrandForm.find('.modal').modal('show');
            const Axios = axios.get('/code-brand');
                Axios.then((response) => {
                    BrandForm.find('[name="_method"]').val('POST');
                    BrandForm.find('#code').text(response.data.data);
                    BrandForm.find('#brand_code').val(response.data.data);
                    BrandForm.find('#name').val('').focus();
                    BrandForm.find('.js-range-slider').ionSliderPercent();
                    BrandForm.find('#del_foto').click();
                    BrandForm.find('.add_brand_logo').removeClass('hidden');
                    BrandForm.find('.preview_brand_logo').css('display', 'none');
                });
                Axios.catch((error) => {
                    failedResponse(error);
                    BrandForm.find('.modal').modal('hide');
                });
        @endif
    });
    $(document).on('click', '._edit', function(e){
        e.preventDefault();
        @if (auth()->user()->canUpdateBrands())
        BrandForm.find('.modal-title').html('EDIT BRAND');
        BrandForm.attr('action', `/brands/${$(this).data('id')}`);
        BrandForm.find('.modal').modal('show');
        const Axios = axios.get(`/brands/${$(this).data('id')}/edit`);
            Axios.then((response) => {
                id = $(this).data('id');
                BrandForm.find('[name="_method"]').val('PUT');
                BrandForm.find('#code').text(response.data.data.brand_code);
                BrandForm.find('#brand_code').val(response.data.data.brand_code);
                BrandForm.find('#name').val(response.data.data.brand_name).focus();
                BrandForm.find('#img_brand_logo').attr('src', response.data.data.logo);
                BrandForm.find('.js-range-slider').ionSliderPercent(function(data){},response.data.data.brand_discount, response.data.data.brand_discount);
                BrandForm.find('.add_brand_logo').addClass('hidden');
                if(response.data.data.logo == ''){
                    BrandForm.find('#del_foto').click();
                }
                BrandForm.find('.preview_brand_logo').css('display', response.data.data.logo != '' ? 'block' : 'none');
            });
            Axios.catch((error) => {
                failedResponse(error);
                BrandForm.find('.modal').modal('hide');
            });
        @endif
    });
    $(document).on('click', '._destroy', function(e){
        e.preventDefault();
        @if (auth()->user()->canDestroyBrands())
        const _this = $(this);
        _this.sweetAlert().then((aggre) => {
            if (aggre) {
                $('.content').find('.box').waitMeShow();
                const Axios = _this.destroy(`brands/${_this.data('id')}`);
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