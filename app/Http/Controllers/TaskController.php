<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index() {
        $tasks = \App\Task::all();

        return view('index', compact('tasks'));
    }
}
