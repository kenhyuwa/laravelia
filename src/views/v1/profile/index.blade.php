@extends(__v() . '.layouts.app')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-7">
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
                            <div class="row">
                                <div class="col-md-12">
                                    
                                </div>
                            </div>
                        </div>
                        {{ box_footer() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-12 can-focus">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ str_title('change password') }}</h3>
                            <div class="box-tools pull-right">
                                {{ box_collapse('collapse') }}
                                {{ box_remove('remove') }}
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    
                                </div>
                            </div>
                        </div>
                        {{ box_footer() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script>
</script>
@endsection