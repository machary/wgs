<?php
/**
 * Rencana Operasi
 * 
 * @author irfan.muslim@sangkuriang.co.id
 */

//require_once 'KogasController.php';

class Ops_RoController extends App_Controller
{
	protected $_satuan = array(
		'udara' => array('jarak'=>'kilometer', 'kecepatan'=>'kmph'),
		'laut' => array('jarak'=>'nautical mile', 'kecepatan'=>'knot'),
		'marinir' => array('jarak'=>'kilometer', 'kecepatan'=>'kmph'),
		'darat' => array('jarak'=>'kilometer', 'kecepatan'=>'kmph'),
		'linud' => array('jarak'=>'kilometer', 'kecepatan'=>'kmph'),
	);


	protected $_koarr = array(
		'kogasud' => array(
			'nama' => 'kogasud',
			'namatabel' => 'ops.ud_cb',
			'crudclass' => 'Ops_Model_Ud_Cb',
			'ruteclass' => array(
				'udara' => 'Ops_Model_Ud_Udara',
			),
		),
		'kogasgabla' => array(
			'nama' => 'kogasgabla',
			'namatabel' => 'ops.gabla_cb',
			'crudclass' => 'Ops_Model_Gabla_Cb',
			'ruteclass' => array(
				'laut' => 'Ops_Model_Gabla_Laut',
				'udara' => 'Ops_Model_Gabla_Udara',
			),
		),
		'kogasgabfib' => array(
			'nama' => 'kogasgabfib',
			'namatabel' => 'ops.gabfib_cb',
			'crudclass' => 'Ops_Model_Gabfib_Cb',
			'ruteclass' => array(
				'laut' => 'Ops_Model_Gabfib_Laut',
				'udara' => 'Ops_Model_Gabfib_Udara',
			),
		),
		'kogasgabrat' => array(
			'nama' => 'kogasgabrat',
			'namatabel' => 'ops.gabrat_cb',
			'crudclass' => 'Ops_Model_Gabrat_Cb',
			'ruteclass' => array(
				'darat' => 'Ops_Model_Gabrat_Darat',
			),
		),
		'kogasgablinud' => array(
			'nama' => 'kogasgablinud',
			'namatabel' => 'ops.gablinud_cb',
			'crudclass' => 'Ops_Model_Gablinud_Cb',
			'ruteclass' => array(
				'udara' => 'Ops_Model_Gablinud_Udara',
				'linud' => 'Ops_Model_Gablinud_Linud',
			),
		),
	);

	public function indexAction()
	{
		$cbTable = new Zend_Db_Table('latihan.pilihan_cb_kogab');
		$cbterpil = $cbTable->find(1)->current();

		//Satu(1) harus diubah dengan id team dari user, sementara hardcode

		$this->view->cbsel = $cbterpil;

		if ($cbterpil['kogasud'] != null){

			$x[] = $this->_ruteSimulasiSemua('udara',$this->_koarr['kogasud'],$cbterpil['kogasud']);
		}

		if ($cbterpil['kogasgabla'] != null){

			$x[] = $this->_ruteSimulasiSemua('udara',$this->_koarr['kogasgabla'],$cbterpil['kogasgabla']);
			$x[] = $this->_ruteSimulasiSemua('laut',$this->_koarr['kogasgabla'],$cbterpil['kogasgabla']);
		}

		if ($cbterpil['kogasgabfib'] != null){

			$x[] = $this->_ruteSimulasiSemua('udara',$this->_koarr['kogasgabfib'],$cbterpil['kogasgabfib']);
			$x[] = $this->_ruteSimulasiSemua('laut',$this->_koarr['kogasgabfib'],$cbterpil['kogasgabfib']);
		}

		if ($cbterpil['kogasgabrat'] != null){

			$x[] = $this->_ruteSimulasiSemua('darat',$this->_koarr['kogasgabrat'],$cbterpil['kogasgabrat']);
		}

		if ($cbterpil['kogasgablinud'] != null){

			$x[] = $this->_ruteSimulasiSemua('udara',$this->_koarr['kogasgablinud'],$cbterpil['kogasgablinud']);
			$x[] = $this->_ruteSimulasiSemua('linud',$this->_koarr['kogasgablinud'],$cbterpil['kogasgablinud']);
		}

		$this->view->cbrut = $x;
	}

	/**
	 * Generic, simulate movement of all routes in a CB
	 * @param string $jenis udara/laut/marinir/...
	 */
	protected function _ruteSimulasiSemua($jenis, $currkogas , $cbId)
	{
		$cbTable = new Zend_Db_Table($currkogas['namatabel']);
		$modelClass = $currkogas['ruteclass'][$jenis];

		$cbrut['jenis'] = $jenis;
		$cbrut['satuan'] = $this->_satuan[$jenis];
		$cbrut['cb'] = $cbTable->find($cbId)->current();
		$cbrut['cbId'] = $cbId;
		$cbrut['items'] = $modelClass::allObjects($cbId);

		return $cbrut;
	}

}