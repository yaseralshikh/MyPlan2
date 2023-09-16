<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;


class DatabaseController extends Controller
{
    public function downloadDatabase()
    {
        Artisan::call('backup:run');
        $path = storage_path('app/MyPlan/*');
        $latest_ctime = 0;
        $latest_filename = '';
        $files = glob($path);
        foreach($files as $file)
        {
            if (is_file($file) && filectime($file) > $latest_ctime)
            {
                $latest_ctime = filectime($file);
                $latest_filename = $file;
            }
        }
        return response()->download($latest_filename);
    }

}
