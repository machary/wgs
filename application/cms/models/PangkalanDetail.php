<?php

/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_PangkalanDetail
{
    //field
    protected $_id = null;
	protected $_nama;
	protected $_puan_fasharkan;
	protected $_jenis_pangkalan;

	//relation field
	protected $_idparent = null;
	protected $_namaparent = null;
	protected $_childs = null;
	protected $_fas_bengkel = null;
	protected $_fas_dermaga = null;
	protected $_fas_dock = null;
	protected $_fas_labuh = null;
	protected $_fas_listrik = null;
	protected $_fas_ranmor = null;
	protected $_fas_tanah = null;
	protected $_fas_umum = null;
	protected $_fas_bek = null;
	protected $_fas_rumah = null;
	protected $_fas_mess = null;
	protected $_fas_rumah_sakit = null;

	//pangkalan pendukung
	protected $_pp = null;

    //object properties
    protected $_primary = 'idpangkalan';
    protected $_table = null;

    public function __construct($id = null)
    {
        if (isset($id)) {

            // coba cari
            $table = $this->table();
            $rowset = $table->find($id);
            if (count($rowset) > 0) {
                $this->_id = $id;
                $this->setFromRow($rowset->current());
            } else {
                $this->_id = null;
            }
        } else {
            $this->_id = null;
        }
    }

    /**
     * Apakah Pangkalan ini ada di database?
     * @return bool
     */
    public function exists()
    {
        return $this->_id !== null;
    }

    /**
     * Mengembalikan kondisi where untuk object ini
     */
    public function where()
    {
        $db = $this->table()->getAdapter();
        return $db->quoteInto("\"{$this->_primary}\" = ?", $this->_id);
    }

	public function getParent()
	{
		$parent = new Cms_Model_DbTable_Pangkalan();
		$rowset = $parent->find($this->_idparent);
		if (count($rowset) > 0) {
			$rowparent = $rowset->current();
			$this->_namaparent = $rowparent['nama'];
		}else{
			$this->_namaparent = null;
		}
	}

	public function getChilds()
	{
		$dbtable = new Cms_Model_DbTable_Pangkalan();
		$this->_childs = $dbtable->getIdParent($this->_id);
	}

	public function getFasBengkel()
	{
		$dbtable = new Cms_Model_DbTable_FasBengkel();
		$this->_fas_bengkel = $dbtable->getIdPangkalan($this->_id);
	}

	public function getFasDock()
	{
		$dbtable = new Cms_Model_DbTable_FasDock();
		$this->_fas_dock = $dbtable->getIdPangkalan($this->_id);
	}

	public function getFasDermaga()
	{
		$dbtable = new Cms_Model_DbTable_FasDermaga();
		$this->_fas_dermaga = $dbtable->getIdPangkalan($this->_id);
	}

	public function getFasLabuh()
	{
		$dbtable = new Cms_Model_DbTable_FasLabuh();
		$this->_fas_labuh = $dbtable->getIdPangkalan($this->_id);
	}

	public function getFasListrik()
	{
		$dbtable = new Cms_Model_DbTable_FasListrik();
		$this->_fas_listrik = $dbtable->getIdPangkalan($this->_id);
	}

	public function getFasRanmor()
	{
		$dbtable = new Cms_Model_DbTable_FasRanmor();
		$this->_fas_ranmor = $dbtable->getIdPangkalan($this->_id);
	}

	public function getFasTanah()
	{
		$dbtable = new Cms_Model_DbTable_FasTanah();
		$this->_fas_tanah = $dbtable->getIdPangkalan($this->_id);
	}

	public function getFasUmum()
	{
		$dbtable = new Cms_Model_DbTable_FasUmum();
		$this->_fas_umum = $dbtable->getIdPangkalan($this->_id);
	}

	public function getFasRumah()
	{
		$dbtable = new Cms_Model_DbTable_FasRumah();
		$this->_fas_rumah = $dbtable->getIdPangkalan($this->_id);
	}

	public function getFasMess()
	{
		$dbtable = new Cms_Model_DbTable_FasMess();
		$this->_fas_mess = $dbtable->getIdPangkalan($this->_id);
	}

	public function getFasRumahSakit()
	{
		$dbtable = new Cms_Model_DbTable_FasRumahSakit();
		$this->_fas_rumah_sakit = $dbtable->getIdPangkalan($this->_id);
	}

	public function getFasBek()
	{
		$dbtable = new Cms_Model_DbTable_FasBek();
		$this->_fas_bek = $dbtable->getIdPangkalan($this->_id);
	}

	public function getPp($id_cbl)
	{
		$dbtable = new Cms_Model_DbTable_PangkalanPendukung();
		$this->_pp = $dbtable->getIdPangkalan($this->_id,$id_cbl);
	}

	/**
	 * Memetakan kolom-kolom dari row database ke property
	 * @param Zend_Db_Table_Row|array $row
	 */
	public function setFromRow($row)
	{
		if (isset($row['idpangkalan'])) {
			$this->_id = $row['idpangkalan'];
			$this->getChilds();
			$this->getFasBengkel();
			$this->getFasDock();
			$this->getFasDermaga();
			$this->getFasLabuh();
			$this->getFasListrik();
			$this->getFasRanmor();
			$this->getFasTanah();
			$this->getFasUmum();
			$this->getFasRumah();
			$this->getFasRumahSakit();
			$this->getFasMess();
			$this->getFasBek();
		}
		if (isset($row['idparent'])) {
			$this->_idparent = $row['idparent'];
			$this->getParent();
		}
		$this->_nama = $row['nama'];
		$this->_puan_fasharkan = $row['puan_fasharkan'];
		$this->_jenis_pangkalan = $row['jenis_pangkalan'];
	}

	/**
	 * Kembalikan array dengan nama key sesuai nama input field form
	 * @return array
	 */
	public function toFormArray()
	{
		return array(
			'idpangkalan' => $this->_id,
			'idparent' => $this->_idparent,
			'namaparent' => $this->_namaparent,
			'nama' => $this->_nama,
			'puan_fasharkan' => $this->_puan_fasharkan,
			'jenis_pangkalan' => $this->_jenis_pangkalan,
			'childs' => $this->_childs,
			'fas_bengkel' => $this->_fas_bengkel,
			'fas_dock' => $this->_fas_dock,
			'fas_dermaga' => $this->_fas_dermaga,
			'fas_labuh' => $this->_fas_labuh,
			'fas_listrik' => $this->_fas_listrik,
			'fas_ranmor' => $this->_fas_ranmor,
			'fas_tanah' => $this->_fas_tanah,
			'fas_umum' => $this->_fas_umum,
			'fas_rumah' => $this->_fas_rumah,
			'fas_rumah_sakit' => $this->_fas_rumah_sakit,
			'fas_mess' => $this->_fas_mess,
			'fas_bek' => $this->_fas_bek,
			'pp' => $this->_pp,
		);
	}

    /**
     * Mengembalikan object dbtable utama Pangkalan
     * @return Zend_Db_Table
     */
    public function table()
    {
        if (!$this->_table) {
            $this->_table = new Cms_Model_DbTable_Pangkalan();
        }
        return $this->_table;
    }

}