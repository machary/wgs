<?php
/**
 * Kogasgablinud
 * 
 * @author Kanwil
 */
require_once 'KogasController.php';

class Ops_KogasgablinudController extends Ops_KogasController
{
	protected $_kogas = 'Kogasgablinud';
	protected $_cbTableName = 'ops.gablinud_cb';
	protected $_cbCrudClass = 'Ops_Model_Gablinud_Cb';
	protected $_ruteClass = array(
		'udara' => 'Ops_Model_Gablinud_Udara',
		'linud' => 'Ops_Model_Gablinud_Linud',
	);
	protected $_perbandinganClass = 'Ops_Model_Gablinud_Perbandingan';
}