<?php


namespace App\Services;


use App\Models\MasterWorkResultType;

class MasterWorkResultTypeService
{
    public static function index()
    {
        return MasterWorkResultType::query()->get();
    }

    public static function keyConstants()
    {
        return ['BOOL', 'INT', 'DBL', 'PCT', 'TXT'];
    }
}
