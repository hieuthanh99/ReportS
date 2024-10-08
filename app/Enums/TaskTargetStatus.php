<?php


namespace App\Enums;

enum TaskTargetStatus: string
{

    case NEW = 'new';
    case COMPLETE = 'complete';
    case ASSIGN = 'assign';
    case REJECT = 'reject';
    case STAFF_COMPLETE = 'staff_complete';
    case ADMIN_APPROVES = 'admin_approves';
    case SUB_ADMIN_COMPLETE = 'sub_admin_complete';
    
    public function label(): string
    {
        return match($this) {
            self::NEW => 'Báo cáo chưa giao việc',
            self::COMPLETE => 'Admin đánh giá hoàn thành',
            self::ASSIGN => 'Báo cáo đã giao việc',
            self::REJECT => 'Sub-Admin từ chối kết quả',
            self::ADMIN_APPROVES => 'Admin duyệt kết quả',
            self::STAFF_COMPLETE => 'Nhân viên hoàn thành báo cáo',
            self::SUB_ADMIN_COMPLETE => 'Sub-Admin duyệt kết quả',
        };
    }
}
