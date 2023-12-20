{{--@extends('main')--}}

@section("title", "Mon planning")

@section('nav')
    <a class="nav-link" href="{{ route('prof') }}">Cours</a>
    <a class="nav-link active" href="{{ route('prof.planning') }}">Planning</a>
@endsection

@section('content')
    <div class="container mb-5">
        <div class="row mt-5">
            <div class="col-12">
                <h1>Planning</h1>
            </div>
            <div class="col-12 mt-3">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
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
