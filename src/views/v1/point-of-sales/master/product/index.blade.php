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
                        <table id="product" class="table table-hover dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Barcode</th>
                                    <th>Images</th>
                                    <th>Departement</th>
                                    <th>Brand</th>
                                    <th>Name</th>
                                    <th>Unit</th>
                                    <th>Selling Price</th>
                                    <th>Discount</th>
                                    <th>Tax</th>
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
@if (auth()->user()->canCreateProducts() || auth()->user()->canUpdateProducts())
@component(__v() . '.components.modal', [
    'action' => route('products.store'),
    'method' => 'POST',
    'target' => 'products',
    'type' => 'modal-lg',
    'title' => 'create product',
    'class' => '',
    'footer' => true
    ])
    <div class="row">
        <div class="col-md-7">
            <div class="form-group">
                <label for="barcode" class="col-sm-4 control-label">Barcode</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="barcode" placeholder="Barcode" name="product_barcode" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-4 control-label">Product Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="name" placeholder="Product Name" name="product_name" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label for="selling_price" class="col-sm-4 control-label">Selling Price</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control price" id="selling_price" placeholder="Selling Price" name="selling_price" value="0" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label for="product_unit" class="col-sm-4 control-label">Product Unit</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="product_unit" placeholder="Pcs" name="product_unit" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label for="product_discount" class="col-sm-4 control-label">Discount <small>(Percentase)</small></label>
                <div class="col-sm-8">
                    <input type="text" class="js-range-slider" id="product_discount" name="product_discount" value="" />
                </div>
            </div>
            <div class="form-group">
                <label for="tax_amount" class="col-sm-4 control-label">Tax Amount</label>
                <div class="col-sm-4">
                    <input type="checkbox" id="tax_amount" name="tax_amount" data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="danger" data-on="Yes" data-off="No">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="departement_id" class="col-sm-5 control-label">Departement</label>
                <div class="col-sm-7">
                    <select class="form-control select2" id="departement_id" data-placeholder="Departement" name="departement_id"></select>
                </div>
            </div>
            <div class="form-group">
                <label for="brand_id" class="col-sm-5 control-label">Brand</label>
                <div class="col-sm-7">
                    <select class="form-control select2" id="brand_id" data-placeholder="Brand" name="brand_id"></select>
                </div>
            </div>
            <div class="form-group">
                <label for="product_images" class="col-sm-5 control-label">{{ _('Product Images') }}</label>
                <div class="col-sm-7">
                    <div class="pic-lg add_product_images">
                        <div class="viewPix-foto" onclick="javascript:addImages('product_images')">
                            <div class='plus-foto plus_product_images'>
                                <i class='fa fa-picture-o'></i>
                            </div>
                        </div>
                    </div>
                    <div class="pic-lg preview_product_images">
                        <div class="viewPix-foto">
                            <img id="img_product_images" src="" class='' alt='' />
                            <div class="delete_img" id="del_foto" onclick="javascript:removeImages('product_images','150px')"><i class="fa fa-close"></i></div>
                        </div>
                    </div>
                    <input type="file" accept="image/*" name="product_images" id="product_images" style="opacity: 0.0;width:1px" OnChange="javascript:updloadImages(this.id)">
                    <input type="hidden" name="remove_product_images" id="remove_product_images"/>
                </div>
            </div>
        </div>
    </div>
@endcomponent
@endif
@endsection
@section('js')
<script>
    $('body').addClass('sidebar-collapse');
    const Table = $('#product').callDatatables(
        [
            { data: 'id', name: 'id', orderable: true, searchable: false, width: '3%' },
            { data: 'barcode', name: 'product_barcode', orderable: true, searchable: true },
            { data: 'images', name: 'images', orderable: false, searchable: false },
            { data: 'departement', name: 'departement.departement_name', orderable: true, searchable: true },
            { data: 'brand', name: 'brand.brand_name', orderable: true, searchable: true },
            { data: 'name', name: 'product_name', orderable: true, searchable: true },
            { data: 'unit', name: 'product_unit', orderable: true, searchable: true },
            { data: 'price', name: 'selling_price', orderable: true, searchable: true },
            { data: 'discount', name: 'product_discount', orderable: true, searchable: true },
            { data: 'tax_amount', name: 'tax_amount', orderable: true, searchable: true },
            { data: 'created_by', name: 'createdBy.name', orderable: true, searchable: true },
            { data: 'updated_by', name: 'updatedBy.name', orderable: true, searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        [
            {
                responsivePriority: 0,
                className: 'text-center', 'targets': [ 0, -1, 1, 2 ],
            }
        ], 5, 'asc'
    );
    @if (auth()->user()->canCreateProducts() || auth()->user()->canUpdateProducts())
    let id = '';
    const ProductForm = $('#productsModal');
    const validators = {
        product_barcode: {
            validators: {
                notEmpty: {},
                numeric: {},
                remote: {
                    url: `${window.App.APP_ROUTE}/create`,
                    data: function(validator, $field, value) {
                        return {
                            id: id,
                            barcode: validator.getFieldElements('product_barcode').val(),
                        };
                    },
                    message: 'Barcode sudah digunakan.',
                    type: 'GET'
                }
            }
        },
        product_name: {
            validators: {
                notEmpty: {},
                remote: {
                    url: `${window.App.APP_ROUTE}/create`,
                    data: function(validator, $field, value) {
                        return {
                            id: id,
                            name: validator.getFieldElements('product_name').val(),
                        };
                    },
                    message: 'Product Name sudah digunakan.',
                    type: 'GET'
                }
            }
        },
        selling_price: {
            validators: {
                notEmpty: {},
            }
        },
        product_unit: {
            validators: {
                notEmpty: {},
            }
        },
        product_discount: {
            validators: {
                numeric: {},
                between: {
                    min: 0,
                    max: 100
                },
            }
        },
        tax_amount: {
            validators: {
                notEmpty: {},
            }
        },
        departement_id: {
            validators: {
                notEmpty: {},
            }
        },
        brand_id: {
            validators: {
                notEmpty: {},
            }
        },
        product_images: {
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
    ProductForm.callFormValidation(validators)
    .on('keypress', '#barcode', justNumber)
    .on('focus', '#barcode', function(){
        if($(this).val().length > 0) $(this).select();
    })
    .on('focus', '#name', function(){
        if($(this).val().length > 0) $(this).select();
    })
    .on('focus', '#selling_price', function(){
        if($(this).val().length > 0) $(this).select();
    })
    .on('focus', '#product_unit', function(){
        if($(this).val().length > 0) $(this).select();
    })
    .on('success.form.fv', function(e) {
        e.preventDefault();
        ProductForm.find('.modal-content').waitMeShow();
        const $form = $(e.target),
            formData = new FormData(),
            params   = $form.serializeArray(),
            files    = $form.find('[name="product_images"]')[0].files,
            fv    = $form.data('formValidation');
            $.each(files, function(i, file) {
                formData.append('product_images', file);
            });
            $.each(params, function(i, val) {
                formData.append(val.name, val.value);
            });
        const Axios = axios.post($form.attr('action'), formData);
            Axios.then((response) => {
                ProductForm.find('.modal').modal('hide');
                successResponse(response.data);
                Table.ajax.reload();
            });
            Axios.catch((error) => {
                failedResponse(error);
                ProductForm.find('.modal').modal('hide');
            });
    });
    ProductForm.find('.modal').on('hidden.bs.modal', function(){
        ProductForm.find('.modal-title').html('CREATE PRODUCT'); id = '';
    });
    @endif
    $(document).on('click', '.new-products', function(e){
        e.preventDefault();
        @if (auth()->user()->canCreateProducts())
            ProductForm.attr('action', '/products');
            ProductForm.find('.modal').modal('show');
            ProductForm.find('[name="_method"]').val('POST');
            ProductForm.find('#barcode').val('').focus();
            ProductForm.find('#name').val('');
            ProductForm.find('#selling_price').val('');
            ProductForm.find('#product_unit').val('');
            ProductForm.find('.js-range-slider').ionSliderPercent();
            ProductForm.find('#tax_amount').prop('checked', false).change();
            ProductForm.find('#departement_id').callSelect2({
                ajax: true,
                url: `${window.App.APP_ROUTE}/departement`,
                modal: $('#productsModal')
            })
            ProductForm.find('#brand_id').callSelect2({
                ajax: true,
                url: `${window.App.APP_ROUTE}/brand`,
                modal: $('#productsModal')
            })
            ProductForm.find('#brand_id').val('');
            ProductForm.find('#del_foto').click();
            ProductForm.find('.add_product_images').removeClass('hidden');
            ProductForm.find('.preview_product_images').css('display', 'none');
        @endif
    });
    $(document).on('click', '._edit', function(e){
        e.preventDefault();
        @if (auth()->user()->canUpdateProducts())
        ProductForm.find('.modal-title').html('EDIT PRODUCT');
        ProductForm.attr('action', `/products/${$(this).data('id')}`);
        ProductForm.find('.modal').modal('show');
        const Axios = axios.get(`/products/${$(this).data('id')}/edit`);
            Axios.then((response) => {
                id = $(this).data('id');
                ProductForm.find('[name="_method"]').val('PUT');
                ProductForm.find('#barcode').val(response.data.data.product_barcode).focus();
                ProductForm.find('#name').val(response.data.data.product_name);
                ProductForm.find('#selling_price').val(response.data.data.selling_price).formatPrice();
                ProductForm.find('#product_unit').val(response.data.data.product_unit);
                ProductForm.find('.js-range-slider').ionSliderPercent(function(data){},response.data.data.product_discount, response.data.data.product_discount);
                if(response.data.data.tax_amount){
                    ProductForm.find('#tax_amount').prop('checked', true).change();
                }else{
                    ProductForm.find('#tax_amount').prop('checked', false).change();
                }
                ProductForm.find('#departement_id').callSelect2({
                    ajax: true,
                    url: `${window.App.APP_ROUTE}/departement`,
                    modal: $('#productsModal')
                });
                ProductForm.find('#departement_id').setValueSelect2({
                    id: response.data.data.departement_id,
                    text: response.data.data.departement_name
                });
                ProductForm.find('#brand_id').callSelect2({
                    ajax: true,
                    url: `${window.App.APP_ROUTE}/brand`,
                    modal: $('#productsModal')
                });
                ProductForm.find('#brand_id').setValueSelect2({
                    id: response.data.data.brand_id,
                    text: response.data.data.brand_name
                });
                ProductForm.find('#img_product_images').attr('src', response.data.data.product_images);
                ProductForm.find('.add_product_images').addClass('hidden');
                ProductForm.find('.preview_product_images').css('display', response.data.data.product_images != '' ? 'block' : 'none');
                if(response.data.data.product_images == ''){
                    ProductForm.find('#del_foto').click();
                }
            });
            Axios.catch((error) => {
                failedResponse(error);
                ProductForm.find('.modal').modal('hide');
            });
        @endif
    });
    $(document).on('click', '._destroy', function(e){
        e.preventDefault();
        @if (auth()->user()->canDestroyProducts())
        const _this = $(this);
        _this.sweetAlert().then((aggre) => {
            if (aggre) {
                $('.content').find('.box').waitMeShow();
                const Axios = _this.destroy(`products/${_this.data('id')}`);
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