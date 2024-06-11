<?php

namespace App\Models\Monitaz\NotifyDataAlert;

use App\Models\App\AppModel;
use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotifyDataAlert extends AppModel
{
    protected $connection = 'mysql3';

    protected $fillable = [
        'username',
        'online',
        'quantity_is_check',
        'quantity_non_check',
        'is_confirm',
        'created_at',
    ];


    protected $table = "notify_data_alerts";

}
