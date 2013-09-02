<?php
class Peta_Form_Logistik_Rs extends Zend_Form
{
    public function __construct($option = null)
    {
        $list = new Cms_Model_DbTable_List();

        $rs = $this->createElement('select', 'id_rumahsakit');
        $rs->setAttrib('label-name', 'RS')
                ->setAttrib('id', 'id_rumahsakit')
                ->removeDecorator('Label')
                ->addMultiOption('', '[Pilih RS]');
        foreach($list->selectAllRumahsakit() as $data)
        {
            $rs->addMultiOption($data['gid'], $data['nama']);
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
        $this->setLegend('Form Rumahsakit Pendukung');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-rs.phtml')),));
    }
}