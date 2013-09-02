<?php
/**
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Form_Kesatuan extends Zend_Form
{
	public function init()
	{
        $model = new Cms_Model_DbTable_List();

        $nama = $this->createElement('text', 'nama');
        $nama->setAttrib('label-name', 'Nama')
                ->removeDecorator('Label');
        $this->addElement($nama);

        $lokasi = $this->createElement('text', 'lokasi');
        $lokasi->setAttrib('label-name', 'Lokasi')
                ->removeDecorator('Label');
        $this->addElement($lokasi);

        $markas = $this->createElement('text', 'markas');
        $markas->setAttrib('label-name', 'Kotama')
                ->removeDecorator('Label');
        $this->addElement($markas);

        $geom = $this->createElement('text', 'geom');
        $geom->setAttrib('label-name', 'Geometry  *format = point(longitude latitude)')
                ->removeDecorator('Label');
        $this->addElement($geom);

        $tipe = $this->createElement('text', 'tipe');
        $tipe->setAttrib('label-name', 'Tipe')
                ->removeDecorator('Label');
        $this->addElement($tipe);

        $matra = $this->createElement('select', 'matra');
        $matra->setAttrib('label-name', 'Matra')
                ->removeDecorator('Label')
                ->addMultiOption('', '[Pilih]')
                ->addMultiOption('AD', 'Darat')
                ->addMultiOption('AU', 'Udara');
        $this->addElement($matra);

        $id_kesatuan = $this->createElement('select', 'id_kesatuan');
        $id_kesatuan->setAttrib('label-name', 'Kesatuan')
                    ->removeDecorator('Label')
                    ->addMultiOption('', '[Pilih]');
        foreach($model->selectAllKesatuan() as $data)
        {
            $id_kesatuan->addMultiOption($data['idkesatuan'], $data['nama_kesatuan']);
        }
        $this->addElement($id_kesatuan);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','cms/kesatuan');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmKesatuan');
        $this->setLegend('Form Kesatuan');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
	}
}