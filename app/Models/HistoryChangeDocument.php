<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}