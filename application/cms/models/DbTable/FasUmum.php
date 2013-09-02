<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_DbTable_FasUmum extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.fasilitas_umum';
    protected $_tableName = 'master.fasilitas_umum';
    protected $_primary = 'idfasum';

	public function getIdPangkalan($id)
	{
		$query = $this->select()
			->from($this->_tableName,
			array(
				'idfasum',
				'jenis',
				'nama',
			))
			->where('idpangkalan =?', $id)
		;

		$result = $this->fetchAll( $query );

		if( empty( $result ) ) return null;
		return $result->toArray();
	}
}