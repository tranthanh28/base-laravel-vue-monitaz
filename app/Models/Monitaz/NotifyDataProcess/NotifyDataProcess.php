<?php

namespace App\Models\Monitaz\NotifyDataProcess;

use App\Models\App\AppModel;
use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotifyDataProcess extends AppModel
{
    protected $connection = 'mysql4';

    protected $fillable = [
        'uid',
        'list_objects',
        'role',
        'status',
        'is_confirm',
        'notice_by',
        'noticed_at',
        'confirm_at',
        'created_at',
    ];


    protected $table = "notify_data_process";

}
