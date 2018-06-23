<?php 
class Product_model extends CI_Model{

    
    public function __construct(){
        $this->load->database();
    }

    #select
     function _select( $var)
    {
        # code...
        $this->db->select($var);
        $query=$this->db->get("product");
        $data = $query->result();
        $d = array();
        #$c=explode(",",$var);
        foreach($data as $value){
            $d[$value->$c[0]]=$value->$c[1];
}
        return $d;
    }
    
    
    public function get_count()
    {
        return $this->db->count_all('product');
    }
    public function get_all_product_name(Type $var = null)
    {
        # code...
        return $this->_select("id,name");

    }
    public function get_product_name($offset,$rows){
        $this->db->limit($offset,$rows);
     return $this->_select("id,name");}
 

    
    public function get_product_quantity()
    {
      return   $this->_select("name,quantity");
    }
 
   function _get_xx_by_id($id,$var)
    {
        $this->db->select($var);
        $this->db->where("id",$id);
        $query = $this->db->get('product');
        return $query->result()[0]->$var;
    }

    public function get_name_by_id($id) 
    {
        return $this->_get_xx_by_id($id,"name");
       
    }
    public function get_price_by_id($id) 
    {
        return $this->_get_xx_by_id($id,"price");
       
    }
    public function get_quantity_by_id($id)
    {
        return $this->_get_xx_by_id($id,"quantity");
    }
#insert
    public function add_product($name){
        $data = array('name' => $name,"quantity"=>0 ,"price"=>100);
        $this->db->insert('product',$data);
    }   
    #update
    public function update_name_price($id,$name,$price){
        $this->db->set("name",$name);
        $this->db->set("price",$price);
        $this->db->where("id",$id);
        $this->db->update("product");

    }
    public function out_bound($id,$count){
        $original = $this->get_quantity_by_id($id);
        if($original-$count<0){

            return FALSE;
        }
        else{
            $this->db->set("quantity",$original-$count);
            $this->db->where("id",$id);
        $this->db->update("product");
        }

    }
    public function in_bound($id,$count){
        $original = $this->get_quantity_by_id($id);
        $this->db->set("quantity",$original+$count);
        $this->db->where("id",$id);
    $this->db->update("product");
    }
    public function delete_product($id){
        $this->db->where("id",$id);
        $this->db->delete("product");
    }


}?>
