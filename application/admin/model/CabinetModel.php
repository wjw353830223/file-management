<?php

namespace app\admin\model;

use think\Model;

class CabinetModel extends Model
{
    protected $name = 'cabinet';
    /**
     * 查询机构
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getCabinetsByWhere($where, $offset, $limit)
    {
        $cabinets = $this->where($where)->limit($offset, $limit)->order('id desc')->select();
        foreach($cabinets as &$vo){
            $room = model('RoomModel')->field('name,organization_id')->getById($vo['room_id']);
            $vo['room']=$room['name'];
            $vo['organization'] = model('OrganizationModel')->where(['id'=>$room['organization_id']])->value('name');
        }
        unset($vo);
        return $cabinets;
    }
    /**
     * 根据搜索条件获取所有的机构数量
     * @param $where
     */
    public function getAllCabinets($where)
    {
        return $this->where($where)->count();
    }
    public function addCabinet($param){
        try{
            $result = $this->validate('CabinetValidate')->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{
                return msg(1, url('cabinet/index'), '添加档案柜成功');
            }
        }catch (\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }
    public function getCabinetsByRoomId($room_id){
        $cabinets = $this->where(['room_id'=>$room_id])->field('id,name')->select();
        return $cabinets;
    }
    /**
     * 编辑文章信息
     * @param $param
     */
    public function editCabinet($param,$id)
    {
        try{

            $result = $this->validate('CabinetValidate')->save($param, ['id' => $id]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{
                return msg(1, url('cabinet/index'), '修改机构信息成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据文章的id 获取文章的信息
     * @param $id
     */
    public function getOneCabinet($id)
    {
        $cabinet = $this->where('id', $id)->find();
        $room = model('RoomModel')->field('organization_id')->getById($cabinet['room_id']);
        $cabinet['organization_id'] = $room['organization_id'];
        return $cabinet;
    }

    /**
     * 删除文章
     * @param $id
     */
    public function delCabinet($id)
    {
        try{
            $this->where('id', $id)->delete();
            return msg(1, '', '删除机构成功');

        }catch(\Exception $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}