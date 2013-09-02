<?php
/**
 * Model untuk table User Login
 * 
 * @author Kanwil
 */
 
class Latihan_Model_Crud_SubmateriProduk extends Management_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'latihan';
	protected $_tableName = 'materi_produk_naskah';

    protected $_ignoreCols = array( 'point');

    protected $_customElements = array(
        'parent' => array(
            'select',
            'label' => 'Materi Penilaian Produk Naskah',
            'required' => true,
            'RegisterInArrayValidator' => false,
            'multiOptions' => array(
                '' => '[Pilih]'
            ),
        ),
        'materi' => array(
            'text',
            'label' => 'Sub Materi Penilaian Produk Naskah',
            'required' => true,
            'class' => 'large'
        ),
        'team_id' => array(
            'hidden'
        ),
    );
}
