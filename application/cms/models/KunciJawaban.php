<?php
class Cms_Model_KunciJawaban extends Cms_Model_Crud
{
    protected $_primary = 'id_kunci_jawaban';
    protected $_tableSchema = 'master';
    protected $_tableName = 'kunci_jawaban';

    public function form()
    {
        $f = new Zend_Form();
        $f->setMethod('post')
            ->setEnctype('multipart/form-data')
            ->setDecorators(array(
            array('ViewScript', array('viewScript' => 'kuncijawaban/form.phtml'))
        ))
            ->addElement('file', 'filepath', array(
            'label' => 'Upload File',
            'required' => true,
            'destination' => './upload', // hardcoded lokasi upload
            'validators' => array(
                array('Count', false, 1),
                array('Extension', false, 'pdf'),
            ),
        ))
            ->addElement('text', 'nama', array(
            'label' => 'Nama',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array('min'=>0, 'max'=>100)),
            ),
        ))
            ->addElement('select', 'id_skenario', array(
            'label' => 'Skenario',
            'required' => true,
            'multiple' => false,
            'multiOptions' => $this->_jenisOptions(),
            'validators' => array(
            ),
        ))
        // terakhir ditambahkan tombol submit
            ->addElement('submit', 'submit', array(
            'ignore' => true,
            'label'  => 'Simpan',
        ))
        ;
        return $f;
    }

    protected function _jenisOptions()
    {
        $id = array();
        $nama = array();

        $table = new Zend_Db_Table('latihan.skenario');
        $query = $table->select()
                    ->from('latihan.skenario')
        ;

        $result = $table->fetchAll($query);

        foreach($result->toArray() as $skenario)
        {
            array_push($id, $skenario['id']);
            array_push($nama, $skenario['nomor']);
        }

        return array_combine($id, $nama);
    }

    /**
     * @override tambahkan path pada file
     */
    public function setFromForm($form)
    {
        parent::setFromForm($form);
        $this->_values['filepath'] = './upload/' . $this->_values['filepath'];
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