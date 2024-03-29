<?php

namespace QingChen\Admin\Grid;

use think\Model as EloquentModel;
use think\model\Relation;
use think\Paginator;
use think\Collection;
use think\facade\Request;

class Model
{
    /**
     * Eloquent model instance of the grid model.
     *
     * @var EloquentModel
     */
    protected $model;

    /**
     * Array of queries of the eloquent model.
     *
     * @var array
     */
    protected $queries = [];

    /**
     * Sort parameters of the model.
     *
     * @var array
     */
    protected $sort;

    /**
     * @var array
     */
    protected $data = [];

    /*
     * 20 items per page as default.
     *
     * @var int
     */
    protected $perPage = 20;

    /**
     * Create a new grid model instance.
     *
     * @param EloquentModel $model
     */
    public function __construct(EloquentModel $model)
    {
        $this->model   = $model;

        $this->queries = new Collection();
    }

    /**
     * Get the eloquent model of the grid model.
     *
     * @return EloquentModel
     */
    public function eloquent()
    {
        return $this->model;
    }

    /**
     * Build.
     *
     * @return array
     */
    public function buildData()
    {
        if (empty($this->data)) {
            // $this->data = $this->get()->getCollection()->toArray();
            $this->data = $this->get();
        }

        return $this->data;
    }

    /**
     * Add conditions to grid model.
     *
     * @param array $conditions
     *
     * @return void
     */
    public function addConditions(array $conditions)
    {
        foreach ($conditions as $condition) {
            call_user_func_array([$this, key($condition)], current($condition));
        }
    }

    /**
     * Get table of the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->model->getTable();
    }

    protected function get()
    {
        if ($this->model instanceof Paginator) {
            return $this->model;
        }

        $this->setSort();
        $this->setPaginate();

        // $this->queries->push([
        //     'method'    => 'alias',
        //     'arguments' => ['a'],
        // ]);

        // $this->queries->push([
        //     'method'    => 'field',
        //     'arguments' => ['id,username,avatar,mobile,email'],
        // ]);

        $this->queries->push([
            'method'    => 'order',
            'arguments' => ['status', 'desc'],
        ]);

        $this->queries->push([
            'method'    => 'paginate',
            'arguments' => [1],
        ]);

        $this->queries->each(function ($query) {
            $this->model = call_user_func_array([$this->model, $query['method']], $query['arguments']);
        });

        return $this->model;
    }

    /**
     * Set the grid paginate.
     *
     * @return void
     */
    protected function setPaginate()
    {
        // $paginate = $this->findQueryByMethod('paginate')->first();
        //
        // $this->queries = $this->queries->reject(function ($query) {
        //     return $query['method'] == 'paginate';
        // });
        //
        // $this->queries->push([
        //     'method'    => 'paginate',
        //     'arguments' => is_null($paginate) ? [$this->perPage] : $paginate['arguments'],
        // ]);
    }

    /**
     * Find query by method name.
     *
     * @param $method
     *
     * @return static
     */
    protected function findQueryByMethod($method)
    {
        return $this->queries->filter(function ($query) use ($method) {
            return $query['method'] == $method;
        });
    }

    /**
     * Set the grid sort.
     *
     * @return void
     */
    protected function setSort()
    {
        // $this->sort = Input::get('_sort', []);
        // if (!is_array($this->sort)) {
        //     return;
        // }
        //
        // if (empty($this->sort['column']) || empty($this->sort['type'])) {
        //     return;
        // }
        //
        // if (str_contains($this->sort['column'], '.')) {
        //     $this->setRelationSort($this->sort['column']);
        // } else {
        //     $this->queries->push([
        //         'method'    => 'orderBy',
        //         'arguments' => [$this->sort['column'], $this->sort['type']],
        //     ]);
        // }
    }

    /**
     * Set relation sort.
     *
     * @param string $column
     *
     * @return void
     */
    protected function setRelationSort($column)
    {
        list($relationName, $relationColumn) = explode('.', $column);

        if ($this->queries->contains(function ($key, $query) use ($relationName) {
            return $query['method'] == 'with' && in_array($relationName, $query['arguments']);
        })) {
            $relation = $this->model->$relationName();

            $this->queries->push([
                'method'    => 'join',
                'arguments' => $this->joinParameters($relation),
            ]);

            $this->queries->push([
                'method'    => 'orderBy',
                'arguments' => [
                    $relation->getRelated()->getTable().'.'.$relationColumn,
                    $this->sort['type'],
                ],
            ]);
        }
    }

    /**
     * Build join parameters.
     *
     * @param Relation $relation
     *
     * @return array
     */
    protected function joinParameters(Relation $relation)
    {
        return [
            $relation->getRelated()->getTable(),
            $relation->getQualifiedParentKeyName(),
            '=',
            $relation->getForeignKey(),
        ];
    }

    public function __call($method, $arguments)
    {
        $this->queries->push([
            'method'    => $method,
            'arguments' => $arguments,
        ]);

        return $this;
    }

    public function __get($key)
    {
        $data = $this->buildData();

        if (array_key_exists($key, $data)) {
            return $data[$key];
        }
    }
}
