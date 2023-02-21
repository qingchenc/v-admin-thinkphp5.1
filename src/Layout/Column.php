<?php

namespace QingChen\Admin\Layout;

use QingChen\Admin\Support\Interfaces\Buildable;

class Column implements Buildable
{
    /**
     * Build column html.
     */
    public function build()
    {
        $this->startColumn();

        $this->endColumn();
    }

    /**
     * Start column.
     */
    protected function startColumn()
    {
        echo "<div>";
    }

    /**
     * End column.
     */
    protected function endColumn()
    {
        echo '</div>';
    }

}