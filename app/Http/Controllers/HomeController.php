<?php

namespace App\Http\Controllers;

use App\Models\Admin\Attachment;
use App\Models\Admin\Comment;
use App\Models\Admin\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Tags\Tag;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return view('home', compact('tags'));
    }

    public function view_document($slug)
    {
        $document = Document::where('slug', $slug)->first();

        if (empty($document)){
            abort(404);
        }
        $attachments = Attachment::where('document_id', $document->id)
            ->where('temp_id', null)
            ->where('is_draft', 0)->get();
        $comments = Comment::where('document_id', $document->id)->get();
        return view('show_doc', compact("document", 'attachments', 'comments'));
    }

    public function add_comment(Request $request){
        $validator = Validator::make($request->all(),
            [
                'document_id' => 'required',
                'content' => 'required',
            ],
            $messages = [
                'document_id.required' => "Thiếu tham số. Vui lòng tải lại trang",
                'content.required' => "Thiếu tham số. Vui lòng tải lại trang",
            ]);

        if ($validator->fails()) {
            return redirect()->back();
        }
        if (trim($request['content']) == ""){
            return redirect()->back();
        }
        Comment::create([
            'document_id' => $request['document_id'],
            'user_id' => Auth::user()->id,
            'content' => $request['content'],
        ]);
        return redirect()->back();
    }
}
