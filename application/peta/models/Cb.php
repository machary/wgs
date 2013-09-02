<?php
class Peta_Model_Cb extends App_Model_Crud
{
    protected $_primary = 'id';
    protected $_tableSchema = 'latihan';
    protected $_tableName = ''; // need override
    protected $_ignoreCols = array('waktu_sebenarnya', 'terpilih', 'team_id',
        'durasi_udara', 'durasi_laut', 'durasi_marinir', 'durasi_darat', 'durasi_linud');

    protected $_foreignKeys = array(
        'pangkalan_aju' => array( // nama kolom yg merupakan foreign key
            'label' => 'Pangkalan Aju',
            'allowEmpty' => true,
            'schema' => 'master',
            'table' => 'pangkalan', // nama table yang ditunjuk
            'field' => 'idpangkalan', // nama kolom yang ditunjuk
            'display' => 'nama', // nama kolom dari table yg dijadikan display option
        ),
    );

    protected $_customElements = array(
        'kotama' => array(
            'select', // element pertama adalah jenis form element
            // element sisanya akan dijadikan option untuk form element
            'label' => 'Kotama',
            'required' => false,
            'multiOptions' => array(
                '' => '-',
                'armatim' => 'Armatim',
                'armabar' => 'Armabar',
            ),
        ),
        'waktu_mulai' => array(
            'text', // element pertama adalah jenis form element
            // element sisanya akan dijadikan option untuk form element
            'label' => 'Waktu Mulai',
            'required' => true,
            'value' => 0
        ),
        'waktu_selesai' => array(
            'text', // element pertama adalah jenis form element
            // element sisanya akan dijadikan option untuk form element
            'label' => 'Waktu Selesai',
            'required' => true,
            'value' => 0
        ),
        'nomor_ro' => array(
            'hidden', // element pertama adalah jenis form element
            // element sisanya akan dijadikan option untuk form element
            'required' => true,
            'value' => 0
        ),
        'status_panglima' => array(
            'hidden', // element pertama adalah jenis form element
            // element sisanya akan dijadikan option untuk form element
            'required' => true,
            'value' => 0
        ),
    );

    public function save()
    {
        if (!$this->exists()) {
            $identity = Zend_Auth::getInstance()->getStorage()->read();
            $this->set('team_id', $identity->id_team);
            $this->set('waktu_sebenarnya', date('Y-m-d H:i:s'));
        }
        parent::save();
    }

    /**
     * @return float durasi terpanjang rute yang dimilikinya dalam jam
     */
    public function maxDuration()
    {
        $max = 0;
        $keys = array('durasi_udara', 'durasi_laut', 'durasi_marinir', 'durasi_darat', 'durasi_linud');
        foreach ($keys as $k) {
            if (isset($this->_values[$k])) {
                $max = ($this->_values[$k] > $max) ? $this->_values[$k] : $max;
            }
        }
        return $max;
    }

}