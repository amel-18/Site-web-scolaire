@extends('main')

@section('title', 'Home')

@section('nav')
    <a class="nav-link active" href="{{ route('admin') }}">Home</a>
    <a class="nav-link" href="{{ route('admin.users') }}">Gestion des utilisateurs</a>
    <a class="nav-link" href="{{ route('admin.formations') }}">Gestion des formations</a>
    <a class="nav-link" href="{{ route('admin.cours') }}">Gestion des cours</a>
@endsection

@section('content')
    <div class="container mb-5">
        <div class="row mt-5">
            <div class="col-12">
                <h1></h1>
            </div>

        </div>
    </div>
@endsection
