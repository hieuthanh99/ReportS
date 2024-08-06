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
//     return view('welcome');
// });

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/tasks/search', [TaskController::class, 'searchTasks'])->name('tasks.search');
    Route::get('/criteria/search', [CriteriaController::class, 'searchCriteria'])->name('criteria.search');

    Route::get('/user/search', [UserController::class, 'searchUser'])->name('users.search');

    Route::get('/get-assigned-organizations', [OrganizationController::class, 'getAssignedOrganizations']);
    Route::delete('/delete-file/{id}', [FileController::class, 'destroy']);
    Route::post('/upload', [FileController::class, 'upload'])->name('upload');
    Route::get('/download/{id}/{type}', [FileController::class, 'download'])->name('file.download');


    Route::get('/organization/search-type', [OrganizationController::class, 'searchOrganizationByType'])->name('organization.search.type');

    Route::get('/organization/search-name', [OrganizationController::class, 'searchOrganizationByNameOrCode'])->name('organization.search.name');

    Route::get('/organization/search-parent', [OrganizationController::class, 'searchOrganizationByParentID'])->name('organization.search.parent');
    
    Route::resource('users', UserController::class);
    // Route để kiểm tra mã công việc
    Route::get('/api/check-task-code/{taskCode}', [TaskController::class, 'checkTaskCode']);
    Route::get('/api/check-document-code/{documentCode}', [DocumentController::class, 'checkDocumentCode'])->name('check.document.code');
    Route::get('/api/get-history/{id}/{type}/{cycle}/{typeCycle}', [DocumentController::class, 'getHistory'])->name('document.history');
    Route::post('/save-assign-organizations', [DocumentController::class, 'assignOrganizations']);

    Route::post('/save-assigned-users', [UserController::class, 'saveAssignedUsers'])->name('saveAssignedUsers');
    Route::post('/assign-users', [UserController::class, 'assignUsers'])->name('users.assign');

    Route::get('/tasks/assign', [TaskController::class, 'getAllByAjax'])->name('task.list.assign');
    Route::get('/categories-list', [CategoryController::class, 'listCategories'])->name('categories.list.document');
    Route::delete('/users/{user}/destroyOrganization', [UserController::class, 'destroyOrganization'])->name('users.destroyOrganization');

    Route::resource('criterias_task', CriteriasTaskController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('documents', DocumentController::class);

    Route::resource('tasks', TaskController::class);
    Route::resource('metrics', MetricController::class);
    Route::resource('organizations', OrganizationController::class);
    Route::get('organizations/create/{parentId}', [OrganizationController::class, 'create'])->name('organizations.create.parent');
    Route::get('/organizations/{id}', [OrganizationController::class, 'show'])->name('organizations.show.id');
    // Route để lấy danh sách người dùng
    Route::get('/users/list', [UserController::class, 'listUsers'])->name('users.list');
    Route::get('/users/listAssign', [UserController::class, 'listUsersAll'])->name('users.listAssign');
    
    // Route để gán người dùng cho tổ chức
    Route::post('/users/assign', [UserController::class, 'assignUser'])->name('users.assign');


    Route::get('/api/check-criteria-code/{criteriaCode}', [CriteriaController::class, 'checkCriteriaCode'])->name('check.criteria.code');

});

require __DIR__.'/auth.php';
