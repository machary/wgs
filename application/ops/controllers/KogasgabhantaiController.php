<?php
/**
 * Kogasgabhantai
 * 
 * @author Kanwil
 */
require_once 'KogasController.php';

class Ops_KogasgabhantaiController extends Ops_KogasController
{
	protected $_kogas = 'Kogasgabhantai';
	protected $_cbTableName = 'ops.gabhantai_cb';
	protected $_cbCrudClass = 'Ops_Model_Gabhantai_Cb';
	protected $_ruteClass = array(
		'darat' => 'Ops_Model_Gabhantai_Darat',
	);
	protected $_perbandinganClass = 'Ops_Model_Gabhantai_Perbandingan';
}