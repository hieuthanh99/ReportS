<?php

namespace App\Http\Controllers;

use App\Models\Document;
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
    public function showReportDocument()
    {
        $datas = Document::paginate(10);
        foreach ($datas as $data) {
            $data->task_count = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->count();
            $data->task_completed_in_time = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_in_time')
            ->count();
            $data->task_completed_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->task_completed_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->task_in_progress_in_time = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'in_progress_in_time')
            ->count();
            $data->task_in_progress_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'in_progress_overdue')
            ->count();

            $data->target_count = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->count();
            $data->target_completed_in_time = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_in_time')
            ->count();
            $data->target_completed_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->target_completed_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->target_in_progress_in_time = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'in_progress_in_time')
            ->count();
            $data->target_in_progress_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'in_progress_overdue')
            ->count();
        }
        return view('reports.report_template_document', compact('datas'));
    }

    public function exportDocument()
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
        
        // Lấy dữ liệu từ cơ sở dữ liệu
        $datas = Document::paginate(10);
        foreach ($datas as $data) {
            $data->task_count = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->count();
            $data->task_completed_in_time = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_in_time')
            ->count();
            $data->task_completed_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->task_completed_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->task_in_progress_in_time = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'in_progress_in_time')
            ->count();
            $data->task_in_progress_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'in_progress_overdue')
            ->count();

            $data->target_count = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->count();
            $data->target_completed_in_time = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_in_time')
            ->count();
            $data->target_completed_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->target_completed_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->target_in_progress_in_time = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'in_progress_in_time')
            ->count();
            $data->target_in_progress_overdue = TaskTarget::where('document_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'in_progress_overdue')
            ->count();
        }
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
        $filename = 'reportDocument.xlsx';

        // Gửi file Excel cho người dùng
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function showReportUnit()
    {
        // Lấy dữ liệu từ database hoặc từ một nguồn khác
        $datas = Organization::paginate(10);
        foreach ($datas as $data) {
            $data->task_count = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Task')
            ->count();
            $data->task_completed_in_time = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_in_time')
            ->count();
            $data->task_completed_overdue = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->task_completed_overdue = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->task_in_progress_in_time = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'in_progress_in_time')
            ->count();
            $data->task_in_progress_overdue = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'in_progress_overdue')
            ->count();

            $data->target_count = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Target')
            ->count();
            $data->target_completed_in_time = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_in_time')
            ->count();
            $data->target_completed_overdue = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->target_completed_overdue = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->target_in_progress_in_time = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'in_progress_in_time')
            ->count();
            $data->target_in_progress_overdue = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'in_progress_overdue')
            ->count();
        }

        // Truyền dữ liệu tới view
        return view('reports.report_template_unit', compact('datas'));
    }

    public function exportUnit()
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
        
        // Lấy dữ liệu từ cơ sở dữ liệu
        $datas = Organization::paginate(10);
        foreach ($datas as $data) {
            $data->task_count = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Task')
            ->count();
            $data->task_completed_in_time = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_in_time')
            ->count();
            $data->task_completed_overdue = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->task_completed_overdue = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->task_in_progress_in_time = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'in_progress_in_time')
            ->count();
            $data->task_in_progress_overdue = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Task')
            ->where('status_code', 'in_progress_overdue')
            ->count();

            $data->target_count = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Target')
            ->count();
            $data->target_completed_in_time = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_in_time')
            ->count();
            $data->target_completed_overdue = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->target_completed_overdue = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'completed_overdue')
            ->count();
            $data->target_in_progress_in_time = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'in_progress_in_time')
            ->count();
            $data->target_in_progress_overdue = TaskTarget::where('organization_id', $data->id)
            ->where('type', 'Target')
            ->where('status_code', 'in_progress_overdue')
            ->count();
        }
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
        $filename = 'reportUnit.xlsx';

        // Gửi file Excel cho người dùng
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function showReportPeriod()
    {
        // Lấy dữ liệu từ database hoặc từ một nguồn khác
        $data = [
                ['date' => '2024-08-01', 'amount' => 150],
                ['date' => '2024-08-02', 'amount' => 200],
                ['date' => '2024-08-03', 'amount' => 250],
    
        ];

        // Truyền dữ liệu tới view
        return view('reports.report_template_preriod', compact('data'));
    }

    public function showReportDetails()
    {
        // Lấy dữ liệu từ database hoặc từ một nguồn khác
        $data = [
                ['date' => '2024-08-01', 'amount' => 150],
                ['date' => '2024-08-02', 'amount' => 200],
                ['date' => '2024-08-03', 'amount' => 250],
    
        ];

        // Truyền dữ liệu tới view
        return view('reports.report_template_details', compact('data'));
    }
    
    
}

