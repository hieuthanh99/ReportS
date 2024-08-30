<?php
// SearchController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Category;
use App\Models\TaskTarget;
use App\Models\OrganizationType;
use App\Models\Organization;
use App\Models\TaskGroup;
use App\Models\IndicatorGroup;
use App\Models\User;


use App\Models\Position;
use App\Models\DocumentCategory;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $searchTerm = $request->input('search');

        $taskTargets = TaskTarget::where('isDelete', 0)->where('name','like', '%' . $query . '%')->orWhere('code','like', '%' . $query . '%')->paginate(10);
       // dd($taskTargets);
        return view('search.results', compact('taskTargets'));
    }
}
