<?php 

    use App\Models\CompanySetting;
    $company = CompanySetting::with(['currencyData'])->where('company_id', \Session::get('company_id') ?? 1)->first();
    
    $logo = !empty( $company->company_logo) ? base_path() . '/uploads/' . $company->company_logo : base_path() . '/uploads/' .'images/brands/logos/default.png';
    $address = $company->address;
    $website = $company->website;
    $company_name = $company->company_name;
    $count = 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
      /* * {
        margin: 0;
        padding: 0;
        font-family: "Roboto", sans-serif;
      }
      body {
        margin: 0;
        padding: 0;
        font-family: "Roboto", sans-serif;
      } */
      .logo-address {
        margin-top: 2rem;
      }
      .logo-address img {
        display: block;
        margin: 0 auto;
        margin-bottom: 0.5rem;
      }
      .logo-address .address,
      .logo-address .phone,
      .logo-address .web {
        text-align: center;
        color: #344054;
        font-family: Roboto;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
        margin-bottom: 0.5rem;
      }
      .container {
        padding: 2rem;
      }
      h1 {
        color: #344054;
        text-align: center;
        /* font-family: Roboto; */
        font-size: 24px;
        font-style: normal;
        font-weight: 700;
        line-height: 24px; /* 100% */
      }
      .text-header {
        color: #344054;
        /* font-family: Roboto; */
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 24px; /* 150% */
      }
      .text-subText {
        color: #1570ef;
        /* font-family: Roboto; */
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
      }
      .text-top {
        color: #939094;
        /* font-family: Roboto; */
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px; /* 150% */
      }
      table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        margin-top: 1rem;
      }
      th,
      td {
        text-align: left;
        padding: 1rem 0.5rem;
        color: #344054;
        /* font-family: Roboto; */
        font-size: 16px;
        font-style: normal;
        line-height: 20px; /* 125% */
      }
      th {
        background-color: #fafafa;
        font-weight: 700;
      }
      td {
        border-bottom: 1px solid #dddddd;
        font-weight: 400;
      }
      .border-bottom {
        border-bottom: 1px solid #dddddd;
        padding: 1rem 0rem;
      }
      .receipt-details {
        display: inline-block;
        margin-left: auto;
        text-align: right;
      }

      .item{
        padding: 0.5rem 0;
      }

      .flex-container {
        /* overflow: hidden;  */
        /* Clearfix */
      }

      .item {
        float: left;
        width: 48%; /* Adjust as needed, leaving some space for margin */
        margin-right: 1%; /* Adjust as needed for spacing between items */
        box-sizing: border-box;
      }

      .item:last-child {
        margin-right: 0; /* Remove right margin for the last item */
      }

      .w-full {
            width: 100%;
        }
        .w-half {
            width: 50%;
        }
    </style>
</head>

<body>
  
    <div class="row" style="text-align: center;margin-bottom:-7px;display:flex;justify-content: center;" >
      <img src="{{ $logo }}" alt="logo" style="width: 150px;text-align:center !important;"/> <br>
       <div>
          <p class="address">{{ $address }}</p>
        </div>
        <p class="web">{{ $website }}</p> <br>
        <h1>PAYMENT RECIEPT</h1>
    </div>

    <div class="row top" style="max-width: 850px;margin: auto;">
      <div class="col-6" style="float:left">
        <p class="text-header">Receipt No.</p>
        <p class="text-subText">{{ $data['receipt_no'] }}</p>
      </div>
       <div class="col-6" style="float:right">
        <p class="text-top">Contract ID</p>
        <p class="text-header">{{ $payment->customer_contract_ids }}</p>
      </div>
    </div>
    
    <div class="">
        {{-- <h1>PAYMENT RECIEPT</h1> --}}
        <div>
            {{-- <div>
                <p class="text-header">Receipt No.</p>
                <p class="text-subText">{{ $data['receipt_no'] }}</p>
            </div> --}}
            
            <div class="flex-container">
                <div class="item">
                    <p class="text-top">Contract ID</p>
                    <p class="text-header">{{ $payment->customer_contract_ids }}</p>
                </div>
                
                <div class="item" style="text-align: end">
                        <p class="text-top">Customer name</p>
                        <p class="text-header">
                            {{ $payment->customer->user->first_name .' '.$payment->customer->user->last_name }}
                        </p>
                </div>
                <div class="item">
                        <p class="text-top">Contract</p>
                        <p class="text-header">${contract}</p>
                </div>
                <div class="item" style="text-align: end">
                    <p class="text-top">Property Location</p>
                    <p class="text-header">{{ $payment->contract->location->name}}</p>
                </div>
                <div class="item">
                    <p class="text-top">Contract Status</p>
                    <p class="text-header">{{ ucfirst($payment->contract->status) }}</p>
                </div>
                <div class="item" style="text-align: end">
                    @if(!empty($payment->contract->primaryMarketer($payment->contract)))
                    <?php 
                        $marketer = $payment->contract->primaryMarketer($payment->contract)
                    ?>
                        <p class="text-top">Marketer</p>
                        <p class="text-header">{{ $marketer['first_name'] . ' ' . $marketer['last_name']}}</p>
                    @endif
                </div>
                <div class="item">
                        {{-- <p class="text-top">Payment Bank</p>
                        <p class="text-header">{{ $payment->account}}</p> --}}
                </div>
                <div class="item" style="text-align: end;">
                    <p class="text-top">Payment Purpose</p>
                    <p class="text-header">{{ ucwords(str_replace('_', ' ',$payment->purpose)) }}</p>
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <table>
            <tr>
                <th>S/N</th>
                <th>Transaction Date</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>{{ $count + 1}}</td>
                <td>{{ date("M jS, Y", strtotime($payment->created_at)) }}</td>
                <td>{{ $payment->description }}</td>
                <td>{{ $company->currencyData->symbol . $payment->amount }}</td>
            </tr>
        </table>
        {{-- {{dd($company->currencyData->symbol)}} --}}
        <div>
            <div class="border-bottom" style="text-align: end; width: fit-content; margin-left: auto;padding: 20px 0;">
                <p class="text-header" style="display: inline-block; padding: 0 30px;">Previous Amount Paid</p>
                <p class="receipt-details" style="display: inline-block;">0</p>
            </div>
            <div class="border-bottom " style="text-align: end; width: fit-content; margin-left: auto;padding: 20px 0;">
                <p class="text-header" style="display: inline-block;padding: 0 30px;">Balance</p>
                <p class="receipt-details">0</p>
            </div>
        </div>

        <div>
            <div class="item">
                <p class="text-top">Payment received by</p>
                <p class="text-header">{{ $payment->staff->user->first_name . ' ' .$payment->staff->user->last_name }}</p>
            </div>
            {{-- <div style="text-align: end" class="item">
                <p class="text-top">Branch</p>
                <p class="text-header">${city}</p>
            </div> --}}
        </div>
      </div>
</body>

</html>