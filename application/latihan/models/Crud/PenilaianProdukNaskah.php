<?php
class Latihan_Model_Crud_PenilaianProdukNaskah extends Zend_Db_Table_Abstract
{
    protected $_name = 'latihan.penilaian_produk_naskah';
    protected $_tableName = 'latihan.penilaian_produk_naskah';
    protected $_primary = 'id';

    protected $_row = array();

    public function getKogas($kogas){
        $id = $this->kogasTranslate($kogas);

        if(!$id) {
            return false;
        } else {

            $query = $this->select()->setIntegrityCheck(false)
                        ->from(array('logins' => 'user.logins'))
                        ->joinLeft(array('roles' => 'user.roles'), 'logins.role_id = roles.id', null)
                        ->joinLeft(array('nilai' => $this->_tableName), 'logins.id = nilai.login_id', array('nilai.nilai', 'nilai.keterangan'))
                        ->where('roles.kogas = ?', $id)
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

    public function getByData($detil,$materi,$kogas,$parameter,$team_id, $field = null){
        $query = $this->select()->setIntegrityCheck(false)
            ->from(array('nilai' => $this->_tableName))
            ->where('nilai.detil = ?', $detil)
            ->where('nilai.materi = ?', $materi)
            ->where('nilai.kogas = ?', $kogas)
            ->where('nilai.team_id = ?', $team_id)
            ->where('nilai.parameter = ?', $parameter);

        $result = $this->fetchRow($query);

        if(!empty($result))
        {
            if($field){
                return $result->nilai;
            } else {
                return $result->toArray();
            }
        }
        else
        {
            return null;
        }
    }

    public function save($data) {
        $id = $this->getByData($data['detil'],$data['materi'],$data['kogas'],$data['parameter'],$data['team_id']);
        if(empty($id)) {
            if($data['nilai']){
                $this->insert($data);
            }
        } else {
            if(!$data['nilai']) $data['nilai'] = 0;
            $this->update($data, "id = ".$id['id']);
        }
    }

}