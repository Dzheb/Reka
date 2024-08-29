<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

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
        session()->put('test', 'value');
        $data = session()->all();
        return response()->json([
            "status" => "Задача добавлена",
            "session" => $data
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
    // deleteTagsUnused
    public function deleteTagsUnused()
    {
         $tags = DB::table('tags')->get();
         foreach ($tags as $tag) {
            $data = DB::table('tag_task')->where('tag_id', '=', $tag->id)->count();
            if($data < 1){
                DB::table('tags')->delete($tag->id);
            }
         }
        $tags = DB::table('tags')->get();
        return response()->json([
            "data" => $tags
        ]);
    }
    //
    public function deleteTag($id,$task_id)
    {
        $tag = Tag::find($id);
        $task = Task::find($task_id);
        $task->tags()->detach($tag);
        $tag->delete();
        return response()->json([
            "status" => "Тэг удалён",
        ]);
    }

}
require app_path('Lib').'/file_operations.php';
