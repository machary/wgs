<?php
/**
 * Datatables untuk CRUD Fasilitas Jaringan Listrik
 * @author Kanwil
 */
 
class Cms_Model_Datatables_FasJarListrik extends App_Datatables
{
	public function getColumns() 
	{
		return array(
			'nama',
			'jenis',
			'merk',
			'jumlah_unit',
			'voltase',
            'jenis_genset',
			''
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table('master.fasilitas_jaringan_listrik');
		return $table->fetchAll()->count();
	}
	
	public function getTotalDisplayRecords()
	{
		$table = new Zend_Db_Table('master.fasilitas_jaringan_listrik');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('listrik' => 'master.fasilitas_jaringan_listrik'), array(
				'idjaringanlistrik', // id
			))
			->join(array('lanal' => 'master.pangkalan'), 'lanal."idpangkalan" = listrik."idpangkalan"', array())
		;
		$this->_search($query);
		return $table->fetchAll($query)->count();
	}
	
	public function retrieveData()
	{
		$table = new Zend_Db_Table('master.fasilitas_jaringan_listrik');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('listrik' => 'master.fasilitas_jaringan_listrik'), array(
				'idjaringanlistrik', // id
				'lanal.nama', 
				'jenis',
				'merk',
				'jumlah_unit',
				'voltase',
                'jenis_genset'
			))
			->join(array('lanal' => 'master.pangkalan'), 'lanal."idpangkalan" = listrik."idpangkalan"', array())
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

			$t[1] = '<a href="'.$hUrl->url(array('action'=>'view', 'id'=>$id)).'">'.$t[1].'</a>';

			$t[] = '<a href="'.$hUrl->url(array('action'=>'edit', 'id'=>$id)).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';
			$result[] = $t;
		}
		return $result;
	}
}