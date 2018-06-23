<?php   

   class Product extends CI_Controller {  
      public function __construct()
      {
          parent::__construct();
          $this->load->model('product_model');
      }
      public function product_list() {
        $offset=$_GET["offset"];
        $rows=$_GET["rows"];
$data = $this->product_model->get_product_name($offset,$rows);
echo json_encode($data);

 }
 public function get_all_product_list(){
     $data = $this->product_model->get_all_product_name();
     echo json_encode($data);
 }
 public function get_product_info(Type $var = null)
 {
     $id=$_GET["id"];
     $name=$this->product_model->get_name_by_id($id);
    $price=$this->product_model->get_price_by_id($id);
    $data["name"]=$name;
    $data["price"]=$price;
    echo json_encode($data);

 }
public function get_count(){
    echo $data["count"]=$this->product_model->get_count();
}

public function index()
{
    
    
  
    $this->load->view("product_view");
   
    # code...
}
public function add(Type $var = null)
{
    # code...
    $this->product_model->add_product($_GET["name"]);
}

public function modify_update(Type $var = null)
{
    # code...
    $id=$_GET["id"];
    $name = $_GET["name"];
    $price = $_GET["price"];
    $this->product_model->update_name_price($id,$name,$price);
}

public function delete(Type $var = null)
{
    # 删除
    $id=$_GET["id"];
    $this->product_model->delete_product($id);
}
} 
?>  