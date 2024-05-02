@extends('layouts.app')
@section('title-block')
{{$data->name}}@endsection
@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between" style="gap: 10px">
    <h1 id='list' data-id='{{$data->id}}' data-user='{{$user->id}}'>Список : {{$data->name}}</h1>
    <form action="{{route('list-delete', $data->id)}}" method="POST">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">Удалить</button>
    </form>
</div>
<!-- общие комментарии -->
@if (count($tasks) > 0)
<h5 class="container my-2">Задачи : </h5>
@foreach($tasks as $task)
<div class="alert alert-info align-items-center">
    <h5 class="mb-3" style="word-wrap: break-word; width:100%;">"{{$task->name}}"<button id="{{$task->id}}" class="btn btn-warning mx-3 delete_task float-end" data-bs-toggle="modal" data-bs-target="#taskDelete">Удалить</button>
        <button id="{{$task->id}}" data-task="{{$task}}" class="btn btn-warning update_task mx-3 float-end" data-bs-toggle="modal" data-bs-target="#taskModal">Изменить</button>
    </h5>
    @if($task->jpg)
    <img class="photo" data-bs-toggle="modal" data-bs-target="#picModal" src="{{ asset($task->jpg) }}" alt="photo" width="150" height="150">
    @endif
    <h6 class="my-3">Время: {{$task->created_at}}</h6>
    <h6>Тэги: </h6>
    <!--  -->
    @if (count($task->tags) > 0)
    @foreach($task->tags as $tag)
    <button id="{{$tag->id}}" class="btn btn-warning mx-3 tag"
    data-task="{{$task->id}}"
    data-bs-toggle="modal" data-bs-target="#tagDelete">{{$tag->name}}</button>
    @endforeach
    @endif
    <button data-task="{{$task->id}}" class="btn btn-danger add-tag"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"
    data-bs-toggle="modal" data-bs-target="#addTagModal">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
        </svg></button>
</div>
@endforeach
@endif
</div>
@endsection

@section('aside')
<div class="aside_panel aside">
    <h3>Новые задачи</h3>
    <!-- Button trigger modal -->
    <button class="btn btn-warning m-3" data-bs-toggle="modal" data-bs-target="#taskModal">
        Добавить задачу
    </button>
    <!-- Тэги -->
    <h3>Теги задач</h3>
    <div class="filter-tags"></div>
</div>
<!-- Modal task-->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="commentModalLabel">Задача</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" enctype="multipart/form-data" name="formTask" class="row md-12 task_input">
                    @csrf
                    <input type="hidden" id="lid" name="lid" value="" />
                    <input type="hidden" id="id" name="id" value="" />

                    <div class="col-md-12">
                        <label for="task">Описание задачи:</label>
                        <textarea class="form-control" rows="5" id="task" name="task_content"></textarea>
                    </div>
                    <div class="col-md-12">
                        <label for="task_pic">Картинка задачи:</label>
                        <input type="file" name="task_pic" value="" class="form-control url_img" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-dismiss="modal">
                    Закрыть
                </button>
                <button class="btn btn-warning save_task">
                    Отправить
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal add tag-->
<div class="modal fade" id="addTagModal" tabindex="-1" aria-labelledby="addTagModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addTagModalLabel">Введите тэг</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" enctype="multipart/form-data" name="formTag" class="row md-12 tag_input">
                    @csrf
                    <input type="hidden" id="task_id" name="task_id" value="" />

                    <div class="col-md-12">
                        <label for="tag-input">Тэг:</label>
                        <input class="form-control" id="tag-input" name="tag_content"></input>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-dismiss="modal">
                    Закрыть
                </button>
                <button class="btn btn-warning save_tag">
                    Отправить
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal big picture-->
<div class="modal fade" id="picModal" tabindex="-1" aria-labelledby="picModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="picModalLabel">Фото</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img class="photo_modal" src="" alt="фото">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-dismiss="modal">
                    Закрыть
                </button>
                <button class="btn btn-warning save_pic">
                    Заменить
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Task delete -->
<div class="modal fade" id="taskDelete" tabindex="-1" aria-labelledby="taskDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-right">
                <h1 class="modal-title fs-5" id="taskDeleteLabel"></h1>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h3 class="modal-title fs-5  ">Удалить задачу?</h3>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-dismiss="modal">
                    Закрыть
                </button>
                <button class="btn btn-warning delete_confirmation">
                    Удалить
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal tag delete -->
<div class="modal fade" id="tagDelete" tabindex="-1" aria-labelledby="tagDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-right">
                <h1 class="modal-title fs-5" id="tagDeleteLabel"></h1>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h3 class="modal-title fs-5  ">Удалить тэг?</h3>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary"
                data-bs-dismiss="modal">
                    Закрыть
                </button>
                <button class="btn btn-warning delete_tag_confirmation"       data-task="">
                    Удалить
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
