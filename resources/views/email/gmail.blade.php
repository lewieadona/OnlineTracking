<html>
<title>Return Request</title>
<body style="background-color:#D3D3D3;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
    <table style="max-width:750px;margin:50px auto 10px;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px #3C8DBC;">
        <tbody>
            <tr>
                <td style="width:100%;padding:20px;vertical-align:top;" colspan="3">
                    <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">{{ $details['title'] }}</span></p>
                    <p style="font-size:14px;margin:0 0 6px 0;">A representative will reach out to you in a few days to process your concern. Please keep your email and phone lines open so that we can contact you.</p>
                    <p style="font-size:14px;margin:0 0 6px 0;">{{ $details['body'] }} <span style="font-weight:bold;display:inline-block;min-width:150px">{{ $details['alldetails']->return_reference_no }}</span></p>
                </td>
            </tr>
            <tr style="border: solid 1px #ddd;">
                <td style="width:100%;padding:15px;vertical-align:top" colspan="1">
                    <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Purchase Location:</span><br>{{ $details['alldetails']->purchase_location }}</p>
                </td>
                <td style="width:100%;padding:15px;vertical-align:top" colspan="1">
                    <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Store:</span><br>{{ $details['alldetails']->store }}</p>
                </td>
                <td style="width:100%;padding:10px;vertical-align:top" colspan="1">
                    @if(!empty($details['alldetails']->branch))
                        <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Branch:</span><br>{{ $details['alldetails']->branch }}</p>
                    @endif
                </td>
            </tr>
            <tr style="border: solid 1px #ddd;">
                <td style="width:100%;padding:15px;vertical-align:top" colspan="1">
                    <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Mode of Return:</span><br>{{ $details['alldetails']->mode_of_return }}</p>
                </td>
                <td style="width:100%;padding:15px;vertical-align:top" colspan="1">
                    @if($details['alldetails']->mode_of_return == "STORE DROP-OFF") 
                        <p style="font-size:15px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Store Drop-Off:</span><br>{{ $details['alldetails']->store_dropoff }}</p>
                    @endif
                </td>
                <td style="width:100%;padding:10px;vertical-align:top" colspan="1">
                    @if($details['alldetails']->mode_of_return == "STORE DROP-OFF") 
                        <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Branch Drop-Off:</span><br>{{ $details['alldetails']->branch_dropoff }}</p>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width:100%;padding:20px;vertical-align:top" colspan="2.5">
                    <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">First Name:</span><br>{{ $details['alldetails']->customer_first_name }}</p>
                    <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Address:</span><br>{{ $details['alldetails']->address }}</p>
                    <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Contact Number:</span><br>{{ $details['alldetails']->contact_no }}</p>
                    <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Purchase Date:</span><br>{{ $details['alldetails']->purchase_date }}</p>
                    <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Original Mode of Payment:</span><br>{{ $details['alldetails']->mode_of_payment }}</p>
                    @if(!empty($details['alldetails']->digits_code))
                        <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Item Code:</span><br>{{ $details['alldetails']->digits_code }}</p>
                    @endif
                </td>

                <td style="width:100%;padding:20px;vertical-align:top" colspan="2.5">
                    <p style="font-size:14px;margin:0 0 6px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Last Name:</span><br>{{ $details['alldetails']->customer_last_name }}</p>
                    <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Email Address:</span><br>{{ $details['alldetails']->email_address }}</p>
                    <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Order Number:</span><br>{{ $details['alldetails']->order_no }}</p>
                    <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Mode of Refund:</span><br>{{ $details['alldetails']->mode_of_refund }}</p>
                    <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Purchase Amount:</span><br>{{ $details['alldetails']->cost }}</p>
                </td>
            </tr>
            @if($details['alldetails']->purchase_location !== "RETAIL STORE")
                <tr>
                    <td style="width:100%;padding:20px;vertical-align:top" colspan="2.5">
                        <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Bank Name:</span><br>{{ $details['alldetails']->bank_name }}</p>
                        <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Bank Account Name:</span><br>{{ $details['alldetails']->bank_account_name }}</p>
                    </td>
                    <td style="width:50%;padding:20px;vertical-align:top" colspan="2.5">
                        <p style="font-size:14px;margin:0 0 0 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Bank Account Number:</span><br>{{ $details['alldetails']->bank_account_no }}</p>
                    </td>
                </tr>
            @endif
            <tr>
                <td style="width:100%;padding:20px;vertical-align:top" colspan="3">
                    <p style="font-size:14px;margin:0 0 0 0;padding:10px;border:solid 1px #ddd;"><span style="font-weight:bold;display:inline-block;min-width:146px">Item Description:</span><br>{{ $details['alldetails']->item_description }}</p>
                    @if(!empty($details['alldetails']->serial_number))
                        <p style="font-size:14px;margin:0 0 0 0;padding:10px;border:solid 1px #ddd;"><span style="font-weight:bold;display:inline-block;min-width:146px">Serial Number:</span><br>{{ $details['alldetails']->serial_number }}</p>
                    @endif
                    <p style="font-size:14px;margin:0 0 0 0;padding:10px;border:solid 1px #ddd;"><span style="font-weight:bold;display:inline-block;min-width:146px">Problem Details:</span><br>{{ $details['alldetails']->problem_details }} @if(!empty($details['alldetails']->problem_details_other)), {{$details['alldetails']->problem_details_other}} @endif</p>
                    <p style="font-size:14px;margin:0 0 0 0;padding:10px;border:solid 1px #ddd;"><span style="font-weight:bold;display:inline-block;min-width:146px">Items Included:</span><br>{{ $details['alldetails']->items_included }} @if(!empty($details['alldetails']->items_included_others)), {{$details['alldetails']->items_included_others}} @endif</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>