<?php

use think\migration\Seeder;

class Attachments extends Seeder
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
            ['id' => 1,'type' => 1,'size' => 68,'size_type' => 2,'name' => "test.jpg",'path' => 'static/images/test.jpg','create_time' => $nowDate,'update_time' => $nowDate],
        ];
        $this->table('admin_attachments')->insert($data)->save();
    }
}