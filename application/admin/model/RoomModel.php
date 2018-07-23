<?php

namespace app\admin\model;

use think\Model;

class RoomModel extends Model
{
    protected $name = 'room';
    /**
     * 查询机构
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getRoomsByWhere($where, $offset, $limit)
    {
        $rooms = $this->where($where)->limit($offset, $limit)->order('id desc')->select();
        foreach($rooms as &$vo){
           $vo['organization'] = model('OrganizationModel')->where(['id'=>$vo['organization_id']])->value('name');
        }
        unset($vo);
        return $rooms;
    }
    public function getRoomsByOrganizationId($organization_id){
        $rooms = $this->where(['organization_id'=>$organization_id])->field('id,name')->select();
        return $rooms;
    }
    /**
     * 根据搜索条件获取所有的机构数量
     * @param $where
     */
    public function getAllRooms($where)
    {
        return $this->where($where)->count();
    }
    public function addRoom($param){
        try{
            $result = $this->validate('RoomValidate')->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{
                return msg(1, url('room/index'), '添加档案室成功');
            }
        }catch (\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }
    /**
     * 编辑文章信息
     * @param $param
     */
    public function editRoom($param,$id)
    {
        try{

            $result = $this->validate('RoomValidate')->save($param, ['id' => $id]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{
                return msg(1, url('room/index'), '修改档案室信息成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据文章的id 获取文章的信息
     * @param $id
     */
    public function getOneRoom($id)
    {
        $room = $this->where('id', $id)->find();
        return $room;
    }

    /**
     * 删除文章
     * @param $id
     */
    public function delRoom($id)
    {
        try{
            $this->where('id', $id)->delete();
            return msg(1, '', '删除档案室成功');

        }catch(\Exception $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}