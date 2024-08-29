<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title-block')</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js">
    </script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</head>

<body class="p-3 mb-2">
    @include('layouts.navigation')
    <div class="container mt-5 main_content">
        <!-- @include('inc.list') -->
        <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-5">
                @include('inc.aside')
            </div>
            <div class="col-sm-12 col-md-8 col-lg-7">
            @include('inc.flashMessages')
            @yield('content')
            </div>
        </div>
    </div>

    @include('inc.footer')
    <script>
        $("document").ready(function() {
            setTimeout(function() {
                $("div.alert-success").remove();
            }, 3000); // 5 secs

        });
        // кнопки
        const btnAddTags = document.querySelectorAll('.add-tag');
        // alert(btnTagsFilter.length)
        const btnDelete = document.querySelectorAll('.delete_task');
        // const btnTagsFilter = document.querySelectorAll('.tag');
        const btnDeleteConfirm = document.querySelector('.delete_confirmation');
        const btnUpdate = document.querySelectorAll('.update_task');
        // передача id на каждую кнопку удаления задачи
        btnDelete.forEach((item) => {
            item.addEventListener('click', function(e) {
                btnDeleteConfirm.setAttribute("id", this.id);
            })
        });
        //
        // передача id задачи на форму тэга
        btnAddTags.forEach((item) => {
            item.addEventListener('click', function(e) {
                document.forms.formTag.task_id.value = this.dataset.task;
            });
        });
        // чтение данных из dataset в форму
        btnUpdate.forEach((item) => {
            item.addEventListener('click', function(e) {
                const task_cur = JSON.parse(this.dataset.task);
                document.forms.formTask.id.value = task_cur.id;
                document.forms.formTask.lid.value = task_cur.lid;
                document.forms.formTask.task_content.value = task_cur.name;
            })
        });
        // нажатие кнопки сохранить задачу
        const btnSave = document.querySelector('.save_task');
        // нажатие кнопки сохранить тэг
        const btnSaveTag = document.
        querySelector('.save_tag');
        // обработка кнопки сохранения задачи
        btnSave.addEventListener('click', function(e) {
            document.forms.formTask.lid.value = document.getElementById('list').dataset.id;
            // ajax request
            if (document.forms.formTask.task_content.value != '' && document.forms.formTask.lid.value)
                sendTaskAjax();
            else alert("Название задачи не должно быть пустым")
        });
        // обработка кнопки сохранения тэга
        btnSaveTag.addEventListener('click', function(e) {
            // ajax request
            if (document.forms.formTag.tag_content.value != '')
                sendTaskAjax();
            else alert("Название тэга не должно быть пустым")
            sendTagAjax();
            document.forms.formTag.tag_content.value = '';
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
                        alert(JSON.stringify(data))
                        window.location.href = "/list/" + lid + "/view";
                        document.forms.formTask.lid.value = '';
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
                        document.forms.formTask.task_content.value = '';
                    }
                });
            }
        }
        //
        // запись  в базу тэга
        function sendTagAjax() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //
            let task_id = '';
            task_id = document.forms.formTag.task_id.value;
            const tag_content = document.forms.formTag.tag_content.value;
            const formData = new FormData(document.forms.formTag);
            $.ajax({
                type: "POST",
                url: "{{ route('post-tag') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    location.reload();
                    document.forms.formTag.task_id.value = '';
                    document.forms.formTag.tag_content.value = '';
                }
            });
            // если нет то update
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
        //#picModal заменить фото
        $(".save_pic").on("click", function(event) {
            alert("Замена")
            $('.photo_modal').attr("src", this.src);
        });

        function formDataJSON(formData) {
            var object = {};
            formData.forEach(function(value, key) {
                object[key] = value;
            });
            return JSON.stringify(object);
        }
        //
        const tagsContainer = document.querySelector('.filter-tags');
        const btnTagsFilter = [...document.querySelectorAll('.tag')];
        // удаление дубликатов
        const texts = new Set(btnTagsFilter.map(x => x.innerHTML));
        //
        texts.forEach((item) => {
            const tagEl = document.createElement('button');
            tagEl.classList.add('filter-tag', 'btn', 'btn-warning', 'm-1');
            tagEl.textContent = item;
            tagsContainer.append(tagEl);
        });

        // фильтрация по кнопкам тэгов
        const tagsFilter = document.querySelectorAll('.filter-tag');
        tagsFilter.forEach((item) => {
            item.addEventListener('click', function(e) {
                if (window.location.href.includes('?')) {
                    window.location.replace(window.location.href.substring(0, window.location.href.lastIndexOf('?') + 1) + 'tag=' + this.textContent);
                } else
                    window.location.replace((window.location.href + '?tag=' + this.textContent));
            })
        });
        // передача id задачи на кнопку тэга
        const btnTagDelete = document.querySelectorAll('.tag');
        const btnTagDeleteConfirmation = document.querySelector('.delete_tag_confirmation');
        // передача id на каждую кнопку удаления тэга
        btnTagDelete.forEach((item) => {
            item.addEventListener('click', function(e) {
                btnTagDeleteConfirmation.setAttribute("id", this.id);
                btnTagDeleteConfirmation.setAttribute('data-task', this.dataset.task);
            })
        });
        //
        // delete tag
        btnTagDeleteConfirmation.addEventListener('click', function(e) {
            // ajax request
            let url = "{{ route('delete-tag',[':id',':task']) }}";
            url = url.replace(':id', this.id);
            url = url.replace(':task', this.dataset.task);
            deleteTagAjax(url);
        });

        function deleteTagAjax(url) {
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
                    alert(JSON.stringify(data))
                    window.location.href = "/list/" + document.getElementById('list').dataset.id + "/view";

                }
            });
        }
        //
    </script>
</body>

</html>
