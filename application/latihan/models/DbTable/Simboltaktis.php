<?php

class Latihan_Model_DbTable_Simboltaktis extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.simbol_taktis';
    protected $_tableName = 'master.simbol_taktis';
    protected $_primary = 'id';

    //<jenisSimbol Fn> hanya mengambil jenis simbol taktis
    //@author : tajhul faijin
    //@return array
    public function jenisSimbol( $matra = null )
    {
        $query1 = $this->select()
                ->distinct( 'jenis' )
                ->from($this->_tableName, array('jenis'));

        if( !is_null($matra) ) {
            //$query1->where('matra = ?', $matra);
        }

        $result = $this->fetchAll($query1)->toArray();

        return $result;
    }

    //<get single row>
    //@author : tajhul faijin
    //@param : key (string / array)
    //@param : val (string)
    //@param : field (array)
    //@return array
    public function getBy($key = null, $val = null, array $field = null){

        if( empty($field) ) $field = array('*');

        $query = $this->select()->from($this->_tableName, $field);

        //jika punya parameter
        if( !empty($key) ){
            //dan jika parameternya array
           if (is_array($key)) {
                foreach ($key as $k => $v) {
                    $query->where($this->quoteIdentifier($k) . ' = ?', $v);
                }
            } else {
                $query->where($key. ' = ?', $val);
            }
        }

        $result = $this->fetchRow($query);
        if( empty($result) ) return null;
        return $result;
    }

    //<get multiple row>
    //@author : tajhul faijin
    //@param : key (string / array)
    //@param : val (string)
    //@param : field (array)
    //@param : count (bool)
    //@return array
	public function getAll($key = null, $val = null, $count = false)
	{
		$query = $this->select()->from($this->_tableName);

		//jika punya parameter
		if( !empty($key) ){
			//dan jika parameternya array
			if (is_array($key)) {
				foreach ($key as $k => $v) {
					$query->where($k . ' = ?', $v);
				}
			} else {
				$query->where($key. ' = ?', $val);
			}
		}

		$result = $this->fetchAll($query);
		if( empty($result) ) return null;

		//jika hanya count data
		if($count){
			return sizeof($result);
		} else {
			return $result->toArray();
		}
	}
}