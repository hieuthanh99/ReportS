<?php


namespace App\Enums;

enum TaskStatus: string
{
    case NOT_COMPLETED = 'not_completed';
    case IN_PROGRESS_IN_TIME = 'in_progress_in_time';
    case IN_PROGRESS_OVERDUE = 'in_progress_overdue';
    case COMPLETED_IN_TIME = 'completed_in_time';
    case COMPLETED_OVERDUE = 'completed_overdue';
    
    public function label(): string
    {
        return match($this) {
            self::NOT_COMPLETED => 'Chưa hoàn thành',
            self::IN_PROGRESS_IN_TIME => 'Đang thực hiện - Trong hạn',
            self::IN_PROGRESS_OVERDUE => 'Đang thực hiện - Quá hạn',
            self::COMPLETED_IN_TIME => 'Hoàn thành - Đúng hạn',
            self::COMPLETED_OVERDUE => 'Hoàn thành - Quá hạn',
        };
    }
}
