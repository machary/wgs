<?php
/**
 * Model untuk table Data KRI
 * @author tajhul.faijin@sangkuriang.co.id
 */
 
class Cms_Model_DataKriCrud extends App_Model_Crud
{
	protected $_primary = 'id_kapal';
	protected $_tableSchema = 'master';
	protected $_tableName = 'data_pokok_kri';
    protected $_customElements = array(
   		'gambar' => array(
   			'file',
   			'label' => 'Gambar',
   		),
   	);
}