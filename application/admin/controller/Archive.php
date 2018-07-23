<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\ArchiveModel;
class Archive extends Controller
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

            $archive = new ArchiveModel();
            $selectResult = $archive->getArchivesByWhere($where, $offset, $limit);
            foreach($selectResult as $key=>$vo){
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $archive->getAllArchives($where);  // 总数据
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
                case 'classify':
                    $organization_id=input('post.organization_id');
                    $data = model('ClassModel')->getClassifiesByOrganizationId($organization_id);
                    break;
                case 'cabinet':
                    $room_id = input('post.room_id');
                    $data = model('CabinetModel')->getCabinetsByRoomId($room_id);
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
            $archive = new ArchiveModel();
            $flag = $archive->addArchive($param);

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
        $archive = new ArchiveModel();
        $id = input('param.id');
        $this->assign([
            'archive' => $archive->getOneArchive($id)
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
        $archive = new ArchiveModel();
        if(request()->isPost()){
            $param = input('post.');
            $flag = $archive->editArchive($param,$id);
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
        $archive = new ArchiveModel();
        $flag = $archive->delArchive($id);
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
                'auth' => 'archive/edit',
                'href' => url('archive/edit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'archive/delete',
                'href' => "javascript:articleDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
