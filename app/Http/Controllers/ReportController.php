<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function showReportDocument()
    {
        // Lấy dữ liệu từ database hoặc từ một nguồn khác
        $data = [
                ['date' => '2024-08-01', 'amount' => 150],
                ['date' => '2024-08-02', 'amount' => 200],
                ['date' => '2024-08-03', 'amount' => 250],
    
        ];

        // Truyền dữ liệu tới view
        return view('reports.report_template_document', compact('data'));
    }

    public function showReportUnit()
    {
        // Lấy dữ liệu từ database hoặc từ một nguồn khác
        $data = [
                ['date' => '2024-08-01', 'amount' => 150],
                ['date' => '2024-08-02', 'amount' => 200],
                ['date' => '2024-08-03', 'amount' => 250],
    
        ];

        // Truyền dữ liệu tới view
        return view('reports.report_template_unit', compact('data'));
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

