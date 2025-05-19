@component('mail::message')
# Name: {{ $name }}
# Email: {{ $email }}<br>
Subject: {{ $sub }} <br><br>
Message:<br> {{ $mess }}

Thanks,
{{ config('app.name') }}
@endcomponent