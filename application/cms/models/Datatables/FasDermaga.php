<?php
/**
 * Datatables untuk CRUD Fasilitas Dermaga
 * @author Kanwil
 */
 
class Cms_Model_Datatables_FasDermaga extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'nama_lanal',
			'nama_dermaga',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('master.fasilitas_dermaga');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
		//@edited irfan.muslim@gmail.com
		$table = new Zend_Db_Table('master.fasilitas_dermaga');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('dermaga' => 'master.fasilitas_dermaga'))
			->join(array('lanal' => 'master.pangkalan'), 'lanal."idpangkalan" = dermaga."idpangkalan"', array());
		$this->_search($query);
		return $table->fetchAll($query)->count();
	}

	/**
	 * Di-override karena postgresql tidak bisa menggunakan column alias di
	 * where clause
	 * @author irfan.muslim@gmail.com
	 */
	protected function _search($query)
	{
		if ($this->_search) {
			foreach ($this->_searchable as $col => $isSearchable) {
				if ($isSearchable) {
					switch ($col) {
						case 'nama_lanal':
							$query->orWhere('lanal."nama" LIKE ?', "%{$this->_search}%");
							break;
						default:
							$query->orWhere('"'.$col.'" LIKE ?', "%{$this->_search}%");
							break;
					}
				}
			}
		}
	}

	public function retrieveData()
	{
		$table = new Zend_Db_Table('master.fasilitas_dermaga');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('dermaga' => 'master.fasilitas_dermaga'), array(
				'iddermaga', // id
				'nama_lanal' => 'lanal.nama', 
				'nama_dermaga',
			))
			->join(array('lanal' => 'master.pangkalan'), 'lanal."idpangkalan" = dermaga."idpangkalan"', array())
			->order($this->_orderColumn.' '.$this->_orderDirection)
			->limit($this->_limit, $this->_offset)
		;

		$this->_search($query);

		$raw = $table->fetchAll($query);
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			$t = array_values($row->toArray());
			$id = array_shift($t);

			//@edited irfan.muslim@sangkuriang.co.id
			$t[1] = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$id)).'">'.$t[1].'</a>';

			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> | 
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
		return $result;
	}
}