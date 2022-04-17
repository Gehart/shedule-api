<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HealthCheckController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Request $request
     * @return string
     */
    public function check(Request $request): string
    {
//        phpinfo();
        $a = 4;
        $b = $a;
        var_dump($b);
//        return ["blabla"];
//        return sprintf('xDebug does%s exists.', extension_loaded('xdebug') ? '' : "n't");
         $connection = pg_connect("host=postgres port=5432 dbname=postgres user=user password=password");

        if ($connection) {
            return 'connected to postgresql';
        } else {
            return 'there has been an error connecting';
        }
    }
}
