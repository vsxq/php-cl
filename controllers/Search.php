<?php   

   class Search extends CI_Controller {  
      public function __construct()
      {
          parent::__construct();
          $this->load->model('product_model');
      }
      public function index()
{

    $data = $this->product_model->get_product_quantity();
    echo json_encode($data);
}}
    ?>