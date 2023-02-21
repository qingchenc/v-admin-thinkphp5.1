<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminRoleUsers extends Migrator
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
        $table = $this->table('admin_role_users',array('engine'=>'InnoDB','encoding' => 'utf8','collation' => 'utf8_general_ci','id' => 'id', 'comment' => '用户角色表'));
        $table
            ->addColumn('role_id', 'integer',array('limit' => 11,'null' => false,'default' => 1,'comment'=>'角色id'))
            ->addColumn('user_id', 'integer',array('limit' => 11,'null' => false,'default' => 1,'comment'=>'用户id'))
            ->addColumn('is_use', 'boolean',array('limit' => 1,'null' => false,'default' => 1,'comment'=>'该角色用户是否还在使用 1：使用 2：未使用'))
            ->addTimestamps()
            ->create();
    }
}
