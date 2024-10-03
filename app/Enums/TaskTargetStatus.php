<?php


namespace App\Enums;

enum TaskTargetStatus: string
{

    case NEW = 'new';
    case COMPLETE = 'complete';
    case ASSIGN = 'assign';
    case REJECT = 'reject';
    case STAFF_COMPLETE = 'staff_complete';
    case SUB_ADMIN_COMPLETE = 'sub_admin_complete';
    
    public function label(): string
    {
        return match($this) {
            self::NEW => 'Đơn mới',
            self::COMPLETE => 'Hoàn thành đánh giá',
            self::ASSIGN => 'Báo cáo đã giao việc',
            self::REJECT => 'Báo cáo bị từ chối',
            self::STAFF_COMPLETE => 'Nhân viên hoàn thành đánh giá',
            self::SUB_ADMIN_COMPLETE => 'Sub-Admin hoàn thành đánh giá',
        };
    }
}
