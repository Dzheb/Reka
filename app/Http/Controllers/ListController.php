<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListRequest;
use App\Models\CheckList;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    public function saveNewList(ListRequest $req)
    {
        // if (!CheckList::where('name', '=', $req->name)->firstOrFail()) {
            $list = new CheckList();
            $list->name = $req->input('name');
            $list->uid = Auth::user()->id;
            $list->save();
            $data = CheckList::latest()->paginate(2);
            return view('lists', compact('data'))->with('success', 'Лист был добавлен');
        // } else {

        //     $data = CheckList::latest()->paginate(2);
        //     return view('lists', compact('data'))->with('success', 'Лист не был добавлен, лист с таким названием существует');
        // }
    }
    public function updateListSubmit($id, ListRequest $req)
    {
        $list = CheckList::find($id);
        $list->name = $req->input('name');
        return redirect()->route('list-data-one', $id)->with('success', 'Иформация была обновлена');
    }
    public function allLists()
    {
        $user = Auth::user();
        $data = CheckList::latest()->paginate(2);
        return view('lists', compact('data'));
    }
    public function showOneList($id)
    {
        $uid =  Auth::user()->id;
        $tasks = Task::where('lid', $id)->get();

        return view('one-list', ['data' => CheckList::find($id), 'tasks' => $tasks, 'user' => auth()->user()]);
    }
 
    public function deleteList($id)
    {
        CheckList::find($id)->delete();
        Task::where('lid', $id)->delete();
        return redirect()->route('lists')->with('success', 'Список был удален');
    }
}
