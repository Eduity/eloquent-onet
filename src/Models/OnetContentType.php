<?php

namespace Eduity\EloquentOnet\Models;

use Eduity\EloquentOnet\Models\OnetContent;

abstract class OnetContentType extends OnetContent
{
    protected $table = 'onet_content_model_reference';
    protected $primaryKey = 'element_id';
    public $incrementing = false;

	/**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new \Eduity\EloquentOnet\Scopes\OnetContentTypeScope);
    }

    /** RELATIONSHIPS */

    /** SCOPES */

    /** ACCESSORS AND MUTATORS */
}
