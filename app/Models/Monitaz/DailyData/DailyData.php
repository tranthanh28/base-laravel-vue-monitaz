<?php

namespace App\Models\Monitaz\DailyData;

use App\Models\App\AppModel;
use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyData extends AppModel
{
    protected $connection = 'mysql2';

    protected $fillable = [
        'id_fb_page',
        'name',
        'name_brand',
        'keyword',
        'status',
    ];


    protected $table = "daily_data";

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
