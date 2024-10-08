<?php


namespace App\Enums;

enum TaskTargetStatusChange: string
{

    case NEW = 'new';
    case COMPLETE = 'complete';
    case PROCESSING = 'processing';
    
    public function label(): string
    {
        return match($this) {
            self::NEW => 'Báo cáo chưa giao việc',
            self::COMPLETE => 'Báo cáo hoàn thành',
            self::PROCESSING => 'Báo cáo đang xử lý',
        };
    }
}
