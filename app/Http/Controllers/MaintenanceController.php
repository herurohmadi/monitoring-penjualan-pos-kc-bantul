<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MaintenanceController extends Controller
{
    public function maintenance()
    {
        Artisan::call('optimize:clear');

        // Hapus file log Laravel
        $logPath = storage_path('logs');

        foreach (glob($logPath . '/*.log') as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        return view('pages.maintenance.maintenance');
    }
}
