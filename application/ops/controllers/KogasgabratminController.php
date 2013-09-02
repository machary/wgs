<?php
/**
 * Kogasgabratmin
 * 
 * @author Kanwil
 */
require_once 'KogasController.php';

class Ops_KogasgabratminController extends Ops_KogasController
{
	protected $_kogas = 'Kogasgabratmin';
	protected $_cbTableName = 'ops.gabratmin_cb';
	protected $_cbCrudClass = 'Ops_Model_Gabratmin_Cb';
	protected $_ruteClass = array(
		'darat' => 'Ops_Model_Gabratmin_Darat',
	);
	protected $_perbandinganClass = 'Ops_Model_Gabratmin_Perbandingan';
}