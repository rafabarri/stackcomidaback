<?php

namespace App\CentralLogics;

use App\Models\Order;
use App\Models\Restaurant;
use Carbon\Carbon;

class CouponLogic
{
    public static function get_discount($coupon, $order_amount)
    {
        $discount_ammount = 0;
        if($coupon->discount_type=='percent' && $coupon->discount > 0)
        {
            $discount_ammount = $order_amount* ($coupon->discount/100);
        }
        else
        {
            $discount_ammount = $coupon->discount;
        }
        if($coupon->max_discount > 0)
        {
            $discount_ammount = $discount_ammount > $coupon->max_discount?$coupon->max_discount:$discount_ammount;
        }
        return $discount_ammount;
    }

    public static function is_valide($coupon, $user_id, $restaurant_id,$order_restaurant_id=null,$order_amount=null)
    {
        $start_date = Carbon::parse($coupon->start_date);
        $expire_date = Carbon::parse($coupon->expire_date);
        $customer_ids=json_decode($coupon->customer_id, true);

        $today = Carbon::now();
        if($start_date->format('Y-m-d') > $today->format('Y-m-d') || $expire_date->format('Y-m-d') < $today->format('Y-m-d'))
        {
            return 407;  //coupon expire
        }

        if($coupon->coupon_type == 'restaurant_wise' && !in_array($restaurant_id, json_decode($coupon->data, true)))
        {
            return 404;
        }
        if($coupon->coupon_type == 'restaurant_wise' && $order_restaurant_id && !in_array($order_restaurant_id, json_decode($coupon->data, true)))
        {
            return 404;
        }
        if($coupon->created_by == 'vendor' && ($order_restaurant_id ? ($order_restaurant_id != $coupon->restaurant_id) : ($restaurant_id?($restaurant_id != $coupon->restaurant_id ):false)) ){
            return 404;
        }

        if((!in_array("all", $customer_ids) && !in_array($user_id,$customer_ids)) ){
        return 408; //unauthorized user
        }

        else if($coupon->coupon_type == 'zone_wise')
        {
            if(json_decode($coupon->data, true)){
                if($restaurant_id){
                    $data = Restaurant::whereIn('zone_id',json_decode($coupon->data, true))->where('id', $restaurant_id)->first();
                }
                else{

                $data = Restaurant::whereIn('zone_id', json_decode($coupon->data, true))
                ->pluck('id');
                }

                if(!$data)
                {
                    return 404;
                }
            }
            else
            {
                return 404;
            }
        }
        else if($coupon->coupon_type == 'first_order')
        {
            $total = Order::where(['user_id' => $user_id])->count();
            if ($total < $coupon['limit']) {
                return 200;
            }else{
                return 406;//Limite orer
            }
        }
        if($order_amount && ($order_amount < $coupon->min_purchase)){
            return 409; //minimum order
        }

        if ($coupon['limit'] == null) {
            return 200;
        } else {
            $total = Order::where(['user_id' => $user_id, 'coupon_code' => $coupon['code']])->count();
            if ($total < $coupon['limit']) {
                return 200;
            }else{
                return 406;//Limite orer
            }
        }
        return 404; //not found
    }

    public static function is_valid_for_guest($coupon, $restaurant_id)
    {

        $start_date = Carbon::parse($coupon->start_date);
        $expire_date = Carbon::parse($coupon->expire_date);

        $today = Carbon::now();
        if($start_date->format('Y-m-d') > $today->format('Y-m-d') || $expire_date->format('Y-m-d') < $today->format('Y-m-d'))
        {
            return 407;  //coupon expire
        }

        if($coupon->coupon_type == 'restaurant_wise' && !in_array($restaurant_id, json_decode($coupon->data, true)))
        {
            return 404;
        }
        if($coupon->created_by == 'vendor' && $restaurant_id != $coupon->restaurant_id  ){
            return 404;
        }

        else if($coupon->coupon_type == 'zone_wise')
        {
            if(json_decode($coupon->data, true)){
                $data = Restaurant::whereIn('zone_id',json_decode($coupon->data, true))->where('id', $restaurant_id)->first();
                if(!$data)
                {
                    return 404;
                }
            }
            else
            {
                return 404;
            }
        }
        return 404; //not found
    }
}
