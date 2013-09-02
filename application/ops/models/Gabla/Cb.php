<?php
/**
 * Kogasgabla CRUD CB
 *
 * @author Kanwil
 */
 
class Ops_Model_Gabla_Cb extends Ops_Model_Cb
{
	protected $_tableName = 'gabla_cb';

    public function getallps($id)
    {
        $table = new Zend_Db_Table('ops.gabla_cb');

        $query = $table->select()->setIntegrityCheck(false)
            ->from(array('prod' => 'ops.gabla_cb'))
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