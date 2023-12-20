@extends('main')

@section("title", "$cour->intitule")

@section('nav')
    <a class="nav-link" href="{{ route('user') }}">Mes cours</a>
    <a class="nav-link" href="{{ route('cours') }}">Tous les cours</a>
@endsection

@section('content')
    <div class="container mb-5">
        <div class="row mt-5">
            <div class="col-12">
                <h1>{{ $cour->intitule }}</h1>
            </div>
            <div class="col-12">
            	<h2>Planning du cours</h2>
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
