<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Tag;

class TaskController extends Controller
{
    public function submitTask(Request $req)
    {
        $input = $req->all();
        $task = new Task();
        $task->lid = $input['lid'];
        $task->name = $input['task_content'];
        $task->jpg = NULL;
        if(!empty($_FILES["file"]) ) {
            if(img_save()){
                $task->jpg = '/images/'.$_FILES["file"]["name"];
            }
        }
        $task->save();
        return response()->json([
            "status" => "Задача добавлена"
        ]);
    }
    //
    public function submitTag(Request $req)
    {
        $input = $req->all();
        $tag = new Tag();
        $tag->name = $input['tag_content'];
        $task = Task::find($input['task_id']);
        $tag->save();
        $tag->tasks()->attach($task);
        return response()->json([
            "status" => "Задача добавлена"
        ]);
    }

    //
    public function updateTask($id,Request $req)
    {
        $task = Task::find($id);
        $task->lid = $req->lid;
        $task->name = $req->task_content;
        if(!empty($_FILES["file"]) ) {
            if(img_save()){
                $task->jpg = '/images/'.$_FILES["file"]["name"];
            }
        }
        $task->save();
        return response()->json([
            "status" => "Задача обновлена",
        ]);
    }
    //
    public function deleteTask($id)
    {
        Task::find($id)->delete();
        return response()->json([
            "status" => "Задача удалёна",
        ]);
    }

}
require app_path('Lib').'/file_operations.php';
