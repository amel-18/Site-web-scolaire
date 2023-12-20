@extends('main')

@section('title', 'Login')

@section('content')
    <div class="container mb-5">
        <div class="row">
            <div class="col-12 mt-5 text-center">
                <h1>Login</h1>
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
            <div class="mb-3 needs-validation">
                <label for="login" class="form-label">Login</label>
                <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}" required>
            </div>
            <div class="mb-3">
                <label for="mdp" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mdp" name="mdp" required>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>
@endsection
