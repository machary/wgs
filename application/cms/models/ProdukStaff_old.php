<?php
/**
 * Model untuk table produk_staff
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Model_ProdukStaff extends Cms_Model_Crud
{
	protected $_primary = 'id_produk_staff';
	protected $_tableSchema = 'master';
	protected $_tableName = 'produk_staff';
	protected $_langkahOpt = null;

	public function form()
	{
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        $model = new Cms_Model_DbTable_ProductStaff();
        $dataMod = $model->tipeskenario($identity->id_team);

        $f = new Zend_Form();
		if( !empty($dataMod) ){
			if ( !empty($dataMod) && $dataMod[0]['id_skenario_latihan'] == 1 || $dataMod[0]['id_skenario_latihan'] == 2 )
			{
				$f->setMethod('post')
					->setEnctype('multipart/form-data')
					->setDecorators(array(
					array('ViewScript', array('viewScript' => 'produk-staff/form.phtml'))
				))
					->addElement('file', 'filepath', array(
					'label' => 'File Produk Staff ( doc, docx, odt, pdf )',
					'required' => true,
					'destination' => './pstaff/', // hardcoded lokasi upload
					'validators' => array(
						array('Count', false, 1),
						array('Extension', false, array( 'doc', 'docx', 'pdf', 'odt')),
					),
				))

					->addElement('text', 'idm_product_staff', array(
					'label' => 'Nama Produk Staff',
					'required' => true,
					'validators' => array(
					),
				))

					->addElement('hidden', 'id_user', array(
					'required' => true,
				))
				// terakhir ditambahkan tombol submit
					->addElement('submit', 'submit', array(
					'ignore' => true,
					'label'  => 'Upload',
				))
				;
			}		
		}
        else
        {
            $f->setMethod('post')
                ->setEnctype('multipart/form-data')
                ->setDecorators(array(
                array('ViewScript', array('viewScript' => 'produk-staff/form.phtml'))
            ))
                ->addElement('file', 'filepath', array(
                'label' => 'File Produk Staff ( doc, docx, odt, pdf )',
                'required' => true,
                'destination' => './pstaff/', // hardcoded lokasi upload
                'validators' => array(
                    array('Count', false, 1),
                    array('Extension', false, array( 'doc', 'docx', 'pdf', 'odt')),
                ),
            ))

                ->addElement('select', 'idm_product_staff', array(
                'label' => 'Nama Produk Staff',
                'required' => true,
                'multiple' => false,
                'multiOptions' => $this->_mprodstaffOptions(),
                'validators' => array(
                ),
            ))

                ->addElement('hidden', 'id_user', array(
                'required' => true,
            ))
            // terakhir ditambahkan tombol submit
                ->addElement('submit', 'submit', array(
                'ignore' => true,
                'label'  => 'Upload',
            ))
            ;
        }

		return $f;
	}

	protected function _mprodstaffOptions()
	{
		$db = $this->table()->getAdapter();
		$select = $db->select()
			->from('master.M_PRODUCT_STAFF',array('id','nama_product_staff'))
			->order('nama_product_staff ASC')
		;
		$raw = $db->fetchAll($select);
		$result = array('' => 'Pilih Nama Produk Staff');
		foreach ($raw as $row) {
			$result[$row['id']] = $row['nama_product_staff'];
		}
		return $result;
	}

	public function _mprodstaffSelected( $id )
	{
		$db = $this->table()->getAdapter();
		$select = $db->select()
			->from('master.M_PRODUCT_STAFF')
			->where('id = ?', $id)
		;
		$raw = $db->fetchRow($select);
		return $raw['nama_product_staff'];
	}

	/**
	 * @override tambahkan path pada file
	 */
	public function setFromForm($form)
	{
		parent::setFromForm($form);
		$this->_values['filepath'] = './pstaff/' . $this->_values['filepath'];
	}

	/**
	 * @override tambahkan path pada file
	 */
	public function setFromPost($post, $file)
	{
		$this->_values = $post;
		$this->_values['filepath'] = './pstaff/' . $file;
	}

	/**
	 * @override tambahkan penghapusan file
	 */
	public function delete()
	{
		if (!$this->exists()) return;
		// hapus file
		@unlink($this->_values['filepath']);
		// hapus row
		$table = $this->table();
		$table->delete($this->where());
		$this->_id = null;
	}
}