<?php
class Cms_Model_Pangkat extends Cms_Model_Crud
{
    protected $_tableSchema = 'master';
    protected $_tableName = 'pangnkat';
    protected $_primary = 'idpangkat';
//    protected $_foreignKeys = array(
//        'idlanal' => array( // nama kolom yg merupakan foreign key
//            'label' => 'Lantamal/Lanal',
//            'schema' => 'master',
//            'table' => 'pangkalan', // nama table yang ditunjuk
//            'field' => 'idpangkalan', // nama kolom yang ditunjuk
//            'display' => 'nama', // nama kolom dari table yg dijadikan display option
//        ),
//    );
}