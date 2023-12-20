@extends('main')

@section('title', 'Register')

@section('content')
    <div class="container mb-5">
        <div class="row">
            <div class="col-12 mt-5 text-center">
                <h1>Register</h1>
            </div>
        </div>
        @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="alert alert-danger mt-2" role="alert">
                    {{ $error }}
                </div>
            @endforeach
        @endif
        <form class="mt-5" method="post">
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
                <label for="formation" class="form-label">Formation</label>
                <select class="form-select" id="formation" name="formation">
                    <option value="0">Enseignant</option>
                    @foreach($formations as $formation)
                        <option value="{{ $formation->id }}">{{ $formation->intitule }}</option>
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
@endsection
