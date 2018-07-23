<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\validate;

use think\Validate;

class ArchiveValidate extends Validate
{
    protected $rule = [
        ['name', 'require', '档案名称不能为空'],
        ['class_id', 'require', '请选择分类'],
        ['cabinet_id', 'require', '请选择档案柜']
    ];

}