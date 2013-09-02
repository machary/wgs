<?php
class Cms_Model_RumahSakit extends Cms_Model_Crud
{
    protected $_primary = 'idrs';
    protected $_tableSchema = 'master';
    protected $_tableName = 'fasilitas_rumah_sakit';
    protected $_foreignKeys = array(
        'idpangkalan' => array( // nama kolom yg merupakan foreign key
            'label' => 'Lantamal/Lanal',
            'schema' => 'master',
            'table' => 'pangkalan', // nama table yang ditunjuk
            'field' => 'idpangkalan', // nama kolom yang ditunjuk
            'display' => 'nama', // nama kolom dari table yg dijadikan display option
        ),
    );
}