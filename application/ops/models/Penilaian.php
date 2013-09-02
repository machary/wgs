<?php
/**
 * Base CRUD CB Operasional
 * 
 * @author Febi
 */
 
class Ops_Model_Penilaian extends App_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'ops';
	protected $_tableName = 'rekap';
	protected $_ignoreCols = array('keterangan');
	
	protected $_foreignKeys = array();
	
	protected $_customElements = array();



    public function exists()
    {
        $table = $this->table();

        if($this->get('id_cb') AND $this->get('id_rute'))
        {
            $exist = $table->fetchRow("id_cb = {$this->get('id_cb')} AND id_rute = {$this->get('id_rute')} ");
            if(isset($exist['id']))
            {
                $this->_id = $exist['id'];
                return $this->_id !== null;
            }
        }
        return null;
    }

    public function getScore( $idCB, $idRute, $jenis, $column = null)
    {
        $table = $this->table();
        $exist = $table->fetchRow("id_cb = {$idCB} AND id_rute = {$idRute} AND jenis = '{$jenis}'");
        if($column)
        {
            return $exist[$column];
        }
        return $exist;
    }

    public function getreport()
    {
        $query = $this->select()->setIntegrityCheck(false)
                    ->from(array('rekap' =>$this->_tableName))
                    ->join(array('tim' => 'user.Team'), 'tim."id" = rekap."id_team"', array('tim.team_name'))
                    ->join(array('ps' => 'master.M_PRODUCT_STAFF'), 'ps."id" = rekap."id_product_staff"', array('ps.nama_product_staff'))
					->order('tim.team_name ASC')
        ;

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