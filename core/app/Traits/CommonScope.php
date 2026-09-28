<?php

namespace App\Traits;

use App\Constants\Status;

trait CommonScope
{
    public function scopeAsc($query, $column = 'id')
    {
        return $query->orderBy($column, 'ASC');
    }
    public function scopeDesc($query, $column = 'id')
    {
        return $query->orderBy($column, 'DESC');
    }
    public function scopeEnabled($query)
    {
        return $query->where('status', Status::ENABLE);
    }
    public function scopeDisabled($query)
    {
        return $query->where('status', Status::DISABLE);
    }
}
