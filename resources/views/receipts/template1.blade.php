<?php 

    use App\Models\CompanySetting;
    $company = CompanySetting::where('company_id',1)->first();

    $logo = !empty( $company->company_logo) ? url('/') . '/uploads/' . base64_encode( $company->company_logo) : url('/') . '/uploads/' .'images/brands/logos/default.png';
    $invoice_number = rand(111111,999999);
    $address = $company->address;
    $website = $company->website;
    $company_name = $company->company_name;


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,500&display=swap"
        rel="stylesheet" />
    <title>Consode Receipt</title>
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
    </style>
</head>

<body>
    <header class="logo-address">
        <img src="{{ $logo }}" alt="logo" style="width: 11%" />
        <div style="
          display: flex;
          align-items: center;
          flex-direction: column;
          margin: 0rem;
          padding: 0rem;
        ">
            <p class="address">{{$address}}</p>
            {{-- <p class="phone">+234 901 234 5678</p> --}}
        </div>
        <p class="web">{{ $website }}</p>
    </header>

    <main class="container">
        <h1 style="color:{{ $company->main_color}}">PAYMENT RECIEPT</h1>
        <div style="
          display: flex;
          gap: 1rem;
          margin-top: 1rem;
          flex-direction: column;
        ">
            <div style="display: flex; gap: 0.5rem; flex-direction: column">
                <p class="text-header">Receipt No.</p>
                <p class="text-subText">{{$invoice_number}}</p>
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
                        <p class="text-header">${contract_id}</p>
                    </div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        <p class="text-top">Customer name</p>
                        <p class="text-header">${customer_name}</p>
                    </div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        <p class="text-top">Contract</p>
                        <p class="text-header">${contract}</p>
                    </div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        <p class="text-top">Property Location</p>
                        <p class="text-header">${property_location}</p>
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
                        <p class="text-header">{contract_status}</p>
                    </div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        <p class="text-top">Marketer</p>
                        <p class="text-header">${markerter}</p>
                    </div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        <p class="text-top">Payment Bank</p>
                        <p class="text-header">${payment_bank}</p>
                    </div>
                    <div style="
                display: flex;
                flex-direction: column;
                gap: 0.3rem;
                padding-bottom: 0.5rem;
              ">
                        <p class="text-top">Payment Purpose</p>
                        <p class="text-header">${payment_purpose}</p>
                    </div>
                </div>
            </div>
        </div>

        <table>
            <tr>
                <th>S/N</th>
                <th>Transaction Date & Time</th>
                <th>Description</th>
                <th>Price</th>
            </tr>
            <tr>
                <td>${s/n}</td>
                <td>${transaction_date}</td>
                <td>${description}</td>
                <td>${price}</td>
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
                <p class="receipt-details">${previous_amountPaid}</p>
            </div>
            <div class="border-bottom" style="
            display: grid;
            grid-template-columns: 250px 1fr;
            justify-content: space-between;
            gap: 3rem;
          ">
                <p class="text-header">To Balance</p>
                <p class="receipt-details">${to_balance}</p>
            </div>
            <div style="
            display: grid;
            grid-template-columns: 1fr 1fr;
            justify-content: space-between;
            gap: 3rem;
          ">
                <p class="text-header">Current Amount Paid</p>
                <p class="text-header">${current_amountPaid}</p>
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
                <p class="text-header">${payment_receivedBy}</p>
            </div>
            <div>
                <p class="text-top">Branch</p>
                <p class="text-header">${city}</p>
            </div>
        </div>
    </main>
</body>

</html>