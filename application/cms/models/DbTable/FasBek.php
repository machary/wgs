<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_DbTable_FasBek extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.fasbek';
    protected $_tableName = 'master.fasbek';
    protected $_primary = 'idfasbek';

	public function getIdPangkalan($id)
	{
		$query = $this->select()
			->from($this->_tableName,
			array(
				'idfasbek',
				'nama_item',
			))
			->where('idpangkalan =?', $id)
		;

		$result = $this->fetchAll( $query );

		if( empty( $result ) ) return null;
		return $result->toArray();
	}
}