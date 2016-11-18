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
        $ranges = $this->getModelRange($model);

        $builder->where(function ($query) use ($model, $ranges) {
            foreach($ranges as $range) {
                $query->orWhere('onet_content_model_reference.element_id', 'LIKE', $range);
            }
        });

        return $builder;
    }

    protected function getModelRange(Model $model)
    {
        if(!$model->content_id_range) {
            throw new \Exception('Must set $content_id_range on "' . class_basename($model) . '", an OnetContentType model.');
        }

        $ids = $model->content_id_range;

        if(!is_array($model->content_id_range)) {
            $ids = [$model->content_id_range];
        }

        foreach($ids as $key => $id) {
            $ids[$key] .= '.%';
        }

        return $ids;
    }
}
