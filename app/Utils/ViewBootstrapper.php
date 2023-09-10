<?php

namespace App\Utils;

use View;
use Exception;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Contracts\Tenant;
use Stancl\Tenancy\Contracts\TenancyBootstrapper;

class ViewBootstrapper implements TenancyBootstrapper
{
    public function bootstrap(Tenant $tenant)
    {
        try {

            $generalSettings = DB::table('general_settings')->first();
            $addons = DB::table('addons')->first();
            // $warehouseCount = DB::table('warehouses')->count();

            $dateFormat = json_decode($generalSettings->business, true)['date_format'];
            $__date_format = str_replace('-', '/', $dateFormat);

            if (isset($generalSettings) && isset($addons)) {

                view()->share('generalSettings', $generalSettings);
                view()->share('addons', $addons);
                // view()->share('warehouseCount', $warehouseCount);
                view()->share('__date_format', $__date_format);
            }
            // Write your logic here.
            View::share('generalSettings', $generalSettings);
            View::share('addons', $addons);
            View::share('dateFormat', $dateFormat);
            View::share('__date_format', $__date_format);
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function revert()
    {
        // Optional, but recommended:
        // Write you logic here that reverts the actions.
    }
}