<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\CabinetModel;
class Cabinet extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['name'] = ['like', '%' . $param['searchText'] . '%'];
            }

            $cabinet = new CabinetModel();
            $selectResult = $cabinet->getCabinetsByWhere($where, $offset, $limit);
            foreach($selectResult as $key=>$vo){
                //$selectResult[$key]['thumbnail'] = '<img src="' . $vo['thumbnail'] . '" width="40px" height="40px">';
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $cabinet->getAllCabinets($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        return $this->fetch();
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //城市三级联动
        if(request()->isPost())
        {
            $type=input('post.type');
            switch($type){
                case 'organization':
                    $data = model('OrganizationModel')->getOrganizations();
                    break;
                case 'room':
                    $organization_id=input('post.organization_id');
                    $data = model('RoomModel')->getRoomsByOrganizationId($organization_id);
                    break;
                default:
                    break;
            }
            return json($data);
        }
        return $this->fetch();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        if(request()->isPost()){
            $param = input('post.');
            $cabinet = new CabinetModel();
            $flag = $cabinet->addCabinet($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $cabinet = new CabinetModel();
        $id = input('param.id');
        $this->assign([
            'cabinet' => $cabinet->getOneCabinet($id)
        ]);
        return $this->fetch();
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $cabinet = new CabinetModel();
        if(request()->isPost()){
            $param = input('post.');
            $flag = $cabinet->editCabinet($param,$id);
            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $id = input('param.id');
        $cabinet = new CabinetModel();
        $flag = $cabinet->delCabinet($id);
        return json(msg($flag['code'], $flag['data'], $flag['msg']));
    }
    /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id)
    {
        return [
            '编辑' => [
                'auth' => 'cabinet/edit',
                'href' => url('cabinet/edit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'cabinet/delete',
                'href' => "javascript:articleDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
