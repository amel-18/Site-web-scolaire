@extends('main')

@section('title', 'Cours')

@section('nav')
    <a class="nav-link active" href="{{ route('prof') }}">Cours</a>
    <a class="nav-link" href="{{ route('prof.planning') }}">Planning</a>
@endsection

@section('content')
    <div class="container mb-5">
        <div class="row mt-5">
            <div class="col-12">
                <h1>Cours</h1>
            </div>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li class="m-1">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session()->has('success'))
                <div class="alert alert-success m-1">
                    {{ session()->get('success') }}
                </div>
            @endif

            <div class="col-12 mt-2">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="search" onkeyup="search()" placeholder="Rechercher un cours">
                </div>

                <div class="card-columns" id="card-container">
                    @foreach($cours as $cour)
                        <div class="card mt-3">
                            <div class="card-header">
                                {{ $cour->intitule }}
                            </div>
                            <div class="card-body">
                                <a class="btn btn-primary" href="{{ route('prof.cour', $cour->id) }}">Voir le cours</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function search() {
            var input, filter, cards, cardContainer, h5, title, i;
            input = document.getElementById("search");
            filter = input.value.toUpperCase();
            cardContainer = document.getElementById("card-container");
            cards = cardContainer.getElementsByClassName("card");
            for (i = 0; i < cards.length; i++) {
                title = cards[i].querySelector(".card-header");
                if (title.innerText.toUpperCase().indexOf(filter) > -1) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    </script>
@endsection
