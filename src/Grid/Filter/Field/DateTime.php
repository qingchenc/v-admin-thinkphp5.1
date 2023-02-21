<?php

namespace QingChen\Admin\Grid\Filter\Field;

class DateTime
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;

        $this->prepare();
    }

    public function prepare()
    {

    }

    public function variables()
    {
        return [];
    }

    public function name()
    {
        return 'datetime';
    }
}
