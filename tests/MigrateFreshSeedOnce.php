<?php
namespace Tests;
use Illuminate\Support\Facades\Artisan;
use App\Models\Employee;
use App\Models\City;
use App\Models\Province;
use App\Models\Dropdown;

trait MigrateFreshSeedOnce
{
    /**
    * If true, setup has run at least once.
    * @var boolean
    */
    protected static $setUpHasRunOnce = false;
    /**
    * After the first run of setUp "migrate:fresh --seed"
    * @return void
    */
    public function setUp() : void
    {
        parent::setUp();

        if (!static::$setUpHasRunOnce) {

            /** Isi collection dengan data default diambil dari seeder*/
            Artisan::call(
                'db:seed', [
                    '--class' => ''
                ]
            );

            static::$setUpHasRunOnce = true; 
         }
    }
}