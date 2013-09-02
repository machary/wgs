<?php
/**
 * Model untuk table User Login
 * 
 * @author Kanwil
 */
 
class Latihan_Model_Crud_AspekNaskah extends Management_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'latihan';
	protected $_tableName = 'aspek_paparan_naskah';

	protected $_customElements = array(
		'aspek_nilai' => array(
            'text',
            'label' => 'Aspek Penilaian',
            'class' => 'large'
        ),
		'team_id' => array(
            'hidden'
        ),
	);

}
