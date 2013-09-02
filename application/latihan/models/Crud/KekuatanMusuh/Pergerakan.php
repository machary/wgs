<?php
/**
 * Model untuk table Kekuatan Musuh - Pergerakan
 * 
 * @author Kanwil
 */
 
class Latihan_Model_Crud_KekuatanMusuh_Pergerakan extends App_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'latihan';
	protected $_tableName = 'kekuatan_musuh_pergerakan';
	protected $_ignoreCols = array('skenario_id');
	
}
	