<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MetricController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\CriteriasTaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskTargetController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\OrganizationTypeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\TaskGroupController;
use App\Http\Controllers\IndicatorGroupController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome')->middleware('check.organization');
// });

// Route::get('/', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'roles:staff,sub_admin'])->group(function () {
   
});
Route::middleware('auth')->group(function () {
    Route::get('/report-update-view-role/{id}/{type}', [DocumentController::class, 'reportViewUpdateRole'])->name('documents.report.update.role')->middleware('check.organization');
    Route::get('/search-documents', [DocumentController::class, 'searchDocuments'])->name('documents.search');

    Route::get('/search-documents-name', [DocumentController::class, 'searchDocumentsName'])->name('documents.search.name');
    Route::get('/api/check-task-code/{taskCode}', [TaskController::class, 'checkTaskCode'])->middleware('check.organization');
    Route::get('/api/check-document-code/{documentCode}', [DocumentController::class, 'checkDocumentCode'])->name('check.document.code')->middleware('check.organization');
    Route::get('/api/get-history/{code}', [DocumentController::class, 'getHistory'])->name('document.history')->middleware('check.organization');
    Route::get('/api/get-history/byId/{code}', [DocumentController::class, 'getHistoryById'])->name('document.history.byId')->middleware('check.organization');
    Route::post('/documents/{document}/task/update-cycle', [DocumentController::class, 'updateTaskCycle'])->name('documents.task.update.cycle')->middleware('check.organization');
    Route::get('/api/check-criteria-code/{criteriaCode}', [CriteriaController::class, 'checkCriteriaCode'])->name('check.criteria.code')->middleware('check.organization');
    Route::delete('/delete-file/{id}', [FileController::class, 'destroy'])->middleware('check.organization');
    Route::post('/upload', [FileController::class, 'upload'])->name('upload')->middleware('check.organization');
    Route::get('/download/{id}/{type}', [FileController::class, 'download'])->name('file.download')->middleware('check.organization');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/file/view/{id}', [FileController::class, 'view'])->name('file.view')->middleware('check.organization');
    Route::post('/tasks/update-remarks', [TaskTargetController::class, 'updateRemarks'])->name('tasks.updateRemarks')->middleware('check.organization');
    Route::resource('organization_types', OrganizationTypeController::class)->middleware('check.organization');
    Route::get('/report-update-view/{document}', [DocumentController::class, 'reportViewUpdate'])->name('documents.report.update')->middleware('check.organization');
    Route::get('/report-update-view-target/{document}', [DocumentController::class, 'reportViewUpdateTarget'])->name('documents.report.update.target')->middleware('check.organization');
    Route::get('/report', [DocumentController::class, 'reportView'])->name('documents.report')->middleware('check.organization');
    Route::get('/report-target', [DocumentController::class, 'reportTargetView'])->name('documents.report.target')->middleware('check.organization');
    Route::get('/report-details-view/{document}', [DocumentController::class, 'detailsReport'])->name('documents.report.details')->middleware('check.organization');
    Route::get('/report-details-view-target/{document}', [DocumentController::class, 'detailsReportTarget'])->name('documents.report.details.target')->middleware('check.organization');
})->middleware('check.organization');

Route::group(['middleware' => ['admin_or_supper_admin']], function () {


 


  

    Route::get('/reports', [ReportController::class, 'showReportDocument'])->name('reports.withDocument')->middleware('check.organization');
    Route::get('/reports-with-unit', [ReportController::class, 'showReportUnit'])->name('reports.withUnit')->middleware('check.organization');
    Route::get('/reports-with-period', [ReportController::class, 'showReportPeriod'])->name('reports.withPeriod')->middleware('check.organization');
    Route::get('/reports-with-details', [ReportController::class, 'showReportDetails'])->name('reports.withDetails')->middleware('check.organization');
    Route::get('/export-Document', [ReportController::class, 'exportDocument'])->middleware('check.organization');
    Route::get('/export-Document-Details', [ReportController::class, 'exportReportDetails'])->middleware('check.organization');
    Route::get('/export-Details', [ReportController::class, 'exportDetails'])->name('task-documents.export-details')->middleware('check.organization');

    Route::get('/export-Period', [ReportController::class, 'exportPeriod'])->middleware('check.organization');
    Route::get('/export-Unit', [ReportController::class, 'exportUnit'])->middleware('check.organization');

    Route::get('/export-Documents', [DocumentController::class, 'exportDocuments'])->name('export.Documents')->middleware('check.organization');
    Route::get('/export-Document-Category', [DocumentCategoryController::class, 'exportDocumentCategory'])->name('export.Document.Category')->middleware('check.organization');
    Route::get('/export-Organization-Type', [OrganizationTypeController::class, 'exportOrganizationType'])->name('export.Organization.Type')->middleware('check.organization');
    Route::get('/export-Position', [PositionController::class, 'exportPosition'])->name('export.Position')->middleware('check.organization');
    Route::get('/export-User', [UserController::class, 'exportUser'])->name('export.User')->middleware('check.organization');
    Route::get('/export-TaskTarget/{type}', [TaskTargetController::class, 'exportTaskTarget'])->name('export.TaskTarget')->middleware('check.organization');
    Route::get('/export-Detail-TaskTarget/{id}/{type}', [TaskTargetController::class, 'exportDetailTaskTarget'])->name('export.Detail.TaskTarget')->middleware('check.organization');

    Route::get('/search', [SearchController::class, 'search'])->name('search')->middleware('check.organization');
    Route::delete('/tasks/{id}/{type}', [TaskTargetController::class, 'destroyTaskTarget'])->name('tasks.destroy.tasktarget')->middleware('check.organization');
    Route::get('/tasks/details/{id}/{type}', [TaskTargetController::class, 'showDetails'])->name('tasks.show-details')->middleware('check.organization');
    Route::get('/tasks/edit/{id}/{type}', [TaskTargetController::class, 'editTaskTarget'])->name('tasks.edit.taskTarget')->middleware('check.organization');


    Route::get('/tasks/edit/approved/{id}/{type}', [DocumentController::class, 'approvedTaskTarget'])->name('tasks.edit.approved')->middleware('check.organization');


    Route::delete('/tasks/delete-organization/{id_task_criteria}/{type}/{id}', [TaskTargetController::class, 'deleteOrganization'])->name('tasks.delete.organization')->middleware('check.organization');
    Route::get('/tasks/type/{type}', [TaskTargetController::class, 'indexView'])->name('tasks.byType')->middleware('check.organization');

    Route::get('/tasks/type/approved/{type}', [TaskTargetController::class, 'indexViewApproved'])->name('tasks.byType.approved')->middleware('check.organization');

    Route::get('/tasks/create/{type}', [TaskTargetController::class, 'createView'])->name('tasks.create.byType')->middleware('check.organization');

    Route::resource('tasks', TaskTargetController::class)->middleware('check.organization');
    Route::put('/tasks/update/{code}/{type}', [TaskTargetController::class, 'updateTaskTarget'])->name('tasks.update.taskTarget')->middleware('check.organization');

    Route::put('/organizations/{id}', [OrganizationController::class, 'update'])->middleware('check.organization');

    Route::resource('indicator_groups', IndicatorGroupController::class)->middleware('check.organization');
    Route::get('/get-organizations/{organization_type_id}', [OrganizationController::class, 'getOrganizationsByType'])->middleware('check.organization');
    Route::get('/get-organization-id/{taskTargetCode}', [TaskTargetController::class, 'getOrganizationIdByCode'])->middleware('check.organization');
    Route::resource('task_groups', TaskGroupController::class)->middleware('check.organization');
    Route::resource('positions', PositionController::class)->middleware('check.organization');
    Route::get('/tasks/assign-organizations/{taskTargetId}', [TaskTargetController::class, 'assignOrganizations'])->name('tasks.assign-organizations')->middleware('check.organization');
    Route::resource('document_categories', DocumentCategoryController::class)->middleware('check.organization');
   
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('check.organization');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('check.organization');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware('check.organization');
    Route::get('/tasks/search', [TaskController::class, 'searchTasks'])->name('tasks.search')->middleware('check.organization');
    Route::get('/criteria/search', [CriteriaController::class, 'searchCriteria'])->name('criteria.search')->middleware('check.organization');

    Route::get('/user/search', [UserController::class, 'searchUser'])->name('users.search')->middleware('check.organization');

    Route::get('/get-assigned-organizations', [OrganizationController::class, 'getAssignedOrganizations'])->middleware('check.organization');


    Route::get('/task-target/{id}/organizations', [TaskTargetController::class, 'getOrganizationsByTaskTargetId'])->middleware('check.organization');

    Route::get('/organization/search-type', [OrganizationController::class, 'searchOrganizationByType'])->name('organization.search.type')->middleware('check.organization');

    Route::get('/organization/search-name', [OrganizationController::class, 'searchOrganizationByNameOrCode'])->name('organization.search.name')->middleware('check.organization');

    Route::get('/organization/search-parent', [OrganizationController::class, 'searchOrganizationByParentID'])->name('organization.search.parent')->middleware('check.organization');
    Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroyOrganizationr'])->name('organization.destroyOrganizationr')->middleware('check.organization');


    Route::put('/update-status/{id}', [DocumentController::class, 'changeComplete'])->middleware('check.organization');

    Route::put('/update-status-approved/{id}', [DocumentController::class, 'changeApproved'])->middleware('check.organization');
    Route::put('/update-status-approved-all/{id}', [DocumentController::class, 'changeApprovedAll'])->middleware('check.organization');
    

    Route::resource('users', UserController::class)->middleware('check.organization');
    // Route để kiểm tra mã công việc
  
    Route::post('/save-assign-organizations', [DocumentController::class, 'assignOrganizations'])->middleware('check.organization');

    // Route::get('/report', [DocumentController::class, 'reportView'])->name('documents.report')->middleware('check.organization');
    // Route::get('/report-update-view/{document}', [DocumentController::class, 'reportViewUpdate'])->name('documents.report.update')->middleware('check.organization');
    // Route::get('/report-details-view/{document}', [DocumentController::class, 'detailsReport'])->name('documents.report.details')->middleware('check.organization');

   

    Route::post('/save-assigned-users', [UserController::class, 'saveAssignedUsers'])->name('saveAssignedUsers')->middleware('check.organization');
    Route::post('/assign-users', [UserController::class, 'assignUsers'])->name('users.assign')->middleware('check.organization');

    Route::get('/tasks/assign', [TaskController::class, 'getAllByAjax'])->name('task.list.assign')->middleware('check.organization');
    Route::get('/categories-list', [CategoryController::class, 'listCategories'])->name('categories.list.document')->middleware('check.organization');
    Route::delete('/users/{user}/destroyOrganization', [UserController::class, 'destroyOrganization'])->name('users.destroyOrganization')->middleware('check.organization');

    Route::resource('criterias_task', CriteriasTaskController::class)->middleware('check.organization');
    Route::resource('categories', CategoryController::class)->middleware('check.organization');
    Route::resource('documents', DocumentController::class)->middleware('check.organization');


    Route::resource('metrics', MetricController::class)->middleware('check.organization');
    Route::resource('organizations', OrganizationController::class)->middleware('check.organization');
    Route::get('organizations/create/{parentId}', [OrganizationController::class, 'create'])->name('organizations.create.parent')->middleware('check.organization');
    Route::get('/organizations/{id}', [OrganizationController::class, 'show'])->name('organizations.show.id')->middleware('check.organization');
    // Route để lấy danh sách người dùng
    Route::get('/users/list', [UserController::class, 'listUsers'])->name('users.list')->middleware('check.organization');
    Route::get('/users/listAssign', [UserController::class, 'listUsersAll'])->name('users.listAssign')->middleware('check.organization');
    
    // Route để gán người dùng cho tổ chức
    //Route::post('/users/assign', [UserController::class, 'assignUser'])->name('users.assign')->middleware('check.organization');



})->middleware('check.organization');

require __DIR__.'/auth.php';
