<?php
/**
 * Datatables untuk CRUD Kekuatan Sendiri - Tajhul
 * @author Tajhul
 */
 
class Latihan_Model_Datatables_KekuatanSendiri_Darat extends App_Datatables
{
	// main table name used
	public $tname = 'latihan.kekuatan_sendiri_darat';
	public function getColumns() 
	{
		return array(
//			'bandara_id',
			'nama',
			'nama_simbol',
		);
	}
	
	public function getTotalRecords() 
	{
		$table = new Zend_Db_Table($this->tname);
		return $table->fetchAll(
			$table->getAdapter()->quoteInto('skenario_id = ?', $this->_params['skenario_id'])
		)->count();
	}
	
	public function getTotalDisplayRecords()
	{
		$table = new Zend_Db_Table($this->tname);
		$query = $table->select()
			->from($this->tname, array(
				'id',
			))
			->where('skenario_id = ?', $this->_params['skenario_id'])
		;
		$this->_search($query);
		
		return $table->fetchAll($query)->count();
	}
	
	public function retrieveData()
	{
		$table = new Zend_Db_Table($this->tname);
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('ksd' => $this->tname), array(
				'ksd.id', // first column is ID
				'ksd.nama',
			))
			->where('skenario_id = ?', $this->_params['skenario_id'])
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

			$allKekuatan = $this->allKekuatan($id);
            $t[] = implode(', ', $allKekuatan);
			$t[] = '
				<a href="'.$hUrl->url(array('action'=>'darat.edit', 'id'=>$id )).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'darat.del', 'id'=>$id)).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>
			';
			$result[] = $t;
		}
		return $result;
	}

	public function allKekuatan($id)
	{
		$table = new Zend_Db_Table('latihan.kekuatan_sendiri_darat_detail');
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('ksd' => 'latihan.kekuatan_sendiri_darat_detail'))
			->joinLeft(array('sb' => 'master.simbol_taktis'), 'ksd."taktis_id" = sb."id"', array('sb.jenis', 'sb.nama as nama_simbol'))
			->where('parent_id = ?', $id)
		;
		$raw = $table->fetchAll($query);

		$result = array();
		foreach ($raw as $i => $row) {
			$result[ $i ] = $row->jumlah . ' | ' . ucwords($row->jenis) . ' - ' . ucwords($row->nama_simbol);
		}

		return $result;
	}
}