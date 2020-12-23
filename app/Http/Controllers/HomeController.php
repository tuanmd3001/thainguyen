<?php

namespace App\Http\Controllers;

use App\Models\Admin\Attachment;
use App\Models\Admin\Comment;
use App\Models\Admin\Config;
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

        $config = Config::first();
        if ($config && $config->log_view == 1) {
            activity('web')
                ->causedBy(Auth::user())
                ->performedOn($document)
                ->log('view');
        }

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
        $comment = Comment::create([
            'document_id' => $request['document_id'],
            'user_id' => Auth::user()->id,
            'content' => $request['content'],
        ]);

        $config = Config::first();
        if ($config && $config->log_comment == 1) {
            activity('web')
                ->causedBy(Auth::user())
                ->performedOn($comment)
                ->log('comment');
        }
        return redirect()->back();
    }

    public function downloadFile($name)
    {
        $attachment  = Attachment::where('store_name', $name)->first();
        if ($attachment){

            $config = Config::first();
            if ($config && $config->log_comment == 1) {
                activity('web')
                    ->causedBy(Auth::user())
                    ->performedOn($attachment)
                    ->log('download');
            }

            $file= public_path(). "/storage/" . $attachment->file_path;
            $headers = [];
            return response()->download($file, $attachment->file_name, $headers);
        }
        return "Không tìm thấy file";
    }
}
