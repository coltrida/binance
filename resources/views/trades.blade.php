@extends('layouts.stile')

@section('content')
    <h2>Inserisci trade</h2>

    <div class="row">
        <div class="col-8">
            <form class="row" method="post" action="{{route('trade.store')}}">
                @csrf
                <div class="col-12 col-md-3 col-lg-3 my-2">
                    <input type="date" class="form-control" name="iscrizione" placeholder="data iscrizione">
                </div>
                <div class="col-12 col-md-3 col-lg-3 my-2">
                    <select name="platform_id" class="form-select">
                        <option></option>
                        @foreach($platforms as $platform)
                            <option value="{{$platform->id}}">{{$platform->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 col-lg-3 my-2">
                    <input type="text" class="form-control" name="import" placeholder="importo in euro">
                </div>
                <div class="col-12 col-md-2 col-lg-2 my-2">
                    <button type="submit" class="btn btn-primary mb-3">Inserisci</button>
                </div>
            </form>
        </div>
        <div class="col-4">
            <div>
        <span class="badge bg-secondary" style="height: 50px">
            {{$btcusdt['symbol']}} <br><br>
            {{$btcusdt['price']}}
        </span>
                <span class="badge bg-warning text-dark" style="height: 50px">
            {{$btcusdt['symbol']}} <br><br>
            {{$btcusdt['price']}}
        </span>
            </div>
        </div>
    </div>




    <h2 class="mt-5">Trades</h2>
        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th scope="col">Data Iscrizione</th>
                <th scope="col">Platform</th>
                <th scope="col">Importo</th>
                <th scope="col">Interessi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($trades as $item)
                <tr>
                    <td>{{$item->iscrizione_formattata}}</td>
                    <td>{{$item->platform->name}}</td>
                    <td>{{$item->importo_formattato}}</td>
                    <td></td>
                </tr>
            @endforeach
            </tbody>
        </table>
@stop
