<?php
class Management_Form_Penilai_Penilai extends Zend_Form
{
    public function __construct($option = null)
    {
        $modLogin = new Management_Model_DbTable_Login();
        $modTeam = new Management_Model_DbTable_Team();
        $modPenilai = new Management_Model_DbTable_Penilai();

        $penilai = $this->createElement('select', 'id_login');
        $penilai->setAttrib('label-name', 'Nama Penilai')
                ->removeDecorator('Label')
                ->addMultiOption('', '[Belum Terpilih]');
        foreach($modLogin->getpenilai() as $pen)
        {
            $penilai->addMultiOption($pen['id'], $pen['nama']);
        }
        $this->addElement($penilai);

        $team = $this->createElement('select', 'id_team');
        $team->setAttrib('label-name', 'Skenario Latihan')
            ->removeDecorator('Label')
            ->addMultiOption('', '[Belum Terpilih]');
        foreach($modTeam->getallteam() as $tim)
        {
            $team->addMultiOption($tim['id'], $tim['team_name']);
        }
        $this->addElement($team);

        $jabatan = $this->createElement('select', 'id_jabatan');
        $jabatan->setAttrib('label-name', 'Kogas/Kotama/Unit Yang Dinilai')
            ->removeDecorator('Label')
            ->addMultiOption('', '[Belum Terpilih]');
        foreach($modPenilai->getjabatan() as $jab)
        {
            $jabatan->addMultiOption($jab['id_jabatan'], $jab['nama_jabatan']);
        }
        $this->addElement($jabatan);

        $redirect = $this->createElement('hidden', 'redirect_text');
        $redirect ->setAttrib('title','management/penilai');
        $this->addElement($redirect);

        $this->setMethod('post');
        $this->setAttrib('id', 'frmPenilai');
        $this->setLegend('Form Penilai');
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'partials/form-default.phtml')),));
    }
}