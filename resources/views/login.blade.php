@extends('_base')

@section('content')
    <form method="post" class="card p-4 mt-4 col-lg-4 col-md-6 offset-lg-4 offset-md-3">
        @csrf

        <div class="mb-2">
            <label for="login">Логин</label>
            <input class="form-control form-control-sm" type="text" name="login" id="login" />
        </div>

        <div class="mb-2">
            <label for="password">Пароль</label>
            <input class="form-control form-control-sm" type="password" name="password" id="password" />
        </div>

        <div class="mb-2">
            <button class="btn btn-primary w-100">Вход</button>
        </div>
    </form>
@endsection
