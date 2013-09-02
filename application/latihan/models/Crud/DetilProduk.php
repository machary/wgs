<?php
/**
 * Model untuk table User Login
 * 
 * @author Kanwil
 */
 
class Latihan_Model_Crud_DetilProduk extends Management_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'latihan';
	protected $_tableName = 'materi_produk_naskah';

    protected $_ignoreCols = array( 'point', 'parent');

    protected $_customElements = array(
        'materi' => array(
            'text',
            'label' => 'Detil Penilaian Produk Naskah',
            'required' => true,
            'class' => 'large'
        ),
        'team_id' => array(
            'hidden'
        ),
    );
}
