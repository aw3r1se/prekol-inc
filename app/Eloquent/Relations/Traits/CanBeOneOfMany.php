<?php

namespace App\Eloquent\Relations\Traits;

use App\Eloquent\Relations\HasOne;
use Closure;
use Illuminate\Database\Eloquent\Relations\Concerns as Vendor;
use InvalidArgumentException;

/** @mixin HasOne */
trait CanBeOneOfMany
{
    use Vendor\CanBeOneOfMany;

    public function ofMany($column = 'id', $aggregate = 'MAX', $relation = null): static
    {
        $this->isOneOfMany = true;

        $this->relationName = $relation ?: $this->getDefaultOneOfManyJoinAlias(
            $this->guessRelationship()
        );

        $keyName = $this->query->getModel()->getKeyName();

        $columns = is_string($columns = $column) ? [
            $column => $aggregate,
            $keyName => $aggregate,
        ] : $column;

        if (! array_key_exists($keyName, $columns)) {
            $columns[$keyName] = 'MAX';
        }

        if ($keyName == 'uuid') {
            unset($columns[$keyName]);
        }

        if ($aggregate instanceof Closure) {
            $closure = $aggregate;
        }

        foreach ($columns as $column => $aggregate) {
            if (! in_array(strtolower($aggregate), ['min', 'max'])) {
                throw new InvalidArgumentException("Invalid aggregate [{$aggregate}] used within ofMany relation. Available aggregates: MIN, MAX");
            }

            $subQuery = $this->newOneOfManySubQuery(
                $this->getOneOfManySubQuerySelectColumns(),
                array_merge([$column], $previous['columns'] ?? []),
                $aggregate,
            );

            if (isset($previous)) {
                $this->addOneOfManyJoinSubQuery(
                    $subQuery,
                    $previous['subQuery'],
                    $previous['columns'],
                );
            }

            if (isset($closure)) {
                $closure($subQuery);
            }

            if (! isset($previous)) {
                $this->oneOfManySubQuery = $subQuery;
            }

            if (array_key_last($columns) == $column) {
                $this->addOneOfManyJoinSubQuery(
                    $this->query,
                    $subQuery,
                    array_merge([$column], $previous['columns'] ?? []),
                );
            }

            $previous = [
                'subQuery' => $subQuery,
                'columns' => array_merge([$column], $previous['columns'] ?? []),
            ];
        }

        $this->addConstraints();

        $columns = $this->query->getQuery()->columns;

        if (is_null($columns) || $columns === ['*']) {
            $this->select([$this->qualifyColumn('*')]);
        }

        return $this;
    }
}
