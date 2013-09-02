<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_DbTable_FasRumahSakit extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.fasilitas_rumah_sakit';
    protected $_tableName = 'master.fasilitas_rumah_sakit';
    protected $_primary = 'idrs';

	public function getIdPangkalan($id)
	{
		$query = $this->select()
			->from($this->_tableName,
			array(
				'idrs',
				'kelas',
			))
			->where('idpangkalan =?', $id)
		;

		$raw = $this->fetchAll( $query );
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t);

			$t[0] = '<a href="'.$hUrl->url(array( 'controller'=>'rumah.sakit','action'=>'view', 'id'=>$id)).'">'.$t[0].'</a>';

			$result[] = $t;
		}
		return $result;
//		$result = $this->fetchAll( $query );
//
//		if( empty( $result ) ) return null;
//		return $result->toArray();
	}
}