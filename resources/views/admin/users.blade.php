@extends('main')

@section('title', 'Gestion des utilisateurs')

@section('nav')
    <a class="nav-link" href="{{ route('admin') }}">Home</a>
    <a class="nav-link active" href="{{ route('admin.users') }}">Gestion des utilisateurs</a>
    <a class="nav-link" href="{{ route('admin.formations') }}">Gestion des formations</a>
    <a class="nav-link" href="{{ route('admin.cours') }}">Gestion des cours</a>

@endsection

@section('content')
<div class="container mb-5">
        <div class="row mt-5">
            <div class="col-12">
                <h1>Gestion des utilisateurs</h1>
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
                <h2>Ajouter un utilisateur</h2>
            </div>
            <div class="col-12 mt-3">
                <form class="needs-validation" method="post" action="{{ route('admin.user.create') }}">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" pattern="[A-Za-zÀ-ÖØ-ö\s]+" class="form-control" id="nom" name="nom" value="{{ old('nom') }}">
                        </div>
                        <div class="mb-3 col">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" pattern="[A-Za-zÀ-ÖØ-ö\s]+" class="form-control" id="prenom" name="prenom" value="{{ old('prenom') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="login" class="form-label">Login</label>
                        <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}">
                    </div>
                    <div class="mb-3">
                        <label for="formation" class="form-label">Formation/Enseignant/Admin</label>
                        <select class="form-select" id="formation" name="formation">
                            <option value=0 selected>Enseignant</option>
                            <option value=-1>Administrateur</option>
                            @foreach($formations as $formation)
                                <option value={{ $formation->id }}>{{ $formation->intitule }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mdp" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="mdp" name="mdp">
                    </div>
                    <div class="mb-3">
                        <label for="mdp_confirmation" class="form-label">Confirmation du mot de passe</label>
                        <input type="password" class="form-control" id="mdp_confirmation" name="mdp_confirmation">
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
            <div class="col-12 mt-5">
                <h2>Liste des utilisateurs</h2>
            </div>
            <div class="col-12 mt-5">
                <div class="input-group mb-3">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio0" autocomplete="off" onclick="tri(0)" checked>
                    <label class="btn btn-outline-primary" for="btnradio0">Tous</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" onclick="tri(1)">
                    <label class="btn btn-outline-primary" for="btnradio1">Etudiants</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" onclick="tri(2)">
                    <label class="btn btn-outline-primary" for="btnradio2">Enseignants</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off" onclick="tri(3)">
                    <label class="btn btn-outline-primary" for="btnradio3">Administrateurs</label>

                    <input type="text" class="form-control" id="search" onkeyup="search()" placeholder="Rechercher par nom/prénom/login">
                </div>


                <table id="table" class="table">
                    <thead>
                    <tr>
{{--                        <th scope="col" onclick="sortTable(0)">ID    <i class="fas fa-sort"></i></th>--}}
                        <th scope="col" onclick="sortTable(0)">Nom    <i class="fas fa-sort"></i></th>
                        <th scope="col" onclick="sortTable(1)">Prénom    <i class="fas fa-sort"></i></th>
                        <th scope="col" onclick="sortTable(2)">Login    <i class="fas fa-sort"></i></th>
                        <th scope="col" onclick="sortTable(3)">Type    <i class="fas fa-sort"></i></th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        @if($user->id == $id)
                            <tr>
                                <form class="needs-validaton" method="post" id="editForm" action="{{ route("admin.user.edit.post") }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                    <td>
                                        <input type="text" class="form-control" id="nom" name="nom" value="{{ $user->nom }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="prenom" name="prenom" value="{{ $user->prenom }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="login" name="login" value="{{ $user->login }}">
                                    </td>
                                    @if($user->isntLocked)
                                        <td>
                                            <select class="form-select" id="type" name="type">
                                                <option value="etudiant" @if($user->type == 'etudiant') selected @endif>Etudiant</option>
                                                <option value="enseignant" @if($user->type == 'enseignant') selected @endif>Enseignant</option>
                                                <option value="admin" @if($user->type == 'admin') selected @endif>Administrateur</option>
                                            </select>
                                        </td>
                                    @else
                                        <td>
                                            <a href="{{ route('admin.user.approve', $user->id) }}" class="btn btn-primary">Approuver le compte</a>
                                        </td>
                                    @endif
                                    <td>
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        <a href="{{ route('admin.users') }}" class="btn btn-danger">Annuler</a>
                                    </td>
                                </form>
                            </tr>
                        @elseif($approve == $user->id)
                            <tr>
                                <form class="needs-validaton" method="post" id="editForm" action="{{ route("admin.user.approve.post") }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                    <td>{{ $user->nom }}</td>
                                    <td>{{ $user->prenom }}</td>
                                    <td>{{ $user->login }}</td>
                                    <td>
                                        <select class="form-select" id="type" name="type">
                                            <option value="etudiant" @if($user->type == 'etudiant') selected @endif>Etudiant</option>
                                            <option value="enseignant" @if($user->type == 'enseignant') selected @endif>Enseignant</option>
                                            <option value="admin" @if($user->type == 'admin') selected @endif>Administrateur</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        <a href="{{ route('admin.users') }}" class="btn btn-danger">Annuler</a>
                                    </td>
                                </form>
                            </tr>
                        @else
                            <tr>
                                <td>{{ $user->nom }}</td>
                                <td>{{ $user->prenom }}</td>
                                <td>{{ $user->login }}</td>
                                @if($user->isntLocked)
                                    <td>{{ $user->type }}</td>
                                @else
                                    <td>
                                        <a href="{{ route('admin.user.approve', $user->id) }}" class="btn btn-primary">Approuver le compte</a>
                                    </td>
                                @endif

                                <td>
                                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary">Editer</a>
                                    <a href="{{ route('admin.user.delete', $user->id) }}" class="btn btn-danger">Supprimer</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
                {{ $users->links()}}
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
                var surname = tr[i].getElementsByTagName("td")[1];
                var login = tr[i].getElementsByTagName("td")[2];

                if (name || surname || login) {
                    if (name.innerHTML.toUpperCase().indexOf(filter) > -1 || surname.innerHTML.toUpperCase().indexOf(filter) > -1 || login.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
    <script>
        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("table");
            switching = true;

            dir = "asc";
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    console.log(rows[i].getElementsByTagName("TD")[n]);
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount ++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
    <script>
        function tri(n) {
            var table, filter, tr;
            table = document.getElementById("table");
            switch (n) {
                case 0:
                    filter = "";
                    break;
                case 1:
                    filter = "ETUDIANT";
                    break;
                case 2:
                    filter = "ENSEIGNANT";
                    break;
                case 3:
                    filter = "ADMIN";
                    break;
            }
            tr = table.getElementsByTagName("tr");

            for (var i = 0; i < tr.length; i++) {
                var type = tr[i].getElementsByTagName("td")[3];
                if (type) {
                    if (filter === "") {
                        tr[i].style.display = "";
                    } else {
                        if (type.innerHTML.toUpperCase() === filter) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }

                }
            }
        }
    </script>
@endsection
