@extends('layouts.app')
@section('title', 'Connexion')
@section('content')
<div class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="card p-4" style="width: 380px;">
        <h4 class="text-center mb-3"><i class="bi bi-mortarboard-fill"></i> Gestion Scolaire</h4>
        <p class="text-muted text-center mb-4">Connectez-vous à votre compte</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
        <p class="text-muted small text-center mt-3 mb-0">Compte par défaut : admin@ecole.test / password</p>
    </div>
</div>
@endsection
