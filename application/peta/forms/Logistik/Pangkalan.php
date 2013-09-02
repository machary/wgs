<?php
class Peta_Form_Logistik_Pangkalan extends Zend_Form
{
    public function __construct($option = null)
    {
        $list = new Peta_Model_Crud_PangkalanPendukung();

        $rs = $this->createElement('select', 'id_pangkalan');
        $rs->setAttrib('label-name', 'Pangkalan')
                ->setAttrib('id', 'id_pangkalan')
                ->removeDecorator('Label')
                ->addMultiOption('', '[Pilih Pangkalan]');
        foreach($list->listAllLanal() as $data)
        {
            $rs->addMultiOption($data['gid'], $data['lanal']);
        }
        $this->addElement($rs);

        $kelas = $this->createElement('text', 'kelas');
        $kelas->setAttrib('label-name', 'Kelas')
                    ->setAttrib('id', 'kelas')
                    ->removeDecorator('Label');
        $this->addElement($kelas);

        $daya_tampung = $this->createElement('text', 'daya_tampung');
        $daya_tampung->setAttrib('label-name', 'Daya Tampung')
                    ->setAttrib('id', 'daya_tampung')
                    ->removeDecorator('Label');
        $this->addElement($daya_tampung);

        $fasilitas_transportasi = $this->createElement('textarea', 'attribute');
        $fasilitas_transportasi->setAttrib('label-name', 'Fasilitas Transportasi <small> )*Pisahkan dengan koma (,)</small>')
                    ->setAttrib('id', 'attribute')
                    ->removeDecorator('Label');
        $this->addElement($fasilitas_transportasi);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmRs');
        $this->setLegend('Tambah Pangkalan Pendukung');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}