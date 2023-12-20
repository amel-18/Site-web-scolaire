@extends('main')

@section('title', 'Gestion des cours')

@section('nav')
    <a class="nav-link" href="{{ route('admin') }}">Home</a>
    <a class="nav-link" href="{{ route('admin.users') }}">Gestion des utilisateurs</a>
    <a class="nav-link" href="{{ route('admin.formations') }}">Gestion des formations</a>
    <a class="nav-link active" href="{{ route('admin.cours') }}">Gestion des cours</a>
@endsection

@section('content')
    <div class="container mb-5">
        <div class="row mt-5">
            <div class="col-12">
                <h1>Gestion des cours</h1>
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
                <h2>Ajouter un cours</h2>
            </div>
            <div class="col-12 mt-3">
                <form class="needs-validation" method="post" action="{{ route('admin.cour.create') }}">
                    @csrf
                    <div class="form-row">
                        <div class="col mb-3">
                            <label for="intitule">Intitulé</label>
                            <input type="text" class="form-control" id="intitule" name="intitule" placeholder="Intitulé" required>
                        </div>
                        <div class="row">
	                        <div class="col mb-3">
								<label for="enseignant">Enseignant</label>
								<select class="form-control" id="enseignant" name="enseignant" required>
									<option value=""selected>Null</option>
									@foreach($enseignants as $enseignant)
										<option value="{{ $enseignant->id }}">{{ $enseignant->nom }} {{ $enseignant->prenom }}</option>
									@endforeach
								</select>
							</div>
							<div class="col mb-3">
								<label for="formation">Formation</label>
								<select class="form-control" id="formation" name="formation" required>
									<option value=""selected>Null</option>
									@foreach($formations as $formation)
										<option value="{{ $formation->id }}">{{ $formation->intitule }}</option>
									@endforeach
								</select>
							</div>
						</div>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
            <div class="col-12 mt-5">
                <h2>Liste des cours</h2>
            </div>
            <div class="col-12 mt-5">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="search" onkeyup="search()" placeholder="Rechercher par intiulé/enseignant/formation">
                </div>
 

                <table id="table" class="table">
                    <thead>
                    <tr>
                        <th scope="col">Intitulé</th>
                        <th scope="col">Enseignant</th>
                        <th scope="col">Formation</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($cours as $cour)
                            <tr>
                                @if($cour->id == $edit)
                                    <form class="needs-validation" method="post" action="{{ route('admin.cour.edit.post') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $cour->id }}">
                                        <td>
                                            <input type="text" class="form-control" id="intitule" name="intitule" value="{{ $cour->intitule }}" required>
                                        </td>
                                        <td>
                                            <select class="form-control" id="enseignant" name="enseignant" required>
                                                <option value=""selected>Null</option>
                                                @foreach($enseignants as $enseignant)
                                                    <option value="{{ $enseignant->id }}" @if($enseignant->id == $cour->user_id) selected @endif>{{ $enseignant->nom }} {{ $enseignant->prenom }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" id="formation" name="formation" required>
                                                <option value=""selected>Null</option>
                                                @foreach($formations as $formation)
                                                    <option value="{{ $formation->id }}" 
                                                        @if($formation->id == $cour->formation_id) selected @endif>
                                                        {{ $formation->intitule }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-primary">Sauvegarder</button>
                                            <a href="{{ route('admin.cours') }}" class="btn btn-danger">Annuler</a>
                                        </td>
                                    </form>
                                @else
                                    <td>{{ $cour->intitule }}</td>
                                    <td>{{ $cour->enseignant->nom }}</td>
                                    <td>{{ $cour->formation->intitule }}</td>
                                    <td>
                                        <a href="{{ route('admin.cour.edit', $cour->id) }}" class="btn btn-primary">Editer</a>
                                        <a href="{{ route('admin.cour.planning', $cour->id) }}" class="btn btn-primary">Gestion du planning</a>
                                        <a href="{{ route('admin.cour.delete', $cour->id) }}" class="btn btn-danger">Supprimer</a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $cours->links()}}
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
                var intitule = tr[i].getElementsByTagName("td")[0];
                var enseignant = tr[i].getElementsByTagName("td")[1];
                var formation = tr[i].getElementsByTagName("td")[2];


                if (intitule || enseignant || formation) {
                    if (intitule.innerHTML.toUpperCase().indexOf(filter) > -1 || enseignant.innerHTML.toUpperCase().indexOf(filter) > -1 || formation.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection
