<?php

class Latihan_Model_DbTable_MateriProdukNaskah extends Zend_Db_Table_Abstract
{
    protected $_name = 'latihan.materi_produk_naskah';
    protected $_tableName = 'latihan.materi_produk_naskah';
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

    public function getParentDetil($team) {
        $query = $this->select()
            ->from($this->_tableName )
            ->where('parent = 0')
            ->where('team_id =?', $team)
            ->order('id');

        $result = $this->fetchAll($query);
        if(count($result))
        {
            return $result->toArray();
        }else{
            return array();
        }
    }

    public function getParentMateri($team) {
        $query = $this->select()
            ->setIntegrityCheck(false)
            ->from(array( 'now' => $this->_tableName))
            ->join(array('parent' => $this->_tableName), 'now.parent = parent.id', null)
            ->where('parent.parent = 0')
            ->where('now.team_id =?', $team)
            ->order('id');

        $result = $this->fetchAll($query);
        if(count($result))
        {
            return $result->toArray();
        }else{
            return array();
        }
    }

    public function getMateriByParent($team, $parent) {
        $query = $this->select()
            ->from($this->_tableName )
            ->where('parent = ?', $parent)
            ->where('team_id =?', $team)
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

        $copyParentMateriData = $this->getParentDetil($copyId);

        $topParentID = null;
        $TEMPtopParentID = null;
        $middleParentID = null;
        $TEMPmiddleParentID = null;
        foreach($copyParentMateriData as $topParent) {
            $TEMPtopParentID = $topParent['id'];
            unset($topParent['id']);
            $topParent['team_id'] = $idTeam;
            $topParentID = $this->insert($topParent);
            foreach($this->getParentMateri($copyId, $TEMPtopParentID) as $middleParent) {
                $TEMPmiddleParentID = $middleParent['id'];
                unset($middleParent['id']);
                $middleParent['team_id'] = $idTeam;
                $middleParent['parent'] = $topParentID;
                $middleParentID = $this->insert($middleParent);
                foreach($this->getMateriByParent($copyId, $TEMPmiddleParentID) as $lastParent) {
                    unset($lastParent['id']);
                    $lastParent['team_id'] = $idTeam;
                    $lastParent['parent'] = $middleParentID;
                    $this->insert($lastParent);
                }
            }
        }
    }

}

?>