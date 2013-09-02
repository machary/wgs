<?php
/**
 * Model untuk table Room BBB
 *
 * @author Hermanet Lay
 */

class Komunikasi_Model_DbTable_Room extends Zend_Db_Table_Abstract
{
	protected $_name	 	   = 'bbb.room';
	protected $_tableName 	   = 'bbb.room';
	protected $_primary 	   = 'room_id';

	public function getAllRoom()
	{
		$query = $this->select()
			->from(array('r' => $this->_tableName));
		$result = $this->fetchAll($query);
		if(count($result))
		{
			return $result->toArray();
		}else{
			return null;
		}
	}

	public function getRoomInfoByID($meetingID){
		$query = $this->select()
			->from(array('r' => $this->_tableName))
			->where('r.room_id = ?', $meetingID);
		$result = $this->fetchRow($query);
		if(count($result))
		{
			return $result->toArray();
		}else{
			return null;
		}
	}

    public function deleteRoom($meetingID)
    {
        $where = $this->getAdapter()->quoteInto('room_id = ?', $meetingID);
        $this->delete($where);
    }
}