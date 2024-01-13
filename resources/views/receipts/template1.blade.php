{{-- <!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice - #123</title>

    <style type="text/css">
        <blade page|%20%7B>margin: 0px;
        }

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
        }

        .invoice h3 {
            margin-left: 15px;
        }

        .information {
            background-color: #60A7A6;
            color: #FFF;
        }

        .information .logo {
            margin: 5px;
        }

        .information table {
            padding: 10px;
        }
    </style>

</head>

<body>

    <div class="information">
        <table width="100%">
            <tr>
                <td align="left" style="width: 40%;">
                    <h3>John Doe</h3>
                    <pre>
Street 15
123456 City
United Kingdom
<br /><br />
Date: 2018-01-01
Identifier: #uniquehash
Status: Paid
</pre>


                </td>
                <td align="center">
                    <img src="/path/to/logo.png" alt="Logo" width="64" class="logo" />
                </td>
                <td align="right" style="width: 40%;">

                    <h3>CompanyName</h3>
                    <pre>
                    https://company.com

                    Street 26
                    123456 City
                    United Kingdom
                </pre>
                </td>
            </tr>

        </table>
    </div>


    <br />

    <div class="invoice">
        <h3>Invoice specification #123</h3>
        <table width="100%">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Item 1</td>
                    <td>1</td>
                    <td align="left">€15,-</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="1"></td>
                    <td align="left">Total</td>
                    <td align="left" class="gray">€15,-</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="information" style="position: absolute; bottom: 0;">
        <table width="100%">
            <tr>
                <td align="left" style="width: 50%;">
                    &copy; {{ date('Y') }} {{ config('app.url') }} - All rights
reserved.
</td>
<td align="right" style="width: 50%;">
    Company Slogan
</td>
</tr>

</table>
</div>
</body>

</html> --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice - #123</title>

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
            width: 50%;
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
                <td align="left" style="width: 40%">
                    <p class="address">15B Sule Abuka Cresent Opebi, Abuja, Nigeria</p>
                    <p class="phone">+234 901 234 5678</p>
                    <pre><br /><br />
                        <span class="text-header">Contract ID</span>
                        ${contract_id}
                        <br /><br />
                        <span class="text-header">Contract</span>
                        ${contract}
                        <br /><br />
                        <span class="text-header">Contract Status</span>
                        ${contract_status}
                        <br /><br />
                        <span class="text-header">Payment Bank</span>
                        ${payment_bank}
                    </pre>
                </td>
                <td align="center">
                    <img src="public/accel234_logo.png" alt="Logo" width="64" class="logo" />
                </td>
                <td align="right" style="width: 40%">
                    <h3>accel234</h3>
                    <pre>
                <span class="web">www.accel234.com</span>
                


 <br /><br />
 <span class="text-header">Customer name</span>
${customer_name}
<br /><br />
<span class="text-header">Property Location</span>
${property_location}
<br /><br />
<span class="text-header">Marketer</span>
${markerter}
<br /><br />
<span class="text-header">Payment Purpose</span>
${payment_purpose}
                </pre>
                </td>
            </tr>
        </table>
    </div>

    <br />

    <div class="invoice">
        <h5>Receipt No. ${receipt_no}</h5>
        <table width="100%">
            <thead>
                <tr>
                    <th align="left">S/N</th>
                    <th align="left">Transaction Date & Time</th>
                    <th align="left">Description</th>
                    <th align="left">Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>${s/n}</td>
                    <td>${transaction_date}</td>
                    <td>${description}</td>
                    <td>${price}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="information1">
        <table width="100%">
            <tr>
                <td align="right" style="width: 40%">
                    <pre>

<span style="border-bottom: 1px solid black; padding-bottom: 0.5rem;">  Previous Amount Paid ${previous_amountPaid}</span>
<br /><br />
<span style="border-bottom: 1px solid black; padding-bottom: 0.5rem;">To Balance ${to_balance}</span>
  <br /><br />
  Current Amount Paid ${current_amountPaid}
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


    <br /><br />
    Payment received by
    ${payment_receivedBy}
    </pre>
                </td>

                <td align="right" style="width: 40%; padding-top: 4rem">
                    <pre>
                  Branch
                  ${city}
                    </pre>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
