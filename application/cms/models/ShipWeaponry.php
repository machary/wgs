<?php
/**
 * Ship Weaponry Model
 * @author Kanwil
 */

class Cms_Model_ShipWeaponry
{
	protected $_shipId;
	
	protected $_objectShip;
	
	// current state
	protected $_bombs = null;
	protected $_radars = null;
	protected $_sonars = null;
	// saved in database state
	protected $_savedBombs = null;
	protected $_savedRadars = null;
	protected $_savedSonars = null;
	
	/**
	 * @param int|string $sid ship ID harus ada dalam database
	 * @throw Exception jika ship ID tidak ada di database
	 */
	public function __construct($sid)
	{
		$ship = new Cms_Model_Ship($sid);
		if ($ship->exists()) {
			$this->_shipId = $sid;
			$this->_objectShip = $ship;
		} else {
			throw new Exception('Invalid Ship ID');
		}
	}
	
	/**
	 * Mengembalikan kondisi where untuk object ini
	 */
	protected function _where()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->quoteInto($db->quoteIdentifier('ship_id') . " = ?", $this->_shipId);
	}
	
	/**
	 * @return Cms_Model_Ship
	 */
	public function getShip()
	{
		return $this->_objectShip;
	}
	
	/**
	 * Mengembalikan semua bomb yang dimiliki kapal
	 * @return array
	 */
	public function getBombs()
	{
		if (!isset($this->_bombs)) {
			$table = new Cms_Model_DbTable_PvShipBomb();
			$this->_savedBombs = $this->_bombs = $table->fetchAll($this->_where())->toArray();
		}
		return $this->_bombs;
	}
	
	/**
	 * Mengembalikan semua radar yang dimiliki kapal
	 * @return array
	 */
	public function getRadars()
	{
		if (!isset($this->_radars)) {
			$table = new Cms_Model_DbTable_PvShipRadar();
			$this->_savedRadars = $this->_radars = $table->fetchAll($this->_where())->toArray();
		}
		return $this->_radars;
	}
	
	/**
	 * Mengembalikan semua sonar yang dimiliki kapal
	 * @return array
	 */
	public function getSonars()
	{
		if (!isset($this->_sonars)) {
			$table = new Cms_Model_DbTable_PvShipSonar();
			$this->_savedSonars = $this->_sonars = $table->fetchAll($this->_where())->toArray();
		}
		return $this->_sonars;
	}
	
	/**
	 * Mengubah data sesuai input dari form
	 * @param Cms_Form_ShipWeaponry|array
	 */
	public function setFromForm($form)
	{
		if (is_a($form, 'Zend_Form')) {
			$form = $form->getValues();
		}
		// bomb
		$this->_bombs = array();
		foreach ($form['bomb_id'] as $i => $bomb_id) {
			$bomb_count = $form['bomb_count'][$i];
			if ($bomb_count && $bomb_id) {
				$this->_bombs[] = array(
					'ship_id' => $this->_shipId,
					'bomb_id' => $bomb_id,
					'bomb_count' => $bomb_count,
				);
			}
		}
		// radar
		$this->_radars = array();
		foreach ($form['radar_id'] as $i => $radar_id) {
			$radar_count = $form['radar_count'][$i];
			if ($radar_count && $radar_id) {
				$this->_radars[] = array(
					'ship_id' => $this->_shipId,
					'radar_id' => $radar_id,
					'radar_count' => $radar_count,
				);
			}
		}
		// sonar
		$this->_sonars = array();
		foreach ($form['sonar_id'] as $i => $sonar_id) {
			$sonar_count = $form['sonar_count'][$i];
			if ($sonar_count && $sonar_id) {
				$this->_sonars[] = array(
					'ship_id' => $this->_shipId,
					'sonar_id' => $sonar_id,
					'sonar_count' => $sonar_count,
				);
			}
		}
	}
	
	/**
	 * Menyimpan seluruh informasi ini ke dalam database
	 */
	public function save()
	{
		// bomb
		$table = new Cms_Model_DbTable_PvShipBomb();
		$table->delete($this->_where());
		
		foreach ($this->_bombs as $b) {
			$table->insert($b);
		}
		$this->_savedBombs = $this->_bombs;
		
		// radar
		$table = new Cms_Model_DbTable_PvShipRadar();
		$table->delete($this->_where());
		
		foreach ($this->_radars as $r) {
			$table->insert($r);
		}
		$this->_savedRadars = $this->_radars;
		
		// sonar
		$table = new Cms_Model_DbTable_PvShipSonar();
		$table->delete($this->_where());
		
		foreach ($this->_sonars as $s) {
			$table->insert($s);
		}
		$this->_savedSonars = $this->_sonars;
	}
}