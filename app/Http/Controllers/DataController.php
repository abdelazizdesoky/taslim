<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\MigrateMSSQLDataJob;

class DataController extends Controller
{
    public function migrateData()
    {
        // استدعاء الـ Job لتنفيذه
        MigrateMSSQLDataJob::dispatch();

        return "Job has been dispatched to migrate data!";
    }
}
