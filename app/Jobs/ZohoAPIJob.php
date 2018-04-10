<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\CustomClasses\ZohoRequests;

class ZohoAPIJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '900M');
        ini_set('max_execution_time', 100000);
        $zohoRequest = new ZohoRequests();

        $zohoRequest->updateProjectsTable();
        $zohoRequest->updateUsersTable();
        $zohoRequest->updateTasksTable();
        $zohoRequest->updateProjectUserTable();
        //$zohoRequest->updateTaskUserTable();

        $this->delete();
    }
}
