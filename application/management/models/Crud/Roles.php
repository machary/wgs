<?php
/**
 * Model untuk table User Login
 * 
 * @author Kanwil
 */
 
class Management_Model_Crud_Roles extends Management_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'user';
	protected $_tableName = 'roles';

	protected $_customElements = array(
		'kogas' => array(
			'select',
			'label' => 'Kogas',
			'required' => false,
			'multiOptions' => array(),
		),

        'panglima' => array(
            'select',
            'label' => 'Sebagai Panglima',
            'required' => false,
            'multiOptions' => array(
                        0 => 'Tidak',
                        1 => 'Ya',
                    ),
            'value' => 0,
        ),
	);


    protected function _init()
    {
        $jabatan = array(''=>'Pilih');
        $modJabatan = new Cms_Model_DbTable_Jabatan();
        foreach($modJabatan->getjabatan() as $jab)
        {
            $jabatan[$jab['id_jabatan']] = $jab['nama_jabatan'];
        }

        $this->_customElements['kogas']['multiOptions'] = $jabatan;
    }

}
