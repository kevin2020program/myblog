<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Works;
use App\History;
use Carbon\Carbon;

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
  
  public function index(Request $request)
  {
    $cond_name = $request->cond_name;
    if ($cond_name != '') {
      // 検索されたら検索結果を取得する
      $posts = Works::where('name', $cond_name)->get();
    } else {
        // それ以外はすべての職務経歴を取得する
        $posts = Works::all();
    }
    return view('admin.work.index', ['posts' => $posts, 'cond_name' => $cond_name]);
  }

  public function edit(Request $request)
  {
      // Works Modelからデータを取得する
      $works = Works::find($request->id);
      if (empty($works)) {
        abort(404);    
      }
      return view('admin.work.edit', ['works_form' => $works]);
  }

  public function update(Request $request)
  {
    // Validationをかける
    $this->validate($request, Works::$rules);
    // Work Modelからデータを取得する
    $works = Works::find($request->id);
    // 送信されてきたフォームデータを格納する
    $works_form = $request->all();

    if (isset($works_form['file'])) {
      $path = $request->file('file')->store('public/file');
      $works->file_path = basename($path);
      unset($works_form['file']);
    } elseif (0 == strcmp($request->remove, 'true')) {
      $works->file_path = null;
    }
    unset($works_form['_token']);
    unset($works_form['remove']);

    // 該当するデータを上書きして保存する
    $works->fill($works_form)->save();

    $history = new History;
    $history->works_id = $works->id;
    $history->edited_at = Carbon::now();
    $history->save();
    
    return redirect('admin/work');
  }

  public function delete(Request $request)
  {
    // 該当するWorks Modelを取得
    $works = Works::find($request->id);
    // 削除する
    $works->delete();
    return redirect('admin/work/');
  } 
}
