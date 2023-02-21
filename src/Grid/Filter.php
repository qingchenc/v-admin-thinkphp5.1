<?php

namespace QingChen\Admin\Grid;

use ReflectionClass;

use QingChen\Admin\Grid;
use QingChen\Admin\Grid\Filter\AbstractFilter;

/**
 * Class Filter.
 *
 * @method Filter     is($column, $label = '')
 * @method Filter     like($column, $label = '')
 * @method Filter     gt($column, $label = '')
 * @method Filter     lt($column, $label = '')
 * @method Filter     between($column, $label = '')
 */
class Filter
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var
     */
    protected $model;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $allows = ['is', 'like', 'gt', 'lt', 'between'];

    /**
     * Create a new filter instance.
     *
     * @param Grid  $grid
     * @param Model $model
     */
    public function __construct(Grid $grid, Model $model)
    {
        $this->grid  = $grid;

        $this->model = $model;

        // $this->is($this->model->eloquent()->getPk());
    }

    /**
     * Get all conditions of the filters.
     *
     * @return array
     */
    // public function conditions()
    // {
    //     $inputs = array_filter(Input::all(), function ($input) {
    //         return $input !== '';
    //     });
    //
    //     $conditions = [];
    //
    //     foreach ($this->filters() as $filter) {
    //         $conditions[] = $filter->condition($inputs);
    //     }
    //
    //     return array_filter($conditions);
    // }

    /**
     * Execute the filter with conditions.
     *
     * @return array
     */
    public function execute()
    {
        // $this->model->addConditions($this->conditions());

        return $this->model->buildData();
    }
}