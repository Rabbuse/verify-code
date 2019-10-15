<?php
namespace stMeteor\code\model;
/**
 * Created by PhpStorm.
 * User: Meteor
 * Date: 2019/4/2
 * Time: 23:05
 */

use think\Model;

class VerifyCode extends Model{

    protected $insert = ['createtime'];

    public function setCreatetimeAttr(){
        return time();
    }

    /*
     * 获取单条信息
     * */
    public function findData($where,$field = ['*'], $except = false){
        return $this->where($where)->field($field, $except)->find();
    }

    /*
     * 获取多条信息
     * */
    public function selectData($where, $field = ['*'], $order = 'id desc', $page = false, $limit = null)
    {
        if ($page) {
            $action = "paginate";
            if (!$limit) {
                $limit = 10;
            }
        } else {
            $action = 'select';
            $limit = null;
        }
        return $this->where($where)->field($field)->order($order)->$action($limit);
    }

    /*
     * 新增/修改信息
     * */
    public function saveData($data, $where = [], $field = true){
        return $this->allowField($field)->isUpdate(!empty($data['id']))->save($data, $where);
    }

    /*
     * 删除信息
     * */
    public function deleteData($where){
        return $this->where($where)->delete();
    }
}