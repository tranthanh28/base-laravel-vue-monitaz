<?php


namespace App\Filters\App\Traits;


trait FacebookUidFilter
{
    public function facebook_uid($search = null)
    {
        $this->singleSearch($search, 'facebook_uid');
    }
}
