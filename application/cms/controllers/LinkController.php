<?php
class Cms_LinkController extends App_Controller
{
    private $_uploadPath = '';
    public function init()
    {
        parent::init();
        $this->_uploadPath = APPLICATION_PATH . '/../public/upload/images/link_logo/';
    }

    public function indexAction()
    {
        //parent::disableViewAndLayout();
    }

    public function dataapiAction()
    {
        $this->disableViewAndLayout();

        //init Model Penyalur
        $model = new Cms_Model_Datatables_Link();

        // Param
        $sEcho = intval( $this->_getParam( 'sEcho' ) );
        $sSearch = $this->_getParam( 'sSearch' );

        // Paging
        $offset = $this->_getParam( 'iDisplayStart' );
        $limit = $this->_getParam( 'iDisplayLength' );

        // Sort Order
        $sortColumn = $this->_getParam( 'iSortCol_0' );
        $order = $this->_getParam( 'sSortDir_0' );

        //custom filter
        $filter = $this->_getParam( 'filter' );
        $areaLevel = $this->_getParam( 'arealevel' );
        $areaID = $this->_getParam( 'areaid' );

        //get rows
        $jsonString = $model->datatablesJSONApi($sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch, $this->view->siteUrl('upload/images/link_logo/') );

        echo  $jsonString;
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $form = new Cms_Form_Link();
        $model = new Cms_Model_DbTable_Link();

        if($request->isPost()) {
            if($request->isPost() AND $form->isValid($this->_request->getPost())) {
                $hasFile = false;
                if( isset($_FILES['file']['name']) ){ //punya file
                    if( self::upload() ) $hasFile = true;
                }

                $data = $form->getValues();
                $link = $data['tautan'];
                $string = 'http://';
                $findHTTPString = strpos($link, $string);
                if( $findHTTPString === false){
                    $data['tautan'] = $string . $link;
                }

                if( !$hasFile ) unset( $data['file'] ); //buang part file

                $model->insert( $data );
                $this->redirectTo('cms/link/');

            } else {
                $this->errorMessage = 'Penyimpanan Gagal Dilakukan';
                $this->redirectTo('cms/link/add');
            }
        } else {
            $form->setAction( $this->view->siteUrl('cms/link/add') );
            $this->view->form = $form;
        }
    }

    public function editAction()
    {
        $id = $this->_getParam( 'id' );

        $request = $this->getRequest();
        $form = new Cms_Form_Link();
        $model = new Cms_Model_DbTable_Link();

        if($request->isPost()) {
            if($request->isPost() AND $form->isValid($this->_request->getPost())) {
                parent::disableViewAndLayout();

                $hasFile = false;
                if( isset($_FILES['file']['name']) ){ //punya file, lalu replace yg lama jika ada
                    if( self::upload() ) $hasFile = true;
                }

                $data = $form->getValues();
                $link = $data['tautan'];
                $string = 'http://';
                $findHTTPString = strpos($link, $string);
                if( $findHTTPString === false){
                    $data['tautan'] = $string . $link;
                }
                if( !$hasFile ) unset( $data['file'] ); //buang part file

                $model->update( $data, "id = {$id}");
                $this->redirectTo('cms/link/');
            } else {
                $this->errorMessage = 'Penyimpanan Gagal Dilakukan';
                $this->redirectTo('cms/link/add');
            }
        } else {

            $data = $model->getByID( $id);
            $file = empty($data['file']) ? false : $data['file'];
            $form->populate($data);
            $this->view->form = $form->setAction( $this->view->url() );
            $this->view->curfile = $form;
            $this->render('add');
        }
    }


    public function deleteAction()
    {
        parent::disableViewAndLayout();
        $id = $this->_getParam( 'id' );
        $file = $this->_getParam( 'file' );

        $model = new Cms_Model_DbTable_Link();
        $model->del('id', $id);
        if( !empty($file) ){
            if( file_exists( $this->_uploadPath . $file) ){
                unlink( $this->_uploadPath . $file );
            }
        }
        $this->redirectTo('cms/link/');
   }

    private function upload()
    {
        $uploaded = true;

        $tmpFile = $_FILES['file']['tmp_name'];
        list($width, $height, $type, $attr) = getimagesize( $tmpFile );

        //tidak boleh > 35 x 35 px
        if( $width > 35 || $height > 35 ) {
            $uploaded = false;
            $this->errorMessage = 'Upload logo gagal!';
        } else {
            if ((($_FILES["file"]["type"] == "image/gif")
                || ($_FILES["file"]["type"] == "image/jpeg")
                || ($_FILES["file"]["type"] == "image/pjpeg")
                || ($_FILES["file"]["type"] == "image/png"))
                && ($_FILES["file"]["size"] < 2000000))
            {
                if ($_FILES["file"]["error"] > 0) {
                    $uploaded = false;
                } else {
                    if( !move_uploaded_file($_FILES["file"]["tmp_name"],$this->_uploadPath . $_FILES["file"]["name"]) ){
                        $uploaded = false;
                        $this->errorMessage = 'Upload logo gagal!';
                    }
                }
            } else {
                $uploaded = false;
                $this->errorMessage = 'Upload logo gagal!';
            }
        }

        return $uploaded;
    }
}