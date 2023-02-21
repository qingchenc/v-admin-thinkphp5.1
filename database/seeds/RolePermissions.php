<?php

use think\migration\Seeder;

class RolePermissions extends Seeder
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
            ['id' => 1,'role_id' => 1,'permission_id' => 1,'is_use' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 2,'role_id' => 2,'permission_id' => 2,'is_use' => 1,'create_time' => $nowDate,'update_time' => $nowDate]
        ];
        $this->table('admin_role_permissions')->insert($data)->save();
    }
}