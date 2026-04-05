@section('content')
<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
    <h2 class="text-xl font-bold mb-4">Registrar</h2>

    <form method="POST" action="/register" class="space-y-4">
        @csrf

        <input name="name" placeholder="Nome" class="w-full border p-2 rounded">
        <input name="email" placeholder="Email" class="w-full border p-2 rounded">
        <input type="password" name="password" placeholder="Senha" class="w-full border p-2 rounded">
        <input type="password" name="password_confirmation" placeholder="Confirmar senha" class="w-full border p-2 rounded">

        <button class="w-full bg-green-500 text-white p-2 rounded">Registrar</button>
    </form>
</div>
@endsection