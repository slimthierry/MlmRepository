<?php

namespace App\Abstracts;

use Illuminate\Database\Eloquent\Model as Eloquent;


abstract class Model extends Eloquent
{


    protected $dates = ['created_at'];

    /**
     * Scope to only include active models.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    /**
     * Scope to only include passive models.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisabled($query)
    {
        return $query->where('enabled', 0);
    }

    public function scopeAccount($query, $accounts)
    {
        if (empty($accounts)) {
            return $query;
        }

        return $query->whereIn('id', (array) $accounts);
    }
}
