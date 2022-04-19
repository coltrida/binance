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
                <th scope="col">Action</th>
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
                        att: &nbsp;{{ number_format($variazioni[$item->id]['price'], 3, ',', '.') }}
                    </td>
                    <td>{{$item->quantita}}</td>
                    <td>
                        <span class="badge {{$variazioni[$item->id]['price'] - $item->prezzoAcquisto < 0 ? 'bg-danger' : 'bg-success'}}">
                        {{ number_format( ((($variazioni[$item->id]['price'] * (float)$item->quantita)  -
                                    ($item->prezzoAcquisto * (float)$item->quantita)) /
                                    ($item->prezzoAcquisto * (float)$item->quantita)) * 100, 3, ',', '.') }} %
                        </span>
                    </td>
                    <td>
                        <form action="{{route('coin.destroy', [ 'coin' => $item->id])}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button title="elimina" type="submit">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>

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

    <h2>Totale Originale: € {{number_format($totaleOriginale, 3, ',', '.')}}</h2>
    <h2>Totale Portafoglio: € {{number_format($totalePortafoglio, 3, ',', '.')}}</h2>
@stop
