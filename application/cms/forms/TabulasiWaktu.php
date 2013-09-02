<?php
class Cms_Form_TabulasiWaktu extends Zend_Form
{
	public function __construct($option = null)
	{
		parent::__construct($option);
		$getData = new Cms_Model_DbTable_List();

		$asal = $this->createElement('select', 'asal');
		$asal->setAttrib('label-name', 'Pelabuhan Asal')
				->setRequired(true)
				->setAttrib('id', 'asal')
				->removeDecorator('Label')
				->addMultiOption('', '[Pilih]');
		foreach($getData->selectAllPelabuhan() as $data)
		{
			$asal->addMultiOption($data['gid'], $data['nama']);
		}
		$this->addElement($asal);

		$tujuan = $this->createElement('select', 'tujuan');
		$tujuan->setAttrib('label-name', 'Pelabuhan Tujuan')
			->setRequired(true)
			->setAttrib('id', 'tujuan')
			->removeDecorator('Label')
			->addMultiOption('', '[Pilih]');
		foreach($getData->selectAllPelabuhan() as $data)
		{
			$tujuan->addMultiOption($data['gid'], $data['nama']);
		}
		$this->addElement($tujuan);

		$jarak = $this->createElement('text', 'jarak');
		$jarak->setAttrib('label-name', 'Jarak Tempuh')
				->setAttrib('id', 'jarak')
				->removeDecorator('Label');
		$this->addElement($jarak);

		$kecepatan = $this->createElement('text', 'kecepatan');
		$kecepatan->setAttrib('label-name', 'Kecepatan (Knot)')
					->setAttrib('id', 'kecepatan')
					->removeDecorator('Label');
		$this->addElement($kecepatan);

		$redirect = $this->createElement('hidden', 'redirect_text');
		$redirect ->setAttrib('title','cms/tabulasi.waktu');
		$this->addElement($redirect);

		$this->setMethod('post');
		$this->setAttrib('id', 'frmTabulasiWaktu');
		$this->setLegend('Form Tabulasi Waktu');
		$this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-tabulasi.phtml')),));
	}
}