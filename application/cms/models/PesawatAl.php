<?php

/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_PesawatAl
{
    //field
    protected $_id;
    protected $_nama;

    //object properties
    protected $_primary = 'pesawat_al_id';
    protected $_table = null;
    protected $_data = null;

//    protected $_objectType = null;
//    protected $_objectCountry = null;

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

    public function datatablesJSONApi($sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC', $search = '')
    {

        $DbTable = new Cms_Model_DbTable_PesawatAl();
        $data = $DbTable->getalldata($limit, $offset, $sortColumn, $order, $search, $count = false);
        $queryrowsCount = $DbTable->getalldata($limit, $offset, $sortColumn, $order, $search, $count = true);

        $jsonString = array();
        if($queryrowsCount > 0)
        {
            $temp = array();
            $hUrl = new Zend_View_Helper_Url();
            foreach($data as $dat)
            {
				$temp['no_lamb'] = '<a href="'.$hUrl->url(array('action'=>'view-pesawat-al', 'id'=>$dat['pesawat_al_id'])).'">'.$dat['no_lamb'].'</a>';
                $temp['tahun_pakai'] = $dat['tahun_pakai'];

                $temp['edel'] = '<a href="'.$hUrl->url(array('action'=>'edit-pesawat-al', 'id'=>$dat['pesawat_al_id'])).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del-pesawat-al', 'id'=>$dat['pesawat_al_id'])).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';

                array_push($jsonString, array_values($temp));
            }
        }

        $jsonArray = array();
        $jsonArray[ 'sEcho' ] = $sEcho;
        $jsonArray[ 'iTotalRecords' ] = $queryrowsCount;
        $jsonArray[ 'iTotalAllValue' ] = $queryrowsCount;
        $jsonArray[ 'iTotalDisplayRecords' ] = $queryrowsCount;
        $jsonArray[ 'aaData' ] = $jsonString;

        return json_encode( $jsonArray );
    }

    /**
     * Apakah Ship ini ada di database?
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
     * Mengembalikan object dbtable utama Ship
     * @return Zend_Db_Table
     */

    public function table()
    {
        if (!$this->_table) {
            $this->_table = new Cms_Model_DbTable_PesawatAl();
        }
        return $this->_table;
    }

    /**
     * @param Cms_Form_Ship|array $form
     */
    public function setFromForm($form)
    {
        if (is_a($form, 'Zend_Form')) {
            $form = $form->getValues();
        }
        $this->_no_lamb = $form['no_lamb'];
        $this->_tahun_pakai = $form['tahun_pakai'];
        $this->_lama_terbang = $form['lama_terbang'];
        $this->_kelas = $form['kelas'];
        $this->_jenis = $form['jenis'];
        $this->_keterangan = $form['keterangan'];
        $this->_pesawat_jenis_id = $form['pesawat_jenis_id'];
    }

    /**
     * Memetakan kolom-kolom dari row database ke property
     * @param Zend_Db_Table_Row|array $row
     */
    public function setFromRow($row)
    {
        if (isset($row['pesawat_al_id'])) {
            $this->_id = $row['pesawat_al_id'];
        }
        $this->_no_lamb = $row['no_lamb'];
        $this->_tahun_pakai = $row['tahun_pakai'];
        $this->_lama_terbang = $row['lama_terbang'];
        $this->_kelas = $row['kelas'];
        $this->_jenis = $row['jenis'];
        $this->_keterangan = $row['keterangan'];
        $this->_pesawat_jenis_id = $row['pesawat_jenis_id'];
    }


    /**
     * @param $_POST
     */
    public function setFromPost( $post )
    {
        if( !empty($post) ) $this->_data = $post;
    }

    /**
     * Kembalikan array dengan nama key sesuai nama input field form
     * @return array
     */
    public function toFormArray()
    {
        return array(
            'no_lamb' => $this->_no_lamb,
            'tahun_pakai' => $this->_tahun_pakai,
            'lama_terbang' => $this->_lama_terbang,
            'kelas' => $this->_kelas,
            'jenis' => $this->_jenis,
            'keterangan' => $this->_keterangan,
            'pesawat_jenis_id' => $this->_pesawat_jenis_id,
        );
    }

    /**
     * Kembalikan array dengan nama key sesuai nama kolom
     * @return array
     */
    public function toRowArray($withId = false)
    {
        $row = array(
            'no_lamb' => $this->_no_lamb,
            'tahun_pakai' => $this->_tahun_pakai,
            'lama_terbang' => $this->_lama_terbang,
            'kelas' => $this->_kelas,
            'jenis' => $this->_jenis,
            'keterangan' => $this->_keterangan,
            'pesawat_jenis_id' => $this->_pesawat_jenis_id,
        );
        if ($withId) {
            $row['pesawat_al_id'] = $this->_id;
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
     * Hapus Ship ini
     */
    public function delete()
    {
        if (!$this->exists()) return;
        $table = $this->table();
        $table->delete($this->where());
        $this->_id = null;
    }

}