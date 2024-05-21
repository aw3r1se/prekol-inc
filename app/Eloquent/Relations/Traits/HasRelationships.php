<?php

namespace App\Eloquent\Relations\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns as Vendor;

/** @mixin Model */
trait HasRelationships
{
    use Vendor\HasRelationships;

    public function hasOne($related, $foreignKey = null, $localKey = null): HasOne
    {
        $instance = $this->newRelatedInstance($related);

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $localKey = $localKey ?: $this->getKeyName();

        return $this->newHasOne($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey);
    }

    /**
     * Instantiate a new HasOne relationship.
     *
     * @param Builder $query
     * @param Model $parent
     * @param string  $foreignKey
     * @param string  $localKey
     * @return HasOne
     */
    protected function newHasOne(Builder $query, Model $parent, $foreignKey, $localKey): HasOne
    {
        return new HasOne($query, $parent, $foreignKey, $localKey);
    }
}
