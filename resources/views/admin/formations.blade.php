@extends('main')

@section('title', 'Gestion des utilisateurs')

@section('nav')
    <a class="nav-link" href="{{ route('admin') }}">Home</a>
    <a class="nav-link" href="{{ route('admin.users') }}">Gestion des utilisateurs</a>
    <a class="nav-link active" href="{{ route('admin.formations') }}">Gestion des formations</a>
    <a class="nav-link" href="{{ route('admin.cours') }}">Gestion des cours</a>
@endsection

@section('content')
    <div class="container mb-5">
        <div class="row mt-5">
            <div class="col-12">
                <h1>Gestion des formations</h1>
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
                <h2>Ajouter une formation</h2>
            </div>
            <div class="col-12 mt-3">
                <form class="needs-validation" method="post" action="{{ route('admin.formation.create') }}">
                    @csrf
                    <div class="form-row">
                        <div class="col mb-3">
                            <label for="intitule">Intitulé</label>
                            <input type="text" class="form-control" id="intitule" name="intitule" placeholder="Intitulé" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
            <div class="col-12 mt-5">
                <h2>Liste des formations</h2>
            </div>
            <div class="col-12 mt-5">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="search" onkeyup="search()" placeholder="Rechercher par intitulé">
                </div>
 

                <table id="table" class="table">
                    <thead>
                    <tr>
                        <th scope="col">Intitulé</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($formations as $formation)
                            <tr>
                                @if($formation->id == $edit)
                                    <form class="needs-validation" method="post" action="{{ route('admin.formation.edit.post') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $formation->id }}">
                                        <td>
                                            <input type="text" class="form-control" id="intitule" name="intitule" value="{{ $formation->intitule }}" required>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-primary">Sauvegarder</button>
                                            <a href="{{ route('admin.formations') }}" class="btn btn-danger">Annuler</a>
                                        </td>
                                    </form>
                                @else
                                    <td>{{ $formation->intitule }}</td>
                                    <td>
                                        <a href="{{ route('admin.formation.edit', $formation->id) }}" class="btn btn-primary">Editer</a>
                                        <a href="{{ route('admin.formation.delete', $formation->id) }}" class="btn btn-danger">Supprimer</a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $formations->links()}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function search() {
            var input, filter, table, tr;
            input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.getElementById("table");
            tr = table.getElementsByTagName("tr");

            for (var i = 0; i < tr.length; i++) {
                var name = tr[i].getElementsByTagName("td")[0];

                if (name) {
                    if (name.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection
