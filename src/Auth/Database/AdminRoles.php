<?php

namespace QingChen\Admin\Auth\Database;

use think\Model;

class AdminRoles extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table;
    // 自动写入时间戳
    protected $autoWriteTimestamp;
    protected $dateFormat;
    // 构造函数
    public function __construct($data = []){
        parent::__construct($data);
        $this->table      = config('admin.database.roles_table');
        $this->dateFormat = config('admin.date_format');
        $this->autoWriteTimestamp = config('admin.auto_write_timestamp');
    }

    public function permission(){
        return $this->belongsToMany("AdminPermissions","AdminRolePermissions","permission_id","role_id");
    }

    public function menus(){
        return $this->belongsToMany("AdminMenus","AdminRoleMenus","menu_id","role_id");
    }
}
