<?php
class Latihan_Model_Crud_PenilaianIndividu extends Zend_Db_Table_Abstract
{
    protected $_name = 'latihan.penilaian_individu';
    protected $_tableName = 'latihan.penilaian_individu';
    protected $_primary = 'id';

    protected $_row = array();

    public function getKogas($id, $team){
        if(!$id) {
            return false;
        } else {

            $query = $this->select()->setIntegrityCheck(false)
                        ->from(array('logins' => 'user.logins'))
                        ->joinLeft(array('roles' => 'user.roles'), 'logins.role_id = roles.id', null)
                        ->joinLeft(array('nilai' => $this->_tableName), 'logins.id = nilai.login_id', array('nilai.nilai', 'nilai.keterangan'))
                        ->where('roles.kogas = ?', $id)
                        ->where('logins.id_team = ?', $team)
                        ->order('logins.nama');

            $result = $this->fetchAll($query);
            if(!empty($result))
            {
                return $result->toArray();
            }
            else
            {
                return null;
            }
        }
    }

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

    public function getByLogin($id){
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('nilai' => $this->_tableName))
            ->where('nilai.login_id = ?', $id);

        $result = $this->fetchRow($query);

        if(!empty($result))
        {
            return $result->toArray();
        }
        else
        {
            return null;
        }
    }

    public function save($idLogin,$data) {
        $login = $this->getByLogin($idLogin);
        if(empty($login)) {
            if($data['nilai'] OR $data['keterangan']){
                $this->insert($data);
            }
        } else {
            if(!$data['nilai']) $data['nilai'] = 0;
            $this->update($data, "login_id = ".$idLogin);
        }
    }

}