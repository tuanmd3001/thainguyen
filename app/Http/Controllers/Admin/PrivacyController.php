<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PrivacyDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePrivacyRequest;
use App\Http\Requests\UpdatePrivacyRequest;
use App\Repositories\PrivacyRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class PrivacyController extends Controller
{
    /** @var  PrivacyRepository */
    private $privacyRepository;

    public function __construct(PrivacyRepository $privacyRepo)
    {
        $this->privacyRepository = $privacyRepo;
    }

    /**
     * Display a listing of the Privacy.
     *
     * @param PrivacyDataTable $privacyDataTable
     * @return Response
     */
    public function index(PrivacyDataTable $privacyDataTable)
    {
        return $privacyDataTable->render('admin.privacy.index');
    }

    /**
     * Show the form for creating a new Privacy.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.privacy.create');
    }

    /**
     * Store a newly created Privacy in storage.
     *
     * @param CreatePrivacyRequest $request
     *
     * @return Response
     */
    public function store(CreatePrivacyRequest $request)
    {
        $input = $request->all();

        $privacy = $this->privacyRepository->create($input);

        Flash::success('Thêm mới thành công');

        return redirect(route('admin.privacy.index'));
    }

    /**
     * Display the specified Privacy.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $privacy = $this->privacyRepository->find($id);

        if (empty($privacy)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.privacy.index'));
        }

        return view('admin.privacy.show')->with('Privacy', $privacy);
    }

    /**
     * Show the form for editing the specified Privacy.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $privacy = $this->privacyRepository->find($id);

        if (empty($privacy)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.privacy.index'));
        }

        return view('admin.privacy.edit')->with('Privacy', $privacy);
    }

    /**
     * Update the specified Privacy in storage.
     *
     * @param  int              $id
     * @param UpdatePrivacyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePrivacyRequest $request)
    {
        $privacy = $this->privacyRepository->find($id);

        if (empty($privacy)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.privacy.index'));
        }

        $privacy = $this->privacyRepository->update($request->all(), $id);

        Flash::success('Cập nhật thông tin thành công');

        return redirect(route('admin.privacy.index'));
    }

    /**
     * Remove the specified Privacy from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $privacy = $this->privacyRepository->find($id);

        if (empty($privacy)) {
            Flash::error('Không tìm thấy thông tin');

            return redirect(route('admin.privacy.index'));
        }

        $this->privacyRepository->delete($id);

        Flash::success('Xóa thành công');

        return redirect(route('admin.privacy.index'));
    }
}
