@extends('layouts.admin.app')

@section('title',translate('Food_Campaign_Preview'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between">
                <h1 class="page-header-title text-break">{{$campaign['title']}}</h1>
                <a href="{{route('admin.campaign.edit',['item',$campaign['id']])}}" class="btn btn--primary float-right">
                    <i class="tio-edit"></i> {{translate('messages.edit')}}
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-3">
            <!-- Body -->
            <div class="card-body">
                <div class="row align-items-md-center">
                    <div class="col-md-6 col-lg-4 mb-3 mb-md-0">
                            <img class="rounded initial-16 onerror-image"   id="viewer"
                            src="{{$campaign?->image_full_url ?? dynamicAsset('public/assets/admin/img/900x400/img1.png') }}"
                            data-onerror-image="{{ dynamicAsset('public/assets/admin/img/900x400/img1.png') }}" alt="image">
                    </div>
                    <div class="col-md-6">
                        <span class="d-block mb-1">
                            {{translate('messages.campaign_starts_from')}} :
                            <strong class="text--title">
                            {{ \App\CentralLogics\Helpers::date_format($campaign->start_date) }}
                            </strong>
                        </span>
                        <span class="d-block mb-1">
                            {{translate('messages.campaign_ends_at')}} :
                            <strong class="text--title">
                                {{ \App\CentralLogics\Helpers::date_format($campaign->end_date) }}
                            </strong>
                        </span>
                        <span class="d-block mb-1">
                            {{translate('messages.available_time_starts')}} :
                            <strong class="text--title">
                                {{ \App\CentralLogics\Helpers::time_format($campaign->start_time) }}
                            </strong>
                        </span>
                        <span class="d-block">
                            {{translate('messages.available_time_ends')}} :
                            <strong class="text--title">
                                {{ \App\CentralLogics\Helpers::time_format($campaign->end_time) }}
                            </strong>
                        </span>
                    </div>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <div class="row g-2">
            <div class="col-lg-4 col-xl-3">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="text-center">
                            <span class="mb-3">{{translate('restaurant_info')}}</span>
                            @if($campaign->restaurant)
                            <a href="{{route('admin.restaurant.view', $campaign->restaurant_id)}}" class="d-block">
                                    <img class="avatar-img avatar-circle initial-17 onerror-image"   id="viewer"
                                    src="{{ $campaign?->restaurant?->logo_full_url ?? dynamicAsset('/public/assets/admin/img/100x100/restaurant-default-image.png') }}"
                                    data-onerror-image="{{ dynamicAsset('/public/assets/admin/img/100x100/restaurant-default-image.png') }}" alt="image">
                                <h2 class="m-0">{{$campaign->restaurant['name']}}</h2>
                            </a>
                            @else
                            <span class="badge-info">{{translate('messages.restaurant_deleted')}}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-xl-9">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-fastfood"></i>
                            </span>
                            <span>
                                {{translate('food_information')}}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless table-thead-bordered table-align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="px-4 w-120px"><h4 class="m-0">{{translate('short_description')}}</h4></th>
                                        <th class="px-4 w-120px"><h4 class="m-0">{{translate('messages.price')}}</h4></th>
                                        <th class="px-4 w-120px"><h4 class="m-0">{{translate('messages.variations')}}</h4></th>
                                        <th class="px-4 w-120px"><h4 class="m-0">{{translate('addons')}}</h4></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4">
                                            <p class="fz-13px">{{$campaign['description']}}</p>
                                        </td>
                                        <td class="px-4">
                                            <div>

                                                <span class="d-block text-dark">{{translate('messages.price')}} : <strong>{{\App\CentralLogics\Helpers::format_currency($campaign['price'])}}</strong>
                                                </span class="d-block text-dark">
                                                <span class="d-block text-dark">{{translate('messages.tax')}} :
                                                    <strong>{{\App\CentralLogics\Helpers::format_currency(\App\CentralLogics\Helpers::tax_calculate($campaign,$campaign['price']))}}</strong>
                                                </span class="d-block text-dark">
                                                <span class="d-block text-dark">{{translate('messages.discount')}} :
                                                    <strong>{{\App\CentralLogics\Helpers::format_currency(\App\CentralLogics\Helpers::discount_calculate($campaign,$campaign['price']))}}</strong>
                                                </span class="text-dark">
                                            </div>
                                        </td>
                                        <td class="px-4">
                                            @foreach(json_decode($campaign->variations,true) as $variation)
                                            @if(isset($variation["price"]))
                                            <span class="d-block mb-1 text-capitalize">
                                                <strong>
                                                    {{ translate('please_update_the_food_variations.') }}
                                                </strong>
                                                </span>
                                                @break
                                                @else
                                                <span class="d-block text-capitalize">
                                                        <strong>
                                                {{$variation['name']}} -
                                            </strong>
                                            @if ($variation['type'] == 'multi')
                                            {{ translate('messages.multiple_select') }}
                                            @elseif($variation['type'] =='single')
                                            {{ translate('messages.single_select') }}

                                            @endif
                                                @if ($variation['required'] == 'on')
                                                - ({{ translate('messages.required') }})
                                                @endif
                                                </span>

                                                @if ($variation['min'] != 0 && $variation['max'] != 0)
                                            ({{ translate('messages.Min_select') }}: {{ $variation['min'] }} - {{ translate('messages.Max_select') }}: {{ $variation['max'] }})
                                                @endif

                                                @if (isset($variation['values']))
                                                @foreach ($variation['values'] as $value)
                                                  <span class="d-block text-capitalize">
                                                    &nbsp;   &nbsp; {{ $value['label']}} :
                                                    <strong>{{\App\CentralLogics\Helpers::format_currency( $value['optionPrice'])}}</strong>
                                                    </span>
                                                @endforeach
                                                @endif
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="px-4">
                                            @foreach(\App\Models\AddOn::withOutGlobalScope(App\Scopes\RestaurantScope::class)->whereIn('id',json_decode($campaign['add_ons'],true))->get() as $addon)
                                                <small class="text-capitalize d-block">
                                                {{$addon['name']}} : {{\App\CentralLogics\Helpers::format_currency($addon['price'])}}
                                                </small>
                                            @endforeach
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->
        <!-- Card -->
        <div class="card mt-3">
            <div class="card-header py-2">
                <div class="search--button-wrapper">
                    <h5 class="card-title">
                        <span class="card-title-icon">
                            <i class="tio-fastfood"></i>
                        </span>
                        <span>{{translate('campaign_restaurant_list')}}</span>
                        <span class="badge badge-pill badge-soft-secondary">{{ count($orders) }}</span>
                    </h5>
                    <form>
                        <div class="input--group input-group">
                            <input type="text" name="search" value="{{ request()?->search ?? null }}" class="form-control" placeholder="{{ translate('Search_here_by_restaurants') }}">
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                    </form>
                    <!-- Unfold -->
                    <div class="hs-unfold">


                        <div class="hs-unfold mr-2">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:;"
                                data-hs-unfold-options='{
                                        "target": "#usersExportDropdown",
                                        "type": "css-animation"
                                    }'>
                                <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                            </a>

                            <div id="usersExportDropdown"
                                class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                                <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                                <a id="export-excel" class="dropdown-item" href="
                                    {{ route('admin.campaign.food_campaign_list_export', ['type' => 'excel', 'campaign_id' =>$campaign['id'] , request()->getQueryString()]) }}
                                    ">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{ dynamicAsset('public/assets/admin') }}/svg/components/excel.svg"
                                        alt="Image Description">
                                    {{ translate('messages.excel') }}
                                </a>
                                <a id="export-csv" class="dropdown-item" href="
                                {{ route('admin.campaign.food_campaign_list_export', ['type' => 'csv', 'campaign_id' =>$campaign['id'] , request()->getQueryString()]) }}">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{ dynamicAsset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                        alt="Image Description">
                                    {{ translate('messages.csv') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive datatable-custom">
                    <table id="datatable"
                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                        "columnDefs": [{
                            "targets": [0],
                            "orderable": false
                        }],
                        "order": [],
                        "info": {
                        "totalQty": "#datatableWithPaginationInfoTotalQty"
                        },
                        "search": "#datatableSearch",
                        "entries": "#datatableEntries",
                        "pageLength": 25,
                        "isResponsive": false,
                        "isShowPaging": false,
                        "pagination": "datatablePagination"
                    }'>
                        <thead class="thead-light">
                        <tr>
                            <th>
                                {{translate('sl')}}
                            </th>
                            <th class="table-column-pl-0">{{translate('messages.order')}}</th>
                            <th>{{translate('messages.date')}}</th>
                            <th>{{translate('messages.customer')}}</th>
                            <th>{{translate('messages.vendor')}}</th>
                            <th>{{translate('messages.payment_status')}}</th>
                            <th>{{translate('messages.total')}}</th>
                            <th>{{translate('messages.order_status')}}</th>
                        </tr>
                        </thead>

                        <tbody id="set-rows">
                        @foreach($orders as $key=>$order)


                            <tr class="status-{{$order['order_status']}} class-all">
                                <td class="">
                                    {{$key+1}}
                                </td>
                                <td class="table-column-pl-0">
                                    <a href="{{ route('admin.order.details',['id'=>$order->order_id])}}">{{$order->order['id']}}</a>
                                </td>
                                <td>
                                    {{  Carbon\Carbon::parse($order['created_at'])->locale(app()->getLocale())->translatedFormat('d M Y') }}
                                <td>
                                    @if($order->order->customer)
                                        <a class="text-body text-capitalize" href="{{route('admin.customer.view',[$order->order['user_id']])}}">{{$order->order->customer['f_name'].' '.$order->order->customer['l_name']}}</a>
                                    @else
                                        <label class="badge badge-danger">{{translate('messages.invalid_customer_data')}}</label>
                                    @endif
                                </td>
                                <td>
                                    <label class="badge badge-soft-primary">{{Str::limit($order->order->restaurant?$order->order->restaurant->name:translate('messages.Restaurant_deleted!'),20,'...')}}</label>
                                </td>
                                <td>
                                    @if($order->order->payment_status=='paid')
                                        <span class="badge badge-soft-success">
                                        {{translate('messages.paid')}}
                                        </span>
                                    @else
                                        <span class="badge badge-soft-danger">
                                        {{translate('messages.unpaid')}}
                                        </span>
                                    @endif
                                </td>
                                <td>{{\App\CentralLogics\Helpers::format_currency($order->order['order_amount'])}}</td>
                                <td class="text-capitalize">
                                    @if($order->order['order_status']=='pending')
                                        <span class="badge badge-soft-info ml-2 ml-sm-3">
                                        {{translate('messages.pending')}}
                                        </span>
                                    @elseif($order->order['order_status']=='confirmed')
                                        <span class="badge badge-soft-info ml-2 ml-sm-3">
                                        {{translate('messages.confirmed')}}
                                        </span>
                                    @elseif($order->order['order_status']=='processing')
                                        <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                        {{translate('messages.processing')}}
                                        </span>
                                    @elseif($order->order['order_status']=='out_for_delivery')
                                        <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                        {{translate('messages.out_for_delivery')}}
                                        </span>
                                    @elseif($order->order['order_status']=='delivered')
                                        <span class="badge badge-soft-success ml-2 ml-sm-3">
                                            {{$order?->order_type == 'dine_in' ? translate('messages.Completed') : translate('messages.delivered')}}
                                        </span>
                                    @else
                                        <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                        {{str_replace('_',' ',$order->order['order_status'])}}
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Footer -->
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">
                    <div>
                        {!! $orders->links() !!}
                    </div>
                </div>
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')

@endpush
