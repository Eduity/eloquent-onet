<?php

namespace Eduity\EloquentOnet\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OnetContentTypeScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        return $builder->where('onet_content_model_reference.element_id', 'LIKE', $this->getModelRange($model));
    }

    protected function getModelRange(Model $model)
    {
        if(!$model->content_id_range) {
            throw new \Exception('Must set $content_id_range on "' . class_basename($model) . '", an OnetContentType model.');
        }

        return str_pad($model->content_id_range, 9, '.%');
    }
}
