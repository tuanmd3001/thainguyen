<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TagDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

class TagController extends Controller
{
    public function index(TagDataTable $tagDataTable)
    {
        return $tagDataTable->render('admin.tags.index');
    }

    public function store(CreateTagRequest $request)
    {
        $tag = Tag::findFromString($request['name']);
        if ($tag) {
            return redirect()->back()->withErrors(['name' => 'Tên đã tồn tại']);
        } else {
            Tag::findOrCreateFromString($request['name']);
            return redirect(route('admin.tags.index'));
        }
    }
//    public function show($id){
//        return redirect(route('admin.tags.index'));
//    }
//
//    public function edit($id)
//    {
//        return redirect(route('admin.tags.index'));
//    }

    public function update($id, UpdateTagRequest $request)
    {
        $tag = Tag::find($id);
        if (empty($tag)){
            return redirect()->back()->withErrors(['name' => 'Không tìm thấy thông tin']);
        }
        $tag->name = $request['name'];
        $tag->save();
        return redirect(route('admin.tags.index'));
    }

    public function destroy($id)
    {
        $tag = Tag::find($id);
        if (empty($tag)){
            return redirect()->back()->withErrors(['name' => 'Không tìm thấy thông tin']);
        }
        $tag->delete();
        return redirect(route('admin.tags.index'));
    }
}
