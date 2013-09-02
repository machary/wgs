<?php
/**
 * Model CRUD untuk table cbl_pangkalan
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Peta_Model_Crud_PangkalanPendukung extends App_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'public';
	protected $_tableName = 'cbl_pangkalan';
	protected $_customElements = array();

    public function form()
    {
        $this->_customElements = array(
        	        		'id_pangkalan' => array(
        	        			'select',
        	        			'required' => true,
        	        			'filter' => array('StringTrim'),
        	                    'label' => 'Pangkalan',
        	                    'multiOptions' => $this->listAllLanal(),
        	        		),
        	        		'id_cb_logistik' => array(
        	        			'hidden',
        	        		)
        	            );
        return parent::form();
    }

	function simpanpp($data)
	{
		$table = $this->table();
		$table->insert($data);
		$this->_id = $table->getAdapter()->lastInsertId();

		return $this->_id;
	}

	function deletepp($where)
	{
		$table = $this->table();

		$table->delete($where);
		$this->_id = null;
	}

	public function petaPPendukung($idcblog)
	{
//		$x = $this->get_param();
		$table = new Zend_Db_Table($this->_tableSchema.'.'.$this->_tableName);
		$query = $table->select()->setIntegrityCheck(false)
			->from(array('ppendukung' => 'public.cbl_pangkalan'))
			->joinLeft(array('mpangkalan' => 'master.pangkalan'), 'mpangkalan."idpangkalan" = ppendukung."id_pangkalan"')
			->joinLeft(array('geopangkalan' => 'public.lanal'), 'geopangkalan."id_master" = ppendukung."id_pangkalan"')
			->where('id_cb_logistik = ?',$idcblog)
		;

		return $table->fetchAll($query)->toArray();
	}


    public function listAllLanal()
    {

        $db = Zend_db_Table::getDefaultAdapter();
        $selectDetectType = new Zend_Db_Select($db);
        $selectDetectType->from('public.lanal')
                         ->order('lanal ASC');

        $raw = $db->fetchAll($selectDetectType);
        $result = array('' => '[Pilih]');

        foreach ($raw as $row) {
//            array_push( $result, array( 'gid' => $row['gid'], 'lanal' => $row['lanal']));
            $result[$row['gid']] = $row['lanal'];
        }

        return $result;
    }

}