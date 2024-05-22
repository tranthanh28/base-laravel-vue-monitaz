<?php


namespace App\Filters\App\Monitaz\TNS;


use App\Filters\App\Traits\DateRangeFilter;
use App\Filters\App\Traits\SearchFilter;
use App\Filters\App\Traits\StatusFilter;
use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class TNSFilter extends FilterBuilder
{
    use SearchFilter;
}
