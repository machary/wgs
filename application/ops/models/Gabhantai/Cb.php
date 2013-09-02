<?php
/**
 * Kogasgabhantai CRUD CB
 *
 * @author Kanwil
 */
 
class Ops_Model_Gabhantai_Cb extends Ops_Model_Cb
{
	protected $_tableName = 'gabhantai_cb';


    public function getallps($id)
    {
        $table = new Zend_Db_Table('ops.gabhantai_cb');

        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('prod' => 'ops.gabhantai_cb'))
            ->join(array('tim' => 'user.Team'), 'tim."id" = prod."team_id"', null)
            ->join(array('penilai' => 'user.penilai'), 'tim."id" = penilai."id_team"', null)
            ->join(array('log' => 'user.logins'), 'log."id" = penilai."id_login"', null)
            ->where('log."id" = '."'".$id."'")
        ;

        $result = $table->fetchAll($query);


        if(!empty($result))
        {
            return $result;
        }
        else
        {
            return null;
        }
    }
}