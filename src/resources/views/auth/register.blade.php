@extends('layouts.app')

@section('title', 'Cadastro')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-center">Criar Conta</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nome</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1">
                    @error('name')
                    <div class="bg-red-600 text-white p-3 rounded mt-2">{{ $message }}</div>
                    @enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700">E-mail</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1">
                    @error('email')
                    <div class="bg-red-600 text-white p-3 rounded mt-2">{{ $message }}</div>
                    @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Senha</label>
            <input type="password" name="password" id="password" required
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1">
                    @error('password')
                    <div class="bg-red-600 text-white p-3 rounded mt-2">{{ $message }}</div>
                    @enderror
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700">Confirmar Senha</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1">
                    @error('password_confirmation')
                    <div class="bg-red-600 text-white p-3 rounded mt-2">{{ $message }}</div>
                    @enderror
        </div>
        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">Cadastrar</button>
    </form>
    <p class="text-center text-gray-600 mt-4">
        Já tem conta? <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Faça login</a>
    </p>
</div>
@endsection
