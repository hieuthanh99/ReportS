<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\OrganizationType;
use App\Models\OrganizationTask;
use App\Models\TaskDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\DB;
class OrganizationController extends Controller
{



    public function getAssignedOrganizations(Request $request)
    {
        $documentId = $request->query('documentId');
        $taskCode = $request->query('taskCode');
        $taskId = $request->query('taskId');
    
        // Tìm các cơ quan/tổ chức đã gán với task cụ thể
        $organizations = TaskDocument::where('document_id', $documentId)
                                          ->where('id', $taskId)
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
    public function getOrganizationsByType($organization_type_id)
    {
        $organizations = Organization::where('organization_type_id', $organization_type_id)->get();
        return response()->json($organizations);
    }

    public function index()
    {
        // Lấy tất cả các tổ chức từ cơ sở dữ liệu và chuyển đổi thành Collection
        $organizations = Organization::all();
        $oranizationType = OrganizationType::all();
    
        // Tạo cây tổ chức từ các tổ chức đã lấy
        $tree = $this->buildTree($oranizationType, $organizations);
    
        // Chuyển đổi mảng cây thành Collection
        $tree = collect($tree);
    
        return view('organizations.index', compact('tree', 'oranizationType', 'organizations'));
    }
    // private function buildTree($elements, $parentId = null)
    // {
    //     $branch = [];
    
    //     foreach ($elements as $element) {
    //         if ($element['parent_id'] == $parentId) {
    //             $children = $this->buildTree($elements, $element['id']);
    //             if ($children) {
    //                 $element['children'] = $children;
    //             }
    //             $branch[] = $element;
    //         }
    //     }
    
    //     return $branch;
    // }
    private function buildTree($organizationTypes, $organizations)
    {
        $branch = [];

        // Lặp qua tất cả các loại tổ chức
        foreach ($organizationTypes as $organizationType) {
            // Tạo một nhánh cho loại tổ chức
            $typeNode = [
                'id' => $organizationType->id,
                'name' => $organizationType->type_name,
                'type' => 'organization_type',
                'children' => []
            ];

            // Tìm tất cả các tổ chức có cùng `organization_type_id`
            $relatedOrganizations = $organizations->where('organization_type_id', $organizationType->id);

            // Sử dụng hàm buildTree để đệ quy qua các tổ chức này
            $typeNode['children'] = $this->buildOrganizationTree($relatedOrganizations, $organizations);

            // Thêm loại tổ chức vào cây
            $branch[] = $typeNode;
        }

        return $branch;
    }

    private function buildOrganizationTree($relatedOrganizations, $organizations,  $parentId = null)
    {
        $branch = [];

        foreach ($relatedOrganizations as $organization) {
            if ($organization->parent_id == $parentId) {
                $organizationNode = [
                    'id' => $organization->id,
                    'name' => $organization->name,
                    'type' => 'organization'
                ];
                \Log::error('children===========: ' . $organization->id);
                $children = $this->buildOrganizationTree($organizations, $organizations, $organization->id);

                if($children){

                    $organizationNode['children'] = $children;
                } 

                $branch[] = $organizationNode;
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
        DB::beginTransaction();
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255|unique:organizations,code',
             ], [
                 'code.required' => 'Mã cơ quan, tổ chức là bắt buộc.',
                 'code.unique' => 'Mã cơ quan, tổ chức đã tồn tại.',
                 'name.required' => 'Tên cơ quan, tổ chức là bắt buộc.',
             ]);
    
            $organization = Organization::create([
                'name' => $request->name,
                'code' => $request->code,
                'type' => $request->type,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'website' => $request->website,
                'parent_id' => $request->parent_id,
                'creator' => auth()->id(),
            ]);
            DB::commit();
            session()->flash('success', 'Tổ chức mới đã được thêm thành công!');
    
            return response()->json(['success' => true, 'organization' => $organization]);
  
            // session(['success' =>  $typeRecord.' '. 'đã giao cho các tổ chức']);
            // return response()->json([
            //     'success' => true,
            //     'message' => $typeRecord.' '. 'đã giao cho các tổ chức'
            // ]);
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi xảy ra
            DB::rollBack();

            // Ghi lỗi vào log (tùy chọn)
            \Log::error('Error creating document: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Đã xảy ra lỗi, vui lòng thử lại.'
            ]);
        }
        
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
        $organizationType = OrganizationType::all();
        $organizations = Organization::all();
        return view('organizations.edit', compact('organization', 'organizationType', 'organizations'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $organization = Organization::find($id);
        try {
            $request->validate([
                'code' => [
                 'required',
                     Rule::unique('organizations', 'code')->ignore($id)
                 ],
                 'name' => 'required', // Validation rule for textarea
             ], [
                 'code.required' => 'Mã cơ quan, tổ chức là bắt buộc.',
                 'code.unique' => 'Mã cơ quan, tổ chức đã tồn tại.',
                 'name.required' => 'Tên cơ quan, tổ chức là bắt buộc.',
             ]);
    
            $organization->update($request->all());
            DB::commit();
            session()->flash('success', 'Cơ quan, tổ chức đã được cập nhật thành công!');
        
            return redirect()->route('organizations.index');
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi xảy ra
            DB::rollBack();

            // Ghi lỗi vào log (tùy chọn)
            \Log::error('Error creating document: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Đã xảy ra lỗi, vui lòng thử lại.'
            ]);
        }
        
      
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return redirect()->route('organizations.index');
    }
}