<?php
/**
 * Quick Coding: Menangani Rute Operasional Udara Kogasgabla
 * @author Kanwil
 */
 
class Peta_Model_RuteUdaraOperasional extends Peta_Model_RuteOperasional
{
	protected $_hasLatum = false;
	protected $_seq = 'sementara.gabla_udara_operasional_rute_id_seq'; // HARDCODED!
	
	// OVERRIDE
	public function table()
	{
		return new Zend_Db_Table('sementara.gabla_udara_operasional_rute');
	}
	
	public function tableTitik()
	{
		return new Zend_Db_Table('sementara.gabla_udara_operasional_rute_titik');
	}
	
	public function tableFormasi()
	{
		return new Zend_Db_Table('sementara.gabla_udara_operasional_rute_formasi');
	}
	
	public function symbols()
	{
		$table = new Zend_Db_Table('master.simbol_taktis');
		return $table->fetchAll($table->getAdapter()->quoteInto('jenis IN (?)', array('sayap putar', 'sayap tetap')));
	}
	
	// ******************************************************
	//                    STATIC METHODS
	// ******************************************************
	
	/**
	 * Mengembalikan seluruh rute milik suatu CB Operasional dalam bentuk row
	 * @param integer|string $cbId 
	 * @return array of Zend_Db_Table_Row
	 */
	public static function allRows($cbId)
	{
		$table = new Zend_Db_Table('sementara.gabla_udara_operasional_rute');
		$query = $table->select()
			->where('cb_operasional_id = ?', $cbId)
			->order('nama ASC')
		;
		return $table->fetchAll($query);
	}
	
	/**	
	 * Mengembalikan seluruh rute milik suatu CB Operasional dalam bentuk instance model ini
	 * @param integer|string $cbId 
	 * @return array of Peta_Model_RuteUdaraOperasional
	 */
	public static function allObjects($cbId) 
	{
		$rows = self::allRows($cbId);
		$return = array();
		foreach ($rows as $row) {
			$return[] = new Peta_Model_RuteUdaraOperasional($cbId, $row->id);
		}
		return $return;
	}
	
	/**
	 * Mengupdate nilai kolom "durasi" bagi suatu CB Operasional 
	 * @param integer|string $cbId 
	 */
	public static function updateDurasi($cbId)
	{
		// retrieve cb row
		$table = new Zend_Db_Table('public.cb_operasional');
		$row = $table->find($cbId)->current();
		if ($row) {
			// retrieve max durasi
			$db = $table->getAdapter();
			$query = $db->select()
				->from('sementara.gabla_udara_operasional_rute', array('max_durasi' => 'MAX(durasi)'))
				->where('cb_operasional_id = ?', $cbId)
			;
			$maxDurasi = $db->fetchOne($query);
			// update value
			$row->durasi_udara = $maxDurasi;
			$row->save();
		}
	}
}