<?php
class Latihan_Model_DbTable_KekuatanSendiriPergerakan extends Zend_Db_Table_Abstract
{
	protected $_name = 'latihan.kekuatan_sendiri_pergerakan';
	protected $_tableName = 'latihan.kekuatan_sendiri_pergerakan';
	protected $_primary = 'id';

	public function saveSimbolPergerakan($tanggal, $waktu, $idSimTak, $point, $rotate, $size, $idSkenario, $keterangan)
	{
		$data = array(
			'tanggal'               => $tanggal,
			'waktu'                 => $waktu,
			'id_simbol_pergerakan'  => $idSimTak,
			'point'                 => $point,
			'rotation'              => empty($rotate) ? 0 : $rotate,
			'size'                  => empty($size) ? 0 : $size,
			'id_skenario'           => $idSkenario,
			'keterangan'            => $keterangan
		);

		$this->insert($data);
	}

	public function getPergerakan( $idSkenario = null )
	{
		if( empty($idSkenario) ) return false;

		$table = $this->tablePergerakan();
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('gerak'=>$this->_tableName))
			->joinLeft(array('sim'=>'master.simbol_pergerakan'), 'sim."id"=intel."id_simbol_pergerakan"', array('sim.filepath'))
			->where("gerak.id_skenario = '".$idSkenario."'")
		;

		$result = $table->fetchAll($query);
		if(!empty($result))
		{
			return $result->toArray();
		}
		else
		{
			return null;
		}
	}

	public function editpergerakan($id_skenario)
	{
		$query = $this->select()->setIntegrityCheck(false)
			->from(array('gerak' => $this->_tableName))
			->joinLeft(array('simbol' => 'master.simbol_pergerakan'), 'simbol."id"=gerak."id_simbol_pergerakan"', array('simbol.filepath'))
			->where("gerak.id_skenario = '".$id_skenario."'");

		$result = $this->fetchAll($query);

		if(!empty($result))
		{
			return $result->toArray();
		}
		else
		{
			return null;
		}
	}

	public function updatepergerakan($tanggal, $waktu, $idSimTak, $point, $rotate, $size, $id, $keterangan)
	{
		$data = array(
			'tanggal'               => $tanggal,
			'waktu'                 => $waktu,
			'id_simbol_pergerakan'  => $idSimTak,
			'point'                 => $point,
			'rotation'              => $rotate,
			'size'                  => $size,
			'keterangan'            => $keterangan
		);

		$this->update($data, 'latihan."kekuatan_sendiri_pergerakan"."id" = '."'".$id."'");
	}

	public function deletepergerakan($id_skenario)
	{
		$this->delete('latihan."kekuatan_sendiri_pergerakan"."id_skenario" = '."'".$id_skenario."'");
	}
}