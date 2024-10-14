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
            self::COMPLETE => 'Hoàn thành',
            self::ASSIGN => 'Chưa báo cáo',
            self::REJECT => 'Bị từ chối',
            self::ADMIN_APPROVES => 'Đã phê duyệt',
            self::STAFF_COMPLETE => 'Chờ phê duyệt',
            self::SUB_ADMIN_COMPLETE => 'Chờ phê duyệt',
        };
    }
}
