@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
    <h2 class="text-xl font-bold mb-4">Login</h2>

    @if($errors->any())
        <p class="text-red-500 mb-2">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="/login" class="space-y-4">
        @csrf

        <input name="email" placeholder="Email" class="w-full border p-2 rounded">
        <input type="password" name="password" placeholder="Senha" class="w-full border p-2 rounded">

        <button class="w-full bg-blue-500 text-white p-2 rounded">Entrar</button>
    </form>

    <p class="mt-4 text-sm">
        Não tem conta? <a href="/register" class="text-blue-500">Registrar</a>
    </p>
</div>
@endsection