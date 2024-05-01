@extends('layouts.app')
@section('content')
@foreach($data as $el)
<div class="alert alert-info">
    <h3>{{$el->name}}</h3>

    <a href="{{route('list-data-one', $el->id)}}"><button class="btn btn-warning">Подробности</button></a>
</div>
@endforeach
<div class="d-flex m-3 float-end">
        {!! $data->links() !!}
    </div>
@endsection
@section('aside')
<div class="d-flex flex-column bd-highlight mb-3 float-end">

    <div class="aside_panel float-end">
        <p>Ввод новых списков</p>
        <!-- Button trigger modal -->
        <a href="{{ route('add-list') }}" class="btn btn-warning">Новый список</a>
    </div>

</div>
@endsection
