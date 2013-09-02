<?php
/**
 * Kesatuan Ranpur Model
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_KesatuanRanpur
{
	protected $_kesatuanId;
	
	protected $_objectKesatuan;
	
	// current state
	protected $_ranpurs = null;
	// saved in database state
	protected $_savedRanpurs = null;

	/**
	 * @param $sid kesatuan ID harus ada dalam database
	 * @throw Exception jika kesatuan ID tidak ada di database
	 */
	public function __construct($sid)
	{
		$kesatuan = new Cms_Model_KesatuanDetail($sid);
		if ($kesatuan->exists()) {
			$this->_kesatuanId = $sid;
			$this->_objectKesatuan = $kesatuan;
		} else {
			throw new Exception('Invalid Kesatuan ID');
		}
	}
	
	/**
	 * Mengembalikan kondisi where untuk object ini
	 */
	protected function _where()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->quoteInto($db->quoteIdentifier('idkesatuan') . " = ?", $this->_kesatuanId);
	}
	
	/**
	 * @return Cms_Model_Kesatuan
	 */
	public function getKesatuan()
	{
		return $this->_objectKesatuan;
	}
	
	/**
	 * Mengembalikan semua ranpur yang dimiliki kesatuan
	 * @return array
	 */
	public function getRanpurs()
	{
		if (!isset($this->_ranpurs)) {
			$table = new Cms_Model_DbTable_PvtRanpur();
			$this->_savedRanpurs = $this->_ranpurs = $table->fetchAll($this->_where())->toArray();
		}
		return $this->_ranpurs;
	}

	/**
	 * Mengubah data sesuai input dari form berdasarkan nolambung
	 * @param Cms_Form_KesatuanRanpur|array
	 */
	public function setFromForm($form)
	{
		if (is_a($form, 'Zend_Form')) {
			$form = $form->getValues();
		}
		// ranpur
		$this->_ranpurs = array();
		foreach ($form['idranpur'] as $i => $idranpur) {
			$nolamb = $form['nomor_lambung'][$i];
			if ($idranpur && $nolamb) {
				$this->_ranpurs[] = array(
					'idkesatuan' => $this->_kesatuanId,
					'idranpur' => $form['idranpur'][$i],
					'nomor_mesin' => $form['nomor_mesin'][$i],
					'nomor_chasis' => $form['nomor_chasis'][$i],
					'nomor_registrasi_pusat' => $form['nomor_registrasi_pusat'][$i],
					'nomor_registrasi_baru' => $form['nomor_registrasi_baru'][$i],
					'nomor_lambung' => $form['nomor_lambung'][$i],
					'kondisi' => $form['kondisi'][$i],
					'keterangan' => $form['keterangan'][$i],
				);
			}
		}
	}
	
	/**
	 * Menyimpan seluruh informasi ini ke dalam database
	 */
	public function save()
	{
		// ranpur
		$table = new Cms_Model_DbTable_PvtRanpur();
		$table->delete($this->_where());
		
		foreach ($this->_ranpurs as $b) {
			$table->insert($b);
		}
		$this->_savedRanpurs = $this->_ranpurs;
		
	}
}