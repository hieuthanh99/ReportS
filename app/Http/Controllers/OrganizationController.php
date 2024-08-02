<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\OrganizationTask;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function getAssignedOrganizations(Request $request)
    {
        $documentId = $request->query('documentId');
        $taskCode = $request->query('taskCode');
        $taskId = $request->query('taskId');
    
        // Tìm các cơ quan/tổ chức đã gán với task cụ thể
        $organizations = OrganizationTask::where('document_id', $documentId)
                                          ->where('tasks_document_id', $taskId)
                                          ->with('organization')
                                          ->get();
    
        return response()->json([
            'organizations' => $organizations->map(function($orgTask) {
                return [
                    'code' => $orgTask->organization->code,
                    'name' => $orgTask->organization->name,
                    'creator' => $orgTask->creator,
                    'email' => $orgTask->organization->email,
                    'phone' => $orgTask->organization->phone
                ];
            }),
        ]);
    }
    public function searchOrganizationByType(Request $request)
    {
        $query = $request->input('query');
        \Log::info('Search Query: ' . $query);
        
        if (!empty($query)) {
            $organizations = Organization::where('type', 'like', '%' . $query . '%')->get();
        } else {
            $organizations =[];
        }
        
        \Log::info('Organizations Found: ', $organizations->toArray());
    
        return response()->json([
            'organizations' => $organizations
        ]);
    }
    
    public function searchOrganizationByNameOrCode(Request $request)
    {
        $query = $request->input('query');
        
        // Nếu query không rỗng, lọc theo query
        if (!empty($query)) {
            $organizations = Organization::where('code', 'like', '%' . $query . '%')->orWhere('name', 'like', '%' . $query . '%')->get();
        } else {
            $organizations = Organization::all();
        }
        
        return response()->json([
            'organizations' => $organizations
        ]);
    }

    public function searchOrganizationByParentID(Request $request)
    {
        $query = $request->input('query');
        
        // Hàm đệ quy để lấy các cấp dưới của một tổ chức
        function getChildren($parentId) {
            // Lấy các tổ chức con trực tiếp
            $children = Organization::where('parent_id', $parentId)->get();
            
            // Khởi tạo mảng để lưu các tổ chức con và tổ chức con của chúng
            $allChildren = $children;
            
            foreach ($children as $child) {
                // Đệ quy lấy tổ chức con của tổ chức con
                $allChildren = $allChildren->merge(getChildren($child->id));
            }
            
            return $allChildren;
        }
        $userId = Auth::id();
        $organizations = getChildren($userId);
        
        return response()->json([
            'organizations' => $organizations
        ]);
    }
    public function index()
    {
        // Lấy tất cả các tổ chức từ cơ sở dữ liệu và chuyển đổi thành Collection
        $organizations = Organization::all();
    
        // Tạo cây tổ chức từ các tổ chức đã lấy
        $tree = $this->buildTree($organizations);
    
        // Chuyển đổi mảng cây thành Collection
        $tree = collect($tree);
    
        return view('organizations.index', compact('tree'));
    }
    
    private function buildTree($elements, $parentId = null)
    {
        $branch = [];
    
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
    
        return $branch;
    }
    public function create($parentId = null)
    {
        return view('organizations.create', ['parentId' => $parentId]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:organizations,code',
            'type' => 'required|in:tỉnh,bộ',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:15',
            'parent_id' => 'nullable|exists:organizations,id',
        ]);

        $organization = Organization::create([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'email' => $request->email,
            'phone' => $request->phone,
            'parent_id' => $request->parent_id,
            'creator' => auth()->id(),
        ]);

        session()->flash('success', 'Tổ chức mới đã được thêm thành công!');

        return response()->json(['success' => true, 'organization' => $organization]);
    }

    public function show($id)
    {
        $organization = Organization::with('users')->find($id);

        if ($organization) {

            return response()->json([
                'organization' => $organization
            ]);
        }

        return response()->json(['error' => 'Organization not found'], 404);
    }

    public function edit(Organization $organization)
    {
        return view('organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $organization->update($request->all());
        return redirect()->route('organizations.index');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return redirect()->route('organizations.index');
    }
}