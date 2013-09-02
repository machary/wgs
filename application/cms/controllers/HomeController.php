<?php
class Cms_HomeController extends App_Controller
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {

    }

    public function dataapiAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        //init Model Penyalur
        $model = new Cms_Model_Datatables_Home();
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
            $filter, $sSearch);

        echo  $jsonString;
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $form = new Cms_Form_Home_Artikel();
        $news = new Cms_Model_DbTable_Home();
        $list = new Cms_Model_DbTable_List();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
                $data = $form->getValues();
                $data['tanggal'] = $list->ConvertDateStringIntel($data['tanggal']);
                $news->addBerita($data);
                $this->_redirect('cms/home');
            }
        }
        else
        {
            $this->errorMessage = 'Penyimpanan Gagal Dilakukan';
        }

        $this->view->form = $form;
        $this->view->nowdate = date('d/m/Y');
    }

    public function editAction()
    {
        $id = $this->_getParam( 'id' );

        $request = $this->getRequest();
        $form = new Cms_Form_Home_Artikel();
        $news = new Cms_Model_DbTable_Home();
        $list = new Cms_Model_DbTable_List();

        if($request->isPost())
        {
            if($request->isPost() AND $form->isValid($this->_request->getPost()))
            {
                parent::disableViewAndLayout();

                $data = $form->getValues();
                $data['tanggal'] = $list->ConvertDate($data['tanggal']);
                $news->update( $data, "id = {$id}");
                $this->_redirect('cms/home');
            }
        }
        else
        {
            $this->errorMessage = 'Penyimpanan Gagal Dilakukan';
        }

        $data = $news->getByID( $id);
        //$data['tanggal'] = $list->ConvertDate($data['tanggal']);
        $form->populate($data);
        $this->view->form = $form;
    }

    public function deleteAction()
    {
        parent::disableViewAndLayout();

        $id = $this->_getParam( 'id' );

        $news = new Cms_Model_DbTable_Home();
        $news->del('id', $id);

        $this->redirectTo('cms/home');
    }

    public function imageUploadAction()
    {
        $request = $this->getRequest();

        if($request->isPost())
        {

            if ((($_FILES["file"]["type"] == "image/gif")
                || ($_FILES["file"]["type"] == "image/jpeg")
                || ($_FILES["file"]["type"] == "image/pjpeg")
                || ($_FILES["file"]["type"] == "image/png"))
                && ($_FILES["file"]["size"] < 2000000))
            {
                if ($_FILES["file"]["error"] > 0)
                {
                    echo 'Upload gagal, Telah terjadi error';
                    $this->view->alert = 'Upload gagal, Telah terjadi error';
                }
                else
                {

                    if (file_exists( APPLICATION_PATH . '/../public/upload/images/' . $_FILES["file"]["name"]))
                    {
                        echo 'Upload gagal, Telah terjadi error';
                        $this->view->alert = 'Upload gagal, Telah terjadi error';
                    }
                    else
                    {
                        $dbTable = new Cms_Model_DbTable_UploadImage();

                        move_uploaded_file($_FILES["file"]["tmp_name"],
                             APPLICATION_PATH . '/../public/upload/images/' . $_FILES["file"]["name"]);

                        $data = array( 'file_name' => $_FILES["file"]["name"], 'timestamp' => date('Y-m-d H:i:s'));
                        $dbTable->insert($data);
                    }
                }
            }
            else
            {
                echo 'Upload gagal, Telah terjadi error';
                $this->view->alert = 'Upload gagal, Telah terjadi error';
            }
            $this->redirectTo('cms/home/image-upload');
        }
    }

    public function dataapiimageAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        //init Model Penyalur
        $model = new Cms_Model_Datatables_UploadImage();
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

        //get rows
        $jsonString = $model->datatablesJSONApi($sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch);

        echo  $jsonString;

    }

    public function deleteImageAction()
    {
        parent::disableViewAndLayout();

        $id = $this->_getParam( 'id' );

        $news = new Cms_Model_DbTable_UploadImage();
        $news->del('id', $id);

        $this->redirectTo('cms/home/image-upload');
    }
}