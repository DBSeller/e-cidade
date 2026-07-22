<?php

namespace App\Domain\Core\Models;

use Illuminate\Database\Eloquent\Model;

class BatchJob extends Model
{
    protected $fillable = [
        'classname'
    ];

    public function queuedJobs()
    {
        return $this->hasMany(QueuedJob::class, 'batch_id', 'id');
    }
}
