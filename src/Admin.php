<?php

namespace Encore\Admin;

use Closure;

/**
 * Class Admin.
 */
class Admin
{
    /**
     * The v-admin version.
     *
     * @var string
     */
    public $version = '1.0.0';
	
	public function getVersion(){
		return $this->version;
	}
}