<?php
class Latihan_Model_Crud_PenilaianPaparanNaskah extends Zend_Db_Table_Abstract
{
    protected $_name = 'latihan.penilaian_paparan_naskah';
    protected $_tableName = 'latihan.penilaian_paparan_naskah';
    protected $_primary = 'id';

    protected $_row = array();

    public function kogasTranslate($kogas){
//        $kogasData = array( 1 => 'Kogasgabla',
//            2 => 'Kogasgabfib',
//            3 => 'Pasrat',
//            4 => 'Kogasgabratmin',
//            5 => 'Kogasgablinud',
//            6 => 'Kogasgabrat',
//            7 => 'Kogasud',
//            8 => 'Kobangdikal',
//            9 => 'Seskoal',
//            10 => 'Kolinlamil',
//            11 => 'Koarmabar',
//            12 => 'Koarmatim',
//            13 => 'AAL',
//            14 => 'Mabesal',
//            15 => 'Mabesal2',
//            16 => 'Mabesal3',
//            17 => 'Kormar');
//
//        return array_search($kogas, $kogasData);
    }

    public function getKogas(){
//        $kogasData = array( 1 => 'Kogasgabla',
//            2 => 'Kogasgabfib',
//            3 => 'Pasrat',
//            4 => 'Kogasgabratmin',
//            5 => 'Kogasgablinud',
//            6 => 'Kogasgabrat',
//            7 => 'Kogasud',
//            8 => 'Kobangdikal',
//            9 => 'Seskoal',
//            10 => 'Kolinlamil',
//            11 => 'Koarmabar',
//            12 => 'Koarmatim',
//            13 => 'AAL',
//            14 => 'Mabesal',
//            15 => 'Mabesal2',
//            16 => 'Mabesal3',
//            17 => 'Kormar');
//
//        return $kogasData;
    }

    public function getByAspekKogas($idAspek, $kogas, $field = null){
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('nilai' => $this->_tableName))
            ->where('nilai.aspek_id = ?', $idAspek)
            ->where('nilai.kogas = ?', $kogas);

        $result = $this->fetchRow($query);
		
        if(!empty($result))
        {
            if($field)
                return $result->$field;
            else
                return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function save($idAspek, $kogas, $data) {
        $login = $this->getByAspekKogas($idAspek,$kogas);
        if(empty($login)) {
            if($data['nilai'] OR $data['keterangan']){
                $this->insert($data);
            }
        } else {
            if(!$data['nilai']) $data['nilai'] = 0;
            $this->update($data, array("aspek_id = {$idAspek}", "kogas = {$kogas}"));
        }
    }

}