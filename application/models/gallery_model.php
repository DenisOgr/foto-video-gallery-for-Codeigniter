<?php 

 /**
 * Gallery_model Class
 *
 * @category Models
 * @author	Porplenko Denis
 * @email	denis.porplenko@gmail.com
 * 
 */
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Gallery_model extends CI_Model
 {
   public $pattern_table='gallery';
   public $table; //�������
   public $idkey = 'gid'; //��� ID

   function __construct() {
    
            parent::__construct();          
     
    }
    
    
  //+������� ���������� ��� ����� �� �������� �� ����  $type 
   public function   get_dir($type)
   {
    $this->db->order_by('section_id','desc');
    $this->db->where('type',$type);
    return $this->db->get('sections')->result_array();
   }
   
   //+������� ��������� ����� � ����� $type
  public function addDir($type)
  {
    $this->load->helper('array');
    $data = elements(array('name','type','file_tmp','mod'),$_POST);
    $data['type']=$type;
    $data['mod']=1;  
   return $this->db->insert('sections',$data);
   
     
    
  } 
  
  //+������� ��������� ������ � �������� �� ��������
   public function addImg($data)
   {
    $this->db->insert('gallery',$data);
   }
   
   
   //+������� ���������� ������ �� ����� �����
    public function getSelection($idDir)
   {
     return $this->db->get_where('sections',array('section_id'=>$idDir),1)->row_array();  
          
   }
   
   
   //+������� ���������� ���� ������� �� �����
   public function getGallery($idDir)
   {
    $this->db->order_by('gid');
     return $this->db->get_where('gallery',array('section_id'=>$idDir))->result_array();  
   }
   
   
      //+������� ���������� ������ ���� ������ �� ID
   public function getDataById($id)
   {
     return $this->db->get_where('gallery',array('gid'=>$id))->row_array();  
   }
   
   
  //+������� ��������� ������ �� ��������
   public function reNameById ($id,$type)
    {
       $this->db->where('gid',$id);       
       return($type=='img')
      ?$this->db->update('gallery', array('name'=>$this->input->post('name')))
      :$this->db->update('gallery', array('name'=>$this->input->post('name'),'code'=>$this->input->post('code')));
    }
   
   
   //+������� ��������� ������ �� ��������
   public function reNameByIdDir ($id)
    {
       $this->db->where('section_id',$id);
    return  $this->db->update('sections',array('name'=>$this->input->post('name'))); 
    
    }
    
    //+������� ������� ����  ������ �� �������� �� ��������� ID 
    public function deleteById ($id)
    {
       $this->db->where('gid',$id);
       return $this->db->delete('gallery');
    }
    
     
     //+������� ������� ��� ������ �� �������� �� ��������� ID �����
     public function deleteAllById ($section_id)
    {
       $this->db->where('section_id',$section_id);
       return $this->db->delete('gallery');
    }
    
    //+������� ������� ����� �� ������� section
     public function deleteDir ($section_id)
    {
       $this->db->where('section_id',$section_id);
       return $this->db->delete('sections');
    }
    
    
}
/*  END  Gallery_model
 /* End of file gallery_model.php*/
/* Location: ./application/models/gallery_model.php */  