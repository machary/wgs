<?php
/**
 * UConverter.php
 * Class universal converter for unix environtment
 * @author tajhul.faijin@sangkuriang.co.id
 * @TODO : create for other OS
 */
class App_UConverter
{
	private $_PROTOCOL = 'abiword';
	private $_DOCUMENT_ROOT = DOCUMENT_ROOT; //<- cons DOCUMENT_ROOT lihat di index.php public
	private $_SHELL_PATH = SHELL_PATH; //<- cons SHELL_PATH lihat di index.php public
	private $_SHELL_FILE = 'convert.sh'; //	
	private $_TO = 'pdf'; //target format
	private $_FROM = 'doc'; //native format
	private $_DIRFILE = null;
	private $_FILENAME = null;

    public function __construct(){}
	
	//SETTER ======================================
	
	/*
	* set protocol
	* @param (string) $protocol, available = abiword
	*/
	public function setUProtocol( $protocol = '' ) {
		$this->_PROTOCOL = $protocol;
	}

	/*
	* set dir file
	* @param (string) $path
	*/
	public function setDirFile( $path = '' ) {
		$this->_DIRFILE = $path;
	}
	
	/*
	* setFileName
	* @param (string) $filename
	*/
	public function setFileName( $filename ) {
		$this->_FILENAME = $filename;
	}

	/*
	* convertTo
	* @param (string) $$format, default pdf
	*/
	public function convertTo( $format = '' ) {
		$this->_TO = strtolower( $format );
	}
		
	//GETTER =====================================
	/*
	* getUProtocol
	* return protocol name
	*/
	public function getUProtocol() {
		return $this->_PROTOCOL;
	}	

	/*
	* getUProtocol
	* return dir name
	*/
	public function getDirFile() {
		return $this->_DIRFILE;
	}		
	
	//ACTION
	
	/*
	* doConvert
	*/
	public function doConvert() {	
		//make sure the shell file is already exist
		if( !file_exists($this->_SHELL_PATH .'/'. $this->_SHELL_FILE) ) die( 'Ops, Shell file was not found!' );

		$cmd = $this->_SHELL_PATH .'/'. $this->_SHELL_FILE .' '. $this->_PROTOCOL .' '. $this->_TO .' '.$this->_DIRFILE .' '. $this->_FILENAME;
		
		//execute shell command
		shell_exec( $cmd );

	}
	
	/*
	* delNativeFile
	* @param : (bool) $del
	*/
	public function delNativeFile( $del = false ) {
		if( $del ) {
			if( file_exists($this->_DIRFILE . $this->_FILENAME) ) {
				unlink($this->_DIRFILE . $this->_FILENAME);
			}
		}
	}
}