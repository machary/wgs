<?php
/**
 * Pasrat
 * 
 * @author Kanwil
 */
require_once 'KogasController.php';

class Ops_PasratController extends Ops_KogasController
{
	protected $_kogas = 'Pasrat';
	protected $_cbTableName = 'ops.pasrat_cb';
	protected $_cbCrudClass = 'Ops_Model_Pasrat_Cb';
	protected $_ruteClass = array(
		'darat' => 'Ops_Model_Pasrat_Darat',
		'marinir' => 'Ops_Model_Pasrat_Marinir',
	);
	protected $_perbandinganClass = 'Ops_Model_Pasrat_Perbandingan';
}