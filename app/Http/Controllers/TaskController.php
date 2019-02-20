<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TaskController extends Controller
{
    public function index() {
        $tasks = \App\Task::all();

        return view('index', compact('tasks'));
    }

    public function create() {
        return view('add');
    }

    public function store(Request $request) {
        $task = new Task();
        //Khởi tạo mới đối tượng Task, gán các trường tương ứng với request gửi lên từ trình duyệt
        $task->title = $request->inputTitle;
        $task->content = $request->inputContent;
        $task->created = $request->inputCreated;
        $task->due_date = $request->inputDueDate;

        //Nếu file k tồn tại thì trường image gán NULL
//        $file = $request->inputFile;
        if (!$request->hasFile('inputFile')) {
            $task->image = $request->inputFile;
        } else {
           $file = $request->file('inputFile');

           //Lấy ra định dạng và tên mới của file từ request
           $fileExtension = $file->getClientOriginalExtension();
           $fileName = $request->inputFileName;

           //Gán tên mới cho file trước khi lưu lên server
            $newFileName = "$fileName.$fileExtension";

            //Lưu file vào thư mục storage/app/public/image với tên mới
            $request->file('inputFile')->storeAs('public/images', $newFileName);

            //Gán trường image của đối tượng task với tên mới
            $task->image = $newFileName;
        }
        $task->save();

        $message = "Tạo Task $request->inputTitle thành công!";
        Session::flash('create-success', $message);

        return redirect()->route('tasks.index', compact('message'));
    }
}
