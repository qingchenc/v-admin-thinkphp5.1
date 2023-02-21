<?php

use think\migration\Seeder;

class MenuRoles extends Seeder
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
            ['id' => 1,'role_id' => 1,'menu_id' => 1,'is_use' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 2,'role_id' => 1,'menu_id' => 2,'is_use' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 3,'role_id' => 1,'menu_id' => 3,'is_use' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 4,'role_id' => 1,'menu_id' => 4,'is_use' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 5,'role_id' => 1,'menu_id' => 5,'is_use' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 6,'role_id' => 1,'menu_id' => 6,'is_use' => 1,'create_time' => $nowDate,'update_time' => $nowDate]
        ];
        $this->table('admin_role_menus')->insert($data)->save();
    }
}