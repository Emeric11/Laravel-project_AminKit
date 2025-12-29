@extends('layouts.adminkit')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">150</h5>
                <p class="card-text">Usuarios</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">24</h5>
                <p class="card-text">Eventos Hoy</p>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title">Bienvenido al Panel</h5>
        <p>AdminKit funcionando correctamente con Laravel.</p>
    </div>
</div>
@endsection