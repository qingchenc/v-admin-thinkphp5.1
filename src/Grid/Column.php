<?php

namespace QingChen\Admin\Grid;

use think\Model as BaseModel;

use QingChen\Admin\Grid;
use QingChen\Admin\Grid\Field\Image;
use QingChen\Admin\Facades\Admin;

class Column
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * Name of column.
     *
     * @var string
     */
    protected $name;

    /**
     * Label of column.
     *
     * @var string
     */
    protected $label;

    /**
     * Label of column.
     *
     * @var string
     */
    protected $labelClass;

    /**
     * Sort of column.
     *
     * @var boolean
     */
    protected $sort;

    /**
     * Image of column.
     *
     * @var boolean
     */
    protected $image;

    /**
     * ImageObject of column.
     *
     * @var boolean
     */
    protected $imageTemplate;

    /**
     * relation
     * @var
     */
    protected $relation;

    /**
     * Display of column.
     *
     * @var boolean
     */
    protected $display;

    /**
     * PureText of column.
     *
     * @var boolean
     */
    protected $pureText;

    /**
     * Express of column.
     *
     * @var boolean
     */
    protected $express;

    /**
     * @var Model
     */
    protected static $model;

    /**
     * @param string $name
     * @param string $label
     */
    public function __construct($name, $label)
    {
        $this->name     = $name;

        $this->label    = $this->formatLabel($label);

        $this->sort     = false;

        $this->pureText = false;

        $this->express  = false;
    }

    /**
     * Get name of this column.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get label of the column.
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Format label.
     *
     * @param $label
     *
     * @return mixed
     */
    protected function formatLabel($label)
    {
        if ($label) {
            return $label;
        }

        $label = ucfirst($this->name);

        return str_replace(['.', '_'], ' ', $label);
    }

    /**
     * Set relation.
     *
     * @param $relation
     *
     * @return $this
     */
    public function setRelation($relation,$relationColumn)
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * If this column is relation column.
     *
     * @return bool
     */
    protected function isRelation()
    {
        return (bool) $this->relation;
    }

    public function sort($sort)
    {
        if(is_bool($sort)){
            $this->sort = $sort;
        }
    }

    public function label($class = "")
    {
        if(empty($class)){
            $class = "layui-bg-green";
        }

        $this->labelClass = "layui-badge ".$class;
    }

    public function image($width = "", $height = "")
    {
        $nameArray   = explode('.',$this->name);
        $imageName   = Admin::replaceField($this->name);
        $this->image = '#'.$imageName;

        $this->imageTemplate = new Image($nameArray[0],$nameArray[1],$imageName);
    }

    public function display($callValue)
    {
        $this->display = $callValue;
    }

    public function pureText()
    {
        $this->pureText = true;
    }

    public function express()
    {
        $this->express = true;
    }

    public function build()
    {
        $name  = $this->name;
        if($this->isRelation()){
            $name = Admin::replaceField($name);
        }

        $label = $this->label;

        $columnStart = "{";
        $columnEnd   = "},";

        $column = <<<COLUMN
            field:'$name', title: '$label',
COLUMN;

        if($this->sort){
            $column = $column. " sort: true,";
        }

        if($this->image){
            $column = $column. " templet: '$this->image',";
        }

        if($this->labelClass){
            $dataField = "+data.$name+";
            if($this->isRelation()){
                list($relationName, $relationColumn) = explode('.', $this->name);
                $dataField = "+data.$relationName.$relationColumn+";
            }
            $labelElement  = "<span class='$this->labelClass'>\"$dataField\"</span>";
            $labelTemplate = <<<LABEL
                templet: function(data){ return "$labelElement"},
LABEL;
            $column = $column.$labelTemplate;
        }

        if($this->display){
            if($this->pureText){
                $column = $column. " templet: function(data){ return '$this->display'},";
            }
            if($this->express){
                $column = $column. " templet: function(data){ return $this->display},";
            }
        }

        echo $columnStart.$column.$columnEnd;
    }

    public function __get($attr)
    {
        return $this->$attr;
    }
}