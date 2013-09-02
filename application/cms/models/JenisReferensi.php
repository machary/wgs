<?php
/**
 * Membungkus operasi terhadap table master.jenis_referensi
 * Referensi mempunya Jenis berupa VARCHAR yang menunjuk salah satu nama dalam Jenis Referensi
 * Referensi tidak menggunakan id agar user bisa leluasa mengubah-ubah tree 
 * Tiap kali jenis diubah, perlu dipastikan ada 1 jenis khusus bernama "Lain-Lain" 
 * Referensi yang jenisnya tidak valid karena terjadi perubahan nama atau penghapusan jenis,
 *    akan otomatis masuk jenis "Lain-Lain"
 *
 * @author Kanwil
 */

class Cms_Model_JenisReferensi
{
	protected $_tableName = 'master.jenis_referensi';
	protected $_table = null;
	// array of array('node'=>array, 'children'=>array)
	protected $_data;
	
	private $_id = 0; // counter
	
	public function __construct()
	{
		// fill data
		$table = $this->table();
		// convert hasil fetch database
		$rows = $table->fetchAll()->toArray();
		$this->_data = App_Helper::rowsToTree($rows);
	}
	
	public function table()
	{
		if (!isset($this->_table)) {
			$this->_table = new Zend_Db_Table($this->_tableName);
		}
		return $this->_table;
	}
	
	/**
	 * @return array siap untuk dijadikan JSON
	 */
	public function toJson()
	{
		return $this->_jsonRecursive($this->_data);
	}
	
	protected function _jsonRecursive($arr) 
	{
		$result = array();
		foreach ($arr as $row) {
			$obj = array();
			$obj['data'] = $row['node']['nama'];
			$obj['state'] = 'open';
			$children = $this->_jsonRecursive($row['children']);
			if (count($children)) {
				$obj['children'] = $children;
			}
			$result[] = $obj;
		}
		return $result;
	}
	
	/**
	 * @param array $post data kiriman
	 */
	public function setFromPost($post)
	{
		// $post['json'] berisi text representasi JSON seluruh tree
		$json = json_decode($post['json']);
		// print_r($json);exit;
		$this->_data = $this->_postRecursive($json);
	}
	
	protected function _postRecursive($arr) 
	{
		$result = array();
		foreach ($arr as $row) {
			$obj = array('node' => array('nama' => $row->data), 'children' => array());
			if (isset($row->children)) {
				$obj['children'] = $this->_postRecursive($row->children);
			}
			$result[] = $obj;
		}
		return $result;
	}
	
	/**
	 * @return boolean true jika siap disimpan
	 */
	public function isValid()
	{
		// @TODO
		return true;
	}
	
	/**
	 * Menyimpan data ke database
	 */
	public function save()
	{
		$table = $this->table();
		// delete old data
		$table->delete(null);
		// save depth-first
		$this->_id = 1;
		$this->_saveRecursive($this->_data, null);
		// pastikan ada jenis "Lain-Lain"
		$etc = 'Lain-Lain';
		$query = $table->select()
			->from($this->_tableName, array(new Zend_Db_Expr('COUNT(id)')))
			->where('nama = ?', $etc)
		;
		$etcCount = $table->getAdapter()->fetchOne($query);
		if ($etcCount < 1) {
			$table->insert(array(
				'id' => $this->_id,
				'nama' => $etc,
				'parent_id' => null,
			));
			$this->_data[] = array(
				'node' => array('nama' => $etc),
				'children' => array(),
			);
		}
		// pengecekan jenis referensi
		// semua Referensi yang nama jenisnya sudah tidak ada dialihkan ke Lain-Lain
		$refTable = new Zend_Db_Table('master.referensi');
		$refTable->update(array('jenis' => $etc), 'jenis NOT IN (SELECT nama FROM '.$this->_tableName.')');
	}
	
	protected function _saveRecursive($arr, $parentId)
	{
		$table = $this->table();
		foreach ($arr as $node) {
			$id = $this->_id++;
			// save this node
			$table->insert(array(
				'id' => $id,
				'nama' => $node['node']['nama'],
				'parent_id' => $parentId,
			));
			// save this node's children
			$this->_saveRecursive($node['children'], $id);
		}
	}
}