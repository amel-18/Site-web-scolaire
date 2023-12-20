@extends('main')

@section('title', 'Mes cours')

@section('nav')
    <a class="nav-link active" href="{{ route('user') }}">Mes cours</a>
    <a class="nav-link" href="{{ route('cours') }}">Tous les cours</a>
@endsection

@section('content')
    <div class="container mb-5">
        <div class="row mt-5">
            <div class="col-12">
                <h1>Mes cours</h1>
            </div>
            <div class="card-columns" id="card-container">
                @foreach($cours as $cour)
                    <div class="card mt-3">
                        <div class="card-header">
                            {{ $cour->intitule }}
                        </div>
                        <div class="card-body">
                            <a class="btn btn-primary" href="{{ route('user.cour', $cour->id) }}">Voir le cours</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-12 mt-3">
                <h2>Planning</h2>
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
                    @foreach($cours as $cour)
                        @foreach($cour->plannings as $planning)
                            {
                                title: 'Cours de {{ $cour->intitule }}',
                                start: '{{ $planning->date_debut }}',
                                end: '{{ $planning->date_fin }}',
                            },
                       @endforeach
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
