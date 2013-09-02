<?php

/**
 * @author irfan.muslim@sangkuriang.co.id
 */

class Cms_Model_Ship
{
    //field
    protected $_id;
    protected $_classId;
    protected $_name;
    protected $_desc;

    //object properties
    protected $_primary = 'SHIP_ID';
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

        $tableShipCategory = new Cms_Model_DbTable_Ship();
        $data = $tableShipCategory->getalldata($limit, $offset, $sortColumn, $order, $search, $count = false);
        $queryrowsCount = $tableShipCategory->getalldata($limit, $offset, $sortColumn, $order, $search, $count = true);

        $jsonString = array();
        if($queryrowsCount > 0)
        {
            $temp = array();
            $hUrl = new Zend_View_Helper_Url();
            foreach($data as $dat)
            {
				$temp['SHIP_NAME'] = '<a href="'.$hUrl->url(array('action'=>'view-ship', 'id'=>$dat['SHIP_ID'])).'">'.$dat['SHIP_NAME'].'</a>';

                $temp['SHIP_NO']        = $dat['SHIP_NO'];
                $temp['SHIP_CLASS_NAME']= $dat['SHIP_CLASS_NAME'];
                $temp['SHIP_TYPE_DESC'] = $dat['SHIP_TYPE_DESC'];

                $temp['edel'] = '<a href="'.$hUrl->url(array('action'=>'edit-ship', 'id'=>$dat['SHIP_ID'])).'">Ubah</a> |
				<a href="'.$hUrl->url(array('action'=>'del-ship', 'id'=>$dat['SHIP_ID'])).'" onclick="return confirm(\'Anda yakin?\')">Hapus</a>';

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
            $this->_table = new Cms_Model_DbTable_Ship();
        }
        return $this->_table;
    }

    /**
     * Memetakan input field milik form ke property sendiri
     * @param Cms_Form_Ship|array $form
     */
    public function setFromForm($form)
    {
        if (is_a($form, 'Zend_Form')) {
            $form = $form->getValues();
        }
        $this->_classId = $form['ship_class'];
        $this->_name = $form['ship_name'];
        $this->_desc = $form['ship_desc'];
        $this->_no = $form['ship_no'];
        $this->_launch = $form['ship_launch'];
        $this->_full = $form['ship_full'];
    }

    /**
     * Memetakan kolom-kolom dari row database ke property
     * @param Zend_Db_Table_Row|array $row
     */
    public function setFromRow($row)
    {
        if (isset($row['SHIP_ID'])) {
            $this->_id = $row['SHIP_ID'];
        }
        $this->_classId = $row['SHIP_CLASS_ID'];
        $this->_name = $row['SHIP_NAME'];
        $this->_desc = $row['SHIP_TYPE_DESC'];
        $this->_no = $row['SHIP_NO'];
        $this->_launch = $row['SHIP_LAUNCHED_DATE'];
        $this->_full = $row['SHIP_DISPLACEMENT_FULL'];
    }

    /**
     * Kembalikan array dengan nama key sesuai nama input field form
     * @return array
     */
    public function toFormArray()
    {
        return array(
            'ship_class' => $this->_classId,
            'ship_name' => $this->_name,
            'ship_desc' => $this->_desc,
            'ship_no' => $this->_no,
            'ship_launch' => $this->_launch,
            'ship_full' => $this->_full,
        );
    }

    /**
     * Kembalikan array dengan nama key sesuai nama kolom
     * @return array
     */
    public function toRowArray($withId = false)
    {
        $row = array(
            'SHIP_CLASS_ID' => $this->_classId,
            'SHIP_NAME' => $this->_name,
            'SHIP_TYPE_DESC' => $this->_desc,
            'SHIP_NO' => $this->_no,
            'SHIP_LAUNCHED_DATE' => $this->_launch,
            'SHIP_DISPLACEMENT_FULL' => $this->_full,

        );
        if ($withId) {
            $row['SHIP_ID'] = $this->_id;
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