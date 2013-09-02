<?php
class Cms_Form_JarakPangkalan extends Zend_Form
{
	public function __construct($option = null)
	{
		parent::__construct($option);
		$getData = new Cms_Model_DbTable_List();

		$asal = $this->createElement('select', 'asal_pangkalan');
		$asal->setAttrib('label-name', 'Asal Pangkalan')
				->removeDecorator('Label')
				->setRequired(true)
				->addMultiOption('', '[Pilih]');
		foreach($getData->selectAllPelabuhan() as $data)
		{
			$asal->addMultiOption($data['gid'], $data['nama']);
		}
		$this->addElement($asal);

		$tujuan = $this->createElement('select', 'tujuan_pangkalan');
		$tujuan->setAttrib('label-name', 'Tujuan Pangkalan')
			->removeDecorator('Label')
			->setRequired(true)
			->addMultiOption('', '[Pilih]');
		foreach($getData->selectAllPelabuhan() as $data)
		{
			$tujuan->addMultiOption($data['gid'], $data['nama']);
		}
		$this->addElement($tujuan);

		$jarak = $this->createElement('text', 'jarak');
		$jarak->setAttrib('label-name', 'Jarak Km')
				->removeDecorator('Label')
				->setRequired(true);
		$this->addElement($jarak);

		$redirect = $this->createElement('hidden', 'redirect_text');
		$redirect ->setAttrib('title','cms/gun');
		$this->addElement($redirect);

		$this->setMethod('post');
		$this->setAttrib('id', 'frmJrkPelabuhan');
		$this->setLegend('Form Jarak Pelabuhan');
		$this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
	}
}