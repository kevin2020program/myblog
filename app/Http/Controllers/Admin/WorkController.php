<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Works;

class WorkController extends Controller
{

  public function add()
  {
    return view('admin.work.create');
  }
  // 以下を追記
  public function create(Request $request)
  {
    
    // Varidationを行う
    $this->validate($request, Works::$rules);

    $works = new Works;
    $form = $request->all();

    // フォームからファイルが送信されてきたら、保存して、$works->file_path に画像のパスを保存する
    if (isset($form['file'])) {
      $path = $request->file('file')->store('public/file');
      $works->file_path = basename($path);
    } else {
        $works->file_path = null;
    }

    unset($form['_token']);
    unset($form['file']);

    // データベースに保存する
    $works->fill($form);
    $works->save();

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
