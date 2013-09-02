<?php
class Peta_Form_Intelijen_UdaraForm extends Zend_Form
{
    public function __construct($option = null)
    {
	    //$model = new Cms_Model_DbTable_List();

	    $tanggal = $this->createElement('text', 'tanggal');
	    $tanggal->setAttrib('label-name','Tanggal')
		    ->setAttrib('class', 'datepicker')
		    ->setRequired(true)
		    ->removeDecorator('Label');
	    $this->addElement($tanggal);

	    $waktu = $this->createElement('text', 'waktu');
	    $waktu->setAttrib('label-name', 'Waktu')
		    ->setAttrib('class', 'timepicker')
		    ->setRequired(true)
		    ->removeDecorator('label');
	    $this->addElement($waktu);

	    $geom = $this->createElement('textarea', 'geom');
	    $geom->setAttrib('label-name', 'Geometry')
		    ->removeDecorator('label');
	    $this->addElement($geom);

	    $longitude = $this->createElement('text', 'longitude');
	    $longitude->setAttrib('label-name','Longitude')
		    ->setAttrib('id', 'longitude')
		    ->setRequired(true)
		    ->removeDecorator('Label');
	    $this->addElement($longitude);

	    $latitude = $this->createElement('text', 'latitude');
	    $latitude->setAttrib('label-name','Latitude')
		    ->setAttrib('id', 'latitude')
		    ->setRequired(true)
		    ->removeDecorator('Label');
	    $this->addElement($latitude);

	    //        $koordinat = $this->createElement('textarea', 'point');
	    //        $koordinat->setAttrib('label-name', 'koordinat')
	    //            ->setRequired(true)
	    //            ->removeDecorator('label');
	    //        $this->addElement($koordinat);

	    $negara = $this->createElement('text', 'negara');
	    $negara->setAttrib('label-name', "Negara")
		    ->setAttrib('id', 'negara')
		    ->setRequired(true)
		    ->removeDecorator('label');
	    $this->addElement($negara);

	    //        $simbol = $this->createElement('select', 'id_simbol_taktis');
	    //        $simbol->setAttrib('label-name', 'Simbol Taktis')
	    //                ->setRequired(true)
	    //                ->removeDecorator('label')
	    //                ->addMultiOption('', '[PILIH]');
	    //        foreach($model->selectAllSymTaktis() as $sym)
	    //        {
	    //	        $explodeSimTak = explode(" ", $sym['jenis']);
	    //	        if($explodeSimTak[0] == 'm')
	    //	        {
	    //		        if($sym['jenis'] == "m infantri" || $sym['jenis'] == "m infantri mekanis" || $sym['jenis'] == "linud"
	    //			        || $sym['jenis'] == "m kaveleri roda" || $sym['jenis'] == "m kaveleri tank" || $sym['jenis'] == "m artileri medan"
	    //			        || $sym['jenis'] == "m artileri hanud" || $sym['jenis'] == "m zeni" || $sym['jenis'] == "m teritorial")
	    //		        {
	    //			        $simbol->addMultiOption($sym['id'], $sym['nama'].' ('.$sym['singkatan'].')');
	    //		        }
	    //	        }
	    //        }
	    //        $this->addElement($simbol);
	    //
	    //        $jumlah = $this->createElement('text', 'jumlah_kekuatan');
	    //        $jumlah->setAttrib('label-name', 'Jumlah Unsur Kekuatan')
	    //            ->setRequired(true)
	    //            ->removeDecorator('label');
	    //        $this->addElement($jumlah);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','peta/intelijen');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmLaut');
        $this->setLegend('Form Musuh Laut');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}