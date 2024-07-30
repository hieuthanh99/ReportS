<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Criteria extends Model
{

    protected $table = 'criteria';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = ['code', 'name'];
}
