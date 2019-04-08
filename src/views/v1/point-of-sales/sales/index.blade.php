@extends(__v() . '.point-of-sales.sales.app')

@section('content')
<section class="content">
    <form action="" method="POST" id="transactions">
        <div class="row">
            <div class="col-md-12 casier can-focus pdb-15">
                <transactions user="{{ ucwords(auth()->user()->name) }}"></transactions><hr>
                <div class="progress progress-xxs active">
                    <div id="progress" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span class="sr-only">0% Complete</span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-group margin col-md-3">
                        <input type="text" class="form-control mousetrap" id="code" placeholder="{{ __('pos.sales.find_a_product_by_code') }}">
                            <toggler></toggler>
                      </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="tbl-header">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 30px">No</th>
                                        <th style="width: 150px" class="text-center">{{ __('pos.sales.table.barcode') }}</th>
                                        <th class="text-center">{{ __('pos.sales.table.product') }}</th>
                                        <th style="width: 100px" class="text-center">{{ __('pos.sales.table.quantity') }}</th>
                                        <th style="width: 50px" class="text-center">{{ __('pos.sales.table.unit') }}</th>
                                        <th style="width: 180px;" class="text-center">{{ __('pos.sales.table.price') }}</th>
                                        <th style="width: 100px" class="text-center">{{ __('pos.sales.table.discount') }}</th>
                                        <th style="width: 180px;" class="text-center">{{ __('pos.sales.table.total') }}</th>
                                        <th style="width: 50px;" class="text-center">
                                            <i class="fa fa-cog"></i>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tbl-content">
                            <table id="table-content" class="table">
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="button" id="nextToPay" class="btn btn-flat btn-md btn-success pull-right">{{ __('Pay') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="transaction_bill" class="bayar">
        {{-- <casier 
            app_name="{{ __config(true)['APP_NAME'] }}"
            casier_name="{{ ucwords(auth()->user()->name) }}"
            ></casier> --}}
    </form>
</section>

<product></product>

@component(__v() . '.components.modal', [
    'action' => '',
    'method' => '',
    'target' => 'endTransaction',
    'type' => '',
    'title' => '',
    'class' => 'notValidate',
    'footer' => true,
    'attributes' => 'data-backdrop=static'
    ])
    <div class="form-group">
        <label for="total" class="col-sm-3 control-label">{{ __('pos.sales.table.total') }} :</label>
        <div class="col-sm-9">
            <div id="nominal_price_modal" class="nominal_price price">0</div>
            <input type="hidden" name="total_price" id="nominal_price_modal_val" class="nominal_price_val">
        </div>
    </div>
    <div class="form-group">
        <label for="bayar" class="col-sm-3 control-label">{{ __('pos.sales.pay') }} :</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-lg text-right white-color input-no-border nominal_price price" id="bayar" autocomplete="off" value="0" onfocus="$(this).select();">
        </div>
    </div>
@endcomponent

@component(__v() . '.components.modal', [
    'action' => '',
    'method' => '',
    'target' => 'information',
    'type' => 'modal-lg',
    'title' => __('pos.sales.information.shortcut_information'),
    'class' => 'notValidate',
    'footer' => false,
    'attributes' => 'data-backdrop=static'
    ])
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 10px" class="text-center">NO</th>
                            <th style="width: 150px" class="text-center">WINDOWS</th>
                            <th style="width: 150px" class="text-center">MACBOOK</th>
                            <th style="width: 150px" class="text-center">{{ __('pos.sales.information.information') }}</th>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td style="width: 10px" class="text-center">1</td>
                            <td>CTRL + ALT + SHIFT</td>
                            <td>COMMAND + ALT + SHIFT</td>
                            <td>{{ __('pos.sales.information.find_a_product') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 10px" class="text-center">2</td>
                            <td>CTRL + ALT + A</td>
                            <td>COMMAND + ALT + A</td>
                            <td>{{ __('pos.sales.information.quantity') }} </td>
                        </tr>
                        <tr>
                            <td style="width: 10px" class="text-center">3</td>
                            <td>CTRL + ALT + F</td>
                            <td>COMMAND + ALT + F</td>
                            <td>{{ __('pos.sales.information.focus_find_product') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 10px" class="text-center">4</td>
                            <td>CTRL + ALT + Z</td>
                            <td>COMMAND + ALT + Z</td>
                            <td>{{ __('pos.sales.information.delete_product') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 10px" class="text-center">5</td>
                            <td>END</td>
                            <td>END</td>
                            <td>{{ __('pos.sales.information.end_transaction') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 10px" class="text-center">6</td>
                            <td>Esc</td>
                            <td>Esc</td>
                            <td>{{ __('pos.sales.information.close_dialog') }}</td>
                        </tr>
                        <tr>
                            <td style="width: 10px" class="text-center">7</td>
                            <td>Enter</td>
                            <td>Enter</td>
                            <td>{{ __('pos.sales.information.done') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endcomponent

@endsection

@section('js')
<script>
    (function($){
        let array_products = [];
        const _table = $('#table-content').children('tbody');
        $('.progress').css('display', 'none');
        $('.navbar').children('.sidebar-toggle').detach();
        $('.navbar').append('<a href="/" class="sidebar-toggler" {{ tooltip(trans('global.tooltip.back'), 'right') }}><i class="fa fa-chevron-left"></i></a>');
        $('.sidebar-toggler').css({
            'float': 'left',
            'background-color': 'transparent',
            'background-image': 'none',
            'padding': '15px 15px',
            'font-family': 'fontAwesome',
        });
        $('.content-wrapper, .main-footer').attr('style', 'margin-left: 0px !important');
        $('.price').formatPrice();
        $(window).on("load resize ", function() {
          var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
          $('.tbl-header').css({'padding-right':scrollWidth});
        }).resize();
        $('#code').focus().on('keypress', function(e){
            const barcode = $(this).val();
            if(barcode != ''){
                const Endpoint = `${window.App.APP_ROUTE}/create?code=${barcode}`;
                const Request = axios.get(Endpoint);
                Request.then((response) => {
                    const product = response.data.data;
                    if(Object.keys(product).length > 0){
                        if(!_.includes(array_products, product.id)){
                            _table.append(
                                '<tr data-id="'+ product.id +'" class="data_'+ product.id +'">'+
                                    '<td style="width: 30px" class="numbering"></td>'+
                                    '<td style="width: 150px" class="text-center">'+ product.product_barcode +'</td>'+
                                    '<td>'+ product.product_name.toUpperCase() +'</td>'+
                                    '<td style="width: 100px;padding: 0px;" class="text-center">'+
                                        '<input type="text" class="form-control text-center input-no-border qty mousetrap" name="products['+ product.id +'][quantity]" value="1" />'+
                                    '</td>'+
                                    '<td style="width: 50px" class="text-center">'+ product.product_unit.toUpperCase() +'</td>'+
                                    '<td style="width: 180px" class="text-right selling_price">'+ toPrice(product.price) +'</td>'+
                                    '<td style="width: 100px" class="text-center discount">'+ toPrice(product.discount) +'</td>'+
                                    '<td style="width: 180px" class="text-right total">'+ toPrice((product.price - product.discount)) +'</td>'+
                                    '<td style="width: 50px" >'+
                                        '<button type="button" data-id="'+ product.id +'" class="btn btn-flat btn-danger btn-xs" {{ tooltip(trans('global.tooltip.delete'), 'top') }}><i class="fa fa-trash"></i></button>'+
                                    '</td>'+
                                    '<input type="hidden" class="selling_price_val" name="products['+ product.id +'][selling_price]" value="'+ product.price +'" />'+
                                    '<input type="hidden" class="discount_val" name="products['+ product.id +'][discount]" value="'+ product.discount +'" />'+
                                    '<input type="hidden" class="total_val" name="products['+ product.id +'][total]" value="'+ (product.price - product.discount) +'" />'+
                                    '<input type="hidden" class="stock_val" value="'+ product.purchase +'" />'+
                                '</tr>'
                            );
                            $('.price').formatPrice();
                            $('#code').val('').blur();
                            array_products.push(product.id);
                            setNominal();
                            $('.qty').focus().select().on('keypress', justNumber);
                        }else{
                            const selector = _table.children('.data_' + product.id);
                            const qty = parseInt(selector.find('.qty').val()) + 1;
                            const sell_price = Math.round(selector.find('.selling_price_val').val());
                            const disc = parseInt(selector.find('.discount_val').val());
                            const total = (sell_price * qty) - (disc * qty);
                            selector.find('.qty').val(qty);
                            selector.find('.total').text(total);
                            selector.find('.total_val').val(total);
                            $('#code').val('').blur();
                            $('.price').formatPrice();
                            selector.find('.qty').focus().select().on('keypress', function(e){
                                const _this = $(this);
                                calculate(e, _this);
                            });
                            setNominal();
                        }
                        $('.qty').each(function(i){
                            store.set(`quantity_${$(this).parents('tr').data('id')}`, $(this).val());
                        });
                    }else{
                        warning(response.data.message);
                    }
                    $('.numbering').numbering();
                });
                Request.catch((error) => {
                    danger(error.response.statusText);
                });
            }
        }).on('keypress', justNumber);
        $(document).off('focus', '.qty').on('focus', '.qty', function(e){
            const _this = $(this);
            $(this).select().on('keypress', justNumber).on('keypress, keyup', function(){
                if(Number($(this).val()) > $('.stock_val').val()){
                    info('Stock tidak memadai');
                    _this.val(1);
                }else{
                    if(Number($(this).val()) < 1){
                        _this.val(1);
                        calculate(e, _this);
                    }
                    calculate(e, _this);
                }
            });
        });
        $(document).off('click', '.btn-danger').on('click', '.btn-danger', function(){
            const _this = $(this);
            const id = _this.data('id');
            _this.closest('tr').detach();
            $('.numbering').numbering();
            const evens = _.remove(array_products, function(n) {
                return n == id;
            });
            setNominal();
            store.remove(`quantity_${id}`);
            if(array_products.length < 1) $('#code').focus();
        });
        $(document).off('submit', '#endTransactionModal').on('submit', '#endTransactionModal', function(e){
            e.preventDefault();
            if($('.nominal_price_val').val() > Math.round(toInt($('#bayar').val()))){
                info('Jumlah bayar kurang dari '+$('.nominal_price_val').val());
                $('#bayar').focus();
            }else{
                $('#transactions').submit();
                $('#endTransactionModal').find('.modal').modal('hide');
            }
        });
        $(document).off('submit', '#transactions').on('submit', '#transactions', function(e){
            e.preventDefault();
            const formData = $(this);
            const Request = axios.post(`/sales`, formData.serialize());
                Request.then((response) => {
                    formData[0].reset();
                    $('#code').focus();
                    $('#table-content tbody tr').each(function(){
                        const id = $(this).data('id');
                        store.remove(`quantity_${id}`);
                    });
                    array_products = [];
                    _table.children().detach();
                    setNominal();
                    $('#endTransaction').modal('hide');
                    const data = response.data.data;
                    $('#transaction_number').text(':' + data.billing.invoice_number);
                    $('#transaction_number_val').val(data.billing.invoice_number);
                    $('#nominal_price').text(data.billing.change);
                    $('.price').formatPrice();
                    $("#progress").progress();
                    info('Printing processing...')
                    // Printing.open().then(function () {
                    //   Printing.align('center')
                    //     .text('Hello World !!')
                    //     .bold(true)
                    //     .text('This is bold text')
                    //     .bold(false)
                    //     .underline(true)
                    //     .text('This is underline text')
                    //     .underline(false)
                    //     .barcode('CODE39', '123456789')
                    //     .cut()
                    //     .print()
                    // })
                });
                Request.catch((error) => {
                    danger(error.response.statusText);
                });
        });
        $(document).off('keyup', '#bayar').on('keyup', '#bayar', function(){
            $('.bayar').val(Math.round(toInt($(this).val())));
        });
        $(document).on({
            click: function(){
                nextToPay();
            }
        }, '#nextToPay');
        if(typeof store.get('products') != 'undefined'){
            const html = store.get('products');
            $('#table-content').children().html(html);
            $('.numbering').numbering();
            let nominal_price = 0;
            $('.total_val').each(function(){
                nominal_price += Math.round($(this).val());
            });
            $('#nominal_price').text('')
            $('#nominal_price').text(toPrice(nominal_price));
            $('#nominal_price_val').val(nominal_price);
            $('#nominal_price_modal').text('')
            $('#nominal_price_modal').text(toPrice(nominal_price));
            $('#nominal_price_modal_val').val(nominal_price);
            $('.price').formatPrice();
            $('#table-content tbody tr').each(function(){
                const id = $(this).data('id');
                array_products.push(id);
                $(this).children().find('.qty').val(store.get(`quantity_${id}`));
            });
        }
        Mousetrap.bind(['shift+f5', 'f5'],function(){
            swal({
              title: "Are you sure?",
              text: "Once deleted, you will not be able to recover this imaginary file!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                swal("Poof! Your imaginary file has been deleted!", {
                  icon: "success",
                });
              } else {
                swal("Your imaginary file is safe!");
              }
            });
            return false;
        });
        Mousetrap.bind(['ctrl+alt+a', 'command+alt+a'], function(){
            $('.qty').focus().select();
        });
        Mousetrap.bind(['ctrl+alt+z', 'command+alt+z'], function(){
            let id = '';
            _table.children().each((index, value) => {
                if(index == (array_products.length - 1)){
                    id = $(value).data('id');
                }
            });
            $(`.data_${id}`).find('.btn-danger').click();
        });
        Mousetrap.bind(['ctrl+alt+shift', 'command+alt+shift'], function(){
            $('.btn-search').click();
        });
        Mousetrap.bind(['ctrl+alt+f', 'command+alt+f'], function(){
            $('#code').focus();
        });
        Mousetrap.bind('end', function(){
            nextToPay();
        });

        function calculate(e, _this){
            if(e.keyCode == 13){
                const thisVal = _this.val();
                const parent = _this.closest('tr');
                const selling_price = parent.children('.selling_price_val').val();
                const disc = parent.children('.discount_val').val();
                const total = (Math.round(selling_price) * thisVal) - (Math.round(disc) * thisVal);
                parent.children('.total').text(toPrice(total));
                parent.children('.total_val').val(total);
                $('.price').formatPrice();
                $('#code').focus();
                setNominal();
            }
                const thisVal = _this.val();
                const parent = _this.closest('tr');
                const selling_price = parent.children('.selling_price_val').val();
                const disc = parent.children('.discount_val').val();
                const total = (Math.round(selling_price) * thisVal) - (Math.round(disc) * thisVal);
                parent.children('.total').text(toPrice(total));
                parent.children('.total_val').val(total);
                $('.price').formatPrice();
                setNominal();
            $('.qty').each(function(i){
                store.set(`quantity_${$(this).parents('tr').data('id')}`, $(this).val());
            });
        }
        function setNominal(){
            let nominal_price = 0;
            $('.total_val').each(function(){
                nominal_price += Math.round($(this).val());
            });
            $('#nominal_price').text('')
            $('#nominal_price').text(toPrice(nominal_price));
            $('#nominal_price_val').val(nominal_price);
            $('#nominal_price_modal').text('')
            $('#nominal_price_modal').text(toPrice(nominal_price));
            $('#nominal_price_modal_val').val(nominal_price);
            $('#bayar').val('0');
            $('.price').formatPrice();
            storage();
        }
        function storage(){
            const html = $('#table-content').children().html();
            store.set('products', html);
            if(html != ''){
                store.set('path_active', window.App.APP_ROUTE);
            }else{
                store.remove('path_active')
            }
        }

        function nextToPay(){
            if(array_products.length > 0){
                let nominal_price = 0;
                $('.total_val').each(function(){
                    nominal_price += Math.round($(this).val());
                });
                $('#endTransaction').modal('show', {
                    backdrop: false,
                });
                $('#endTransaction').on('hide.bs.modal', function(){
                    $('#bayar').val(0);
                });
                $('#endTransaction').on('shown.bs.modal', function(){
                    $('#bayar').val(toPrice(nominal_price)).focus().select();
                });
                $('.price').formatPrice();
                $(document).off('keypress', '#bayar').on('keypress', '#bayar', function(e){
                    if(e.keyCode == 13){
                        e.preventDefault();
                        if($('.nominal_price_val').val() > Math.round(toInt($(this).val()))){
                            info('Jumlah bayar kurang dari '+$('.bayar').val());
                            $(this).focus();
                        }else{
                            $('#transactions').submit();
                        }
                    }
                });
            }
            return false;
        }
    }(jQuery));
</script>
@endsection
@push('lang')
<script>
    const Language = {!! json_encode([
        'number' => __('pos.sales.number'),
        'datetime' => __('pos.sales.datetime'),
        'casier' => __('pos.sales.casier'),
        'departement' => __('pos.sales.table.departement'),
        'brand' => __('pos.sales.table.brand'),
        'barcode' => __('pos.sales.table.barcode'),
        'product' => __('pos.sales.table.product'),
        'price' => __('pos.sales.table.price'),
        'discount' => __('pos.sales.table.discount'),
        'stock' => __('pos.sales.table.stock'),
        'choose_a_product' => __('pos.sales.product.choose_a_product'),
        'find_a_product' => __('pos.sales.product.find_a_product'),
        'choose' => __('global.tooltip.choose')
    ]) !!};
</script>
@endpush