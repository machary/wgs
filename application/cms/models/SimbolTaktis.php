<?php
/**
 * Model Simbol Taktis
 *
 * @author Kanwil
 */
 
class Cms_Model_SimbolTaktis extends App_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'master';
	protected $_tableName = 'simbol_taktis';
	
	protected $_customElements = array(
		'jenis' => array(
			'select',
			'label' => 'Jenis',
			'required' => true,
			'multiOptions' => array(),
		),
		'filepath' => array(
			'file',
			'label' => 'Gambar',
			'required' => true,
			'destination' => './upload/simbol', // hardcoded lokasi upload
			'validators' => array(
				array('Count', false, 1),
				array('Extension', false, 'jpg,png,gif'),
			),
		),
	);
	
	protected function _init()
	{
		$this->_customElements['jenis']['multiOptions'] = $this->_jenisOptions();
	}
	
	/**
	 * @override set enctype
	 */
	public function form()
	{
		$form = parent::form();
		$form->setEnctype('multipart/form-data');
		if ($this->exists()) {
			// berarto edit
			$form->removeElement('filepath');
		}
		return $form;
	}
	
	/**
	 * @override upload the file
	 * @override tambahkan path pada file
	 * @todo auto rename mencegah konflik
	 */
	public function setFromForm($form)
	{
		parent::setFromForm($form);
		$prefix = './upload/simbol/';
		if (isset($this->_values['filepath']) && (strpos($this->_values['filepath'], $prefix) === false)) {
			$this->_values['filepath'] = $prefix . $this->_values['filepath'];
		}
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
		parent::delete();
	}
	
	/**
	 * @return array pilihan jenis
	 */
	protected function _jenisOptions()
	{
		$opt =
            array('kapal','infantri','infantri mekanis','kavaleri roda','kavaleri tank',
                  'linud','artileri medan','artileri hanud','teritorial','zeni','komando operasional',
                  'marinir','zeni marinir','kuat log', 'sayap tetap', 'sayap putar','landasan udara','pangkalan udara','radar',
                  'm kapal','m infantri','m infantri mekanis','m kavaleri roda','m kavaleri tank',
                  'm linud','m artileri medan','m artileri hanud','m teritorial','m zeni','m komando operasional',
                  'm marinir','m zeni marinir', 'm sayap tetap', 'm sayap putar','m pangkalan udara','m radar'

            );
		return array_combine($opt, $opt);
	}
}