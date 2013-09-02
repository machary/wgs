<?php

class Latihan_Model_DbTable_Rol extends Zend_Db_Table_Abstract
{
    protected $_name = 'latihan.rol';
    protected $_tableName = 'latihan.rol';
    protected $_primary = 'id_rol';

    public function addRol($post){
        $data = array(
            'realtime_start' => $post['realtime_start'],
            'realtime_end' => $post['realtime_end'],
            'asumsi_start' => $post['asumsi_start'],
            'asumsi_end' => $post['asumsi_end'],
            'nama_kegiatan' => $post['nama_kegiatan'],
            'id_jabatan' => $post['id_jabatan'],
            'id_skenario' => $post['id_skenario'],
            'asumsi_perbandingan' => $post['asumsi_perbandingan']
        );
        $this->insert($data);
    }

    public function updateRol($post,$id)
    {
        $data = array(
            'realtime_start' => $post['realtime_start'],
            'realtime_end' => $post['realtime_end'],
            'asumsi_start' => $post['asumsi_start'],
            'asumsi_end' => $post['asumsi_end'],
            'nama_kegiatan' => $post['nama_kegiatan'],
            'id_jabatan' => $post['id_jabatan'],
            'id_skenario' => $post['id_skenario'],
            'asumsi_perbandingan' => $post['asumsi_perbandingan']
        );

        $this->update($data, 'latihan."rol"."id_rol" = '.$id.'');
    }

    public function deleteRol($id)
    {
        $this->delete('latihan."rol"."id_rol" = '.$id.'');
    }

    public function getRol($startTime, $endTime, $jabatanID = null, $skenarioID = null)
    {
        $startTime = date('Y-m-d g:i:s', $startTime);
        $endTime = date('Y-m-d g:i:s', $endTime);
        //where query for getting event that overlapping given date - avoid using native postgre
        //OVERLAPS since risking error occured caused by locale
        $whereQuery   = "to_date('".$startTime."','YYYY-MM-DD HH24:MI:SS') <= rol.realtime_end";
        $whereQuery  .= " AND to_date('".$endTime."','YYYY-MM-DD HH24:MI:SS') >= rol.realtime_end)";
        $whereQuery  .= " OR (to_date('".$startTime."','YYYY-MM-DD HH24:MI:SS') <= rol.realtime_start";
        $whereQuery  .= " AND to_date('".$endTime."','YYYY-MM-DD HH24:MI:SS') >= rol.realtime_start)";
        $whereQuery  .= " OR (to_date('".$startTime."','YYYY-MM-DD HH24:MI:SS') <= rol.realtime_start";
        $whereQuery  .= " AND to_date('".$endTime."','YYYY-MM-DD HH24:MI:SS') >= rol.realtime_end)";
        $whereQuery  .= " OR (to_date('".$startTime."','YYYY-MM-DD HH24:MI:SS') >= rol.realtime_start";
        $whereQuery  .= " AND to_date('".$endTime."','YYYY-MM-DD HH24:MI:SS') <= rol.realtime_end";

        $query = $this->select()
                 ->from(array('rol' => $this->_tableName ))
                 ->where($whereQuery)
                 ->where('id_skenario = ?', $skenarioID);
        if($jabatanID != 0 AND $jabatanID != null)
        {
            $query->where('id_jabatan = ?', $jabatanID);
        }
        $result = $this->fetchAll($query);
        if(count($result))
        {
            return $result->toArray();
        }else{
            return array();
        }
    }

    public function getRolByRolID($rolID)
    {
        $query = $this->select()
                 ->from(array('rol' => $this->_tableName))
                 ->where('rol.id_rol = ?', $rolID);
        $result = $this->fetchRow($query);
        if(count($result))
        {
            return $result->toArray();
        }else{
            return null;
        }
    }

}

?>