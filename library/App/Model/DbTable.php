<?php
/**
 * Model Dasar DbTable
 * Berisi fungsi2 operasi CRUD biasa
 * @author tajhul.faijin@sangkuriang.co.id
 */
class App_Model_DbTable extends Zend_Db_Table_Abstract
{
    protected $_primary = 'id';
   	protected $_columns = array();

    /*
   	 * Override insert() method with intersecting key
   	 */
   	public function insert(array $data)
   	{
   		return parent::insert(array_intersect_key($data, $this->_columns));
   	}

    public function getlastID()
    {
        return $this->_db->lastInsertId();
    }

   	/*
   	 * Override update() method with intersecting key
   	 */
   	public function update(array $data, $where)
   	{
   		return parent::update(array_intersect_key($data, $this->_columns), $where);
   	}

    /*
   	 * Update by primary key
     * multiple where clause if id is array
   	 */
   	public function edit($id, $data)
   	{
       try {
           if (is_array($id)) {
         			$this->update($data, $this->arrayToWhere($id));
         		} else {
         			$primary = $this->primarySingle();
         			$this->update($data, $this->_db->quoteInto($primary . ' = ?', $id));
         		}
           return true;
       } catch (Exception $e) {
           return false;
       }
   	}

   	/*
   	 * Delete by primary key
     * multiple where clause if id is array
   	 */
   	public function del($id)
   	{
       try {
           if (is_array($id)) {
               $this->fetchRow($this->arrayToWhere($id))->delete();
           } else {
               $primary = $this->primarySingle();
               $this->fetchRow($this->_db->quoteInto($primary . ' = ?', $id))->delete();
           }
           return true;
       } catch (Exception $e) {
           return false;
       }
   	}

    public function delBy($key, $value)
   	{
       try {

           $this->fetchRow($this->_db->quoteInto($key . ' = ?', $value))->delete();

           return true;
       } catch (Exception $e) {
           return false;
       }
   	}


    protected function primarySingle()
   	{
   		return $this->_db->quoteIdentifier(is_array($this->_primary) ? current($this->_primary) : $this->_primary);
   	}

    /**
   	 * Transform array('name' => 'Juke', 'gender' => 'male')
   	 * info "`name` = 'Juke' AND `gender` = 'male'
   	 */
   	protected function arrayToWhere($ar)
   	{
   		$where = '';
   		foreach ($ar as  $k => $v) {
   			if ($where) $where .= ' AND ';
   			$where .= $this->_db->quoteIdentifier($k) . ' = ' . $this->_db->quote($v);
   		}
   		return $where;
   	}


   	/*
   	 * Return all rows, order by something
   	 * @param $retSelect if true then return Zend_Db_Select, else array
   	 */
   	public function all($order = null, $count = false)
   	{
        $query = $this->_db->select()->from($this->_name);

        echo $query; exit;

        if ($order) $query->order($order);
        $result = $this->_db->fetchAll($query);

        if($count){
            return count($result);
        } else {
            return $result;
        }
   	}

   	/*
   	 * Return all rows matching column $key = $val
   	 * @param string|array $key if array then be considered [keys] => [vals] and $val ignored
   	 * @param $retSelect if true then return Zend_Db_Select, else array
   	 */
   	public function allBy($key, $val = null, $order = null, $count = false)
   	{
   		$query = $this->_db->select()->from($this->_name);
   		if (is_array($key)) {
   			foreach ($key as $k => $v) {
   				$query->where($this->_db->quoteIdentifier($k) . ' = ?', $v);
   			}
   		} else {
   			$query->where($this->_db->quoteIdentifier($key) . ' = ?', $val);
   		}
   		if ($order) $query->order($order);

       $result = $this->_db->fetchAll($query);

       if($count){
           return count($result);
       } else {
           return $result;
       }
   		//return $retSelect ? $query : $this->_db->fetchAll($query);
   	}

   	/*
   	 * Return 1 row matching column $key = $val
   	 * @param string|array $key if array then be considered [keys] => [vals] and $val ignored
   	 */
   	public function getBy($key, $val = null, $order = null, $fields = null)
   	{
        $fields = empty( $fields ) ? array('*') : $fields;

   		$query = $this->_db->select()->from($this->_name, $fields);
   		if (is_array($key)) {
   			foreach ($key as $k => $v) {
   				$query->where($this->_db->quoteIdentifier($k) . ' = ?', $v);
   			}
   		} else {
   			$query->where($this->_db->quoteIdentifier($key) . ' = ?', $val);
   		}
   		if ($order) $query->order($order);
   		$row = $this->_db->fetchRow($query);
   		return $row ? $row : null;
   	}

    public function getByPrimary($val = null, $fields = null)
   	{
        if( is_null($val) ) return false;

        $fields = empty( $fields ) ? array('*') : $fields;
   		$query = $this->_db->select()->from($this->_name, $fields)
           ->where($this->_db->quoteIdentifier( $this->primarySingle() ) . ' = ?', $val)
        ;
   		$row = $this->_db->fetchRow($query);
   		return $row ? $row : null;
   	}


   	/*
   	 * Return amount of data
   	 */
    public function count($field = null)
    {
        $field = empty($field) ? array('COUNT(*) as amount') : $field;
        $query = $this->_db->select()->from($this->_name, $field);
        $result = $this->_db->fetchRow($query);

        return $result['amount'];
    }
}