<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_DbTable_FasRumah extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.fasilitas_rumah';
    protected $_tableName = 'master.fasilitas_rumah';
    protected $_primary = 'idrumah';

	public function getIdPangkalan($id)
	{
		$query = $this->select()
			->from($this->_tableName,
			array(
				'idrumah',
				'tipe_rumah',
			))
			->where('idpangkalan =?', $id)
		;

		$raw = $this->fetchAll( $query );
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t);

			$t[0] = '<a href="'.$hUrl->url(array( 'controller'=>'rumah','action'=>'view', 'id'=>$id)).'">'.$t[0].'</a>';

			$result[] = $t;
		}
		return $result;
	}
}