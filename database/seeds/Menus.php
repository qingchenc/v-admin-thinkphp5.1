<?php

use think\migration\Seeder;

class Menus extends Seeder
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
            ['id' => 1,'parent_id' => 0,'sort' => 1,'title' => 'Admin','icon' => 'layui-icon-component','status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 2,'parent_id' => 1,'sort' => 2,'title' => '菜单管理','icon' => 'layui-icon-component','url' => '/admin/auth/menuIndex','status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 3,'parent_id' => 1,'sort' => 3,'title' => '角色管理','icon' => 'layui-icon-component','url' => '/admin/auth/roleIndex','status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 4,'parent_id' => 1,'sort' => 4,'title' => '权限管理','icon' => 'layui-icon-component','url' => '/admin/auth/permissionIndex','status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 5,'parent_id' => 1,'sort' => 5,'title' => '数据字典','icon' => 'layui-icon-component','url' => '/admin/auth/dictIndex','status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
            ['id' => 6,'parent_id' => 1,'sort' => 6,'title' => '用户管理','icon' => 'layui-icon-component','url' => '/admin/auth/userIndex','status' => 1,'create_time' => $nowDate,'update_time' => $nowDate],
        ];
        $this->table('admin_menus')->insert($data)->save();
    }
}