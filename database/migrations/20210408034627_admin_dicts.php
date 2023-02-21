<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminDicts extends Migrator
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
        $table = $this->table('admin_dicts',array('engine'=>'InnoDB','encoding' => 'utf8','collation' => 'utf8_general_ci','id' => 'id', 'comment' => '数据字典表'));
        $table
            ->addColumn('title', 'string',array('limit' => 190,'null' => false,'default' => 'name','comment'=>'字典分类中文名称'))
            ->addColumn('title_en', 'string',array('limit' => 190,'null' => true,'default' => null,'comment'=>'字典分类英文名称'))
            ->addColumn('slug', 'string',array('limit' => 190,'null' => false,'default' => 'name','comment'=>'字典分类名称标识'))
            ->addColumn('level', 'boolean',array('limit' => 1,'null' => false,'default' => 1,'comment'=>'字典分类级别，最高是1级'))
            ->addColumn('parent_id', 'integer',array('limit' => 11,'null' => false,'default' => 0,'comment'=>'父级id'))
            ->addColumn('sort', 'integer',array('limit' => 11,'null' => false,'default' => 1,'comment'=>'排序'))
            ->addColumn('icon', 'integer',array('limit' => 11,'null' => true,'comment'=>'图标id'))
            ->addColumn('desc', 'text',array('null' => true,'comment'=>'分类描述'))
            ->addColumn('status', 'boolean',array('limit' => 1,'null' => false,'default' => 1,'comment'=>'分类状态 1：正常 2：停用'))
            ->addColumn('delete_time', 'timestamp',array('null' => true,'comment'=>'删除时间'))
            ->addTimestamps()
            ->create();
    }
}
