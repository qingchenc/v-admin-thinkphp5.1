<?php

namespace QingChen\Admin\Auth\Database;

use think\Model;
use think\model\relation\HasOne;
use think\model\relation\BelongsToMany;

class AdminUsers extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table;
    // 自动写入时间戳
    protected $autoWriteTimestamp;
    protected $dateFormat;
    // 构造函数
    public function __construct($data = []){
        parent::__construct($data);
        $this->table      = config('admin.database.users_table');
        $this->dateFormat = config('admin.date_format');
        $this->autoWriteTimestamp = config('admin.auto_write_timestamp');
    }

    /**
     * A user has to one avatar.
     *
     * @return HasOne
     */
    public function avatars(){
        return $this->hasOne("Attachments",'id','avatar')->field('id,path,type');
    }

    /**
     * 用户与角色之间的多对多关联
     * belongsToMany('关联模型名','中间表名','关联模型在中间表中的外键名','当前模型在中间表中的关联键名');
     *
     * @return BelongsToMany
     */
    public function roles(){
        return $this->belongsToMany("AdminRoles","AdminRoleUsers","role_id","user_id");
    }
}
