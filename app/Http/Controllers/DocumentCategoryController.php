<?php
namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\DocumentCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\TaskTarget;
use App\Models\Document;
use App\Models\TaskResult;


class DocumentCategoryController extends Controller
{
    protected $documentCategoryRepository;

    public function __construct(DocumentCategoryRepositoryInterface $documentCategoryRepository)
    {
        $this->documentCategoryRepository = $documentCategoryRepository;
    }

    public function index()
    {
        $categories = DocumentCategory::where('isDelete', 0)->paginate(10);
        return view('document_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('document_categories.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'code' => 'required|unique:document_categories,code',
                'name' => 'required'
            ], [
                'code.required' => 'Mã loại văn bản là bắt buộc.',
                'code.unique' => 'Mã loại văn bản đã tồn tại.',
                'name.required' => 'Tên loại văn bản là bắt buộc.'
            ]);

            $this->documentCategoryRepository->create($request->all());
            DB::commit();

            return redirect()->route('document_categories.index')->with('success', 'Loại Văn bản tạo thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating document: ' . $e->getMessage());

            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $documentCategory = $this->documentCategoryRepository->find($id);
        return view('document_categories.edit', compact('documentCategory'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'code' => 'required|unique:document_categories,code,' . $id,
                'name' => 'required',
                'description' => 'required'
            ], [
                'code.required' => 'Mã loại văn bản là bắt buộc.',
                'code.unique' => 'Mã loại văn bản đã tồn tại.',
                'name.required' => 'Tên loại văn bản là bắt buộc.',
                'description.required' => 'Chi tiết loại văn bản là bắt buộc.'
            ]);

            $this->documentCategoryRepository->update($id, $request->all());
            DB::commit();

            return redirect()->route('document_categories.index')->with('success', 'Loại Văn bản cập nhật thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating document category: ' . $e->getMessage());

            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // $this->documentCategoryRepository->delete($id);
            $item = DocumentCategory::findOrFail($id);
            $item->isDelete = 1;
            $item->save();
            DB::commit();


            $documents = Document::where('category_id', $id)->get();

            foreach ($documents as $document) {

                $taskTargets = TaskTarget::where('document_id', $document->id)->get();

                foreach ($taskTargets as $taskTarget) {
                    $taskTarget->isDelete = 1;
                    $taskTarget->save();
                }
        
                $taskReults = TaskResult::where('document_id', $document->id)->get();
        
                foreach ($taskReults as $taskReult) {
                    $taskReult->isDelete = 1;
                    $taskReult->save();
                }

                $document->isDelete = 1;
                $document->save();
            }

           
            $item = DocumentCategory::findOrFail($id);
            $item->isDelete = 1;
            $item->save();
            DB::commit();
            return redirect()->route('document_categories.index')->with('success', 'Loại Văn bản, các văn bản, các nhiệm vụ/chỉ tiêu đã được xóa thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting document category: ' . $e->getMessage());

            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}