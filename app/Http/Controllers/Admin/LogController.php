<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\LogDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(LogDataTable $LogDataTable) {
        return $LogDataTable->render('admin.logs.index');
    }
}
