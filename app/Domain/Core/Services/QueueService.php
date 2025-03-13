<?php

namespace App\Domain\Core\Services;

use App\Domain\Core\Models\BatchJob;
use App\Domain\Core\Models\QueuedJob;

class QueueService
{
    private $batch;

    public function __construct($job)
    {
        $this->batch = BatchJob::create(['classname' => $job]);
    }

    public function next()
    {
        $queuedJob = (new QueuedJob)->batch()->associate($this->batch);
        $queuedJob->save();
        return $queuedJob;
    }

    public function terminate(QueuedJob $queuedJob)
    {
        $queuedJob->delete();
        return $this->batch->queuedJobs()->count();
    }
}
