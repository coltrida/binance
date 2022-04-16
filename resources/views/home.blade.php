@extends('layouts.stile')

@section('content')
    @auth
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <p class="lead">
                    <a href="{{route('trade.index')}}" style="width: 250px"
                       class="btn btn-lg btn-secondary fw-bold border-white bg-white">Staking</a>
                </p>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <p class="lead">
                    <a href="{{route('platform.index')}}" style="width: 250px"
                       class="btn btn-lg btn-secondary fw-bold border-white bg-white">Inserisci Piattaforma</a>
                </p>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <p class="lead">
                    <a href="{{route('coin.index')}}" style="width: 250px"
                       class="btn btn-lg btn-secondary fw-bold border-white bg-white">Portafoglio coins</a>
                </p>
            </div>
        </div>
    @endauth
@stop
