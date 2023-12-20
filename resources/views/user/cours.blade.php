@extends('main')

@section('title', 'Tous les cours')

@section('nav')
    <a class="nav-link" href="{{ route('user') }}">Mes cours</a>
    <a class="nav-link active" href="{{ route('cours') }}">Tous les cours</a>
@endsection

@section('content')
    <div class="container mb-5">
        <div class="row mt-5">
            <div class="col-12">
                <h1>Tout les cours</h1>
            </div>

            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        {{ $error }}
                    </div>
                @endforeach
            @endif

            @if(session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session()->get('success') }}
                </div>
            @endif

            <div class="col-12 mt-5">
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
                                @if($cour->inscrit)
                                    <a class="btn btn-primary" href="{{ route('user.cour', $cour->id) }}">Voir le cours</a>
                                    <a class="btn btn-danger" href="{{ route('user.cour.desinscription', $cour->id) }}">Se désinscrire</a>
                                @else
                                    <a class="btn btn-primary" href="{{ route('user.cour.inscription', $cour->id) }}">S'inscrire</a>
                                @endif
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
