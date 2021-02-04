@include('meta')

@include('header')  

<div class="search" style="padding-bottom: 40px;">
    <h1 class="title-homepage" style="font-size: 45px;"><strong>Create A Warranty Request</strong></h1>
    <div class="create-form">
        <a href="{{ URL::to('/')}}"><i class="fa fa-arrow-left" aria-hidden="true" style="font-size: 2em;color:dimgray;"></i></a>

        <!-- Start of Modal -->
        <div class="modal fade" id="myModal" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="float:right;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="thank-you-pop">
                            <img src="{{ asset('images/Green-Round-Tick.png')}}" alt="">
                            <h3 style="font-size:21px;font-weight:600;">
                                Your Warranty Request is now being processed!
                            </h3>
                            <p>
                                Please check your email for a copy of your request. A representative will reach out to you in a few days to process your concern.
                            </p>
                            <h3 class="cupon-pop">{{ session('success') }}</h3>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ URL::to('/')}}"><button type="button" class="btn hvr-hover" style="color:white">Go Back To Home</button></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal -->

        @if($message = Session::get('success'))
            <script>
                $(function() {
                $('#myModal').modal('show');
                });
            </script>
        @endif

        @if($message = Session::get('failed'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>Failed to Submit</strong>
            </div>
        @endif

        <form class="mt-3 review-form-box" method="post" action="{{URL::to('/createFormProcess')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-row">
                <div class="form-group col-md-6" id="purchase_location_div" > <!-- Purchase Location -->
                    <label class="mb-0">Purchase Location</label>
                    <select class="form-control" name="purchase_location" id="channels" onchange="showStores()" required>
                        <option value="" selected disabled>Choose store here...</option>
                        @foreach($result['channels'] as $key=>$channel)
                            <option value="{{$channel->id}}">{{ $channel->channel_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6" id="store_div"> <!-- Store -->
                    <label class="mb-0">Store</label>
                    <select class="form-control" name="store" id="selectedStores" onchange="showCustomerLocation()" required>
                        <option value="" selected disabled>Choose store here...</option>
                    </select>
                </div>

                <div class="form-group col-md-6" id="branch_div"> <!-- Branch -->
                    <label class="mb-0">Branch</label>
                    <select class="form-control" name="branch" id="branch" >
                        <option value="" selected disabled>Choose branch here...</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="form-row">
                <div class="form-group col-md-6" id="mode_of_return_div"> <!-- Mode of Return -->
                    <label class="mb-0">Mode of Return</label>
                    <select class="form-control" name="mode_of_return" id="mode_of_return" onchange="showDropOff()" required>
                        <option value="" selected disabled>Choose mode of return here...</option>
                    </select>
                </div>
                
                <div class="form-group col-md-6" id="store_drop_off_div"> <!-- Store Drop-Off -->
                    <label class="mb-0">Store Drop-Off</label>
                    <select class="form-control" name="store_drop_off" id="store_drop_off" onchange="showBranch()" >
                        <option value="" selected disabled>Choose store drop-off here...</option>
                        @foreach($result['stores_drop_off'] as $key=>$stores_drop_off)
                            <option value="{{$stores_drop_off->store_drop_off_name}}">{{ $stores_drop_off->store_drop_off_name }}</option>
                        @endforeach
                    </select>                    
                </div>

                <div class="form-group col-md-6" id="branch_branch_dropoff_div"> <!-- Branch Drop-Off -->
                    <label class="mb-0">Branch Drop-Off</label>
                    <select class="form-control" name="branch_dropoff" id="branch_dropoff" >
                        <option value="" selected disabled>Choose branch drop-off here...</option>
                    </select>
                </div>                        
            </div>
            <br><br>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="mb-0">First Name</label> <!-- First Name -->
                    <input type="text" class="form-control" name="firstname" placeholder="First Name" required> 
                </div>
                <div class="form-group col-md-6">
                    <label class="mb-0">Last Name</label> <!-- Last Name -->
                    <input type="text" class="form-control" name="lastname" placeholder="Last Name" required> 
                </div>
                <div class="form-group col-md-6"> <!-- Address Line 1 -->
                    <label class="mb-0">Address Line 1</label>
                    <input type="text" class="form-control" name="address_one" placeholder="Address Line 1">
                </div>
                <div class="form-group col-md-6"> <!-- Address Line 2 -->
                    <label class="mb-0">Address Line 2</label>
                    <input type="text" class="form-control" name="address_two" placeholder="Address Line 2">
                </div>
                <div class="form-group col-md-6"> <!-- State/Province -->
                    <label class="mb-0">State/Province</label>
                    <select class="form-control" name="province" id="provinces" onchange="showCity()" required>
                        <option value="" selected disabled>Choose state/province here...</option>
                        @foreach($result['province'] as $provinces)
                            <option class="capitalize" value="{{$provinces->provCode}}">
                                {{ $provinces->provDesc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6"> <!-- City/Municipality -->
                    <label class="mb-0">City/Municipality</label>
                    <select class="form-control" name="city" id="selectedcities" onchange="showBrngy()" required>
                        <option value="" selected disabled>Choose city/municipality here...</option>
                        @foreach($result['city'] as $cities)
                            <option class="capitalize" value="{{$cities->citymunCode}}">
                                {{ $cities->citymunDesc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6"> <!-- Barangay -->
                    <label class="mb-0">Barangay</label>
                    <input type="text" class="form-control" name="brgy" placeholder="Barangay" required> 
                </div>
                <div class="form-group col-md-6"> <!-- Country -->
                    <label class="mb-0">Country</label>
                    <select class="form-control capitalize" name="country" placeholder="Country" required>
                        <option value="Philippines" selected>Philippines</option>
                    </select>
                </div>
                <div class="form-group col-md-6"> <!-- Email Address -->
                    <label class="mb-0">Email Address</label>
                    <input type="email" class="form-control" name="email_address" placeholder="Enter Email" required> 
                </div>
                <div class="form-group col-md-6"> <!-- Contact Number -->
                    <label class="mb-0">Contact Number</label>
                    <input type="text" class="form-control" name="contact_number" pattern="^(09|\+639)[0-9]{9}$" placeholder="Contact Number" required> 
                </div>
            </div>

            <br><br>

            <div class="form-row">
                <div class="form-group col-md-6"> <!-- Order Number -->
                    <label class="mb-0">Order Number</label>
                    <input type="text" class="form-control" name="order_no" placeholder="Order Number" required> 
                </div>
                <div class="form-group col-md-6"> <!-- Purchase Date -->
                    <label class="mb-0">Purchase Date</label>
                    <input type="date" id="purchase_date" name="purchase_date" class="form-control"  required/>
                </div>
                <div class="form-group col-md-6"> <!-- Mode of Refund -->
                    <label class="mb-0">Mode of Refund</label>
                    @if(!empty($result['mode_of_payment']))
                        <select class="form-control" name="refund" required>
                            <option value="" selected disabled>Choose mode of refund here...</option>
                            @foreach($result['mode_of_payment'] as $key=>$payment)
                                @if($payment->id != '1')
                                    <option value="{{ $payment->payment_name }}">{{ $payment->payment_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="form-group col-md-6"> <!-- Original Mode of Payment -->
                    <label class="mb-0">Original Mode of Payment</label>
                    @if(!empty($result['mode_of_payment']))
                        <select class="form-control" name="payment" required>
                            <option value="" selected disabled>Choose mode of payment here...</option>
                            @foreach($result['mode_of_payment'] as $key=>$payment)
                                <option value="{{$payment->id}}">{{ $payment->payment_name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <br/>
                <div class="form-group col-md-6"> <!-- Purchase Amount -->
                    <label class="mb-0">Purchase Amount</label>
                    <input type="text" class="form-control" name="purchase_amount" pattern="\d*" placeholder="Purchase Amount" maxlength="6" required> 
                </div>
            </div>

            <br>
            
            <div class="form-row">
                <div class="form-group col-md-12" id="bank_text" style="background-color:#3C8DBC" hidden="true">
                    <p style="font-weight: bold; color: white;" >
                        The information below will be used to refund your transaction once we confirm the validity of your warranty.
                        Do not give us your Credit Card / Debit Card numbers, nor your CVC
                    </p>
                </div>
            </div>  
            <div class="form-row">
                <div class="form-group col-md-6" id="bank_name" hidden="true"> <!-- Bank Name -->
                    <label class="mb-0">Bank Name</label>
                    <input type="text" class="form-control" name="bankname" placeholder="Bank Name"> 
                </div>
                <div class="form-group col-md-6" id="bank_acc_no" hidden="true"> <!-- Bank Account Number -->
                    <label class="mb-0">Bank Account Number</label>
                    <input type="text" class="form-control" name="bank_account_no" placeholder="Bank Account Number"> 
                </div>
                <div class="form-group col-md-6" id="bank_acc_name" hidden="true"> <!-- Bank Account Name -->
                    <label class="mb-0">Bank Account Name</label>
                    <input type="text" class="form-control" name="bank_account_name" placeholder="Bank Account Name"> 
                </div>
            </div>
            <br><br>
            <div class="form-row">
                <div class="form-group col-md-12"> <!-- Item Code -->
                    <label class="mb-0">Item Code</label>
                    <input type="text" class="form-control" name="digits_code" placeholder="Item Code"> 
                </div>
                <div class="form-group col-md-12"> <!-- Item Description -->
                    <label class="mb-0">Item Description</label>
                    <input type="text" class="form-control" name="item_desc" placeholder="Item Description" required> 
                </div>
                <div class="form-group col-md-12"> <!-- Serial Number -->
                    <label class="mb-0">Serial Number</label>
                    <input type="text" class="form-control" name="serial_number" placeholder="Serial Number" > 
                </div>
                <div class="form-group col-md-12"> <!-- Problem Details -->
                    <label class="mb-0">Problem Details</label>
                    @if(!empty($result['problem_details']))
                        <select data-placeholder="Choose problem details here..." name="problem_details[]" id="ProblemDetail" onchange="OtherProblemDetail()" class="form-control limitedNumbSelect2" multiple="true" required>
                            @foreach($result['problem_details'] as $key=>$problem_detail)
                                <option value="{{$problem_detail->id}}">{{ $problem_detail->problem_details }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="form-group col-md-12" id="show_other_problem"></div>

                <div class="form-group col-md-12"> <!-- Items Included -->
                    <label class="mb-0">Items Included</label>
                    @if(!empty($result['items_included']))
                        <select data-placeholder="Choose items included here..." name="items_included[]" id="items_included" onchange="selectedOther()" class="form-control limitedNumbSelect2" multiple="true" required>
                            @foreach($result['items_included'] as $key=>$item_included)
                                <option value="{{$item_included->id}}">{{ $item_included->items_description_included }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="form-group col-md-12" id="show_other_item"></div>            
            </div>
    
            <div class="form-row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn hvr-hover" style="float: right; background-color: #3C8DBC;" >Create</button>
                </div>
            </div>
        </form>
    </div>
</div>


@include('footer')  

@include('script')
