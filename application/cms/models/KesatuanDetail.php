<?php

/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_KesatuanDetail
{
    //field
    protected $_id;

    //object properties
    protected $_primary = 'idkesatuan';
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
     * Apakah kesatuan ini ada di database?
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

    /**
     * Mengembalikan object dbtable utama kesatuan
     * @return Zend_Db_Table
     */

    public function table()
    {
        if (!$this->_table) {
            $this->_table = new Cms_Model_DbTable_Kesatuan();
        }
        return $this->_table;
    }

    /**
     * Memetakan input field milik form ke property sendiri
     * @param Cms_Form_Kesatuan|array $form
     */
    public function setFromForm($form)
    {
        if (is_a($form, 'Zend_Form')) {
            $form = $form->getValues();
			if ($form['idparent'] === '') {
				unset($form['idparent']);
			}
        }

		$this->_idparent = $form['idparent'];
		$this->_idkomando = $form['idkomando'];
		$this->_nama_kesatuan = $form['nama_kesatuan'];
        $this->_jenis_kesatuan = $form['jenis_kesatuan'];
        $this->_jenis_pasukan = $form['jenis_pasukan'];
        $this->_matra = $form['matra'];
    }

    /**
     * Memetakan kolom-kolom dari row database ke property
     * @param Zend_Db_Table_Row|array $row
     */
    public function setFromRow($row)
    {
        if (isset($row['idkesatuan'])) {
            $this->_id = $row['idkesatuan'];
        }
		$this->_idparent = $row['idparent'];
		$this->_idkomando = $row['idkomando'];
		$this->_nama_kesatuan = $row['nama_kesatuan'];
        $this->_jenis_kesatuan = $row['jenis_kesatuan'];
        $this->_jenis_pasukan = $row['jenis_pasukan'];
        $this->_matra = $row['matra'];
    }

    /**
     * Kembalikan array dengan nama key sesuai nama input field form
     * @return array
     */
    public function toFormArray()
    {
        return array(
			'idparent' => $this->_idparent,
			'idkomando' => $this->_idkomando,
			'nama_kesatuan' => $this->_nama_kesatuan,
            'jenis_kesatuan' => $this->_jenis_kesatuan,
            'jenis_pasukan' => $this->_jenis_pasukan,
            'matra' => $this->_matra,
        );
    }

    /**
     * Kembalikan array dengan nama key sesuai nama kolom
     * @return array
     */
    public function toRowArray($withId = false)
    {
        $row = array(
			'idparent' => $this->_idparent,
			'idkomando' => $this->_idkomando,
			'nama_kesatuan' => $this->_nama_kesatuan,
            'jenis_kesatuan' => $this->_jenis_kesatuan,
            'jenis_pasukan' => $this->_jenis_pasukan,
            'matra' => $this->_matra,

        );
        if ($withId) {
            $row['idkesatuan'] = $this->_id;
        }
        return $row;
    }

    /**
     * Simpan penambahan/perubahan ke database
     */
    public function save()
    {
        $table = $this->table();
        if ($this->exists()) {
            $table->update($this->toRowArray(), $this->where());
        } else {
            $table->insert($this->toRowArray());
            $this->_id = $table->getAdapter()->lastInsertId();
        }
    }

    /**
     * Hapus kesatuan ini
     */
    public function delete()
    {
        if (!$this->exists()) return;
        $table = $this->table();
        $table->delete($this->where());
        $this->_id = null;
    }

}