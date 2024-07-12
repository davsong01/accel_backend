<?php 
  $company_id = \Session::get('company_id') ?? 1;
  $companySetting = (object) sessionDetails('settings');
  $website = sessionDetails('company_url');
  $companySetting->currency_data = (object) $companySetting->currency_data;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $payment->transaction_id }}</title>

  <style type="text/css">
    body {
      margin: 0px;
    }

    * {
      font-family: Verdana, Arial, sans-serif;
    }

    a {
      color: #fff;
      text-decoration: none;
    }

    table {
      font-size: x-small;
    }

    tfoot tr td {
      font-weight: bold;
      font-size: x-small;
    }

    .invoice table {
      margin: 15px;
      margin-bottom: 0rem;
    }

    .invoice table th {
      background-color: #fafafa;
    }

    .invoice table th,
    .invoice table td {
      text-align: left;
      padding: 1rem 0.5rem;
      color: #344054;
      font-family: Roboto;
      font-size: 16px;
      font-style: normal;
      line-height: 20px;
      /* 125% */
    }

    .invoice h5 {
      margin-left: 15px;
    }

    .information {
      /* background-color: #60A7A6; */
      color: black;
    }

    .information1 {
      background-color: transparent;
      color: black;
    }

    .information .logo {
      margin: 5px;
      width: 10%;
    }

    .information table {
      padding: 10px;
      padding-bottom: 0rem;
      font-size: 12px;
    }

    .information1 table {
      padding: 10px;
      padding-bottom: 0rem;
    }

    .text-header {
      color: #344054;
      font-family: Roboto;
      font-size: 16px;
      font-style: normal;
      font-weight: 600;
      line-height: 24px;
      /* 150% */
    }

    .address,
    .phone,
    .web {
      color: black;
      font-size: 12px;
      font-style: normal;
      font-weight: 700;
    }
  </style>
</head>

<body>
  <div class="information">
    <table width="100%">
      <tr>
        <td align="center">
          <div>
            <img src="{{ base_path($companySetting->raw_logo) }}" alt="Logo" width="64" class="logo" />
            <p class="address">{{ $companySetting->address }}</p>
            {{-- <p class="phone">+234 901 234 5678</p> --}}
            <span class="web">{{ $website }}</span>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <div class="information">
    <table width="100%">
      <tr>
        <td align="left" style="width: 40%">

          <pre>
<br /><br />
<span class="text-header">Transaction Id</span>
{{ $payment->transaction_id }}
<br /><br />
<span class="text-header">Contract</span>
{{$contract->contract_name}}
<br /><br />
<span class="text-header">Contract Status</span>
{{ ucfirst($contract->contract_status)}}
<br /><br />
{{-- <span class="text-header">Payment Bank</span>
${payment_bank} --}}
</pre>
        </td>
        <td align="right" style="width: 40%">
          <pre>
 <br /><br />
 <span class="text-header">Customer name</span>
{{ $payment->customer->user->first_name .' '.$payment->customer->user->first_name. ' '.$payment->customer->user->last_name}}
<br /><br />
<span class="text-header">Property Location</span>
{{$contract->location->name}}
<br /><br />
{{-- {{dd($contract->primaryMarketer())}} --}}
<span class="text-header">Marketer</span>
{{ $contract->primaryMarketer()['first_name'] . ' ' .  $contract->primaryMarketer()['last_name'] }}
<br /><br />
<span class="text-header">Payment Purpose</span>
{{ucwords(str_replace("_"," ", $payment->transaction->purpose ?? $payment->purpose))}}
</pre>
        </td>
      </tr>
    </table>
  </div>

  <br />

  <div class="invoice">
    <h5>Receipt No. {{ $payment->transaction_id . '-'.$payment->id}}</h5>
    <table width="100%">
      <thead>
        <tr>
          <th align="left">S/N</th>
          <th align="left">Transaction Date & Time</th>
          <th align="left">Description</th>
          <th align="left">Property Price</th>
          <th align="left">Discount Applied</th>
          <th align="left">Amount Paid</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>{{$payment->created_at}}</td>
          <td>{{$payment->description}}</td>
          <td>{{ $companySetting->currency_data->identifier.number_format($others['contract_value'] ?? $payment->contract_value) }}</td>
          <td>{{ $companySetting->currency_data->identifier.number_format($others['discount_applied'] ?? $payment->discount_applied ) }}</td>
          <td>{{ $companySetting->currency_data->identifier.number_format($payment->amount) }}</td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="information1">
    <table width="100%">
      <tr>
        <td align="right" style="width: 40%">
          <pre>
              {{-- <span style="border-bottom: 1px solid black; padding-bottom: 0.5rem;">Previous Amount Paid <strong>${previous_amountPaid}</strong></span> --}}
              {{-- <br /><br /> --}}
            Current Amount Paid <strong>{{ $companySetting->currency_data->identifier.number_format($payment->amount) }}</strong>

            Total Amount Paid <strong>{{ $companySetting->currency_data->identifier.number_format($others['total_amount_paid'] ?? $payment->amount) }}</strong> <br><br>
            <span style="border-bottom: 1px solid black; padding-bottom: 0.5rem;">Balance <strong>{{ $companySetting->currency_data->identifier.number_format($others['balance'] ?? $payment->balance) }}</strong></span>
          </pre>
        </td>
      </tr>
    </table>
  </div>

  <div class="information1">
    <table width="100%">
      <tr>
        <td align="left" style="width: 40%">
          <pre>
    Payment received by 
    <strong>{{ ($payment->transaction->staff->user->first_name ?? $payment->staff_firstname) . ' ' . ($payment->transaction->staff->user->last_name ?? $payment->staff_lasttname) }}</strong>
    </pre>
        </td>

        @if(isset($payment->branch))
        <td align="right" style="width: 40%; padding-top: 4rem">
          <pre>
                  Branch
                  <strong>{{ $payment->branch->name}}</strong>
                    </pre>
        </td>
        @endif
      </tr>
    </table>
  </div>

  <!-- <div class="information" style="position: absolute; bottom: 0">
      <table width="100%">
        <tr>
          <td align="left" style="width: 50%">
            &copy; {{ date('Y') }} {{ config('app.url') }} - All rights
            reserved.
          </td>
          <td align="right" style="width: 50%">Company Slogan</td>
        </tr>
      </table>
    </div> -->
</body>

</html>
