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
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\OrganizationTypeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IndicatorGroupController;
use App\Http\Controllers\TaskGroupController;
use App\Http\Controllers\TaskTargetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

use App\Models\Position;
use App\Models\DocumentCategory;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $searchTerm = $request->input('search_in');
        $searchType = $request->input('search_type');
        if($searchTerm == 'documents') {
            return app(DocumentController::class)->callAction('index', $parameters = ['request'=> $request, 'text' => $query]);

        }else  if($searchTerm == 'document_categories') {
            return app(DocumentCategoryController::class)->callAction('index', $parameters = ['text' => $query]);

        }else  if($searchTerm == 'organization_types') {
            return app(OrganizationTypeController::class)->callAction('index', $parameters = ['text' => $query]);
        }else  if($searchTerm == 'task_groups') {
            return app(TaskGroupController::class)->callAction('index', $parameters = ['text' => $query]);
        }else  if($searchTerm == 'indicator_groups') {
            return app(IndicatorGroupController::class)->callAction('index', $parameters = ['text' => $query]);
        }else  if($searchTerm == 'positions') {
            return app(PositionController::class)->callAction('index', $parameters = ['text' => $query]);
        }else  if($searchTerm == 'tasks') {
            return app(TaskTargetController::class)->callAction('indexView', $parameters = ['request'=> $request, 'type'=> $searchType, 'text' => $query]);
        }else  if($searchTerm == 'report') {
            return app(DocumentController::class)->callAction('indexView', $parameters = ['request'=> $request, 'type'=> $searchType, 'text' => $query]);
        }else  if($searchTerm == 'dashboard') {
            return app(DashboardController::class)->callAction('index', $parameters = [ 'text' => $query]);
        }
        if($searchTerm == 'users') {
            return app(UserController::class)->callAction('index', $parameters = [ 'text' => $query]);
        }
        else  if($searchTerm == 'report') {
            return app(ReportController::class)->callAction('showReportDocument', $parameters = ['request'=> $request, 'text' => $query]);
        }
        else  if($searchTerm == 'reports-with-unit') {
            return app(ReportController::class)->callAction('showReportUnit', $parameters = [ 'request'=> $request,'text' => $query]);
        }
        else  if($searchTerm == 'reports-with-period') {
            return app(ReportController::class)->callAction('showReportPeriod', $parameters = [ 'request'=> $request,'text' => $query]);
        }
        else  if($searchTerm == 'reports-with-details') {
            return app(ReportController::class)->callAction('showReportDetails', $parameters = [ 'request'=> $request,'text' => $query]);
        }

        return redirect()->back()->withErrors(['error' => 'Không thể tìm kiếm tại trang này']);

    }
}
