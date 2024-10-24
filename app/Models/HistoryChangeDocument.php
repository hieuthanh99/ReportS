<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HistoryChangeDocument extends Model
{
    use HasFactory;

    protected $table = 'history_change_document';

    protected $fillable = [
        'mapping_id',
        'type_save',
        'result',
        'description',
        'number_cycle',
        'type_cycle',
        'update_date',
        'update_user'
    ];
    public function getEndDate()
    {
        return $this->update_date ? Carbon::parse($this->update_date)->format('d/m/Y') : '';
    }
    public static function getCycleTypes()
    {
        return [
            '1' => 'Chu kỳ tuần',
            '2' => 'Chu kỳ tháng',
            '3' => 'Chu kỳ quý',
            '4' => 'Chu kỳ năm'
        ];
    }

    // Phương thức để lấy giá trị cycle_type dưới dạng văn bản
    public function getCycleTypeTextAttribute()
    {
        $cycleTypes = self::getCycleTypes();
        return $cycleTypes[$this->type_cycle] ?? 'Không xác định';
    }
}