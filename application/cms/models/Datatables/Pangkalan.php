<?php
/**
 * Datatables untuk CRUD Pangkalan
 * @author Kanwil
 */
 
class Cms_Model_Datatables_Pangkalan extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'nama_pangkalan',
			'jenis_pangkalan',
			'nama_lantamal',
			'',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('master.pangkalan');
		return $table->fetchAll()->count();
	}
	
	/**
	 * Di-override karena postgresql tidak bisa menggunakan column alias di 
	 * where clause
	 */
	protected function _search($query)
	{
		if ($this->_search) {
			foreach ($this->_searchable as $col => $isSearchable) {
				if ($isSearchable) {
					switch ($col) {
						case 'nama_lantamal':
							$query->orWhere('lantam."nama" LIKE ?', "%{$this->_search}%");
							break;
						case 'nama_pangkalan':
							$query->orWhere('lanal."nama" LIKE ?', "%{$this->_search}%");
							break;
						case 'jenis_pangkalan':
							$query->orWhere('lanal."jenis_pangkalan" LIKE ?', "%{$this->_search}%");
							break;
						default:
							$query->orWhere('"'.$col.'" LIKE ?', "%{$this->_search}%");
							break;
					}
				}
			}
		}
	}
	
	public function getTotalDisplayRecords()
	{
		$table = new Zend_Db_Table('master.pangkalan');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('lanal' => 'master.pangkalan'), array(
				'lanal.idpangkalan', // id
			))
			->joinLeft(array('lantam' => 'master.pangkalan'), 'lanal."idparent" = lantam."idpangkalan"', array())
		;
		$this->_search($query);
		// print_r($query);exit;
		return $table->fetchAll($query)->count();
	}
	
	public function retrieveData()
	{
		$table = new Zend_Db_Table('master.pangkalan');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('lanal' => 'master.pangkalan'), array(
				'lanal.idpangkalan', // id
				'nama_pangkalan' => 'lanal.nama',
				'lanal.jenis_pangkalan',
				'nama_lantamal' => 'lantam.nama',
			))
			->joinLeft(array('lantam' => 'master.pangkalan'), 'lanal."idparent" = lantam."idpangkalan"', array())
			->order($this->_orderColumn.' '.$this->_orderDirection)
			->limit($this->_limit, $this->_offset)
		;
		$this->_search($query);
		
		$raw = $table->fetchAll($query);
		$result = array();
		$hUrl = new Zend_View_Helper_Url();
		foreach ($raw as $row) {
			/*
			if (!$row['nama_lantamal']) {
				$row['nama_lantamal'] = $row['nama_pangkalan'];
				$row['nama_pangkalan'] = '';
			}
			*/
			$t = array_values($row->toArray());
			$id = array_shift($t);

			$t[0] = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$id)).'">'.$t[0].'</a>';

			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
		return $result;
	}
}