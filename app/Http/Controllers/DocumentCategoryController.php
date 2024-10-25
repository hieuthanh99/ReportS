<?php
namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\DocumentCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\TaskTarget;
use App\Models\Document;
use App\Models\TaskResult;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DocumentCategoryController extends Controller
{
    protected $documentCategoryRepository;

    public function __construct(DocumentCategoryRepositoryInterface $documentCategoryRepository)
    {
        $this->documentCategoryRepository = $documentCategoryRepository;
    }

    public function index($text = null)
    {
        $categories = DocumentCategory::orderBy('created_at', 'desc')->where('isDelete', 0);
        if($text){
            $categories->where('name', 'like', '%' . $text . '%');
        }
        $categories =  $categories->paginate(10);
        $countsl =  $categories->count();
        return view('document_categories.index', compact('categories', 'countsl'));
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
                'code' => 'required',
                'name' => 'required'
            ], [
                'code.required' => 'Mã loại văn bản là bắt buộc.',
                'code.unique' => 'Mã loại văn bản đã tồn tại.',
                'name.required' => 'Tên loại văn bản là bắt buộc.'
            ]);
            $exitItem = DocumentCategory::where('isDelete', 0)->where('code', $request->code)->first();
            if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
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
                'code' => 'required',
                'name' => 'required',
                'description' => 'required'
            ], [
                'code.required' => 'Mã loại văn bản là bắt buộc.',
                'code.unique' => 'Mã loại văn bản đã tồn tại.',
                'name.required' => 'Tên loại văn bản là bắt buộc.',
                'description.required' => 'Chi tiết loại văn bản là bắt buộc.'
            ]);
            $exitItem = DocumentCategory::where('isDelete', 0)->where('code', $request->code)->where('id','!=', $id)->first();
            if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
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
        \Log::error('Deletecategory: ' . $id);

        try {
            // $this->documentCategoryRepository->delete($id);


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

    public function exportDocumentCategory(Request $request, $text=null){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'DANH SÁCH LOẠI VĂN BẢN');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFC2C2C2'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->setCellValue('A2', 'STT');
        $sheet->setCellValue('B2', 'Mã loại văn bản');
        $sheet->setCellValue('C2', 'Tên loại văn bản');
        $sheet->setCellValue('D2', 'Chi tiết');

        // Định dạng hàng tiêu đề
        $sheet->getStyle('A2:D2')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFC2C2C2'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        $categories = DocumentCategory::orderBy('created_at', 'desc')->where('isDelete', 0)->get();
        $row = 3;
        foreach ($categories as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->code);
            $sheet->setCellValue('C' . $row, $data->name);
            $sheet->setCellValue('D' . $row, $data->description);

            $row++;
        }
        $sheet->getStyle('A3:D' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Danh sách loại văn bản.xlsx';

        // Gửi file Excel cho người dùng
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
