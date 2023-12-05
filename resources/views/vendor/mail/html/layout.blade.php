{{-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
@media only screen and (max-width: 600px) {
.inner-body {
width: 100% !important;
}

.footer {
width: 100% !important;
}
}

@media only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}
</style>
</head>
<body>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
{{ $header ?? '' }}

<!-- Email Body -->
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<!-- Body content -->
<tr>
<td class="content-cell">
{{ Illuminate\Mail\Markdown::parse($slot) }}

{{ $subcopy ?? '' }}
</td>
</tr>
</table>
</td>
</tr>

{{ $footer ?? '' }}
</table>
</td>
</tr>
</table>
</body>
</html> --}}
<?php 
    $logo = !empty(session('companySetting')->company_logo) ? url('/') . '/uploads/' . base64_encode(session('companySetting')->company_logo) : url('/') . '/uploads/' .'images/brands/logos/default.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ config('app.name') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <style>
        @media only screen and (max-width: 600px) {
        .inner-body {
        width: 100% !important;
        }

        .footer {
        width: 100% !important;
        }
        }

        @media only screen and (max-width: 500px) {
        .button {
        width: 100% !important;
        }
        }
    </style>
</head>
<body>
    <div class="logo" style="width: 20%;margin-left: 0px;">  
        <img src="{{$logo}}" alt="logo">
    </div>
 <section class="Top-secton" style="width=100%;cellpadding=0;cellspacing=0;border: hidden !important;">    
    <div class="">
        {{ Illuminate\Mail\Markdown::parse($slot) }}
    </div>
 </section>
   <section class="Footer-section">
    <div class="footercredit">
        <p class="Footer-section-p">Powered by {{ config('app.name')}} © {{date('Y')}} All rights reserved.</p>
        </div>
   </section>
</div>
</body>
</html>
