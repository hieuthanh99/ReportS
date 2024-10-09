<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\OrganizationType;
use App\Models\Organization;
use App\Models\TaskTarget;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function dataReportDocument(Request $request, $text=null){
        //dd($request);
        $datas = Document::query()->where('isDelete', 0);
        $executionTimeFrom = $request->input('execution_time_from');
        $executionTimeTo = $request->input('execution_time_to');

        // Kiểm tra nếu cả hai thời gian đều có giá trị
        if ($executionTimeFrom && $executionTimeTo) {
            try {
                // Chuyển đổi thời gian thành đối tượng Carbon để dễ so sánh
                $executionTimeFrom = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeFrom);
                $executionTimeTo = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeTo);
        
                // Kiểm tra nếu thời gian từ lớn hơn thời gian đến
                if ($executionTimeFrom->gt($executionTimeTo)) {
                    return redirect()->back()->withErrors([
                        'error' => "Thời gian từ ({$executionTimeFrom->format('d-m-Y')}) không được lớn hơn thời gian đến ({$executionTimeTo->format('d-m-Y')})."
                    ]);
                }
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                return redirect()->back()->withErrors(['error' => 'Định dạng thời gian không hợp lệ. Vui lòng nhập đúng định dạng ngày: dd-mm-yyyy.']);
            }
        }

        if ($request->filled('organization_id') && $request->organization_id !== 'null') {
            $datas->where('issuing_department', $request->organization_id);
        }
        if ($executionTimeFrom) {
            $datas->whereDate('release_date', '>=', $executionTimeFrom);
        }

        if ($executionTimeTo) {
            $datas->whereDate('release_date', '<=', $executionTimeTo);
        }
        if($text){
            $datas->where('document_name', 'like', '%' . $text . '%');
        }
        $datas = $datas->paginate(10);
        foreach ($datas as $data) {
            $data->task_count = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('isDelete', 0)
            ->count();
            $data->task_completed_in_time = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('isDelete', 0)
            ->where('status_code', 'completed_in_time')
            ->count();
            $data->task_completed_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('isDelete', 0)
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->task_completed_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_overdue')
            ->where('isDelete', 0)
            ->count();
            $data->task_in_progress_in_time = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'in_progress_in_time')
            ->where('isDelete', 0)
            ->count();
            $data->task_in_progress_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'in_progress_overdue')
            ->where('isDelete', 0)
            ->count();

            $data->target_count = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('isDelete', 0)
            ->count();
            $data->target_completed_in_time = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('isDelete', 0)
            ->where('status_code', 'completed_in_time')
            ->count();
            $data->target_completed_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_overdue')
            ->where('isDelete', 0)
            ->count();
            $data->target_completed_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_overdue')
            ->where('isDelete', 0)
            ->count();
            $data->target_in_progress_in_time = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'in_progress_in_time')
            ->where('isDelete', 0)
            ->count();
            $data->target_in_progress_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'in_progress_overdue')
            ->where('isDelete', 0)
            ->count();
        }
        $organizations = Organization::where('isDelete', 0)->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->orderBy('type_name', 'asc')->get();

        return [$datas, $organizationsType, $organizations];
    }
    public function showReportDocument(Request $request, $text=null)
    {
        $reportData = $this->dataReportDocument($request, $text);
        $datas = $reportData[0];
        $organizationsType = $reportData[1];
        $organizations = $reportData[2];
        return view('reports.report_template_document', compact('datas', 'organizationsType', 'organizations'));
    }

    public function exportDocument(Request $request, $text=null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'BÁO CÁO TỔNG HỢP KẾT QUẢ THỰC HIỆN NHIỆM VỤ CHỈ TIÊU THEO VĂN BẢN');
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1:M1')->applyFromArray([
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

        $sheet->setCellValue('A2', '');
        $sheet->setCellValue('B2', '');
        $sheet->setCellValue('C2', '');
        $sheet->setCellValue('D2', 'Nhiệm vụ');
        $sheet->setCellValue('I2', 'Chỉ tiêu');
        $sheet->mergeCells('D2:H2');
        $sheet->mergeCells('I2:M2');
        $sheet->getStyle('A2:M2')->applyFromArray([
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

        $sheet->setCellValue('A3', '');
        $sheet->setCellValue('B3', '');
        $sheet->setCellValue('C3', '');
        $sheet->setCellValue('D3', '');
        $sheet->setCellValue('E3', 'Hoàn thành');
        $sheet->setCellValue('G3', 'Đang thực hiện');
        $sheet->setCellValue('I3', '');
        $sheet->setCellValue('J3', 'Hoàn thành');
        $sheet->setCellValue('L3', 'Đang thực hiện');
        $sheet->mergeCells('E3:F3');
        $sheet->mergeCells('G3:H3');
        $sheet->mergeCells('J3:K3');
        $sheet->mergeCells('L3:M3');
        $sheet->getStyle('A3:M3')->applyFromArray([
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

        // Đặt tiêu đề cho các cột
        $sheet->setCellValue('A4', 'STT');
        $sheet->setCellValue('B4', 'Mã văn bản');
        $sheet->setCellValue('C4', 'Tên văn bản');
        $sheet->setCellValue('D4', 'Tổng số');
        $sheet->setCellValue('E4', 'Đúng hạn');
        $sheet->setCellValue('F4', 'Quá hạn');
        $sheet->setCellValue('G4', 'Trong hạn');
        $sheet->setCellValue('H4', 'Quá hạn');
        $sheet->setCellValue('I4', 'Tổng số');
        $sheet->setCellValue('J4', 'Đúng hạn');
        $sheet->setCellValue('K4', 'Quá hạn');
        $sheet->setCellValue('L4', 'Trong hạn');
        $sheet->setCellValue('M4', 'Quá hạn');

        // Định dạng hàng tiêu đề
        $sheet->getStyle('A4:M4')->applyFromArray([
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
        // dd($request);
        $reportData = $this->dataReportDocument($request, $text);
        $datas = $reportData[0];
        $row = 5; // Bắt đầu từ hàng thứ 4 để không ghi đè tiêu đề

        foreach ($datas as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->document_code);
            $sheet->setCellValue('C' . $row, $data->document_name);
            $sheet->setCellValue('D' . $row, $data->task_count);
            $sheet->setCellValue('E' . $row, $data->task_completed_in_time);
            $sheet->setCellValue('F' . $row, $data->task_completed_overdue);
            $sheet->setCellValue('G' . $row, $data->task_in_progress_in_time);
            $sheet->setCellValue('H' . $row, $data->task_in_progress_overdue);
            $sheet->setCellValue('I' . $row, $data->target_count);
            $sheet->setCellValue('J' . $row, $data->target_completed_in_time);
            $sheet->setCellValue('K' . $row, $data->target_completed_overdue);
            $sheet->setCellValue('L' . $row, $data->target_in_progress_in_time);
            $sheet->setCellValue('M' . $row, $data->target_in_progress_overdue);

            $row++;
        }

        // Định dạng dữ liệu
        $sheet->getStyle('A5:M' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Báo cáo tổng hợp theo văn bản.xlsx';

        // Gửi file Excel cho người dùng
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function dataReportUnit(Request $request, $text=null){
          // Lấy dữ liệu từ database hoặc từ một nguồn khác
          $datas = Organization::query()->where('isDelete', 0)->whereNotNull('organization_type_id')->orderBy('name', 'asc');
          $dataDocuments = Document::query()->where('isDelete', 0);
          $executionTimeFrom = $request->input('execution_time_from');
          $executionTimeTo = $request->input('execution_time_to');
  
          // Kiểm tra nếu cả hai thời gian đều có giá trị
          if ($executionTimeFrom && $executionTimeTo) {
              try {
                  // Chuyển đổi thời gian thành đối tượng Carbon để dễ so sánh
                  $executionTimeFrom = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeFrom);
                  $executionTimeTo = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeTo);
          
                  // Kiểm tra nếu thời gian từ lớn hơn thời gian đến
                  if ($executionTimeFrom->gt($executionTimeTo)) {
                      return redirect()->back()->withErrors([
                          'error' => "Thời gian từ ({$executionTimeFrom->format('d-m-Y')}) không được lớn hơn thời gian đến ({$executionTimeTo->format('d-m-Y')})."
                      ]);
                  }
              } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                  return redirect()->back()->withErrors(['error' => 'Định dạng thời gian không hợp lệ. Vui lòng nhập đúng định dạng ngày: dd-mm-yyyy.']);
              }
          }
  
          if ($request->filled('document_id')) {
              $dataDocuments->where('id',  $request->input('document_id'));
          }
          if ($executionTimeFrom) {
              $dataDocuments->whereDate('release_date', '>=', $executionTimeFrom);
          }
  
          if ($executionTimeTo) {
              $dataDocuments->whereDate('release_date', '<=', $executionTimeTo);
          }
          if($text){
              $datas->where('document_name', 'like', '%' . $text . '%');
          }
          if ($request->filled('document_id') || $executionTimeFrom || $executionTimeTo) {
              $dataDocuments = $dataDocuments->pluck('id');
              $datas = Organization::whereHas('taskTargets', function ($query) use ($dataDocuments) {
                  $query->whereIn('document_id', $dataDocuments)->where('isDelete', 0);
              });
          }
         
          $datas = $datas->paginate(10);
  
          foreach ($datas as $data) {
              if ($request->filled('document_id') || $executionTimeFrom || $executionTimeTo) {                
                  $data->task_count = TaskTarget::where('organization_id', $data->id)->where('isDelete', 0)->whereIn('document_id', $dataDocuments->pluck('id'))
                  ->where('type', 'Task')
                  ->count();
                  $data->task_completed_in_time = TaskTarget::where('organization_id', $data->id)->where('isDelete', 0)->whereIn('document_id', $dataDocuments->pluck('id'))
                  ->where('type', 'Task')
                  ->where('status_code', 'completed_in_time')
                  ->count();
                  $data->task_completed_overdue = TaskTarget::where('organization_id', $data->id)->where('isDelete', 0)->whereIn('document_id', $dataDocuments->pluck('id'))
                  ->where('type', 'Task')
                  ->where('status_code', 'completed_overdue')
                  ->count();
                  $data->task_completed_overdue = TaskTarget::where('organization_id', $data->id)->where('isDelete', 0)->whereIn('document_id', $dataDocuments->pluck('id'))
                  ->where('type', 'Task')
                  ->where('status_code', 'completed_overdue')
                  ->count();
                  $data->task_in_progress_in_time = TaskTarget::where('organization_id', $data->id)->where('isDelete', 0)->whereIn('document_id', $dataDocuments->pluck('id'))
                  ->where('type', 'Task')
                  ->where('status_code', 'in_progress_in_time')
                  ->count();
                  $data->task_in_progress_overdue = TaskTarget::where('organization_id', $data->id)->where('isDelete', 0)->whereIn('document_id', $dataDocuments->pluck('id'))
                  ->where('type', 'Task')
                  ->where('status_code', 'in_progress_overdue')
                  ->count();
      
                  $data->target_count = TaskTarget::where('organization_id', $data->id)->where('isDelete', 0)->whereIn('document_id', $dataDocuments->pluck('id'))
                  ->where('type', 'Target')
                  ->count();
                  $data->target_completed_in_time = TaskTarget::where('organization_id', $data->id)->where('isDelete', 0)->whereIn('document_id', $dataDocuments->pluck('id'))
                  ->where('type', 'Target')
                  ->where('status_code', 'completed_in_time')
                  ->count();
                  $data->target_completed_overdue = TaskTarget::where('organization_id', $data->id)->where('isDelete', 0)->whereIn('document_id', $dataDocuments->pluck('id'))
                  ->where('type', 'Target')
                  ->where('status_code', 'completed_overdue')
                  ->count();
                  $data->target_completed_overdue = TaskTarget::where('organization_id', $data->id)->where('isDelete', 0)->whereIn('document_id', $dataDocuments->pluck('id'))
                  ->where('type', 'Target')
                  ->where('status_code', 'completed_overdue')
                  ->count();
                  $data->target_in_progress_in_time = TaskTarget::where('organization_id', $data->id)->where('isDelete', 0)->whereIn('document_id', $dataDocuments->pluck('id'))
                  ->where('type', 'Target')
                  ->where('status_code', 'in_progress_in_time')
                  ->count();
                  $data->target_in_progress_overdue = TaskTarget::where('organization_id', $data->id)->where('isDelete', 0)->whereIn('document_id', $dataDocuments->pluck('id'))
                  ->where('type', 'Target')
                  ->where('status_code', 'in_progress_overdue')
                  ->count();
              }else{
                  $data->task_count = TaskTarget::where('organization_id', $data->id)
                  ->where('type', 'Task')
                  ->where('isDelete', 0)
                  ->count();
                  $data->task_completed_in_time = TaskTarget::where('organization_id', $data->id)
                  ->where('type', 'Task')
                  ->where('status_code', 'completed_in_time')
                  ->where('isDelete', 0)
                  ->count();
                  $data->task_completed_overdue = TaskTarget::where('organization_id', $data->id)
                  ->where('type', 'Task')
                  ->where('status_code', 'completed_overdue')
                  ->where('isDelete', 0)
                  ->count();
                  $data->task_completed_overdue = TaskTarget::where('organization_id', $data->id)
                  ->where('type', 'Task')
                  ->where('status_code', 'completed_overdue')
                  ->where('isDelete', 0)
                  ->count();
                  $data->task_in_progress_in_time = TaskTarget::where('organization_id', $data->id)
                  ->where('type', 'Task')
                  ->where('status_code', 'in_progress_in_time')
                  ->where('isDelete', 0)
                  ->count();
                  $data->task_in_progress_overdue = TaskTarget::where('organization_id', $data->id)
                  ->where('type', 'Task')
                  ->where('status_code', 'in_progress_overdue')
                  ->where('isDelete', 0)
                  ->count();
      
                  $data->target_count = TaskTarget::where('organization_id', $data->id)
                  ->where('type', 'Target')
                  ->where('isDelete', 0)
                  ->count();
                  $data->target_completed_in_time = TaskTarget::where('organization_id', $data->id)
                  ->where('type', 'Target')
                  ->where('status_code', 'completed_in_time')
                  ->where('isDelete', 0)
                  ->count();
                  $data->target_completed_overdue = TaskTarget::where('organization_id', $data->id)
                  ->where('type', 'Target')
                  ->where('status_code', 'completed_overdue')
                  ->where('isDelete', 0)
                  ->count();
                  $data->target_completed_overdue = TaskTarget::where('organization_id', $data->id)
                  ->where('type', 'Target')
                  ->where('status_code', 'completed_overdue')
                  ->where('isDelete', 0)
                  ->count();
                  $data->target_in_progress_in_time = TaskTarget::where('organization_id', $data->id)
                  ->where('type', 'Target')
                  ->where('status_code', 'in_progress_in_time')
                  ->where('isDelete', 0)
                  ->count();
                  $data->target_in_progress_overdue = TaskTarget::where('organization_id', $data->id)
                  ->where('type', 'Target')
                  ->where('status_code', 'in_progress_overdue')
                  ->where('isDelete', 0)
                  ->count();
              }
             
          }
          $organizationsType = OrganizationType::where('isDelete', 0)->get();
          $documentsSearch = Document::where('isDelete', 0)->get();
        return [$datas, $organizationsType, $documentsSearch];
    }

    public function showReportUnit(Request $request, $text=null)
    {
        $reportData = $this->dataReportUnit($request, $text);
        $datas = $reportData[0];
        $organizationsType = $reportData[1];
        $documentsSearch = $reportData[2];
        // Truyền dữ liệu tới view
        return view('reports.report_template_unit', compact('datas', 'organizationsType', 'documentsSearch'));
    }

    public function exportUnit(Request $request, $text=null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'BÁO CÁO THỐNG KÊ KẾT QUẢ THỰC HIỆN NHIỆM VỤ CHỈ TIÊU THEO ĐƠN VỊ');
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1:M1')->applyFromArray([
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

        $sheet->setCellValue('A2', '');
        $sheet->setCellValue('B2', '');
        $sheet->setCellValue('C2', '');
        $sheet->setCellValue('D2', 'Nhiệm vụ');
        $sheet->setCellValue('I2', 'Chỉ tiêu');
        $sheet->mergeCells('D2:H2');
        $sheet->mergeCells('I2:M2');
        $sheet->getStyle('A2:M2')->applyFromArray([
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

        $sheet->setCellValue('A3', '');
        $sheet->setCellValue('B3', '');
        $sheet->setCellValue('C3', '');
        $sheet->setCellValue('D3', '');
        $sheet->setCellValue('E3', 'Hoàn thành');
        $sheet->setCellValue('G3', 'Đang thực hiện');
        $sheet->setCellValue('I3', '');
        $sheet->setCellValue('J3', 'Hoàn thành');
        $sheet->setCellValue('L3', 'Đang thực hiện');
        $sheet->mergeCells('E3:F3');
        $sheet->mergeCells('G3:H3');
        $sheet->mergeCells('J3:K3');
        $sheet->mergeCells('L3:M3');
        $sheet->getStyle('A3:M3')->applyFromArray([
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

        // Đặt tiêu đề cho các cột
        $sheet->setCellValue('A4', 'STT');
        $sheet->setCellValue('B4', 'Loại cơ quan');
        $sheet->setCellValue('C4', 'Cơ quan');
        $sheet->setCellValue('D4', 'Tổng số');
        $sheet->setCellValue('E4', 'Đúng hạn');
        $sheet->setCellValue('F4', 'Quá hạn');
        $sheet->setCellValue('G4', 'Trong hạn');
        $sheet->setCellValue('H4', 'Quá hạn');
        $sheet->setCellValue('I4', 'Tổng số');
        $sheet->setCellValue('J4', 'Đúng hạn');
        $sheet->setCellValue('K4', 'Quá hạn');
        $sheet->setCellValue('L4', 'Trong hạn');
        $sheet->setCellValue('M4', 'Quá hạn');

        // Định dạng hàng tiêu đề
        $sheet->getStyle('A4:M4')->applyFromArray([
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
        
        $reportData = $this->dataReportUnit($request, $text);
        $datas = $reportData[0];
        $row = 5; // Bắt đầu từ hàng thứ 4 để không ghi đè tiêu đề

        foreach ($datas as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->code);
            $sheet->setCellValue('C' . $row, $data->name);
            $sheet->setCellValue('D' . $row, $data->task_count);
            $sheet->setCellValue('E' . $row, $data->task_completed_in_time);
            $sheet->setCellValue('F' . $row, $data->task_completed_overdue);
            $sheet->setCellValue('G' . $row, $data->task_in_progress_in_time);
            $sheet->setCellValue('H' . $row, $data->task_in_progress_overdue);
            $sheet->setCellValue('I' . $row, $data->target_count);
            $sheet->setCellValue('J' . $row, $data->target_completed_in_time);
            $sheet->setCellValue('K' . $row, $data->target_completed_overdue);
            $sheet->setCellValue('L' . $row, $data->target_in_progress_in_time);
            $sheet->setCellValue('M' . $row, $data->target_in_progress_overdue);

            $row++;
        }

        // Định dạng dữ liệu
        $sheet->getStyle('A5:M' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Báo cáo tổng hợp theo đơn vị.xlsx';

        // Gửi file Excel cho người dùng
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }


    public function dataReportPeriod(Request $request, $text=null){
        $executionTimeFrom = $request->input('execution_time_from');
        $executionTimeTo = $request->input('execution_time_to');
        $datas = Document::query()->where('isDelete', 0);
        $organizations = Organization::where('isDelete', 0)->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->orderBy('type_name', 'asc')->get();

        if ($executionTimeFrom && $executionTimeTo) {
            try {
                $executionTimeFrom = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeFrom);
                $executionTimeTo = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeTo);
                if ($executionTimeFrom->gt($executionTimeTo)) {
                    return redirect()->back()->withErrors([
                        'error' => "Thời gian từ ({$executionTimeFrom->format('d-m-Y')}) không được lớn hơn thời gian đến ({$executionTimeTo->format('d-m-Y')})."
                    ]);
                }
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                return redirect()->back()->withErrors(['error' => 'Định dạng thời gian không hợp lệ. Vui lòng nhập đúng định dạng ngày: dd-mm-yyyy.']);
            }
        }

        if ($request->filled('document_id')) {
            $datas->where('id', $request->document_id);
        }
        if ($executionTimeFrom) {
            $datas->whereDate('release_date', '>=', $executionTimeFrom);
        }

        if ($executionTimeTo) {
            $datas->whereDate('release_date', '<=', $executionTimeTo);
        }
        if($text){
            $datas->where('document_name', 'like', '%' . $text . '%');
        }
        // $datas = $datas->paginate(10);

        $documents = $datas->with('issuingDepartment')->where('isDelete', 0)->orderBy('created_at', 'desc');

        $taskDocuments = TaskTarget::whereIn('document_id', $documents->pluck('id'))->where('isDelete', 0)->paginate(10);
        $documentsSearch = Document::where('isDelete', 0)->get();
        return [$datas, $organizationsType, $organizations,  $taskDocuments, $documentsSearch, $documents];
    }

    public function showReportPeriod(Request $request, $text=null)
    {
        $reportData = $this->dataReportPeriod($request, $text);
        $datas = $reportData[0];
        $organizationsType = $reportData[1];
        $organizations = $reportData[2];
        $taskDocuments = $reportData[3];
        $documentsSearch = $reportData[4];

        return view('reports.report_template_preriod', compact('datas', 'organizationsType', 'organizations', 'taskDocuments', 'documentsSearch'));
    }

    public function exportPeriod(Request $request, $text=null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'BÁO CÁO TỔNG HỢP CHỈ TIÊU/ NHIỆM VỤ THEO ĐƠN VỊ TỪNG CHU KỲ');
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1:K1')->applyFromArray([
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

        // Đặt tiêu đề cho các cột
        $sheet->setCellValue('A2', 'STT');
        $sheet->setCellValue('B2', 'Mã văn bản');
        $sheet->setCellValue('C2', 'Tên văn bản');
        $sheet->setCellValue('D2', 'Mã chỉ tiêu/ nhiệm vụ');
        $sheet->setCellValue('E2', 'Tên chỉ tiêu/ nhiệm vụ');
        $sheet->setCellValue('F2', 'Loại Cơ quan thực hiện');
        $sheet->setCellValue('G2', 'Cơ quan thực hiện');
        $sheet->setCellValue('H2', 'Tiến độ');
        $sheet->setCellValue('I2', 'Đánh giá tiến độ');
        $sheet->setCellValue('J2', 'Loại chu kỳ');
        $sheet->setCellValue('K2', 'Kết quả');

        // Định dạng hàng tiêu đề
        $sheet->getStyle('A2:K2')->applyFromArray([
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
        
        // Lấy dữ liệu từ cơ sở dữ liệu
        $reportData = $this->dataReportPeriod($request, $text);
        $datas = $reportData[0];
        $taskDocuments = $reportData[3];
        $row = 3; // Bắt đầu từ hàng thứ 4 để không ghi đè tiêu đề
    
        foreach ($taskDocuments as $index => $data) {
            $type = $data->latestTaskResult()->type ?? null;
            $numberType = $data->latestTaskResult()->number_type ?? '';


            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->document->document_code);
            $sheet->setCellValue('C' . $row, $data->document->document_name);
            $sheet->setCellValue('D' . $row, $data->code);
            $sheet->setCellValue('E' . $row, $data->name);
            $sheet->setCellValue('F' . $row, $data->organization->organizationType->type_name);
            $sheet->setCellValue('G' . $row, $data->organization->name);
            $sheet->setCellValue('H' . $row, $data->results);
            $sheet->setCellValue('I' . $row, $data->getTaskStatusDescription());
            $sheet->setCellValue('J' . $row, $this->formatCycleType($type, $numberType));
            $sheet->setCellValue('K' . $row, $data->latestTaskResult()->result ?? '');
            $row++;
        }
        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Báo cáo tổng hợp theo chu kỳ.xlsx';

        // Gửi file Excel cho người dùng
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }


    public function exportDetails()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'BÁO CÁO CHI TIẾT CHỈ TIÊU/ NHIỆM VỤ');
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1:K1')->applyFromArray([
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

        // Đặt tiêu đề cho các cột
        $sheet->setCellValue('A2', 'STT');
        $sheet->setCellValue('B2', 'Mã văn bản');
        $sheet->setCellValue('C2', 'Tên văn bản');
        $sheet->setCellValue('D2', 'Mã chỉ tiêu/ nhiệm vụ');
        $sheet->setCellValue('E2', 'Tên chỉ tiêu/ nhiệm vụ');
        $sheet->setCellValue('F2', 'Loại Cơ quan thực hiện');
        $sheet->setCellValue('G2', 'Cơ quan thực hiện');
        $sheet->setCellValue('H2', 'Tiến độ');
        $sheet->setCellValue('I2', 'Đánh giá tiến độ');
        $sheet->setCellValue('J2', 'Loại chu kỳ');
        $sheet->setCellValue('K2', 'Kết quả');

        // Định dạng hàng tiêu đề
        $sheet->getStyle('A2:K2')->applyFromArray([
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
        
        // Lấy dữ liệu từ cơ sở dữ liệu
        $datas = Document::query();
        $documents = $datas->with('issuingDepartment')->where('isDelete', 0)->orderBy('created_at', 'desc');

        $taskDocuments = TaskTarget::whereIn('document_id', $documents->pluck('id'))->where('isDelete', 0)->get();
        $row = 3; // Bắt đầu từ hàng thứ 4 để không ghi đè tiêu đề
    
        foreach ($taskDocuments as $index => $data) {
            $type = $data->latestTaskResult()->type ?? null;
            $numberType = $data->latestTaskResult()->number_type ?? '';

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->document->document_code);
            $sheet->setCellValue('C' . $row, $data->document->document_name);
            $sheet->setCellValue('D' . $row, $data->code);
            $sheet->setCellValue('E' . $row, $data->name);
            $sheet->setCellValue('F' . $row, $data->organization->organizationType->type_name);
            $sheet->setCellValue('G' . $row, $data->organization->name);
            $sheet->setCellValue('H' . $row, $data->results);
            $sheet->setCellValue('I' . $row, $data->getTaskStatusDescription());
            $sheet->setCellValue('J' . $row, $this->formatCycleType($type, $numberType));
            $sheet->setCellValue('K' . $row, $data->latestTaskResult()->result ?? '');
            $row++;
        }
        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Báo cáo tổng hợp chi tiết.xlsx';

        // Gửi file Excel cho người dùng
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    private function formatCycleType($type, $numberType)
    {
        switch ($type) {
            case 1: // Tuần
                return "Tuần $numberType";
            case 2: // Tháng
                return "Tháng $numberType";
            case 3: // Quý
                return "Quý $numberType";
            case 4: // Năm
                return "Năm $numberType";
            default:
                return '';
        }
    }


    public function dataReportDetails(Request $request, $text=null)
    {
 
        $executionTimeFrom = $request->input('execution_time_from');
        $executionTimeTo = $request->input('execution_time_to');
        $datas = Document::query()->where('isDelete', 0);
        $organizations = Organization::where('isDelete', 0)->whereNotNull('organization_type_id')->orderBy('name', 'asc')->get();
        $organizationsType = OrganizationType::where('isDelete', 0)->orderBy('type_name', 'asc')->get();

        if ($executionTimeFrom && $executionTimeTo) {
            try {
                $executionTimeFrom = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeFrom);
                $executionTimeTo = \Carbon\Carbon::createFromFormat('Y-m-d', $executionTimeTo);
                if ($executionTimeFrom->gt($executionTimeTo)) {
                    return redirect()->back()->withErrors([
                        'error' => "Thời gian từ ({$executionTimeFrom->format('d-m-Y')}) không được lớn hơn thời gian đến ({$executionTimeTo->format('d-m-Y')})."
                    ]);
                }
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                return redirect()->back()->withErrors(['error' => 'Định dạng thời gian không hợp lệ. Vui lòng nhập đúng định dạng ngày: dd-mm-yyyy.']);
            }
        }

        if ($request->filled('document_id')) {
            $datas->where('id', $request->document_id);
        }
      
        if ($executionTimeFrom) {
            $datas->whereDate('release_date', '>=', $executionTimeFrom);
        }

        if ($executionTimeTo) {
            $datas->whereDate('release_date', '<=', $executionTimeTo);
        }
        if($text){
            $datas->where('document_name', 'like', '%' . $text . '%');
        }
        // $datas = $datas->paginate(10);

        $documents = $datas->with('issuingDepartment')->where('isDelete', 0)->orderBy('created_at', 'desc');

        $taskDocuments = TaskTarget::whereIn('document_id', $documents->pluck('id'))->where('isDelete', 0);
        if ($request->filled('type') && $request->type !== 'null') {
            $taskDocuments->where('type', $request->type);
        }
        $taskDocuments = $taskDocuments->paginate(10);
        $documentsSearch = Document::where('isDelete', 0)->get();
        return [$datas, $organizationsType, $organizations, $taskDocuments, $documentsSearch];
    }
    public function showReportDetails(Request $request, $text=null)
    {
        $reportData = $this->dataReportDetails($request, $text);
        $datas = $reportData[0];
        $organizationsType = $reportData[1];
        $organizations = $reportData[2];
        $taskDocuments = $reportData[3];
        $documentsSearch = $reportData[4];  
     
        return view('reports.report_template_details', compact('datas', 'organizationsType', 'organizations', 'taskDocuments', 'documentsSearch'));
    }
    public function exportReportDetails(Request $request, $text=null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'BÁO CÁO TỔNG HỢP CHỈ TIÊU/ NHIỆM VỤ THEO ĐƠN VỊ TỪNG VĂN BẢN');
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1:K1')->applyFromArray([
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

        // Đặt tiêu đề cho các cột
        $sheet->setCellValue('A2', 'STT');
        $sheet->setCellValue('B2', 'Mã văn bản');
        $sheet->setCellValue('C2', 'Tên văn bản');
        $sheet->setCellValue('D2', 'Mã chỉ tiêu/ nhiệm vụ');
        $sheet->setCellValue('E2', 'Tên chỉ tiêu/ nhiệm vụ');
        $sheet->setCellValue('F2', 'Loại Cơ quan thực hiện');
        $sheet->setCellValue('G2', 'Cơ quan thực hiện');
        $sheet->setCellValue('H2', 'Tiến độ');
        $sheet->setCellValue('I2', 'Đánh giá tiến độ');
        $sheet->setCellValue('J2', 'Loại chu kỳ');
        $sheet->setCellValue('K2', 'Kết quả');

        // Định dạng hàng tiêu đề
        $sheet->getStyle('A2:K2')->applyFromArray([
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
        
        // Lấy dữ liệu từ cơ sở dữ liệu
        $reportData = $this->dataReportDetails($request, $text);
        $datas = $reportData[0];
        $taskDocuments = $reportData[3];
        $row = 3; // Bắt đầu từ hàng thứ 4 để không ghi đè tiêu đề
    
        foreach ($taskDocuments as $index => $data) {
            $type = $data->latestTaskResult()->type ?? null;
            $numberType = $data->latestTaskResult()->number_type ?? '';


            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $data->document->document_code);
            $sheet->setCellValue('C' . $row, $data->document->document_name);
            $sheet->setCellValue('D' . $row, $data->code);
            $sheet->setCellValue('E' . $row, $data->name);
            $sheet->setCellValue('F' . $row, $data->organization->organizationType->type_name);
            $sheet->setCellValue('G' . $row, $data->organization->name);
            $sheet->setCellValue('H' . $row, $data->results);
            $sheet->setCellValue('I' . $row, $data->getTaskStatusDescription());
            $sheet->setCellValue('J' . $row, $this->formatCycleType($type, $numberType));
            $sheet->setCellValue('K' . $row, $data->latestTaskResult()->result ?? '');
            $row++;
        }
        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Báo cáo tổng hợp theo văn bản.xlsx';

        // Gửi file Excel cho người dùng
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}

