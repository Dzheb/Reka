@extends('layouts.app')
@section('title-block')
Контакты
@endsection
@section('content')
<h1>Новый список</h1>

<form action="{{ route('save-new-list') }}" method="post">
    @csrf
    <div class="form-group d-grid gap-2">
        <label  for="name">Введите имя списка</label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Имя списка"/>
    </div>
    <button class="btn btn-success mt-3" >Отправить</button>
</form>
@endsection
