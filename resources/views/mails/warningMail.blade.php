@component('mail::message')
# Introduction

Il delta di {{$platform}} è: {{$delta}} E POI {{$delta < 4}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
