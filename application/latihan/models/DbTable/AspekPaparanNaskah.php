<?php

class Latihan_Model_DbTable_AspekPaparanNaskah extends Zend_Db_Table_Abstract
{
    protected $_name = 'latihan.aspek_paparan_naskah';
    protected $_tableName = 'latihan.aspek_paparan_naskah';
    protected $_primary = 'id';

    public function all()
    {
        $query = $this->select()
                 ->from($this->_tableName )
                 ->order('id');

        $result = $this->fetchAll($query);
        if(count($result))
        {
            return $result->toArray();
        }else{
            return array();
        }
    }

    public function allByTeamId($id)
    {
        $query = $this->select()
                 ->from($this->_tableName )
                 ->where('team_id =?', $id)
                 ->order('id');

        $result = $this->fetchAll($query);
        if(count($result))
        {
            return $result->toArray();
        }else{
            return array();
        }
    }

    public function copy($idTeam, $copyId) {

        $this->delete("team_id = {$idTeam}");

        $copyData = $this->allByTeamId($copyId);

        foreach($copyData as $copy) {
            unset($copy['id']);
            $copy['team_id'] = $idTeam;
            $this->insert($copy);
        }

    }

}

?>