@extends('main')

@section("title", "$cour->intitule")

@section('nav')
	<a class="nav-link" href="{{ route('admin') }}">Home</a>
    <a class="nav-link" href="{{ route('admin.users') }}">Gestion des utilisateurs</a>
    <a class="nav-link" href="{{ route('admin.formations') }}">Gestion des formations</a>
    <a class="nav-link" href="{{ route('admin.cours') }}">Gestion des cours</a>
@endsection

@section('content')
    <div class="container mb-5">
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
            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
            <div class="col-12 mt-3">
                <h2>Ajouter un cours au planning</h2>
            </div>
            <div class="col-12 mt-3">
                <form class="needs-validation" method="post" action="{{ route('admin.planning.create') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $cour->id }}">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="date_debut">Date de début</label>
                            <input type="datetime-local" class="form-control" id="date_debut" name="date_debut" value="{{ old('date_debut') }}" required>
                        </div>
                        <div class="col mb-3">
                        	<label for="date_fin">Date de fin</label>
                        	<input type="datetime-local" class="form-control" id="date_fin" name="date_fin" value="{{ old('date_fin') }}" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Créer</button>
                </form>
            </div>
            <div class="col-12">
            	<h2>Planning du cours</h2>
            	<p class="text-secondary">(cliquer sur le cours pour l'éditer/supprimer)</p>
            	<div id="calendar"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
	<script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                events: [
                    @foreach($plannings as $planning)
                        {
                            title: 'Cours de {{ $cour->intitule }}',
                            start: '{{ $planning->date_debut }}',
                            end: '{{ $planning->date_fin }}',
                            url: '{{ route('admin.planning.edit', ['id' => $planning->id]) }}',
                        },
                    @endforeach
                ],
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultView: 'month',
                locale: 'fr',
            });
        });
    </script>
@endsection
