<?php

namespace QingChen\Admin;

use Closure;
use think\View;
use think\Model as Eloquent;
use think\model\Relation;

use QingChen\Admin\Grid\Column;
use QingChen\Admin\Support\Arr;
use QingChen\Admin\Grid\Model;
use QingChen\Admin\Grid\Filter;

class Grid
{
    /**
     * grid表格所关联的model数据模型
     *
     * @var Model
     */
    protected $model;

    /**
     * grid表格列
     *
     * @var
     */
    protected $columns;

    /**
     * 图片字段的列
     *
     * @var
     */
    protected $imageColumns;

    /**
     * Collection of all data rows.
     */
    protected $rows;

    /**
     * Grid builder.
     */
    protected $builder;

    /**
     * The grid Filter.
     *
     * @var Filter
     */
    protected $filter;

    /**
     * view object
     */
    protected $view;

    /**
     * Default primary key name.
     */
    protected $keyName = 'id';

    /**
     * Default items count per-page.
     */
    public $perPage = 20;

    /**
     * table idName
     */
    public $tableID;

    /**
     * grid表格配置项
     *
     * @var array
     */
    protected $options = [
        'show_pagination'        => true,
        'show_filter'            => false,
        'show_exporter'          => true,
        'show_actions'           => true,
        'show_row_selector'      => true,
        'show_create_btn'        => true,
    ];

    /**
     * Create a new grid instance.
     *
     * Grid constructor.
     *
     * @param $model
     * @param Closure|null $builder
     */
    public function __construct(Eloquent $model, Closure $builder = null)
    {
        // 当前模型
        $this->model   = new Model($model);
        //获取模型主键
        $this->keyName = $model->getPk();
        $this->builder = $builder;

        $this->initialize();
    }

    /**
     * 初始化函数方法
     *
     * @return void
     */
    protected function initialize()
    {
        //设置table标签的类名
        $this->tableID = uniqid('grid-table');
        //表格的列
        $this->columns = [];
        //表格中图片的列
        $this->imageColumns = [];
        //表格的行
        $this->rows    = [];
        //初始化视图类
        $this->view    = new View();

        $this->setupFilter();
    }

    protected function setupFilter()
    {
        $this->filter = new Filter($this, $this->model());
    }

    public function title($title = null){
        if(is_null($title)){
            return config('table_page_title');
        }

        return $title;
    }

    /**
     * 禁用创建按钮
     *
     * @return $this
     */
    public function disableCreateBtn(){
        $this->option('show_create_btn',false);

        return $this;
    }

    /**
     * 禁用表格行中的操作按钮
     *
     * @return $this
     */
    public function disableActions(){
        $this->option('show_actions',false);

        return $this;
    }

    /**
     * 禁用分页
     *
     * @return $this
     */
    public function disablePagination(){
        $this->option('show_pagination',false);

        return $this;
    }

    /**
     * 禁用导出按钮功能
     *
     * @return $this
     */
    public function disableExport(){
        $this->option('show_exporter',false);

        return $this;
    }

    public function disableRowSelector(){
        $this->option('show_row_selector',false);

        return $this;
    }

    public function filter(callable $callback){
        $this->option('show_filter',true);
        call_user_func($callback,$this);

        return $this;
    }

    /**
     * 设置options选项
     *
     * @param $key
     * @param mixed  $value
     *
     * @return $this|mixed
     */
    public function option($key, $value = null)
    {
        if (is_null($value)) {
            return $this->options[$key];
        }

        $this->options[$key] = $value;

        return $this;
    }

    /**
     * 获取该模型的主键
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->keyName ?: 'id';
    }

    /**
     * 获取grid当前的模型
     *
     * @return mixed
     */
    public function model(){
        return $this->model;
    }

    /**
     * Add variables to grid view.
     *
     * @param array $variables
     *
     * @return $this
     */
    public function with($variables = [])
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * Get all variables will used in grid view.
     *
     * @return array
     */
    protected function variables()
    {
        $this->variables['grid'] = $this;

        return $this->variables;
    }

    /**
     * 添加一个列到grid中
     *
     * @param string $name
     * @param string $label
     * @return $this
     */
    public function column($name, $label = '')
    {
        $relationName = $relationColumn = '';
        if (strpos($name, '.') !== false) {
            list($relationName, $relationColumn) = explode('.', $name);

            $relation = $this->model()->eloquent()->$relationName();

            $label = empty($label) ? ucfirst($relationColumn) : $label;
        }

        $column = $this->addColumn($name, $label);

        if (isset($relation) && $relation instanceof Relation) {
            $this->model()->with($relationName);
            $this->setRelation($relation, $relationColumn);
        }

        return $column;
    }

    protected function addColumn($column = '', $label = '')
    {
        if(is_string($column)){
            if(!isset($this->columns[$column]) || empty($this->columns[$column])){
                $this->columns[] = new Column($column,$label);
            }
        }

        return $this;
    }

    public function sort()
    {
        $lastColumn = end($this->columns);

        $lastColumn->sort(true);

        return $this;
    }

    public function label($class = "")
    {
        $lastColumn = end($this->columns);

        $lastColumn->label($class);

        return $this;
    }

    public function image($width = "", $height="")
    {
        $lastColumn = end($this->columns);

        $lastColumn->image($width,$height);

        return $this;
    }

    public function display(callable $callback)
    {
        $lastColumn = end($this->columns);
        $callValue  = call_user_func($callback,$this);
        if($callValue){
            $lastColumn->display($callValue);
        }

        return $this;
    }

    public function pureText()
    {
        $lastColumn = end($this->columns);

        $lastColumn->pureText();

        return $this;
    }

    public function express()
    {
        $lastColumn = end($this->columns);

        $lastColumn->express();

        return $this;
    }

    public function setRelation($relation, $relationColumn)
    {
        $lastColumn = end($this->columns);

        $lastColumn->setRelation($relation, $relationColumn);

        return $this;
    }

    public function getImage($column): string
    {
        $image = "";

        if($column instanceof Column){
            $image = $column->imageTemplate->preview();
            $this->imageColumns[] = $image;
        }

        return $image;
    }

    public function getColumn(): string
    {
        $columnStart = "[";
        $columnEnd   = "]";
        $actions     = "{align:'center', fixed: 'right', toolbar: '#table-content-list',title: '操作'},";

        ob_start();

        foreach ($this->columns as $key => $val){
            if($val->image){
                $this->getImage($val);
            }
            $val->build();
        }

        $column = ob_get_contents();

        ob_end_clean();

        if($this->option('show_actions')){
            $columnEnd = $actions.$columnEnd;
        }

        return $columnStart.$column.$columnEnd;
    }

    public function getImageColumn(): string
    {
        return implode($this->imageColumns);
    }

    public function build()
    {
        return $this->filter->execute();
    }
}
