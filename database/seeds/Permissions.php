<?php

use think\migration\Seeder;

class Permissions extends Seeder
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
            ['id' => 1,'name' => 'administrator','slug' => '*','http_path' => '/admin/*','status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 2,'name' => 'admin','slug' => 'admin','http_path' => '/admin/*','status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
        ];
        $this->table('admin_permissions')->insert($data)->save();
    }
}