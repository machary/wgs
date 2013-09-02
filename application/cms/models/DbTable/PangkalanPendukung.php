<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_DbTable_PangkalanPendukung extends Zend_Db_Table_Abstract
{
    protected $_name = 'public.cbl_pangkalan';
    protected $_tableName = 'public.cbl_pangkalan';
    protected $_primary = 'id';

	public function getIdPangkalan($id,$id_cbl)
	{
		$query = $this->select()
			->from($this->_tableName,
			array(
				'id',
				'id_pangkalan',
				'id_cb_logistik',
				'keterangan',
			))
			->where('id_pangkalan =?', $id)
			->where('id_cb_logistik =?', $id_cbl)
		;

		$result = $this->fetchAll( $query );

		if( empty( $result ) ) return null;
		return $result->toArray();
	}
}