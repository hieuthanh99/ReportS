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
        $searchIn = $request->input('search_in');

        $results = [];

        switch ($searchIn) {
            case 'documents':
                $results = Document::where('title', 'like', "%$query%")
                    ->orWhere('description', 'like', "%$query%")
                    ->get();
                break;

            case 'document_categories':
                $results = DocumentCategory::where('name', 'like', "%$query%")
                    ->get();
                break;

            case 'tasks':
                $results = TaskTarget::where('name', 'like', "%$query%")
                    ->orWhere('details', 'like', "%$query%")
                    ->get();
                break;

            case 'categories':
                $results = Category::where('name', 'like', "%$query%")
                    ->get();
                break;

            case 'organization_types':
                $results = OrganizationType::where('name', 'like', "%$query%")
                    ->get();
                break;

            case 'organizations':
                $results = Organization::where('name', 'like', "%$query%")
                    ->get();
                break;

            case 'task_groups':
                $results = TaskGroup::where('name', 'like', "%$query%")
                    ->get();
                break;

            case 'indicator_groups':
                $results = IndicatorGroup::where('name', 'like', "%$query%")
                    ->get();
                break;

            case 'positions':
                $results = Position::where('name', 'like', "%$query%")
                    ->get();
                break;

            case 'users':
                $results = User::where('name', 'like', "%$query%")
                    ->get();
                break;

            default:
                $results = []; // Hoặc thông báo lỗi
                break;
        }

        return view('search.results', ['results' => $results, 'searchIn' => $searchIn]);
    }
}
