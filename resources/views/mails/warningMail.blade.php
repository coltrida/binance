@component('mail::message')
# Introduction

Il delta di {{$platform}} è: {{$delta}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
