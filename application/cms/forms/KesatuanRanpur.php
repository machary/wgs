<?php
/**
 * Form Kesatuan Ranpur
 * @author irfan.muslim@sangkuriang.co.id
 */
 
class Cms_Form_KesatuanRanpur extends Zend_Form
{
	public function init()
	{
		$this
		->setMethod('post')
		->setDecorators(array(
			array('ViewScript', array('viewScript' => 'kesatuan-ranpur/form.phtml'))
		))

		// Ranpur
		->addElement('select', 'idranpur', array(
			'isArray' => true,
			'required' => false,
			'multiple' => false,
			'multiOptions' => Cms_Model_Idname::factory('ranpur')->allAsOptions(),
			'filters' => array('Int'),
			'validators' => array(
				
			),
		))

		->addElement('text', 'nomor_mesin', array(
			'isArray' => true,
			'required' => false,
			'validators' => array(
				
			),
		))

			->addElement('text', 'nomor_chasis', array(
			'isArray' => true,
			'required' => false,
			'validators' => array(

			),
		))
			->addElement('text', 'nomor_registrasi_pusat', array(
			'isArray' => true,
			'required' => false,
			'validators' => array(

			),
		))
			->addElement('text', 'nomor_registrasi_baru', array(
			'isArray' => true,
			'required' => false,
			'validators' => array(

			),
		))
			->addElement('text', 'nomor_lambung', array(
			'isArray' => true,
			'required' => false,
			'validators' => array(

			),
		))
			->addElement('text', 'kondisi', array(
			'isArray' => true,
			'required' => false,
			'validators' => array(

			),
		))
			->addElement('text', 'keterangan', array(
			'isArray' => true,
			'required' => false,
			'validators' => array(

			),
		))




		->addElement('submit', 'submit', array(
			'ignore' => true,
			'label'  => 'Simpan',
		))
		;
	}
}