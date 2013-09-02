<?php
class Latihan_Form_KekuatanMusuhPergerakan extends Zend_Form
{
	public function __construct($option = null)
	{
		parent::__construct($option);
		$this->setName('Gun');
		$list = new Cms_Model_DbTable_List();

		$tanggal = $this->createElement('text', 'tanggal');
		$tanggal->setAttrib('label-name', 'Tanggal')
			->setAttrib('class', 'datepicker')
			->setRequired(true);
		$this->addElement($tanggal);

		$waktu = $this->createElement('text', 'waktu');
		$waktu->setAttrib('label-name', 'Waktu')
			->setRequired(true)
			->setAttrib('class', 'timepicker');
		$this->addElement($waktu);

		$simbol = $this->createElement('select', 'simbol_pergerakan');
		$simbol->setAttrib('label-name', 'Simbol Pergerakan')
			->setAttrib('id', 'simbol-pergerakan')
			->removeDecorator('Label')
			->addMultiOption('', '[Pilih]');
		foreach($list->selectAllSymPergerakan() as $data)
		{
			$simbol->addMultiOption($data['id'], $data['nama']);
		}
		$this->addElement($simbol);

		$simbol_value = $this->createElement('hidden', 'simbol_value');
		$simbol_value->setAttrib('id', 'simbol_value');
		$this->addElement($simbol_value);

		$point = $this->createElement('textarea', 'point');
		$point->setAttrib('label-name', 'Point')
			->setAttrib('id', 'point')
			->setRequired(true);
		$this->addElement($point);

		$rotation = $this->createElement('textarea', 'rotation');
		$rotation->setAttrib('label-name', 'Rotation')
			->setAttrib('id', 'rotation-value')
			->setRequired(true);
		$this->addElement($rotation);

		$size = $this->createElement('textarea', 'size')
			->setAttrib('label-name', 'Size')
			->setAttrib('id', 'size-simbol')
			->setRequired(true);
		$this->addElement($size);

		//		$jumlah = $this->createElement('text', 'jumlah');
		//		$jumlah->setAttrib('label-name', 'Jumlah')
		//				->setRequired(true);
		//		$this->addElement($jumlah);

		$keterangan = $this->createElement('textarea', 'keterangan');
		$keterangan->setAttrib('label-name', 'Keterangan');
		$this->addElement($keterangan);

		$redirect = $this->createElement('hidden', 'redirect_text');
		$redirect ->setAttrib('title','latihan/pergerakan');
		$this->addElement($redirect);

		$this->setMethod('post');
		$this->setAttrib('id', 'frmPergerakan');
		$this->setLegend('Form Pergerakan');
		$this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
	}
}