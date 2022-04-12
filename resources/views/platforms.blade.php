@extends('layouts.stile')

@section('content')
    <h2>Inserisci Piattaforma</h2>

    <form class="row" method="post" action="{{route('platform.store')}}">
        @csrf
        <div class="col-12 col-md-4 col-lg-4">
            <input type="text" class="form-control" name="name" placeholder="Nome">
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <input type="text" class="form-control" name="interessi" placeholder="interessi percentuali">
        </div>
        <div class="col-12 col-md-2 col-lg-2">
            <button type="submit" class="btn btn-primary mb-3">Inserisci</button>
        </div>
    </form>

    <h2 class="mt-5">Piattaforme</h2>
        <table class="table table-dark table-striped">
            <thead>
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">Interessi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($platforms as $item)
                <tr>
                    <td>{{$item->name}}</td>
                    <td>{{$item->interessi}} %</td>
                </tr>
            @endforeach
            </tbody>
        </table>
@stop
