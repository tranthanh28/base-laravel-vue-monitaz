<?php

namespace App\Models\Monitaz\ReportProgress;

use App\Models\App\AppModel;
use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportProgress extends AppModel
{
    protected $connection = 'mysql2';

    protected $fillable = [
        'name',
        'file_path',
        'report_type',
        'status',
        'created_at',
    ];


    protected $table = "report_progress";

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  $date
     * @return string
     */
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
