@extends('main')

@section('title', 'Profile')

@section('nav')
	@if(Auth::user()->type == "enseignant")
		<a></a>
	@elseif(Auth::user()->type == "etudiant")
		<a class="nav-link" href="{{ route('user') }}">Mes cours</a>
		<a class="nav-link" href="{{ route('cours') }}">Tout les cours</a>
	@endif
@endsection

@section('content')
	<div class="container mb-5">
		<div class="row mt-5">
			<div class="col-12">
				<h1>{{ $user->nom }}</h1>
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
			<h2 class="m-3">Modification du profil</h2>
			<div class="col-12 mt-3">
				<form class="needs-validation" method="post" action="{{ route('profile.edit') }}">
					@csrf
					<input type="hidden" name="id" value="{{ $user->id }}">
					<div class="row">
						<div class="col mb-3">
							<label for="nom">Nom</label>
							<input type="text" class="form-control" id="nom" name="nom" value="{{ $user->nom }}" required>
						</div>
						<div class="col mb-3">
							<label for="prenom">Prénom</label>
							<input type="prenom" class="form-control" id="prenom" name="prenom" value="{{ $user->prenom }}" required>
						</div>
					</div>
					<button type="submit" class="btn btn-primary">Modifier</button>
				</form>
			</div>
			<h2 class="mt-3">Modification du mot de passe</h2>
			<div class="col-12 mt-3">
				<form class="needs-validation" method="post" action="{{ route('profile.password') }}">
					@csrf
					<input type="hidden" name="id" value="{{ $user->id }}">
					<div class="row">
						<div class="col mb-3">
							<label for="mdp">Mot de passe</label>
							<input type="mdp" class="form-control" id="mdp" name="mdp" required>
						</div>
						<div class="col mb-3">
							<label for="mdp_confirmation">Confirmation du mot de passe</label>
							<input type="mdp_confirmation" class="form-control" id="mdp_confirmation" name="mdp_confirmation" required>
						</div>
					</div>
					<button type="submit" class="btn btn-primary">Modifier</button>
				</form>
		</div>
	</div>
	@endsection
