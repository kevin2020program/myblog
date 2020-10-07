<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkController extends Controller
{

  public function add()
  {
    return view('admin.work.create');
  }
  // 以下を追記
  public function create(Request $request)
  {
    // admin/work/createにリダイレクトする
    return redirect('admin/work/create');
  }
  
  public function edit()
  {
    return view('admin.work.edit');
  }

  public function update()
  {
    return redirect('admin/work/edit');
  }
}
