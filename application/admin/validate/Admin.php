<?php
namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'admin_username'  =>  'require|unique:admin',
        'admin_email'  =>  'email',
    ];

    protected $message  =   [
        'admin_username.require' => '请填写用户名',
        'admin_username.unique'  => '此用户名已存在，请重新输入',
        'admin_email.email'  => '请重新输入正确的邮箱',
    ];


}