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
   public $table; //тяблица
   public $idkey = 'gid'; //Имя ID

   function __construct() {
    
            parent::__construct();          
     
    }
    
    
  //+Функция возвращает все папки из галлереи по типу  $type 
   public function   get_dir($type)
   {
    $this->db->order_by('section_id','desc');
    $this->db->where('type',$type);
    return $this->db->get('sections')->result_array();
   }
   
   //+Функция добавляет папку с типом $type
  public function addDir($type)
  {
    $this->load->helper('array');
    $data = elements(array('name','type','file_tmp','mod'),$_POST);
    $data['type']=$type;
    $data['mod']=1;  
   return $this->db->insert('sections',$data);
   
     
    
  } 
  
  //+Функция добавляет запись в галлерею об картинки
   public function addImg($data)
   {
    $this->db->insert('gallery',$data);
   }
   
   
   //+Функция возвращает данные об одной папки
    public function getSelection($idDir)
   {
     return $this->db->get_where('sections',array('section_id'=>$idDir),1)->row_array();  
          
   }
   
   
   //+Функция возвращает весь контент из папки
   public function getGallery($idDir)
   {
    $this->db->order_by('gid');
     return $this->db->get_where('gallery',array('section_id'=>$idDir))->result_array();  
   }
   
   
      //+Функция возвращает только одну запись по ID
   public function getDataById($id)
   {
     return $this->db->get_where('gallery',array('gid'=>$id))->row_array();  
   }
   
   
  //+Функция обновляет данные об контенте
   public function reNameById ($id,$type)
    {
       $this->db->where('gid',$id);       
       return($type=='img')
      ?$this->db->update('gallery', array('name'=>$this->input->post('name')))
      :$this->db->update('gallery', array('name'=>$this->input->post('name'),'code'=>$this->input->post('code')));
    }
   
   
   //+Функция обновляет данные об контенте
   public function reNameByIdDir ($id)
    {
       $this->db->where('section_id',$id);
    return  $this->db->update('sections',array('name'=>$this->input->post('name'))); 
    
    }
    
    //+Функция удаляет одну  записи из галлереи по исходному ID 
    public function deleteById ($id)
    {
       $this->db->where('gid',$id);
       return $this->db->delete('gallery');
    }
    
     
     //+Функция удаляет все записи из галлереи по исходному ID папки
     public function deleteAllById ($section_id)
    {
       $this->db->where('section_id',$section_id);
       return $this->db->delete('gallery');
    }
    
    //+Функция удаляет папку из таблицы section
     public function deleteDir ($section_id)
    {
       $this->db->where('section_id',$section_id);
       return $this->db->delete('sections');
    }
    
    
}
/*  END  Gallery_model
 /* End of file gallery_model.php*/
/* Location: ./application/models/gallery_model.php */  