<?php

use think\migration\Seeder;

class Roles extends Seeder
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
            ['id' => 1,'name' => '超级管理员','slug' => 'administrator','status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 2,'name' => '管理员','slug' => 'admin','status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
        ];
        $this->table('admin_roles')->insert($data)->save();
    }
}