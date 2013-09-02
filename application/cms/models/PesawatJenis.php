<?php

/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_PesawatJenis
{
    //field
    protected $_id;
    protected $_nama;
    protected $_model;

    //object properties
    protected $_primary = 'pesawat_jenis_id';
    protected $_table = null;

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

        $DbTable = new Cms_Model_DbTable_PesawatJenis();
        $data = $DbTable->getalldata($limit, $offset, $sortColumn, $order, $search, $count = false);
        $queryrowsCount = $DbTable->getalldata($limit, $offset, $sortColumn, $order, $search, $count = true);

        $jsonString = array();
        if($queryrowsCount > 0)
        {
            $temp = array();
            $hUrl = new Zend_View_Helper_Url();
            foreach($data as $dat)
            {

				$temp['nama'] = '<a href="'.$hUrl->url(array('action'=>'view-pesawat-jenis', 'id'=>$dat['pesawat_jenis_id'])).'">'.$dat['nama'].'</a>';

                $temp['model'] = $dat['model'];

                $temp['edel'] = '<a href="'.$hUrl->url(array('action'=>'edit-pesawat-jenis', 'id'=>$dat['pesawat_jenis_id'])).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del-pesawat-jenis', 'id'=>$dat['pesawat_jenis_id'])).'" onclick="return confirm(\'Hati-hati!, menghapus data ini akan berakibat hilangnya data lain. Apakah anda yakin?\')">Hapus</a>';

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
            $this->_table = new Cms_Model_DbTable_PesawatJenis();
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
        $this->_nama = $form['nama'];
        $this->_model = $form['model'];
        $this->_pabrik = $form['pabrik'];
        $this->_engine = $form['engine'];
        $this->_model_engine = $form['model_engine'];
        $this->_negara_asal = $form['negara_asal'];
        $this->_tahun_buat = $form['tahun_buat'];
        $this->_kec_full = $form['kec_full'];
        $this->_kec_jel = $form['kec_jel'];
        $this->_kec_eko = $form['kec_eko'];
        $this->_ketinggian = $form['ketinggian'];
        $this->_jarak_jel = $form['jarak_jel'];
        $this->_lama_terbang_min = $form['lama_terbang_min'];
        $this->_lama_terbang_max = $form['lama_terbang_max'];
        $this->_tangki_bb = $form['tangki_bb'];
        $this->_koms_bb_perjam = $form['koms_bb_perjam'];
        $this->_awak_pes = $form['awak_pes'];
        $this->_dayaangkut_brg = $form['dayaangkut_brg'];
        $this->_dayaangkut_org = $form['dayaangkut_org'];
    }

    /**
     * Memetakan kolom-kolom dari row database ke property
     * @param Zend_Db_Table_Row|array $row
     */
    public function setFromRow($row)
    {
        if (isset($row['pesawat_jenis_id'])) {
            $this->_id = $row['pesawat_jenis_id'];
        }
        $this->_nama = $row['nama'];
        $this->_model = $row['model'];
        $this->_pabrik = $row['pabrik'];
        $this->_engine = $row['engine'];
        $this->_model_engine = $row['model_engine'];
        $this->_negara_asal = $row['negara_asal'];
        $this->_tahun_buat = $row['tahun_buat'];
        $this->_kec_full = $row['kec_full'];
        $this->_kec_jel = $row['kec_jel'];
        $this->_kec_eko = $row['kec_eko'];
        $this->_ketinggian = $row['ketinggian'];
        $this->_jarak_jel = $row['jarak_jel'];
        $this->_lama_terbang_min = $row['lama_terbang_min'];
        $this->_lama_terbang_max = $row['lama_terbang_max'];
        $this->_tangki_bb = $row['tangki_bb'];
        $this->_koms_bb_perjam = $row['koms_bb_perjam'];
        $this->_awak_pes = $row['awak_pes'];
        $this->_dayaangkut_brg = $row['dayaangkut_brg'];
        $this->_dayaangkut_org = $row['dayaangkut_org'];
    }

    /**
     * Kembalikan array dengan nama key sesuai nama input field form
     * @return array
     */
    public function toFormArray()
    {
        return array(
            'nama' => $this->_nama,
            'model' => $this->_model,
            'pabrik' => $this->_pabrik,
            'engine' => $this->_engine,
            'model_engine' => $this->_model_engine,
            'negara_asal' => $this->_negara_asal,
            'tahun_buat' => $this->_tahun_buat,
            'kec_full' => $this->_kec_full,
            'kec_jel' => $this->_kec_jel,
            'kec_eko' => $this->_kec_eko,
            'ketinggian' => $this->_ketinggian,
            'jarak_jel' => $this->_jarak_jel,
            'lama_terbang_min' => $this->_lama_terbang_min,
            'lama_terbang_max' => $this->_lama_terbang_max,
            'tangki_bb' => $this->_tangki_bb,
            'koms_bb_perjam' => $this->_koms_bb_perjam,
            'awak_pes' => $this->_awak_pes,
            'dayaangkut_brg' => $this->_dayaangkut_brg,
            'dayaangkut_org' => $this->_dayaangkut_org,
        );
    }

    /**
     * Kembalikan array dengan nama key sesuai nama kolom
     * @return array
     */
    public function toRowArray($withId = false)
    {
        $row = array(
            'nama' => $this->_nama,
            'model' => $this->_model,
            'pabrik' => $this->_pabrik,
            'engine' => $this->_engine,
            'model_engine' => $this->_model_engine,
            'negara_asal' => $this->_negara_asal,
            'tahun_buat' => $this->_tahun_buat,
            'kec_full' => $this->_kec_full,
            'kec_jel' => $this->_kec_jel,
            'kec_eko' => $this->_kec_eko,
            'ketinggian' => $this->_ketinggian,
            'jarak_jel' => $this->_jarak_jel,
            'lama_terbang_min' => $this->_lama_terbang_min,
            'lama_terbang_max' => $this->_lama_terbang_max,
            'tangki_bb' => $this->_tangki_bb,
            'koms_bb_perjam' => $this->_koms_bb_perjam,
            'awak_pes' => $this->_awak_pes,
            'dayaangkut_brg' => $this->_dayaangkut_brg,
            'dayaangkut_org' => $this->_dayaangkut_org,
        );
        if ($withId) {
            $row['pesawat_jenis_id'] = $this->_id;
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