<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdminAttachments extends Migrator
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
        $table = $this->table('admin_attachments',array('engine'=>'InnoDB','encoding' => 'utf8','collation' => 'utf8_general_ci','id' => 'id', 'comment' => '上传的文件表'));
        $table
            ->addColumn('type', 'boolean',array('limit' => 1,'null' => false,'default' => 1,'comment'=>'文件类型 1：图片 2：文件(.pdf .excel等) 3：视频 4：音频'))
            ->addColumn('size', 'integer',array('limit' => 11,'null' => false,'default' => 100,'comment'=>'文件大小'))
            ->addColumn('size_type', 'boolean',array('limit' => 1,'null' => false,'default' => 1,'comment'=>'文件大小单位 1:beta 2：KB 3：MB 4：GB 5：TB'))
            ->addColumn('name', 'string',array('limit' => 190,'null' => false,'default' => 'name','comment'=>'文件名称'))
            ->addColumn('path', 'string',array('limit' => 190,'null' => false,'default' => 'path','comment'=>'文件储存的路径'))
            ->addTimestamps()
            ->create();
    }
}
