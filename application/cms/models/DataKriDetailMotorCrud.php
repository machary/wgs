<?php
/**
 * Model untuk table Data KRI Detail Motor
 * @author tajhul.faijin@sangkuriang.co.id
 */
 
class Cms_Model_DataKriDetailMotorCrud extends App_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'master';
	protected $_tableName = 'data_pokok_kri_detail_motor';
//    protected $_foreignKeys = array(
//   		'id_kapal' => array( // nama kolom yg merupakan foreign key
//   			'label' => 'Kapal',
//   			'schema' => 'master',
//   			'table' => 'data_pokok_kri', // nama table yang ditunjuk
//   			'field' => 'id_kapal', // nama kolom yang ditunjuk
//   			'display' => 'nama_kri', // nama kolom dari table yg dijadikan display option
//   		),
//   	);

    protected $_customElements = array(
   		'id_kapal' => array(
   			'hidden',
        //'label' => 'Kapal',
   		),
   	);
}