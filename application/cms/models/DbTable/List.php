<?php
class Cms_Model_DbTable_List
{
    public function selectAllCategory(){
        $db = Zend_db_Table::getDefaultAdapter();
        $selectCategory = new Zend_Db_Select($db);
        $selectCategory->from('master.M_GUN_CATEGORY');

        $result = $db->fetchAll($selectCategory);

        return $result;
    }

    public function selectAllJabatan(){
        $db = Zend_db_Table::getDefaultAdapter();
        $select = new Zend_Db_Select($db);
        $select->from('master.M_JABATAN');

        $result = $db->fetchAll($select);

        return $result;
    }

    public function selectFolderTelegram()
    {
        $db = Zend_db_Table::getDefaultAdapter();
        $select = new Zend_Db_Select($db);
        $select->from('master.folder_telegram')
                ->where("parent_id IS NULL");

        $result = $db->fetchAll($select);

        return $result;
    }

	public function selectAllPelabuhan()
	{
		$db = Zend_db_Table::getDefaultAdapter();
		$select = new Zend_Db_Select($db);
		$select ->from('pelabuhan')
                ->order('nama ASC')
            ;

		$result = $db->fetchAll($select);

		return $result;
	}

    public function selectAllRumahsakit(){
        $db = Zend_db_Table::getDefaultAdapter();
        $select = new Zend_Db_Select($db);
        $select->from('public.rumahsakit')
               ->order('x ASC')
        ;

        $result = $db->fetchAll($select);

        return $result;
    }

    public function selectAllDepoPertamina(){
        $db = Zend_db_Table::getDefaultAdapter();
        $select = new Zend_Db_Select($db);
        $select->from('public.pertamina');

        $result = $db->fetchAll($select);

        return $result;
    }

    public function selectAllSymTaktis(){
        $db = Zend_db_Table::getDefaultAdapter();
        $selectSym = new Zend_Db_Select($db);
        $selectSym->from('master.simbol_taktis')
            ->order('nama ASC');

        $result = $db->fetchAll($selectSym);

        return $result;
    }

    public function selectAllSymPergerakan()
    {
        $db = Zend_db_Table::getDefaultAdapter();
        $selectSym = new Zend_Db_Select($db);
        $selectSym->from('master.simbol_pergerakan');

        $result = $db->fetchAll($selectSym);

        return $result;
    }

    public function selectAllCountry(){
        $db = Zend_db_Table::getDefaultAdapter();
        $selectCountry = new Zend_Db_Select($db);
        $selectCountry->from('master.M_COUNTRY')
                      ->order('COUNTRY_NAME ASC') ;

        $result = $db->fetchAll($selectCountry);

        return $result;
    }

    public function selectAllMissileType(){
        $db = Zend_db_Table::getDefaultAdapter();
        $selectMissileType = new Zend_Db_Select($db);
        $selectMissileType->from('master.M_MISSILE_MID_COURSE_TYPE')
                          ->order('MISSILE_MID_COURSE_TYPE_NAME ASC')  ;

        $result = $db->fetchAll($selectMissileType);

        return $result;
    }

    public function selectAllLaunchType(){
        $db = Zend_db_Table::getDefaultAdapter();
        $selectLaunchType = new Zend_Db_Select($db);
        $selectLaunchType->from('master.M_LAUNCH_TYPE');

        $result = $db->fetchAll($selectLaunchType);

        return $result;
    }

    public function selectAllDetectType(){
        $db = Zend_db_Table::getDefaultAdapter();
        $selectDetectType = new Zend_Db_Select($db);
        $selectDetectType->from('master.M_DETECT_TYPE');

        $result = $db->fetchAll($selectDetectType);

        return $result;
    }

    public function selectAllKesatuan(){
        $db = Zend_db_Table::getDefaultAdapter();
        $selectDetectType = new Zend_Db_Select($db);
        $selectDetectType->from('master.kesatuan');

        $result = $db->fetchAll($selectDetectType);

        return $result;
    }

    public function selectAllPerahu(){
        $db = Zend_db_Table::getDefaultAdapter();
        $selectDetectType = new Zend_Db_Select($db);
        $selectDetectType->from(array('ship' => 'master.M_SHIP'), array('ship.SHIP_ID', 'ship.SHIP_CLASS_ID', 'ship.SHIP_NAME'))
                         ->join(array('class' => 'master.M_SHIP_CLASS'), 'class."SHIP_CLASS_ID" = ship."SHIP_CLASS_ID"', array('class.SHIP_CLASS_NAME'));

        $result = $db->fetchAll($selectDetectType);

        return $result;
    }

    public function selectAllPesawat(){
        $db = Zend_db_Table::getDefaultAdapter();
        $selectDetectType = new Zend_Db_Select($db);
        $selectDetectType->from('master.pesawat_jenis');

        $result = $db->fetchAll($selectDetectType);

        return $result;
    }

    public function selectAllLanal()
    {
        $db = Zend_db_Table::getDefaultAdapter();
        $selectDetectType = new Zend_Db_Select($db);
        $selectDetectType->from('master.lanal')
                         ->order('nama ASC');

        $raw = $db->fetchAll($selectDetectType);
        $result = array('' => '[PILIH]');
        foreach ($raw as $row) {
            $result[$row['idlanal']] = $row['nama'];
        }
        return $result;
    }

    public function selectAllLantamal()
    {
        $db = Zend_db_Table::getDefaultAdapter();
        $lantamal = new Zend_Db_Select($db);
        $lantamal->from('master.pangkalan')
                 ->where('master."pangkalan"."idparent" = NULL AND
                          master."pangkalan"."jenis_pangkalan" like ' . "'" . 'lantamal' . "'");

        $result = $db->fetchAll($lantamal);

        return $result;
    }

    public function selectAllLanudal()
    {
        $db = Zend_db_Table::getDefaultAdapter();
        $lantamal = new Zend_Db_Select($db);
        $lantamal->from('master.pangkalan')
            ->where('master."pangkalan"."jenis_pangkalan" like ' . "'" . 'lanudal' . "'");

        $result = $db->fetchAll($lantamal);

        return $result;
    }

    public function ConvertDate($dateadd){
        list($d, $m, $y) = explode('/', $dateadd);
        $mk=mktime(0, 0, 0, $m, $d, $y);
        $dateadd_display=strftime('%Y-%m-%d',$mk);
        return $dateadd_display;
    }

    public function ConvertDateString($dateadd){
        list($y, $m, $d) = explode('-', $dateadd);
        $mk=mktime(0, 0, 0, $m, $d, $y);
        $dateadd_display=strftime('%d/%m/%Y',$mk);
        return $dateadd_display;
    }

    public function ConvertDateStringIntel($dateadd){
        list($y, $m, $d) = explode('-', $dateadd);
        $mk=mktime(0, 0, 0, $m, $d, $y);
        $dateadd_display=strftime('%m/%d/%Y',$mk);
        return $dateadd_display;
    }

    public function dataintelijen()
    {
        $db = Zend_db_Table::getDefaultAdapter();
        $COG = new Zend_Db_Select($db);
        $COG->from('intelijen_poly');

        $result = $db->fetchAll($COG);

        return $result;
    }
}