<?php

namespace App\Classes; // <- important

use App\Models\User;
use Google\Cloud\BigQuery\BigQueryClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class MyHelper
{
    // your code

    static public function ghjgload_events34(){
        Log::channel('app_log')->info('run  load_events');

    }
}
