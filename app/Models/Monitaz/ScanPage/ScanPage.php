<?php

namespace App\Models\Monitaz\ScanPage;

use App\Models\App\AppModel;
use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScanPage extends AppModel
{
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m',
        'updated_at' => 'datetime:Y-m-d H:m',
    ];
    protected $fillable = ['name', 'created_at', 'status', 'content_file','pass_day', 'file_name'];

    protected $table = "scan_pages";
}
