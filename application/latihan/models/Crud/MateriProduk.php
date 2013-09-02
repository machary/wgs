<?php
/**
 * Model untuk table User Login
 * 
 * @author Kanwil
 */
 
class Latihan_Model_Crud_MateriProduk extends Management_Model_Crud
{
	protected $_primary = 'id';
	protected $_tableSchema = 'latihan';
	protected $_tableName = 'materi_produk_naskah';

    protected $_ignoreCols = array( );

    protected $_customElements = array(
        'parent' => array(
            'select',
            'label' => 'Detil Penilaian Produk Naskah',
            'required' => true,
            'RegisterInArrayValidator' => false,
            'multiOptions' => array(
                '' => '[Pilih]'
            ),
        ),
        'materi' => array(
            'text',
            'label' => 'Materi Penilaian Produk Naskah',
            'required' => true,
            'class' => 'large'
        ),
        'singkatan' => array(
            'text',
            'label' => 'Singkatan Materi Penilaian Produk Naskah',
            'required' => true,
            'class' => 'small'
        ),
        'point' => array(
            'text',
            'label' => 'Poin Penilaian Produk Naskah',
            'required' => true,
            'class' => 'small'
        ),
        'team_id' => array(
            'hidden'
        ),
    );
}
