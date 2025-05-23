<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\WalletTransaction;
use App\CentralLogics\CustomerLogic;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Exports\CustomerWalletTransactionExport;

class CustomerWalletController extends Controller
{
    public function add_fund_view()
    {
        if(BusinessSetting::where('key','wallet_status')->first()?->value != 1)
        {
            Toastr::error(translate('messages.customer_wallet_disable_warning_admin'));
            return back();
        }
        return view('admin-views.customer.wallet.add_fund');
    }

    public function add_fund(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'=>'exists:users,id',
            'amount'=>'numeric|min:.01',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $wallet_transaction = CustomerLogic::create_wallet_transaction(user_id:$request->customer_id, amount:$request->amount, transaction_type:'add_fund_by_admin',referance:$request->referance);

        if($wallet_transaction)
        {
            try{
                Helpers::add_fund_push_notification($request->customer_id, $request->amount);
                $notification_status= Helpers::getNotificationStatusData('customer','customer_add_fund_to_wallet');
                if( $notification_status?->mail_status == 'active' &&  config('mail.status') && $wallet_transaction?->user?->email  && Helpers::get_mail_status('add_fund_mail_status_user') == '1') {
                    Mail::to($wallet_transaction->user->email)->send(new \App\Mail\AddFundToWallet($wallet_transaction));
                }
            }catch(\Exception $ex)
            {
                info($ex->getMessage());
            }

            return response()->json([], 200);
        }

        return response()->json(['errors'=>[
            'message'=>translate('messages.failed_to_create_transaction')
        ]], 200);
    }

    public function report(Request $request)
    {
        $data = WalletTransaction::selectRaw('sum(credit) as total_credit, sum(debit) as total_debit')
        ->with('user')->
        when(($request->from && $request->to),function($query)use($request){
            $query->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59']);
        })
        ->when($request->transaction_type, function($query)use($request){
            $query->where('transaction_type',$request->transaction_type);
        })
        ->when($request->customer_id, function($query)use($request){
            $query->where('user_id',$request->customer_id);
        })
        ->get();

        $transactions = WalletTransaction::with('user')->
        when(($request->from && $request->to),function($query)use($request){
            $query->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59']);
        })
        ->when($request->transaction_type, function($query)use($request){
            $query->where('transaction_type',$request->transaction_type);
        })
        ->when($request->customer_id, function($query)use($request){
            $query->where('user_id',$request->customer_id);
        })
        ->whereNull('delivery_man_id')
        ->latest()
        ->paginate(config('default_pagination'));

        return view('admin-views.customer.wallet.report', compact('data','transactions'));
    }

    public function export(Request $request)
    {
        try{
                $data = WalletTransaction::selectRaw('sum(credit) as total_credit, sum(debit) as total_debit')
                ->with('user')->
                when(($request->from && $request->to),function($query)use($request){
                    $query->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59']);
                })
                ->when($request->transaction_type, function($query)use($request){
                    $query->where('transaction_type',$request->transaction_type);
                })
                ->when($request->customer_id, function($query)use($request){
                    $query->where('user_id',$request->customer_id);
                })
                ->get();

                $transactions = WalletTransaction::with('user')->
                when(($request->from && $request->to),function($query)use($request){
                    $query->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59']);
                })
                ->when($request->transaction_type, function($query)use($request){
                    $query->where('transaction_type',$request->transaction_type);
                })
                ->when($request->customer_id, function($query)use($request){
                    $query->where('user_id',$request->customer_id);
                })
                ->latest()
                ->get();

                $data = [
                    'transactions'=>$transactions,
                    'data'=>$data,
                    'from'=>$request->from??null,
                    'to'=>$request->to??null,
                    'transaction_type'=>$request->transaction_type??null,
                    'customer'=>$request->customer_id?Helpers::get_customer_name($request->customer_id):null,

                ];

                if ($request->type == 'excel') {
                    return Excel::download(new CustomerWalletTransactionExport($data), 'CustomerWalletTransactions.xlsx');
                } else if ($request->type == 'csv') {
                    return Excel::download(new CustomerWalletTransactionExport($data), 'CustomerWalletTransactions.csv');
                }
            }  catch(\Exception $e){
                Toastr::error("line___{$e->getLine()}",$e->getMessage());
                info(["line___{$e->getLine()}",$e->getMessage()]);
                return back();
            }
    }
}
