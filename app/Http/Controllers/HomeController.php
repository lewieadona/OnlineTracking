<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\Gmail;

use DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }

    public function trackingresult(Request $request)
    {
        if(!empty($request->search))
        {
            $result = array();

            $route = Route::getFacadeRoot()->current()->uri();
            $result['route'] = $route;

            if(mb_substr($request->search, 8, 8, "UTF-8") == "E")
            {
                $search_result = DB::table('returns_tracking_status')
                ->join('warranty_statuses', 'returns_tracking_status.returns_status', '=', 'warranty_statuses.id')
                ->join('returns_header', 'returns_tracking_status.return_reference_no', '=', 'returns_header.return_reference_no')
                ->select('returns_header.*',
                         'warranty_statuses.*',
                         'returns_tracking_status.return_reference_no',
                         'returns_tracking_status.returns_status',
                         'returns_tracking_status.created_at as date'
                        )
                ->where('returns_tracking_status.return_reference_no', $request->search)->orderBy('returns_tracking_status.created_at', 'DESC')->get();
            
            }elseif(mb_substr($request->search, 8, 8, "UTF-8") == "R")
            {
                $search_result = DB::table('returns_tracking_status')
                ->join('warranty_statuses', 'returns_tracking_status.returns_status', '=', 'warranty_statuses.id')
                ->join('returns_header_retail', 'returns_tracking_status.return_reference_no', '=', 'returns_header_retail.return_reference_no')
                ->select('returns_header_retail.*',
                        'warranty_statuses.*',
                        'returns_tracking_status.return_reference_no',
                        'returns_tracking_status.returns_status',
                        'returns_tracking_status.created_at as date'
                    )
                ->where('returns_tracking_status.return_reference_no', $request->search)->orderBy('returns_tracking_status.created_at', 'DESC')->get();
            }else{
                $search_result = array();
            }
            
            $result['search'] = $search_result;

            return view('index')->with('result', $result);

        }else{
            return redirect('/');
        }
    }

    public function createForm(Request $request)
    {
        $result = array();

        $problem_details = DB::table('srof_problem_details')->where('status','=','ACTIVE')->get();
        $result['problem_details'] = $problem_details;

        $items_included = DB::table('items_included')->where('status','=','ACTIVE')->get();
        $result['items_included']  = $items_included;

        $stores = DB::table('stores')->where('store_status','=','ACTIVE')->get();
        $result['stores']  = $stores;

        $mode_of_payment = DB::table('mode_of_payment')->where('status','=','ACTIVE')->get();
        $result['mode_of_payment']  = $mode_of_payment;

        $channels = DB::table('yqzmgrqyns.channels')->where('channel_status','=','ACTIVE')->orderBy('channel_name', 'ASC')->get();
        $result['channels'] = $channels;

        $province = DB::table('yqzmgrqyns.refprovince')->orderBy('provDesc')->get();
        $result['province'] = $province;

        $city = DB::table('yqzmgrqyns.refcitymun')->orderBy('citymunDesc', 'ASC')->get();
        $result['city'] = $city;

        $barangay = DB::table('yqzmgrqyns.refbrgy')->orderBy('brgyDesc', 'ASC')->get();
        $result['barangay'] = $barangay;

        $stores_drop_off = DB::table('yqzmgrqyns.stores_backend')
                                ->leftjoin('stores', 'stores_backend.stores_frontend_id','=', 'stores.id')
                                ->select('stores.store_name as store_drop_off_name')
                                ->where('stores_backend.store_status','=','ACTIVE')->where('stores_backend.channels_id', 6)->orderBy('stores.store_name', 'ASC')->groupby('stores.store_name')->get();
        $result['stores_drop_off'] = $stores_drop_off;

        return view('createForm')->with('result', $result);
    }

    public function createFormProcess(Request $request)
    {
        //**START OF REFERENCE NO**
        $checkExistingRef = DB::table('yqzmgrqyns.returns_header')->selectRaw('SUBSTR(return_reference_no, 1, 8) AS refno')->orderBy('refno', 'DESC')->first();
        if(!empty($checkExistingRef)){
            $numeric = $checkExistingRef->refno + 1;
        }else{
            $numeric = 0 + 1;
        }

        if($request->purchase_location == 6)
        {
            $ref_code = 'R'; //RETAIL
        }else{                               
            $ref_code = 'E'; //ECOMM
        }
        
        $i = 0;
        do{
            $numberCode = str_pad($numeric + $i, 8, "0", STR_PAD_LEFT);
            $tracking_number = $numberCode.''.$ref_code;
            $i++;
        }while( DB::connection('mysql2')->table('dtc_digits_ws.returns_header_retail')->whereRaw('SUBSTR(return_reference_no, 1, 8) >= '.$numberCode)->exists() OR DB::table('yqzmgrqyns.returns_header_retail')->whereRaw('SUBSTR(return_reference_no, 1, 8) >= '.$numberCode)->exists());
        //**END OF REFERENCE NO**

        //***START OF ADDRESS***
        if(!empty($request->address_one)){
            $addressOne = $request->address_one;
        }else{
            $addressOne = '';
        }

        if(!empty($request->address_two)){
            $addressTwo = $request->address_two;
        }else{
            $addressTwo = '';
        }

        if(!empty($request->province)){
            $State = DB::table('yqzmgrqyns.refprovince')->where('provCode',$request->province)->first();
        }else{
            $State = '';
        }

        if(!empty($request->city)){
            $City = DB::table('yqzmgrqyns.refcitymun')->where('citymunCode',$request->city)->first();
        }else{
            $City = '';
        }

        if(!empty($request->brgy))
        {
            $checkbrgy = DB::table('yqzmgrqyns.refbrgy')->where('id',$request->brgy)->first();
            if($checkbrgy)
            {
                $Barangay = $checkbrgy->brgyDesc;
            }else{
                $Barangay = $request->brgy;
            }
            
        }else{
            $Barangay = '';
        }
        //***END OF ADDRESS***

        //START OF MODE OF PAYMENT***
        if(!empty($request->payment)){
            $Payment = DB::table('mode_of_payment')->where('id',$request->payment)->where('status','=','ACTIVE')->first();
            $PaymentName = $Payment->payment_name;
        }else{
            $PaymentName = '';
        }
        //***END OF MODE OF PAYMENT***/

        //***START OF ITEMS INCLUDED AND OTHER ITEMS INCLUDED***/
        $items_included_id = $request->items_included;
        $ItemsIncluded = '';
        foreach ($items_included_id as $keys=>$item_included_id) 
        {
            $Items = DB::table('items_included')->where('id',$item_included_id)->where('status','=','ACTIVE')->first();
            $ItemsIncluded .= $Items->items_description_included .', ';
        }

        if(!empty($request->items_included_others)){
          
            $ItemsIncluded_Others = $request->items_included_others;
        }else{
            $ItemsIncluded_Others = '';
        }
        //***END OF ITEMS INCLUDED AND OTHER ITEMS INCLUDED***/

        //***START OF PROBLEM DETAILS AND OTHER PROBLEM DETAILS***/
        $problem_details_id = $request->problem_details;
        $problemDetails = '';
        foreach ($problem_details_id as $keys=>$problem_detail_id) 
        {
            $Details = DB::table('srof_problem_details')->where('id',$problem_detail_id)->where('status','=','ACTIVE')->first();
            $problemDetails .= $Details->problem_details .', ';
        }

        if(!empty($request->problem_details_other))
        {
            $other_problem_details = $request->problem_details_other;
        }else{
            $other_problem_details = '';
        }
        //***END OF PROBLEM DETAILS AND OTHER PROBLEM DETAILS***/

        //***START OF QUANTITY***/
        if(!empty($request->qty))
        {
            $Qty = $request->qty;
        }else{
            $Qty = 1;
        }
        //***END OF QUANTITY***/

        //***START OF PURCHASE LOCATION***/
        if(!empty($request->purchase_location)){
            $Channel = DB::table('channels')->where('id',$request->purchase_location)->where('channel_status','=','ACTIVE')->first();
            $ChannelName = $Channel->channel_name;
        }else{
            $ChannelName = '';
        }
        //***END OF PURCHASE LOCATION***/

        // FOR INSERT QUERY
        if($request->purchase_location == 6) //RETAIL
        {
            $header_id = DB::table('returns_header_retail')->insertGetId([
				'returns_status'   		=> 1,
				'returns_status_1'   	=> 1,
				'return_reference_no'   => $tracking_number,
				'purchase_location'     => $ChannelName,
				'store' 			    => $request->store,
				'customer_last_name'    => $request->lastname,
				'customer_first_name'   => $request->firstname,
				'address'               => $addressOne.' '.$addressTwo.' '.$Barangay.' '.$City->citymunDesc.' '.$State->provDesc.' '.$request->country,
				'email_address'         => $request->email_address,
				'contact_no'            => $request->contact_number,
				'order_no'              => $request->order_no,
				'purchase_date'         => $request->purchase_date,
                'mode_of_payment'       => $PaymentName,
                'mode_of_refund'        => $request->refund,
                'items_included'        => substr($ItemsIncluded, 0, -2),
				'items_included_others' => $ItemsIncluded_Others,
				// 'comments' 				=> $request->comments,
                //'customer_location' 	=> $request->customer_location,
                'mode_of_return'        => $request->mode_of_return,
                'store_dropoff'         => $request->store_drop_off,
                'branch'                => $request->branch,
                'branch_dropoff'        => $request->branch_dropoff,
				'created_at'            => date("Y-m-d H:i:s")
            ]);
            
            $body_item_id = DB::table('returns_body_item_retail')->insertGetId([
				'returns_header_id'   	=> $header_id,
				'digits_code'     		=> $request->digits_code,
				// 'upc_code' 			    => $request->upc_code,
				'item_description'    	=> $request->item_desc,
				// 'brand'   				=> $request->brand,
				'cost'               	=> $request->purchase_amount,
				'quantity'         		=> $Qty,
                'problem_details'       => substr($problemDetails, 0, -2),
                'problem_details_other'	=> $other_problem_details,
                'serialize'             => 1,
                'created_at'            => date("Y-m-d H:i:s")
            ]);

            $serial_id = DB::table('returns_serial_retail')->insertGetId([
				'returns_header_id'   	=> $header_id,
				'returns_body_item_id'  => $body_item_id,
				'serial_number' 	    => $request->serial_number,
				'created_at'            => date("Y-m-d H:i:s")
            ]);

            $header_id_backend_rtl =DB::connection('mysql2')->table('returns_header_retail')->insertGetId([
                'id'                    => $header_id,
				'returns_status'   		=> 1,
				'returns_status_1'   	=> 1,
				'return_reference_no'   => $tracking_number,
				'purchase_location'     => $ChannelName,
				'store' 			    => $request->store,
				'customer_last_name'    => $request->lastname,
				'customer_first_name'   => $request->firstname,
				'address'               => $addressOne.' '.$addressTwo.' '.$Barangay.' '.$City->citymunDesc.' '.$State->provDesc.' '.$request->country,
				'email_address'         => $request->email_address,
				'contact_no'            => $request->contact_number,
				'order_no'              => $request->order_no,
				'purchase_date'         => $request->purchase_date,
                'mode_of_payment'       => $PaymentName,
                'mode_of_refund'        => $request->refund,
				'items_included'        => substr($ItemsIncluded, 0, -2),
				'items_included_others' => $ItemsIncluded_Others,
				// 'comments' 				=> $request->comments,
                //'customer_location' 	=> $request->customer_location,
                'mode_of_return'        => $request->mode_of_return,
                'store_dropoff'         => $request->store_drop_off,
                'branch'                => $request->branch,
                'branch_dropoff'        => $request->branch_dropoff,
				'created_at'            => date("Y-m-d H:i:s")
			]);
            
            $body_item_backend_rtl =  DB::connection('mysql2')->table('returns_body_item_retail')->insertGetId([
                //'id'                    => $body_item_id,
				'returns_header_id'   	=> $header_id_backend_rtl,
				'digits_code'     		=> $request->digits_code,
				// 'upc_code' 			    => $request->upc_code,
				'item_description'    	=> $request->item_desc,
				// 'brand'   				=> $request->brand,
				'cost'               	=> $request->purchase_amount,
				'quantity'         		=> $Qty,
				'problem_details'       => substr($problemDetails, 0, -2),
                'problem_details_other'	=> $other_problem_details,
                'serialize'             => 1,
                'created_at'            => date("Y-m-d H:i:s")
			]);

            DB::connection('mysql2')->table('returns_serial_retail')->insert([
              //  'id'   	                => $serial_id,
				'returns_header_id'   	=> $header_id_backend_rtl,
				'returns_body_item_id'  => $body_item_backend_rtl,
				'serial_number' 	    => $request->serial_number,
				'created_at'            => date("Y-m-d H:i:s")
            ]);

            $tracking = DB::table('returns_header_retail')
            ->leftjoin('returns_body_item_retail', 'returns_header_retail.id', '=', 'returns_body_item_retail.returns_header_id')
            ->leftjoin('returns_serial_retail', 'returns_body_item_retail.id', '=', 'returns_serial_retail.returns_body_item_id')
            ->where('returns_header_retail.id', $header_id)->first();
        
        }else{ //ECOMM
            $header_id = DB::table('returns_header')->insertGetId([
                'returns_status'        => 1,
                'returns_status_1'      => 1,
                'return_reference_no'   => $tracking_number,
                'store'                 => $request->store,
                'customer_last_name'    => $request->lastname,
                'customer_first_name'   => $request->firstname,
                'address'               => $addressOne.' '.$addressTwo.' '.$Barangay.' '.$City->citymunDesc.' '.$State->provDesc.' '.$request->country,
                'email_address'         => $request->email_address,
                'contact_no'            => $request->contact_number,
                'order_no'              => $request->order_no,
                'purchase_location'     => $ChannelName,
                'purchase_date'         => $request->purchase_date,
                'mode_of_payment'       => $PaymentName,
                'mode_of_refund'        => $request->refund,
                'bank_name'             => $request->bankname,
                'bank_account_no'       => $request->bank_account_no,
                'bank_account_name'     => $request->bank_account_name,
                'items_included'        => substr($ItemsIncluded, 0, -2),
                'items_included_others' => $ItemsIncluded_Others,
                'mode_of_return'        => $request->mode_of_return,
                'store_dropoff'         => $request->store_drop_off,
                'branch'                => $request->branch,
                'branch_dropoff'        => $request->branch_dropoff,
                'created_at'            => date("Y-m-d H:i:s")
            ]);

            $body_item_id = DB::table('returns_body_item')->insertGetId([
                'returns_header_id'     => $header_id,
                'digits_code'     		=> $request->digits_code,
                'item_description'      => $request->item_desc,
                'cost'                  => $request->purchase_amount,
                'quantity'              => $Qty,
                'problem_details'       => substr($problemDetails, 0, -2),
                'problem_details_other' => $other_problem_details,
                'serialize'             => 1,
                'created_at'            => date("Y-m-d H:i:s")
            ]);

            $serial_id = DB::table('returns_serial')->insertGetId([
                'returns_header_id'     => $header_id,
                'returns_body_item_id'  => $body_item_id,
                'serial_number'         => $request->serial_number,
                'created_at'            => date("Y-m-d H:i:s")
            ]);

            $header_id_backend = DB::connection('mysql2')->table('returns_header')->insertGetId([
                'id'                    => $header_id,
                'returns_status'        => 1,
                'returns_status_1'      => 1,
                'return_reference_no'   => $tracking_number,
                'store'                 => $request->store,
                'customer_last_name'    => $request->lastname,
                'customer_first_name'   => $request->firstname,
                'address'               => $addressOne.' '.$addressTwo.' '.$Barangay.' '.$City->citymunDesc.' '.$State->provDesc.' '.$request->country,
                'email_address'         => $request->email_address,
                'contact_no'            => $request->contact_number,
                'order_no'              => $request->order_no,
                'purchase_location'     => $ChannelName,
                'purchase_date'         => $request->purchase_date,
                'mode_of_payment'       => $PaymentName,
                'mode_of_refund'        => $request->refund,
                'bank_name'             => $request->bankname,
                'bank_account_no'       => $request->bank_account_no,
                'bank_account_name'     => $request->bank_account_name,
                'items_included'        => substr($ItemsIncluded, 0, -2),
                'items_included_others' => $ItemsIncluded_Others,
                'mode_of_return'        => $request->mode_of_return,
                'store_dropoff'         => $request->store_drop_off,
                'branch'                => $request->branch,
                'branch_dropoff'        => $request->branch_dropoff,
                'created_at'            => date("Y-m-d H:i:s")
            ]);

            $body_item_backend = DB::connection('mysql2')->table('returns_body_item')->insertGetId([
                //'id'                    => $body_item_id,
                'returns_header_id'     => $header_id_backend,
                'digits_code'     		=> $request->digits_code,
                'item_description'      => $request->item_desc,
                'cost'                  => $request->purchase_amount,
                'quantity'              => $Qty,
                'problem_details'       => substr($problemDetails, 0, -2),
                'problem_details_other' => $other_problem_details,
                'serialize'             => 1,
                'created_at'            => date("Y-m-d H:i:s")
            ]);

            DB::connection('mysql2')->table('returns_serial')->insert([
                //'id'   	                => $serial_id,
                'returns_header_id'     => $header_id_backend,
                'returns_body_item_id'  => $body_item_backend,
                'serial_number'         => $request->serial_number,
                'created_at'            => date("Y-m-d H:i:s")
            ]);

            $tracking = DB::table('returns_header')
            ->leftjoin('returns_body_item', 'returns_header.id', '=', 'returns_body_item.returns_header_id')
            ->leftjoin('returns_serial', 'returns_body_item.id', '=', 'returns_serial.returns_body_item_id')
            ->where('returns_header.id', $header_id)->first();
        }

        DB::table('returns_tracking_status')->insert([
            'return_reference_no'   => $tracking_number,
            'returns_status'        => 1,
            'created_at'            => date("Y-m-d H:i:s")
        ]);

        $email = $tracking->email_address;

        $details = [
            'title' => 'Your Return Request is now being processed!',
            'body' => 'Your Reference Number is:.',
            'alldetails' => $tracking
        ];

        Mail::to($email)->send(new Gmail($details));
        
        return back()->with('success', 'Your Reference Number is '.$tracking->return_reference_no);
    }

    public function province(Request $request)
    {
        $province = DB::table('yqzmgrqyns.refcitymun')->where('provCode',$request->provinces)->orderBy('citymunDesc', 'ASC')->get();

        return($province);
    }

    public function city(Request $request)
    {
        if(empty($request->provinces))
        {
            $city = DB::table('yqzmgrqyns.refbrgy')->where('citymunCode',$request->cities)->orderBy('brgyDesc', 'ASC')->get();
        }else{
            $city = DB::table('yqzmgrqyns.refbrgy')->where('provCode',$request->provinces)->orderBy('brgyDesc', 'ASC')->get();
        }
        
        return($city);
    }

    public function stores(Request $request)
    {
        if(!empty($request->stores))
        {
            $channels = DB::table('stores')->where('channels_id',$request->stores)->where('store_status','=','ACTIVE')->orderBy('store_name', 'ASC')->get();
        }else{
            $channels = DB::table('stores')->orderBy('store_name', 'ASC')->where('store_status','=','ACTIVE')->get();
        }
        
        return($channels);
    }

    public function backend_stores(Request $request)
    {   
        if(!empty($request->store_backend))
        {
            $store_id = DB::table('stores')
                ->where('store_name', $request->store_backend)
                ->where('channels_id', $request->purchase_location)
                ->where('store_status', 'ACTIVE')->first();

            $customer_location = DB::table('stores_backend')
                ->where('stores_frontend_id',$store_id->id)
                ->where('store_status', 'ACTIVE')
                ->orderBy('branch_id', 'ASC')->get();
        }else{

            $customer_location = DB::table('stores_backend')->where('store_status', 'ACTIVE')->orderBy('branch_id', 'ASC')->get();
        }
        
        return($customer_location);
    }

    public function branch_drop_off(Request $request)
    {
        if(!empty($request->drop_off_store))
        {
            $store_id =          DB::table('stores')->where('store_name', $request->drop_off_store)->where('channels_id', 6)->where('store_status', 'ACTIVE')->first();

            $customer_location = DB::table('stores_backend')
                                 ->where('stores_frontend_id',$store_id->id)->where('store_status', 'ACTIVE')->where('store_dropoff_privilege', 'YES')->orderBy('branch_id', 'ASC')->get();
        }else{

            $customer_location = DB::table('stores_backend')->where('store_status', 'ACTIVE')->orderBy('branch_id', 'ASC')->get();

            //$customer_location = DB::table('stores_backend')->where('store_status','=','ACTIVE')->orderBy('branch_name', 'ASC')->get();
            //$customer_location = DB::table('stores_backend')->orderBy('store_name', 'ASC')->where('store_status','=','ACTIVE')->get();
        }
        
        return($customer_location);
    }
}
