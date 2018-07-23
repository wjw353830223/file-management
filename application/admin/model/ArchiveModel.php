<?php

namespace app\admin\model;

use think\Model;

class ArchiveModel extends Model
{
    protected $name = 'archive';
    /**
     * 查询机构
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getArchivesByWhere($where, $offset, $limit)
    {
        $archives = $this->where($where)->limit($offset, $limit)->order('id desc')->select();
        foreach($archives as &$vo){
            $cabinet = model('CabinetModel')->field('name,room_id')->getById($vo['cabinet_id']);
            $room = model('RoomModel')->field('name,organization_id')->getById($cabinet['room_id']);
            $vo['classify']=model('ClassModel')->where(['id'=>$vo['class_id']])->value('name');
            $vo['cabinet']=$cabinet['name'];
            $vo['room']=$room['name'];
            $vo['organization'] = model('OrganizationModel')->where(['id'=>$room['organization_id']])->value('name');
        }
        unset($vo);
        return $archives;
    }
    /**
     * 根据搜索条件获取所有的机构数量
     * @param $where
     */
    public function getAllArchives($where)
    {
        return $this->where($where)->count();
    }
    public function addArchive($param){
        try{
            $param['created_at'] = $param['updated_at'] = time();
            $result = $this->validate('ArchiveValidate')->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{
                return msg(1, url('archive/index'), '添加档案成功');
            }
        }catch (\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }
    /**
     * 编辑文章信息
     * @param $param
     */
    public function editArchive($param,$id)
    {
        try{

            $result = $this->validate('ArchiveValidate')->save($param, ['id' => $id]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{
                return msg(1, url('archive/index'), '修改档案信息成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据文章的id 获取文章的信息
     * @param $id
     */
    public function getOneArchive($id)
    {
        $archive = $this->where('id', $id)->find();
        $cabinet = model('CabinetModel')->field('room_id')->getById($archive['cabinet_id']);
        $room = model('RoomModel')->field('organization_id')->getById($cabinet['room_id']);
        $archive['room_id'] = $cabinet['room_id'];
        $archive['organization_id'] = $room['organization_id'];
        return $archive;
    }

    /**
     * 删除文章
     * @param $id
     */
    public function delArchive($id)
    {
        try{
            $this->where('id', $id)->delete();
            return msg(1, '', '删除档案成功');

        }catch(\Exception $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}