@extends('layouts.stile')

@section('content')
    <h2>Inserisci Staking</h2>

    <div class="row">
        <div class="col-8">
            <form class="row" method="post" action="{{route('trade.store')}}">
                @csrf
                <input type="hidden" name="saldo" value="{{$btcusdt['price']}}">
                <div class="col-12 col-md-3 col-lg-3 my-2">
                    <input type="date" required class="form-control" name="iscrizione" placeholder="data iscrizione">
                </div>
                <div class="col-12 col-md-3 col-lg-3 my-2">
                    <select name="platform_id" required class="form-select">
                        <option></option>
                        @foreach($platforms as $platform)
                            <option value="{{$platform->id}}">{{$platform->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 col-lg-3 my-2">
                    <input type="text" required class="form-control" name="import" placeholder="importo in euro">
                </div>
                <div class="col-12 col-md-2 col-lg-2 my-2">
                    <button type="submit" class="btn btn-primary mb-3">Inserisci</button>
                </div>
            </form>
        </div>
        <div class="col-4">
            <div>
                <span class="badge bg-warning text-dark" style="height: 50px">
            {{$btcusdt['symbol']}} <br><br>
            {{number_format($btcusdt['price'], 3, ',', '.')}}
        </span>
            </div>
        </div>
    </div>




    <h2 class="mt-5">Trades</h2>
        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th scope="col">Platform</th>
                <th scope="col">Importo</th>
                <th scope="col">€/$</th>
                <th scope="col">delta</th>
            </tr>
            </thead>
            <tbody>
            @foreach($trades as $item)
                <tr>
                    <td>
                        {{$item->iscrizione_formattata}} <br> {{$item->platform->name}}
                    </td>
                    <td>{{$item->importo_formattato}}</td>
                    <td>{{ number_format($item->saldo, 3, ',', '.') }}</td>
                    <td>
                        @if($item->saldo)
                            <span class="badge {{$btcusdt['price'] - $item->saldo > 0 ? 'bg-danger' : 'bg-success'}}">
                                {{ number_format((($item->saldo - $btcusdt['price']) / $item->saldo) * 100, 2, ',', '.') }} %
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-center">
                        <div style="display: flex; justify-content: center">
                            <div >
                                {{$trades->links('vendor.pagination.bootstrap-4')}}
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    <h3>Tot. Invstimento = € {{ number_format($totInvestimento, 0, ',', '.') }}</h3>
@stop
