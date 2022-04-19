@extends('layouts.stile')

@section('content')
    <h2>Inserisci Coin</h2>

    <div class="row">
            <form class="row" method="post" action="{{route('coin.store')}}">
                @csrf
                <div class="col-12 col-md-2 col-lg-2 my-2">
                    <input required type="date" class="form-control" name="acquisizione" >
                </div>
                <div class="col-12 col-md-2 col-lg-2 my-2">
                    <input required type="text" class="form-control" name="ticker" placeholder="ticker">
                </div>
                <div class="col-12 col-md-2 col-lg-2 my-2">
                    <input required type="text" class="form-control" name="prezzoAcquisto" placeholder="prezzo di acquisto">
                </div>
                <div class="col-12 col-md-2 col-lg-2 my-2">
                    <input required type="text" class="form-control" name="quantita" placeholder="quantita">
                </div>
                <div class="col-12 col-md-2 col-lg-2 my-2">
                    <button type="submit" class="btn btn-primary mb-3">Inserisci</button>
                </div>
            </form>
    </div>


    <h2 class="mt-5">Coins</h2>
        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th scope="col">Ticker</th>
                <th scope="col">prezzi</th>
                <th scope="col">qta</th>
                <th scope="col">Delta</th>
            </tr>
            </thead>
            <tbody>
            @foreach($coins as $item)
                <tr>
                    <td>
                        {{$item->acquisizione}} <br> {{$item->ticker}}
                    </td>
                    <td>
                        acq: {{ number_format($item->prezzoAcquisto, 7, ',', '.') }} <br>
                        att: &nbsp;{{ number_format($variazioni[$item->id]['price'], 7, ',', '.') }}
                    </td>
                    <td>{{$item->quantita}}</td>
                    <td>
                        {{ number_format( ($variazioni[$item->id]['price'] * (float)$item->quantita)  -
                                    ($item->prezzoAcquisto * (float)$item->quantita), 7, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-center">
                        <div style="display: flex; justify-content: center">
                            <div >
                                {{$coins->links('vendor.pagination.bootstrap-4')}}
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
@stop
