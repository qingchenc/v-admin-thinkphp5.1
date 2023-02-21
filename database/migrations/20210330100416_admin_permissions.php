<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminPermissions extends Migrator
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
        $table = $this->table('admin_permissions',array('engine'=>'InnoDB','encoding' => 'utf8','collation' => 'utf8_general_ci','id' => 'id', 'comment' => '权限表'));
        $table
            ->addColumn('name', 'string',array('limit' => 190,'null' => false,'default' => null,'comment'=>'权限名称'))
            ->addColumn('slug', 'string',array('limit' => 50,'null' => false,'default' => null,'comment'=>'权限的唯一标识'))
            ->addColumn('http_method', 'string',array('limit' => 255,'null' => true,'default' => null,'comment'=>'权限允许请求方法'))
            ->addColumn('http_path', 'text',array('null' => true,'default' => null,'comment'=>'权限允许请求路由'))
            ->addColumn('status', 'boolean',array('limit' => 1,'null' => false,'default' => 1,'comment'=>'状态 1：正常 2：停用'))
            ->addTimestamps()
            ->addIndex(array('name'), array('unique' => true, 'name' => 'idx_name'))
            ->addIndex(array('slug'), array('unique' => true, 'name' => 'idx_slug'))
            ->create();
    }
}
