<?php

namespace App\Http\Controllers\Api\Web\Languages;

use App\Http\Controllers\Controller;
use App\Models\DeviceAuthAndHierarchyElementRole;
use App\Models\HierarchyElement;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
       
        return response()->json(Language::where('code', 'de')->orderBy('id', 'asc')->get());
    }
}
