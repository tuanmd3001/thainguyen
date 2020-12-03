<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\DocumentDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateDocumentRequest;
use App\Http\Requests\Admin\UpdateDocumentRequest;
use App\Models\Admin\Attachment;
use App\Models\Admin\Document;
use App\Repositories\Admin\DocumentRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Ramsey\Uuid\Uuid;
use Response;
use Spatie\Tags\Tag;

class DocumentController extends AppBaseController
{
    /** @var  DocumentRepository */
    private $documentRepository;

    public function __construct(DocumentRepository $documentRepo)
    {
        $this->documentRepository = $documentRepo;
    }

    /**
     * Display a listing of the Document.
     *
     * @param DocumentDataTable $documentDataTable
     * @return Response
     */
    public function index(DocumentDataTable $documentDataTable)
    {
        return $documentDataTable->render('admin.documents.index');
    }

    /**
     * Show the form for creating a new Document.
     *
     * @return Response
     */
    public function create()
    {
        $temp_id = "temp_" . Uuid::uuid4();
        $tags = Tag::all();
        return view('admin.documents.create', compact('tags', 'temp_id'));
    }

    /**
     * Store a newly created Document in storage.
     *
     * @param CreateDocumentRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->storeAs(
                'thumbnails', Uuid::uuid4() . '.' . $request->file('thumbnail')->getClientOriginalExtension(),
                ['disk' => 'public']
            );
            $input['thumbnail'] = $path;
        }

        $input['disable_comment'] = (isset($input['disable_comment']) && $input['disable_comment']) == 1 ? 1 : 0;
        $input['draft'] = $input['save_type'] == Document::SAVE_TYPE_DRAFT ? 1 : 0;
        $document = $this->documentRepository->create($input);

        if (isset($input['tags']) && count($input['tags'])){
            foreach ($input['tags'] as $tag){
                $document->attachTag(Tag::findOrCreate($tag));
            }
        }

        if (isset($input['files']) && count($input['files'])){
            Attachment::where("temp_id", $input['temp_id'])->whereIn('store_name', $input['files'])->update([
                'document_id' => $document->id,
                'temp_id' => null,
                'is_draft' => 0
            ]);
            Attachment::where('temp_id', $input['temp_id'])->delte();
        }

        Flash::success('Document saved successfully.');

        return redirect(route('admin.documents.index'));
    }

    /**
     * Display the specified Document.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $document = $this->documentRepository->find($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('admin.documents.index'));
        }

        return view('admin.documents.show')->with('document', $document);
    }

    /**
     * Show the form for editing the specified Document.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $document = $this->documentRepository->find($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('admin.documents.index'));
        }
        $tags = Tag::all();
        return view('admin.documents.edit', compact('document', 'tags'));
    }

    /**
     * Update the specified Document in storage.
     *
     * @param  int              $id
     * @param UpdateDocumentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentRequest $request)
    {
        $document = $this->documentRepository->find($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('admin.documents.index'));
        }

        $document = $this->documentRepository->update($request->all(), $id);

        Flash::success('Document updated successfully.');

        return redirect(route('admin.documents.index'));
    }

    /**
     * Remove the specified Document from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $document = $this->documentRepository->find($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('admin.documents.index'));
        }

        $this->documentRepository->delete($id);

        Flash::success('Document deleted successfully.');

        return redirect(route('admin.documents.index'));
    }
}
