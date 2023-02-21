<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminMenus extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        // create the table
        $table = $this->table('admin_menus',array('engine'=>'InnoDB','encoding' => 'utf8','collation' => 'utf8_general_ci','id' => 'id', 'comment' => '菜单表'));
        $table
            ->addColumn('parent_id', 'integer',array('limit' => 11,'null' => false,'default' => 1,'comment'=>'父级id'))
            ->addColumn('sort', 'integer',array('limit' => 11,'null' => false,'default' => 1,'comment'=>'排序'))
            ->addColumn('title', 'string',array('limit' => 50,'null' => false,'comment'=>'菜单名称'))
            ->addColumn('icon', 'string',array('limit' => 50,'null' => false,'comment'=>'菜单对应的图标'))
            ->addColumn('url', 'string',array('limit' => 255,'null' => true,'comment'=>'菜单对应的路由'))
            ->addColumn('status', 'boolean',array('limit' => 1,'null' => false,'default' => 1,'comment'=>'状态 1：正常 2：停用'))
            ->addTimestamps()
            ->create();
    }
}
