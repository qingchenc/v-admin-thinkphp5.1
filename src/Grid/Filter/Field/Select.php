<?php

namespace QingChen\Admin\Grid\Filter\Field;

class Select
{
    protected $options = ['' => '请选择'];

    public function __construct($options)
    {

    }

    public function variables()
    {

    }

    public function name()
    {
        return 'select';
    }
}
