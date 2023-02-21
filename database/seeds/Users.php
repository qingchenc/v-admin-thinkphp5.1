<?php

use think\migration\Seeder;

class Users extends Seeder
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
            ['id' => 1,'username' => 'administrator','password' => 'e10adc3949ba59abbe56e057f20f883e','email' => '12002992643@qq.com','mobile' => '13002992657','status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
        ];
        $this->table('admin_users')->insert($data)->save();
    }
}