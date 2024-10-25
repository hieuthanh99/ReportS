<?php
namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PositionController extends Controller
{
    public function index($text = null)
    {
        $types = Position::orderBy('created_at', 'desc')->where('isDelete', 0);
        if($types){
            $types->where('name', 'like', '%' . $text . '%');
        }
        $types =  $types->paginate(10);
        return view('positions.index', compact('types'));
    }

    public function create()
    {
        return view('positions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'description' => 'nullable',
        ], [
            'code.required' => 'Mã chức danh là bắt buộc.',
            'code.unique' => 'Mã chức danh đã tồn tại.',
            'name.required' => 'Tên chức danh là bắt buộc.',
        ]);
        $exitItem = Position::where('isDelete', 0)->where('code', $request->code)->first();
        if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
        Position::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('positions.index')->with('success', 'Thêm mới chức danh thành công.');
    }

    public function edit(Position $position)
    {
        return view('positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'description' => 'nullable',
        ], [
            'code.required' => 'Mã chức danh là bắt buộc.',
            'code.unique' => 'Mã chức danh đã tồn tại.',
            'name.required' => 'Tên chức danh là bắt buộc.',
        ]);
        $exitItem = Position::where('isDelete', 0)->where('code', $request->code)->where('id','!=', $position->id)->first();
        if($exitItem)  return redirect()->back()->with('error', 'Mã đã tồn tại!');
        $position->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('positions.index')->with('success', 'Cập nhật thành công chức danh.');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Xóa thành công chức danh');
    }

    public function exportPosition(Request $request, $text=null){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'DANH SÁCH CHỨC VỤ');
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1:C1')->applyFromArray([
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
        $sheet->setCellValue('B2', 'Mã chức vụ');
        $sheet->setCellValue('C2', 'Tên chức vụ');

        // Định dạng hàng tiêu đề
        $sheet->getStyle('A2:C2')->applyFromArray([
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
        $types = Position::orderBy('created_at', 'desc')->where('isDelete', 0)->get();
        $row = 3;
        foreach ($types as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->code);
            $sheet->setCellValue('C' . $row, $data->name);

            $row++;
        }
        $sheet->getStyle('A3:C' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Danh sách chức vụ.xlsx';

        // Gửi file Excel cho người dùng
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
