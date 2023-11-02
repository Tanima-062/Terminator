<?php

namespace App\Http\Controllers\Api\Web\CompanyRole;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\CompanyRole as CompanyRoleModel;

class CompanyRoleController extends Controller
{
    //
    public function index()
    {

        return response()->json(CompanyRoleModel::orderBy('id', 'asc')->get());
    }
}
