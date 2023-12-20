@extends('main')

@section("title", "$cour->intitule")

@section('nav')
    <a class="nav-link" href="{{ route('prof') }}">Cours</a>
    <a class="nav-link" href="{{ route('prof.planning') }}">Planning</a>
@endsection

@section('content')
    <div class="container mb-6">
        <div class="row mt-5">
            <div class="col-12">
                <h1>{{ $cour->intitule }}</h1>
            </div>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h2>Modification du cours</h2>
            <div class="col-12 mt-3">
                <form class="needs-validation" method="post" action="{{ route('prof.cour.edit.post') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $planning->id }}">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="date_debut">Date de début</label>
                            <input type="datetime-local" class="form-control" id="date_debut" name="date_debut" value="{{ $planning->date_debut }}" required>
                        </div>
                        <div class="col mb-3">
                            <label for="date_fin">Date de fin</label>
                            <input type="datetime-local" class="form-control" id="date_fin" name="date_fin" value="{{ $planning->date_fin }}" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                    <a href="{{ route('prof.cour.delete', $planning->id) }}" class="btn btn-danger">Supprimer</a>
                </form>
            </div>
        </div>
    </div>
@endsection
