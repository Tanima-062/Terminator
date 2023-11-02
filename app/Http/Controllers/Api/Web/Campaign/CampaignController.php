<?php

namespace App\Http\Controllers\Api\Web\Campaign;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {

        return response()->json(Campaign::orderBy('id', 'asc')->active()->get());
    }
}
