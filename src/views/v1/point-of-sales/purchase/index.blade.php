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
                        <table id="purchase" class="table table-hover dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Seri</th>
                                    <th>Barcode</th>
                                    <th>Product</th>
                                    <th>Selling Price</th>
                                    <th>Discount</th>
                                    <th>Unit</th>
                                    <th>Tax</th>
                                    <th>Cost Of Purchase</th>
                                    <th>Stock</th>
                                    <th>Print</th>
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
@endsection
@section('js')
<script>
    $('body').addClass('sidebar-collapse');
    const Table = $('#purchase').callDatatables(
        [
            { data: 'id', name: 'id', orderable: true, searchable: true, width: '3%' },
            { data: 'product_seri', name: 'product_seri', orderable: true, searchable: true },
            { data: 'barcode', name: 'barcode', orderable: true, searchable: true },
            { data: 'product_name', name: 'product_name', orderable: true, searchable: true },
            { data: 'selling_price', name: 'selling_price', orderable: true, searchable: true },
            { data: 'product_discount', name: 'product_discount', orderable: true, searchable: true },
            { data: 'product_unit', name: 'product_unit', orderable: true, searchable: true },
            { data: 'tax_amount', name: 'tax_amount', orderable: true, searchable: true },
            { data: 'cost_of_purchase', name: 'cost_of_purchase', orderable: true, searchable: true },
            { data: 'product_stock', name: 'product_stock', orderable: true, searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        [
            {
                className: 'text-center', 'targets': [ 1, 6, 9, -1 ],
            },
            {
                responsivePriority: 0, 'targets': [ 0, 6, -2 ],
            },
            {
                className: 'text-right', 'targets': [ 4, 5 ]
            },
            { 
                width: '10%', 'targets': [ 4, 5, 6, 8, 9]
            },
        ], 3, 'asc'
    );
    const purchasingData = (query) => {
        @if (auth()->user()->canStorePurchase())
            $('.box').waitMeShow();
            const Axios = axios.post('/purchase', query);
                Axios.then((response) => {
                    successResponse(response.data);
                    Table.ajax.reload();
                    $('.box').waitMeHide();
                });
                Axios.catch((error) => {
                    failedResponse(error);
                    $('.box').waitMeHide();
                });
        @endif
    };
    $(document).on({
        keypress: function(e){
            const _this = $(this);
            _this.formatPrice();
            if(e.keyCode === 13){
                const selling_price = toInt(_this.closest('tr').children().eq(4).text());
                const query = {
                    cost_of_purchase: Math.round(toInt(_this.val())),
                    id: _this.data('id')
                };
                if(Math.round(toInt(_this.val())) > Math.round(selling_price)){
                    warning('Harga beli terlalu tinngi.');
                }else{
                    purchasingData(query);
                }
            }
        },
    }, '.cost_of_purchase_table');
    $(document).on({
        focus: function(){
            $(this).select();
        },
        keypress: function(event){
            const _this = $(this);
            const key = window.event ? event.keyCode : event.which;
            if(key === 13){
                const query = {
                    stock: parseInt(_this.val()),
                    id: _this.data('id')
                };
                purchasingData(query);
            }
            if (event.keyCode === 8 || event.keyCode === 46) {
                return true;
            } else if ( key < 48 || key > 57 ) {
                return false;
            } else if(parseInt(_this.val()) < 1){
                _this.val('').trigger('change');
                return false;
            } else {
                return true;
            }
        },
    }, '.product_stock_table');
    @if (auth()->user()->canCreatePurchase())
        $(document).on({
            click: function(){
                if(!$('#purchase_filter').children('#product_id').length){
                    $('#purchase_filter').prepend('<select class="form-control select2" id="product_id" data-placeholder="Product" style="width: 250px;"></select>');
                    $('#product_id').callSelect2({
                        ajax: true,
                        url: `${window.App.APP_ROUTE}/product`,
                        modal: false,
                        width: '250px'
                    }).on('select2:selecting', function (e) { 
                        $(this).sweetAlert('Product baru akan ditambahkan')
                        .then((apply) => {
                            if(apply){
                                $('.box').waitMeShow();
                                axios.put(`/purchase/${e.target.value}`)
                                .then((response) => {
                                    successResponse(response.data);
                                    Table.ajax.reload();
                                    $('.box').waitMeHide();
                                    $('#purchase_filter').children().eq(0).detach();
                                }).catch((error) => {
                                    failedResponse(error);
                                    $('.box').waitMeHide();
                                    $('#purchase_filter').children().eq(0).detach();
                                });
                            }
                            $('#purchase_filter').children().eq(0).detach();
                        });
                    });
                }
            }
        }, '.new-purchase');
    @endif
</script>
@endsection