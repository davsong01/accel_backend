<?php 
    $logo = !empty( $company->company_logo) ? base_path() . '/uploads/' . session('companySetting')->company_logo : base_path() . '/uploads/' .'images/brands/logos/default.png';
?>
@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
           
            <img src="{{ $logo }}" class="logo" alt="">
           
        <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
 {{-- {{dd(session('companySetting'))}} --}}
