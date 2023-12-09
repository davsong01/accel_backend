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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin /> --}}
    
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: "Roboto", sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "Roboto", sans-serif;
        }

        .logo-address {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            margin-top: 2rem;
        }

        .logo-address .address {
            color: #344054;
            text-align: center;
            font-family: Roboto;
            font-size: 16px;
            font-style: normal;
            font-weight: 700;
            line-height: normal;
        }

        .logo-address .phone,
        .logo-address .web,
        .receipt-details {
            color: #344054;
            text-align: center;
            font-family: Roboto;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: 24px;
            /* 150% */
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
        }

        img {
            margin-bottom: 0.5rem;
        }

        .container {
            padding: 2rem 2rem;
        }

        h1 {
            color: #344054;
            text-align: center;
            font-family: Roboto;
            font-size: 24px;
            font-style: normal;
            font-weight: 700;
            line-height: 24px;
            /* 100% */
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

        .text-subText {
            color: #1570ef;
            font-family: Roboto;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
        }

        .text-top {
            color: #939094;
            font-family: Roboto;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: 24px;
            /* 150% */
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            text-align: left;
            padding: 1rem 0.5rem;
            color: #344054;
            font-family: Roboto;
            font-size: 16px;
            font-style: normal;
            font-weight: 700;
            line-height: 20px;
            /* 125% */
        }

        td {
            border-bottom: 1px solid #dddddd;
            text-align: left;
            color: #344054;
            font-feature-settings: "clig"off, "liga"off;
            font-family: Roboto;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: 20px;
            /* 125% */
        }

        th {
            background-color: #fafafa;
        }

        td {
            padding: 1.5rem 0.5rem;
        }

        .border-bottom {
            border-bottom: 1px solid #dddddd;
            padding: 1rem 0rem;
        }

        /* cyrillic-ext */

    </style>
</head>

<body>
    <header class="logo-address">
        <img src="{{ $logo }}" alt="logo" style="width: 5%" style="text-align:center"/>
        <div style="display: flex;
          align-items: center;
          flex-direction: column;
          margin: 0rem;
          padding: 0rem;
        ">
            <p class="address">{{ $address }}</p>
            {{-- <p class="phone">+234 901 234 5678</p> --}}
        </div>
        <p class="web">{{ $website }}</p>
    </header>

    <main class="container">
        <h1>PAYMENT RECIEPT</h1>
        <div style="
          display: flex;
          gap: 1rem;
          margin-top: 1rem;
          flex-direction: column;
        ">
            <div style="display: flex; gap: 0.5rem; flex-direction: column">
                <p class="text-header">Receipt No.</p>
                <p class="text-subText">{{ $data['receipt_no'] }}</p>
            </div>
            <div style="display: flex; justify-content: space-between">
                <div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        <p class="text-top">Contract ID</p>
                        <p class="text-header">{{ $payment->customer_contract_ids }}</p>
                    </div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        <p class="text-top">Customer name</p>
                        <p class="text-header">
                            {{ $payment->customer->user->first_name .' '.$payment->customer->user->last_name }}
                        </p>
                    </div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        {{-- <p class="text-top">Contract</p>
                        <p class="text-header">${contract}</p> --}}
                    </div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        <p class="text-top">Property Location</p>
                        <p class="text-header">{{ $payment->contract->location->name}}</p>
                    </div>
                </div>
                <div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        <p class="text-top">Contract Status</p>
                        <p class="text-header">{{ ucfirst($payment->contract->status) }}</p>
                    </div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                    @if(!empty($payment->contract->primaryMarketer($payment->contract)))
                    <?php 
                        $marketer = $payment->contract->primaryMarketer($payment->contract)
                    ?>
                        <p class="text-top">Marketer</p>
                        <p class="text-header">{{ $marketer['first_name'] . ' ' . $marketer['last_name']}}</p>
                    @endif
                    </div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        {{-- <p class="text-top">Payment Bank</p>
                        <p class="text-header">{{ $payment->account}}</p> --}}
                    </div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        <p class="text-top">Payment Purpose</p>
                        <p class="text-header">{{ ucwords(str_replace('_', ' ',$payment->purpose)) }}</p>
                    </div>
                </div>
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
                <td>{{ $company->currencyData->symbol . number_format($payment->amount) }}</td>
            </tr>
        </table>
        <div style="
          display: flex;
          flex-direction: column;
          width: 100%;
          align-items: end;
          gap: 1rem;
          margin-top: 1rem;
        ">
            <div class="border-bottom" style="
            display: grid;
            grid-template-columns: 1fr 170px;
            justify-content: space-between;
            gap: 3rem;
          ">
                <p class="text-header">Previous Amount Paid</p>
                <p class="receipt-details">0</p>
            </div>
            <div class="border-bottom" style="
            display: grid;
            grid-template-columns: 1fr 170px;
            justify-content: space-between;
            gap: 3rem;
          ">
                <p class="text-header">Balance</p>
                <p class="receipt-details">0</p>
            </div>
        </div>

        <div style="
          display: flex;
          width: 100%;
          padding: 1rem 0;
          justify-content: space-between;
          margin-top: 1rem;
        ">
            <div>
                <p class="text-top">Payment received by</p>
                <p class="text-header">{{ $payment->staff->user->first_name . ' ' .$payment->staff->user->last_name }}</p>
            </div>
            {{-- <div>
                <p class="text-top">Branch</p>
                <p class="text-header">${city}</p>
            </div> --}}
        </div>
    </main>
</body>

</html>