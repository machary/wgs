<?php
class Cms_Model_Help extends Cms_Model_Crud
{
    protected $_primary = 'id';
    protected $_tableSchema = 'master';
    protected $_tableName = 'help';

    public function form()
    {
        $f = new Zend_Form();
        $f->setMethod('post')
            ->setEnctype('multipart/form-data')
            ->setDecorators(array(
            array('ViewScript', array('viewScript' => 'help/form.phtml'))
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
            ->addElement('textarea', 'description', array(
            'label' => 'Deskripsi',
            'required' => true,
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