<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PurchasesService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PurchasesService';
    }
}
