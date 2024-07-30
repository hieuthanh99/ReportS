<?php
// app/Models/TaskDocument.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDocument extends Model
{
    // Bảng tương ứng với model này
    protected $table = 'tasks_document';

    // Các thuộc tính có thể được gán hàng loạt
    protected $fillable = [
        'document_id',
        'task_code',
        'task_name',
        'reporting_cycle',
        'category',
        'required_result',
        'start_date',
        'end_date',
        'creator',
        'status',
    ];

    // Định nghĩa mối quan hệ với Document
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
