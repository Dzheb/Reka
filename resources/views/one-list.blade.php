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
    @if($task->tags != 'null')

    <button id="{{$task->tags}}" class="btn btn-warning tags" data-bs-toggle="modal" data-bs-target="#tag">{{$task->tags}}</button>

    @endif
</div>
@endforeach
@endif
<!--  -->

</div>
<script>
    // кнопки
    const btnDelete = document.querySelectorAll('.delete_task');
    const btnDeleteConfirm = document.querySelector('.delete_confirmation');
    const btnUpdate = document.querySelectorAll('.update_task');
    // передача id на каждую кнопку удаления задачи
    btnDelete.forEach((item) => {
        item.addEventListener('click', function(e) {
            btnDeleteConfirm.setAttribute("id", this.id);
        })
    });
    // чтение данных из dataset в форму
    btnUpdate.forEach((item) => {
        item.addEventListener('click', function(e) {
            const task_cur = JSON.parse(this.dataset.task);
            document.forms.formTask.id.value = task_cur.id;
            document.forms.formTask.lid.value = task_cur.lid;
            document.forms.formTask.task_content.value = task_cur.name;
            document.forms.formTask.tags.value = task_cur.tags;

        })
    });
    // нажатие кнопки сохранить
    const btnSave = document.querySelector('.save_task');
    btnSave.addEventListener('click', function(e) {
        document.forms.formTask.lid.value = document.getElementById('list').dataset.id;
        // ajax request
        if (document.forms.formTask.task_content.value != '' && document.forms.formTask.lid.value)
            sendTaskAjax();
        else alert("Название задачи не должно быть пустым")
    });
// запись  в базу задачи
    function sendTaskAjax() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //
        let lid = '';
        let id = '';
        lid = document.forms.formTask.lid.value;
        id = document.forms.formTask.id.value;
        const task_content = document.forms.formTask.task_content.value;
        const formData = new FormData(document.forms.formTask);
        formData.append('tags', document.forms.formTask.tags.value);
        if (document.forms.formTask.task_pic.files[0] != null) {
            if (checkImgAjax(document.forms.formTask.task_pic)) {
                formData.append('file', document.forms.formTask.task_pic.files[0]);
            }
        }

        // если нет id то сохранить
        if (id == '') {
            $.ajax({
                type: "POST",
                url: "{{ route('post-task') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    window.location.href = "/list/" + lid + "/view";
                    document.forms.formTask.lid.value = '';
                    document.forms.formTask.tags.value = '';
                    document.forms.formTask.task_content.value = '';
                }
            });
            // если нет то update
        } else {
            // update
            let url = "{{ route('put-task',':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    window.location.href = "/list/" + lid + "/view";
                    document.forms.formTask.lid.value = '';
                    document.forms.formTask.id.value = '';
                    document.forms.formTask.tags.value = '';
                    document.forms.formTask.task_content.value = '';
                }
            });
        }
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
        if (file.files[0].name != '') {
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

    function formDataJSON(formData) {
        var object = {};
        formData.forEach(function(value, key) {
            object[key] = value;
        });
        return JSON.stringify(object);
    }
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
                    <input type="hidden" id="id" name="id" value="" />

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
@endsection
