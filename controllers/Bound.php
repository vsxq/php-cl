<?php   

   class Bound extends CI_Controller {  
      public function __construct()
      {
          parent::__construct();
          $this->load->model('product_model');
      }
      public function index()
      {
          echo "外边的人想进来,里边的人想出去";

      }
 function entry( $var )
 {
    $id=$_GET["id"];
    $count = $_GET["count"];
    $data["status"]=$this->product_model->$var($id,$count);
    $data["quantity"]=$this->product_model->get_quantity_by_id($id);
    $data["name"]=$this->product_model->get_name_by_id($id);
    $data["money"]=$this->product_model->get_price_by_id($id)*$count;
    echo json_encode($data);
 }
      public function in(Type $var = null)
{
    # code...
    $this->entry("in_bound");
}
    
        public function out(){
            $this->entry("out_bound");
        }
    
    }
?>