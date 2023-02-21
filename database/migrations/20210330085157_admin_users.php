<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminUsers extends Migrator
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
        $table = $this->table('admin_users',array('engine'=>'InnoDB','encoding' => 'utf8','collation' => 'utf8_general_ci','id' => 'id', 'comment' => '后台用户表'));
        $table
            ->addColumn('username', 'string',array('limit' => 190,'null' => false,'default' => '用户','comment'=>'用户名称'))
            ->addColumn('password', 'string',array('limit' => 190,'null' => false,'default' => '123456','comment'=>'用户密码,加密之后的'))
            ->addColumn('email', 'string',array('limit' => 190,'null' => true,'default' => null,'comment'=>'用户邮箱地址'))
            ->addColumn('mobile', 'string',array('limit' => 190,'null' => true,'default' => null,'comment'=>'用户手机号'))
            ->addColumn('avatar', 'integer',array('limit' => 11,'null' => true,'default' => null,'comment'=>'用户头像地址'))
            ->addColumn('status', 'boolean',array('limit' => 1,'null' => false,'default' => 1,'comment'=>'用户状态 1：正常 2：停用'))
            ->addTimestamps()
            ->create();
    }

    /**
     * Migrate Up.
     */
    public function up()
    {

    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
