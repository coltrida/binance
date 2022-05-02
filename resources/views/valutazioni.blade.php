@extends('layouts.stile')

@section('content')
    <h2>Valutazioni Coin</h2>

    <div class="row">
            <form class="row" method="post" action="{{route('coin.esegui.valutazioni')}}">
                @csrf
                <div class="col-12 col-md-2 col-lg-2 my-2">
                    <div class="row">
                        <div class="col col-lg-8 col-md-8">
                            <input required type="text" class="form-control" name="percentuale" placeholder="percentuale">
                        </div>
                        <div class="col">
                            %
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-2 col-lg-2 my-2">
                    <button type="submit" class="btn btn-primary mb-3">Verifica</button>
                </div>
            </form>
    </div>
@stop
