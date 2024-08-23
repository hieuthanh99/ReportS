<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskApprovalHistory extends Model
{
   // Bảng tương ứng với model này
   protected $table = 'task_approval_history';

   // Các thuộc tính có thể được gán hàng loạt
   protected $fillable = [
       'task_target_id',
       'approver_id',
       'status',
       'remarks',
       'type',
       'number_type',
       'task_result_id'
   ];
}
