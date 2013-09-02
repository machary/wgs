<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_DbTable_Pangkalan extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.pangkalan';
    protected $_tableName = 'master.pangkalan';
    protected $_primary = 'idpangkalan';

	public function getIdParent($id)
	{
		$query = $this->select()
			->from($this->_tableName,
			array(
				'idpangkalan',
				'nama',
				'jenis_pangkalan',
			))
			->where('idparent =?', $id)
		;

		$result = $this->fetchAll( $query );

		if( empty( $result ) ) return null;
		return $result->toArray();
	}

    public function getAllPangkalan()
    {
        $query = $this->select()->setIntegrityCheck(false)
                      ->from($this->_tableName, array('idpangkalan', 'nama'))
        ;
        $result = $this->fetchAll($query);
        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }
}