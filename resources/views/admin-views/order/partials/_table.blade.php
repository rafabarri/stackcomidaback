@foreach($orders as $key=>$order)

    <tr class="status-{{$order['order_status']}} class-all">
        <td class="">
            {{$key+1}}
        </td>
        <td class="table-column-pl-0">
            <a href="{{route('admin.order.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
        </td>
        <td class="text-uppercase">
            <div>
                {{ Carbon\Carbon::parse($order['created_at'])->locale(app()->getLocale())->translatedFormat('d M Y') }}

            </div>
            <div>
                {{ Carbon\Carbon::parse($order['created_at'])->locale(app()->getLocale())->translatedFormat(config('timeformat')) }}
            </div>
        </td>
        <td>
            @if($order->is_guest)
                 <?php
                                        $customer_details = json_decode($order['delivery_address'],true);
                                    ?>
                <strong>{{$customer_details['contact_person_name']}}</strong>
                <div>{{$customer_details['contact_person_number']}}</div>
            @elseif($order->customer)
                <a class="text-body text-capitalize"
                   href="{{route('admin.customer.view',[$order['user_id']])}}">
                   <div class="customer--name">
                        {{$order->customer['f_name'].' '.$order->customer['l_name']}}
                   </div>
                   <span class="phone">
                        {{$order->customer['phone']}}
                   </span>
                </a>
            @else
                <label class="badge badge-danger">{{translate('messages.invalid_customer_data')}}</label>
            @endif
        </td>
        <td>
            <label class="m-0">
                <a href="{{route('admin.restaurant.view', $order->restaurant_id)}}" class="text--title" alt="view restaurant">
                    {{Str::limit($order->restaurant?$order->restaurant->name:translate('messages.Restaurant_deleted!'),20,'...')}}
                </a>
            </label>
        </td>
        <td>
            <div class="text-right mw-85px">
                <div>
                    {{\App\CentralLogics\Helpers::format_currency($order['order_amount'])}}
                </div>
                @if($order->payment_status=='paid')
                    <strong class="text-success">
                    {{translate('messages.paid')}}
                    </strong>
                    @elseif($order->payment_status=='partially_paid')
                    <strong class="text-success">
                        {{translate('messages.partially_paid')}}
                    </strong>
                    @else
                    <strong class="text-danger">
                    {{translate('messages.unpaid')}}
                    </strong>
                @endif
            </div>
        </td>
        @if (isset($order->subscription)  && $order->subscription->status != 'canceled' )
            @php
                $order->order_status = $order->subscription_log ? $order->subscription_log->order_status : $order->order_status;
            @endphp
        @endif
        <td class="text-capitalize text-center">
            @if($order['order_status']=='pending')
                <span class="badge badge-soft-info mb-1">
                  {{translate('messages.pending')}}
                </span>
            @elseif($order['order_status']=='confirmed')
                <span class="badge badge-soft-info mb-1">
                  {{translate('messages.confirmed')}}
                </span>
            @elseif($order['order_status']=='processing')
                <span class="badge badge-soft-warning mb-1">
                  {{translate('messages.processing')}}
                </span>
            @elseif($order['order_status']=='picked_up')
                <span class="badge badge-soft-warning mb-1">
                  {{translate('messages.out_for_delivery')}}
                </span>
            @elseif($order['order_status']=='delivered')
                <span class="badge badge-soft-success mb-1">
                    {{$order?->order_type == 'dine_in' ? translate('messages.Completed') : translate('messages.delivered')}}
                </span>
            @elseif($order['order_status']=='failed')
                <span class="badge badge-soft-danger mb-1">
                  {{translate('messages.payment_failed')}}
                </span>
            @else
                <span class="badge badge-soft-danger mb-1">
                  {{translate(str_replace('_',' ',$order['order_status']))}}
                </span>
            @endif
            <div class="text-capitalze opacity-7">
            @if($order['order_type']=='take_away')
                <span>
                    {{translate('messages.take_away')}}
                </span>
                @elseif ($order['order_type'] == 'dine_in')
                <span>
                    {{ translate('Dine_in') }}
                </span>
            @else
                <span>
                    {{translate('home_delivery')}}
                </span>
            @endif
            </div>
        </td>
        <td>
            <div class="btn--container justify-content-center">
                <a class="ml-2 btn btn-sm btn--warning btn-outline-warning action-btn" href="{{route('admin.order.details',['id'=>$order['id']])}}"><i class="tio-invisible"></i></a>

                <a class="ml-2 btn btn-sm btn--primary btn-outline-primary download--btn action-btn" href="{{ route('admin.order.generate-invoice', [$order['id']]) }}"><i class="tio-print"></i></a>
            </div>
        </td>
    </tr>

@endforeach
