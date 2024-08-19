<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Builder;

class CompanyIdScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if ($model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'company_id')) {
            $builder->where($model->getTable() . '.company_id', sessionDetails('company_id'));
        }
    }
}
