<?php

namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Freevital\Repository\Contracts\CriteriaContract;
use Freevital\Repository\Contracts\RepositoryContract;

class AppListCriteria implements CriteriaContract
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Apply criteria in query repository.
     *
     * @param Builder            $query
     * @param RepositoryContract $repository
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $query, RepositoryContract $repository): Builder
    {
        $query->with('board')->withMedia();

        if ($value = array_get($this->params, 'board_id')) {
            $query->byBoardId($value);
        }

        if ($value = array_get($this->params, 'type_id')) {
            $query->where('type_id', $value);
        }

        if ($value = array_get($this->params, 'active')) {
            $query->where('is_active', true);
        }

        return $query;
    }
}
