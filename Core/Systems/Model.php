<?php
/*
 * Title:沉沦云MVC开发框架
 * Project:Model功能类
 * Author:流逝中沉沦
 * QQ：1178710004
*/

namespace Systems;

use Systems\Db;

class Model
{
    protected $name; //表名

    protected $id = "id"; //主键ID
    /**
     * DB实例
     */
    public function DB($name = null)
    {
        return Db::init()->name($name == null ? $this->name : $name);
    }
    /**
     * 查询数据(多条记录)
     * @param Array $where 条件
     * @param String $field 字段
     * @return Mixed 数据集
     */
    public function Select($where = array(), $field = "*")
    {
        try {
            if (empty($where)) {
                return $this->DB()->field($field)->select();
            } else {
                return $this->DB()->field($field)->where($where)->select();
            }
        } catch (\Exception $th) {
            return $th;
        }
    }
    /**
     * 查询数据(单条记录)
     * @param Mixed $where 条件
     */
    public function Find($where = null, $field = "*")
    {
        try {
            if (is_array($where)) {
                return $this->DB()->where($where)->field($field)->find();
            } elseif (is_int($where)) {
                return $this->DB()->where(array($this->id => $where))->field($field)->find();
            } else {
                return $this->DB()->field($field)->find();
            }
        } catch (\Exception $th) {
            return $th;
        }
    }
    /**
     * 插入数据(单条)
     * @param Array $data 数据
     * @return Boolean 结果
     */
    public function Insert($data = array())
    {
        try {
            if (empty($data)) {
                return false;
            } else {
                return $this->DB()->insert($data);
            }
        } catch (\Exception $th) {
            return $th;
        }
    }
    /**
     * 插入数据(多条)
     * @param Array $data 数据
     * @return Array 结果
     */
    public function Inserts($data = array())
    {
        try {
            if (empty($data)) {
                return false;
            } else {
                $res = array();
                foreach ($data as $key) {
                    $res[] = $this->Insert($key);
                }
                return $res;
            }
        } catch (\Exception $th) {
            return $th;
        }
    }
    /**
     * 删除数据
     * @param Array $where 条件
     * @return Boolean 结果
     */
    public function Delete($where = null)
    {
        try {
            if (empty($data)) {
                return false;
            } else {
                if (is_array($where)) {
                    return $this->DB()->where($where)->delete();
                } else {
                    return $this->DB()->where(array($this->id => intval($where)))->delete();
                }
            }
        } catch (\Exception $th) {
            return $th;
        }
    }
    /**
     * 修改数据
     * @param Array $where 条件
     * @param Array $data 数据
     * @return Boolean 结果
     */
    public function Update($where = null, $data = array())
    {
        if (is_int($where)) {
            return $this->DB()->where(array($this->id => intval($where)))->update($data);
        } else if (is_array($where)) {
            return $this->DB()->where($where)->update($data);
        } else {
            return false;
        }
    }
}
