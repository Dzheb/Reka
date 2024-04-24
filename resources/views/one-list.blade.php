@extends('layouts.app')
@section('title-block')
{{$data->name}}@endsection
@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between" style="gap: 10px">
    <h1 id='list' data-id='{{$data->id}}' data-user='{{$user->id}}'>Список: {{$data->name}}</h1>
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
    <h5 class="mb-3" style="word-wrap: break-word; width:100%;">"{{$task->name}}"<button id="{{$task->id}}" class="btn btn-warning delete_task float-end" data-bs-toggle="modal" data-bs-target="#taskDelete">Удалить</button></h5>
    @if($task->jpg)
    <img class="photo" data-bs-toggle="modal" data-bs-target="#picModal" src="{{ asset($task->jpg) }}" alt="photo" width="150" height="150">
    @endif
    <h6 class="mx-5">Время: {{$task->created_at}}</h6>

    <button id="{{$task->tags}}" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#tag">{{$task->tags}}</button>
</div>
@endforeach
@endif
<!--  -->

</div>
<script>
    // save task
    const btnDeleteConfirm = document.querySelector('.delete_confirmation');
    const btnDelete = document.querySelectorAll('.delete_task');
    btnDelete.forEach((item) => {
        item.addEventListener('click', function(e) {
            btnDeleteConfirm.setAttribute("id", this.id);
        })
    });
    const btnSave = document.querySelector('.save_task');
    btnSave.addEventListener('click', function(e) {
        document.forms.formTask.lid.value = document.getElementById('list').dataset.id;
        // ajax request
        if (formTask.task_content.value != '' && formTask.lid.value)
            sendTaskAjax();
        else alert("Название задачи не должно быть пустым")
    });

    function sendTaskAjax() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //
        const lid = formTask.lid.value;
        const task_content = formTask.task_content.value;
        let formData = new FormData(formTask);
        formData.append('tags', JSON.stringify(formTask.tags.value));
        if (checkImgAjax(formTask.task_pic)) {
            formData.append('file', formTask.task_pic.files[0]);
        }
        $.ajax({
            type: "POST",
            url: "{{ route('post-task') }}",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                window.location.href = "/list/" + lid + "/view";
                document.forms.formTask.lid.value = '';
                document.forms.formTask.name.value = '';
            }
        });

    }
    // delete task
    btnDeleteConfirm.addEventListener('click', function(e) {
        // ajax request
        let url = "{{ route('delete-task',':id') }}";
        url = url.replace(':id', this.id);
        deleteTaskAjax(url);
    });

    function deleteTaskAjax(url) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //
        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(data) {
                window.location.href = "/list/" + document.getElementById('list').dataset.id + "/view";

            }
        });
    }
    // проверка загружаемого файла
    function checkImgAjax(file) {
        if (file != '') {
            // проверка на тип и размер файла
            if (
                file.files[0].type == 'image/png' ||
                file.files[0].type == 'image/jpeg' ||
                file.files[0].type == 'image/jpg'
            ) {
                if (file.files[0].size < 2000000) {
                    return true;
                } else {
                    alert('Размера файла больше 2Мбайт');
                    check_file = false;
                    return false;
                }
            } else {
                alert('Неправильный тип файла. Тип файл может быть png или jpeg.');
                return false;
            }
            return false;
        }
    }
    //#picModal поместить фото
    $(".photo").on("click", function(event) {
        $('.photo_modal').attr("src", this.src);
    });
</script>
@endsection
@section('aside')
<div class="aside_panel float-end">
    <h3>Новые задачи</h3>
    <!-- Button trigger modal -->
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#taskModal">
        Добавить задачу
    </button>
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

                    <div class="col-md-12">
                        <label for="task">Описание задачи:</label>
                        <textarea class="form-control" rows="5" id="task" name="task_content"></textarea>
                    </div>
                    <div class="col-md-12">
                        <label for="task_pic">Картинка задачи:</label>
                        <input type="file" name="task_pic" value="" class="form-control url_img" />
                    </div>
                    <div class="col-md-12">
                        <label for="tags">Тэги:</label>
                        <input type="text" name="tags" value="" class="tag" />
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
<!--  -->
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
@endsection
