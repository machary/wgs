<?php
/**
 * Model untuk table Kekuatan Sendiri - Pergerakan
 * 
 * @author Kanwil
 */
 
class Latihan_Model_Crud_KekuatanSendiri_Pergerakan extends App_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'latihan';
	protected $_tableName = 'kekuatan_sendiri_pergerakan';
	protected $_ignoreCols = array('skenario_id');
	
}
