@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl text-black font-bold mb-6 text-center">Acessar Sistema</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-gray-700">E-mail</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                class="w-full text-black border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-blue-200">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Senha</label>
            <input type="password" name="password" id="password" required
                class="w-full text-black  border border-gray-300 rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-blue-200">
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Entrar</button>
    </form>
    <p class="text-center text-gray-600 mt-4 text-black">
        Não tem conta? <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Cadastre-se</a>
    </p>
</div>
@endsection
