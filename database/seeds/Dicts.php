<?php

use think\migration\Seeder;

class Dicts extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $nowDate = date("Y-m-d H:i:s",time());
        $data    = [
            ['id' => 1,'title' => '测试','title_en' => '测试','slug' => 'test','level' => 1,'parent_id' => 0,'sort' => 1,'status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 2,'title' => '测试1','title_en' => '测试1','slug' => 'test1','level' => 2,'parent_id' => 1,'sort' => 1,'status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
        ];
        $this->table('admin_dicts')->insert($data)->save();
    }
}