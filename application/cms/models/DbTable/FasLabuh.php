<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_DbTable_FasLabuh extends Zend_Db_Table_Abstract
{
    protected $_name = 'master.fasilitas_labuh_udara';
    protected $_tableName = 'master.fasilitas_labuh_udara';
    protected $_primary = 'idlabuh_udara';

	public function getIdPangkalan($id)
	{
		$query = $this->select()
			->from($this->_tableName,
			array(
				'idlabuh_udara',
				'nama_item',
			))
			->where('idpangkalan =?', $id)
		;

		$raw = $this->fetchAll( $query );
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t);

			$t[0] = '<a href="'.$hUrl->url(array( 'controller'=>'fasilitaslabuhudara','action'=>'view', 'id'=>$id)).'">'.$t[0].'</a>';

			$result[] = $t;
		}
		return $result;
	}
}