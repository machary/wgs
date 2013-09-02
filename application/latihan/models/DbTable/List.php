<?php
/**
 * Created by JetBrains PhpStorm.
 * User: MacHary
 * Date: 4/25/12
 * Time: 11:11 AM
 * To change this template use File | Settings | File Templates.
 */

class Latihan_Model_DbTable_List
{
    public function listAllJabatan()
    {
        $db = Zend_db_Table::getDefaultAdapter();
        $r_select = new Zend_Db_Select($db);
        $r_select->from('master.M_JABATAN');

        $result = $db->fetchAll($r_select);

        return $result;
    }

    public function listAllRol()
    {
        $db = Zend_db_Table::getDefaultAdapter();
        $r_select = new Zend_Db_Select($db);
        $r_select->from('latihan.rol');

        $result = $db->fetchAll($r_select);

        return $result;
    }

    public function listSkenarioAktif()
    {
        $db = Zend_db_Table::getDefaultAdapter();
        $r_select = new Zend_Db_Select($db);
        $r_select->from(array('skenario' => 'latihan.skenario'), array('skenario.id', 'skenario.nomor'))
                 ->where('latihan."skenario"."closed" = 0 ');

        $result = $db->fetchAll($r_select);

        return $result;
    }
}