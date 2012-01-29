<?php 
/**
 * 
 *Gallery Class
 *
 * @category Controllers
 * @author	Porplenko Denis
 * @email	denis.porplenko@gmail.com
 * 
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Controller
{
    public function __construct()
    {
       parent::__construct();            
    }
    
    
 public function index()
   {   
   $name='welcome'; 
   $this->display_lib->page($data=0,$name);  
         
  }  
  
 /*****************************������ � �������******************************************************/ 
     
     //�������, ������� ������� ����� �� ��������
     //�������� img - ����� ������ � ����������
     //�������� video - ����� � �������������      
     public function viewDir($type='img')
        {       
         
         $data=array();  
         $data['type'] = $type;  
         $data['name_admin_cat']=($type=='img')?'�������� ����':'������� �����'; 
         $data['dir_list']=$this->gallery_model->get_dir($type); 
         $name='gallery_dir';  
         $this->display_lib->page($data,$name);
          
      }
      
      
      
    //�������, ������� ��������� ����� � ��������
     //�������� img - ����� ������ � ����������
     //�������� video - ����� � �������������     
     public function addDir($type='img')
       {
         $result_text='';
         $data['name_admin_cat']='��������';  
         $data['type'] = $type;  
         $name='gallery_dir';         
         
      if($this->form_validation->run('addDir')==TRUE)
             {
              
                
                 $config['upload_path'] = './upload/temp/';
                 $config['allowed_types'] = 'gif|jpg|png|jpeg';
                 //$config['allowed_types'] = '*';
                 $config['max_size'] = '1000';
                 $config['max_width'] = '1024';
                 $config['max_height'] = '750';
                
                $this->load->library('upload', $config);
                
                $array_img = $this->upload->multi_upload('userfield');
                
                     if(empty($array_img['error']))
                          {
                           $result_text.="���������� '".$_POST['name']."' ����������� �������!<br>";  
                           //�������� ���������              
                                 $this->load->library('image_lib'); 
                                 $unic_pre=time().mt_rand(0,1000);
                                 $small_file = $array_img[0]['raw_name']."_".$unic_pre."_thumb".$array_img[0]['file_ext']; 
                                 $config['image_library'] = 'gd2'; // �������� ����������
                                 $config['source_image'] = $array_img[0]['full_path']; 
                                 $config['new_image'] = FCPATH.'/upload/_tumb/'.$small_file; 
                                 $config['maintain_ratio'] = TRUE; // ��������� ���������
                                 $config['width'] = $this->config->item('gallery_dir_w');
                                 $config['height'] = $this->config->item('gallery_dir_h');;
                                 $this->image_lib->initialize($config);
                                 $this->image_lib->resize();  
                                 
                                $_POST['file_tmp']=$small_file;
                               
                                 $this->gallery_model->addDir($type);
                                 unlink($array_img[0]['full_path']);         
                          } 
                          else
                          {
                        $result_text.="���������� '".$_POST['name']." '�� �����������!<br>";
                          } 
         }
         else
             {
             $result_text.= '<br>� ��� ������ ��� ����� ����������. <br>������ �����������!';  
             }
             
            
             $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_result_title'),'popup_text'=>$result_text);  
             
             
                          
             $data['dir_list']=$this->gallery_model->get_dir($type); 
             $this->display_lib->page($data,$name,$popup_info);  
   
      }  


  
    //������� ���������� ���������� �����
    public function view($idDir)
    { 
        $data = array();      
        
        $data['id_dir']=(int)$idDir;
       
       //������� ������ �� ����� �� ��
        $data['section']= $this->gallery_model->getSelection($idDir);    
       
  
        if(!empty($data['section']))
        {
            //������� ������� �� �����.
             $data['content_list'] = $this->gallery_model->getGallery($idDir);
              
            //������ � ������ ����� � ����������� �� ����� 
            //���� � ����� ���, ����� ������ � ������ ����       
           switch($data['section']['type'])
           {    
                case('img'):
                {
                   
                   $name='foto/add'; 
                    break;                    
                }
                
                 case('video'):
                 {
                   $name='video/add';           
                    break;   
                }
                  default:{exit(); }
           } 
         $popup_info='';  
        }
        else
        {
         $name='welcome';                
         $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_dir'));
       }

    $this->display_lib->page($data,$name,$popup_info);     
  }
  
  
  
   //������� ���������� ������ �� ����� 
  //� ���� ����� ��� ��������������  
   public function getNameDir($content_id)
   {
    
     settype($content_id,'integer');
    $data = $this->gallery_model->getSelection($content_id);   

    if(!empty($data))
    {
        printf(' 
        <div id="forma">
        <h1 class="title" >��������������</h1>
             <form action="%sgallery/reNameDir/%s" name="newDir" method="post">
             <label> ��������</label></br>
             <input type="text" name="name"  value="%s" /></br></br> 
             
             <input type="submit" value="��������" name="send_new_dir"/>         
               </form><br><br>
                <a href="%sgallery/deleteDir/%s" style="color:red">������� �����</a></div>',
                 base_url(),$data['section_id'],$data['name'],base_url(),$data['section_id'] );   
              
   }
   else
   {
    echo "� ��� ��� ����� �����!";
   }
   }  
    
   
   //������� �������� �������� � �������� ����� 
  public function reNameDir($section_id)
      {
        $data = array();            
        
        //������� ��� ������ �� ���� �����, ��� �� ������ �� ���
        // � � ���������� ������� ��� ����� ����� ����
        $dataById = $this->gallery_model->getSelection($section_id); 
        $data['name_admin_cat']=($dataById['type']=='img')?'�������� ����':'�������� �����';
        $data['type'] = $dataById['type']; 
     
     if($this->form_validation->run('addDir')==TRUE)
     {
         $this->gallery_model->reNameByIdDir($section_id);           
         $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_result_title'),'popup_text'=>$this->lang->line('gallery_msg_true_update'));
     }
     else
     {
       $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_rules'));      
     }
         //������� ����� �� ��������.
         $data['dir_list']=$this->gallery_model->get_dir($dataById['type']);    
         $name='gallery_dir'; 
          $this->display_lib->page($data,$name,$popup_info);  
         
        
    }   
        
         
         //������� ������� ����� ����� � �����/����������/���������
     	public function deleteDir($section_id)
            {
                 $data = array();
               
                 //������� ��� ������ �� ���� �����, ��� �� ������ �� ���
                // � � ���������� ������� ��� ����� ����� ����
                $dataById = $this->gallery_model->getSelection($section_id);
               //���� �� ����� �����?
                if(!empty($dataById['type']))
                {
                 //������� ��� ������, ��� �� � ����� ������� ��� �������� c �������
                $dataAllCont = $this->gallery_model->getGallery($section_id);
                   //������ ��������
                   foreach($dataAllCont as $item)
                        {
                         unlink(DROOT.'/upload/_tumb/'.$item['file_tmp']);
                         ($dataById['type']=='img')?unlink(DROOT.'/upload/real/'.$item['file']):false;         
                        }
                   //������ ������ � ��
                      $this->gallery_model->deleteAllById($section_id); 
                    //������ �������� �����
                     unlink(DROOT.'/upload/_tumb/'.$dataById['file_tmp']);  
                    //������ ������ � ����� ����
                    $this->gallery_model->deleteDir($section_id);          
                   
                    $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_result_title'),'popup_text'=>$this->lang->line('gallery_msg_true_delete_dir'));
                    $data['name_admin_cat']=($dataById['type']=='img')?'�������� ����':'�������� �����';
                    $data['dir_list']=$this->gallery_model->get_dir($dataById['type']);    
                    $data['type'] = $dataById['type']; 
                    $name='gallery_dir'; 
                 }
                 else
                 {
                   $name='welcome'; 
                   $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_result_title'),'popup_text'=>$this->lang->line('gallery_msg_false_dir'));
                   $this->display_lib->page($data=0,$name); 
                 }
             $this->display_lib->page($data,$name,$popup_info);      
               
           }    
    


 /*****************************������ � ���������(����, �����)****************�******************************/ 
  
     //������� ���������� ������ �� �������� ��� ����� �����
   //� ���� ����� ��� ��������������.   
   public function getName($content_id)
   {    
     
    settype($content_id,'integer');
    $data = $this->gallery_model->getDataById($content_id);   

    if(!empty($data))
    {
       switch($data['type'])
        {
            case 'img':
            {
             printf('<div id="forma">
             <h1 class="title" >Edit</h1>
             <form action="%sgallery/reNameContent/%s" name="newDir" method="post">
             <label> �������� </label></br>
             <input type="text" name="name" value="%s" /></br></br>
             
             <input type="submit" value="��������" name="send_new_dir"/>         
               </form><br><br>
                <a href="%sgallery/delete/%s" style="color:red">������� ��������</a></div>',
                base_url(),$data['gid'],$data['name'],base_url(),$data['gid'] );   
                break;
            }
           
           case 'video':
           {  
            printf(' <div id="forma">
            <h1 class="title" >��������������</h1>
             <form action="%sgallery/reNameContent/%s" name="newDir" method="post">
             <label> ��������</label></br>
             <input type="text" name="name" value="%s" /></br></br>            
             
             <label> ������ �� �����</label></br>
             <textarea cols="20" rows="4" name="code" >%s</textarea></br></br>
           
            <input type="submit" value="��������" name="send_new_dir"/>         
            </form><br><br>
            <a href="%sgallery/delete/%s" style="color:red">������� ����������</a></div>',
            base_url(),$data['gid'],$data['name'],$data['code'],base_url(),$data['gid'] );      
       break; 
           }
           
        }
   }
 else
   {
    echo "� ��� ��� ����� ��������!";
   }
   }  
   
   	
       
       
       public function reNameContent($content_id)
    {
        
        $data = array(); 
        $content_id = (int)$content_id;
        //������� ��� ������ �� ������� ��������, ��� �� ������ ��� section_id 
        //(ID ����� � ������� �� ���������, ��� �� � ���������� 
        //������� ���� ������� �����)
        $dataById = $this->gallery_model->getDataById($content_id);       
       
        //���� �� ����� ������ � ��������?
        if(!empty($dataById['section_id']))
              {
                //������� ������ �� ����� �� ��
                 $data['section']= $this->gallery_model->getSelection($dataById['section_id']);  
                //���� �� ����� �����?
                if(!empty($data['section']['type']))
                   {
                        //�����  �������� �� ��� �������, ��� ����� �������������� �������  ������������   ���
                         switch($data['section']['type'])
                         {
                              case 'img':
                              {
                                $name='foto/add';
                                $rules = 'addImg';
                                
                                break;
                              }
                              case 'video':
                              {
                                $name='video/add';
                                $rules = 'addVid';
                                break;
                              }
                              default:{ exit();}
                         }
                           if($this->form_validation->run($rules)==TRUE)
                             {
                                 $this->gallery_model->reNameById($content_id,$data['section']['type']);   
                                 $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_result_title'),'popup_text'=>$this->lang->line('gallery_msg_true_update'));
                             }
                             else
                             {
                               $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_rules') );       
                             }
            
                             //������� ������� �� �����.                     
                             $data['content_list'] = $this->gallery_model->getGallery($dataById['section_id']); 
                             $data['id_dir'] = $dataById['section_id'];
                   
                     }
                     else
                     {
                        //���������, ��� ��� �����
                        $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_dir') );  
                        $name='gallery_dir';
                        //������� ����� � ����������
                        $data['dir_list']=$this->gallery_model->get_dir('img'); 
                     }
    
         }
         else
          {
           //���������, ��� ��� ���������� ��������
           $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_content') );   
           $name='gallery_dir';
           //������� ����� � ����������
           $data['dir_list']=$this->gallery_model->get_dir('img');   
          }
         
         $this->display_lib->page($data,$name,$popup_info);
        
    }
    
    
    
  //������� ��������� ���� �� ������, ������ ������ � �����  ������ � ��
    public function uploadImg($dir='')
    {     
         $data=array();            
         $data['id_dir']=(int)$dir;
         $name='foto/add';  
       
        //������� ������ �� ����� �� ��
         $data['section']= $this->gallery_model->getSelection($dir);  
        //���� �����, � ������� �������� ��������?
        if(!empty($data['section']))
             {
             //���� ������ ��� ����� ������?
             if($this->form_validation->run('addImg')==TRUE)
                    {
                          //������� ��������   ������ temp       
                             $config['upload_path'] = './upload/temp/';
                             $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            // $config['allowed_types'] = '*';
                             $config['max_size'] = '2000';
                             $config['max_width'] = '1025';
                             $config['max_height'] = '770';
                             $config['overwrite'] = 'false';
                             
                            $this->load->library('upload', $config);                           
                            $array_img = $this->upload->multi_upload('foto');                            
                          
                            $result='';
                            $i=0;
                            //������ �� ������������ ������ ����� � ����������?
                            if(!empty($array_img))
                            {                              
                              $array_name=$this->input->post('name');
                                //������ ������ ����������� ��� ������� ����
                                $this->load->library('image_lib'); 
                                foreach($array_img as $item)
                                {
                                  
                                  if(empty($item['error']))
                                  {
                                    $result.="���������� '".$array_name[$i]."' ����������� �������!<br>";  
                                         //�������� ���������
                                         $unic_pre=time().mt_rand(0,1000);
                                         $small_file = $item['raw_name']."_".$unic_pre."_thumb".$item['file_ext'];
                                         $config['image_library'] = 'gd2'; 
                                         $config['source_image'] = $item['full_path']; 
                                         $config['new_image'] = FCPATH.'/upload/_tumb/'.$small_file; 
                                         $config['maintain_ratio'] = TRUE;                                              
                                         $config['width'] = $this->config->item('gallery_thumb_w'); 
                                         $config['height'] = $this->config->item('gallery_thumb_h');                          
                                         
                                         $this->image_lib->initialize($config);
                                         $this->image_lib->resize();              
                                       
                                       
                                        //��������  ������� ��������                                         
                                         $real_file = $item['raw_name']."_".$unic_pre.$item['file_ext'];
                                         $config['new_image'] = FCPATH.'/upload/real/'.$real_file; 
                                         $config['maintain_ratio'] = TRUE; // ��������� ���������   
                                         $config['width'] = $this->config->item('gallery_real_w'); 
                                         $config['height'] = $this->config->item('gallery_real_h');  
                                         $this->image_lib->initialize($config);
                                         $this->image_lib->resize();  
                                       
                                        //�������� ������ ��� ������ � �� 
                                        $dataInsert=array(
                                        'section_id'=>$dir,
                                        'type'=> $data['section']['type'],
                                        'name'=>$array_name[$i],                                             
                                        'file'=>$real_file,
                                        'file_tmp'=>$small_file,
                                        'mod'=>1
                                        );
                                        //����� ������ � ��
                                       $this->gallery_model->addImg($dataInsert);   
                                        //������ �������� ��������
                                     
                                        unlink($item['full_path']); 
                                                                  
                                  } 
                                  else
                                  {
                                    $result.="���������� '".$array_name[$i]." '�� �����������!<br>";
                                  } 
                                 $i++;  
                                }
                            $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_result_title'),'popup_text'=>$result);
                       }
                       else
                       {
                      //���������, ��� ������ �� �������� �� ����� ��������
                     $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_rules'));
                       } 
                   }
                    else
                    { 
                      //���������, ��� ������ ��� ����� ������
                     $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_rules'));
                     
                    }   
                 //������� ������� �� �����. 
                 $data['content_list'] = $this->gallery_model->getGallery($dir);        
             }
             else
             { 
             //���������, ��� ��� �����, ���� ����� ���������� ����
             $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_dir'));
             $name='gallery_dir';
             $data['dir_list']=$this->gallery_model->get_dir('img');  
            }
             
      $this->display_lib->page($data,$name,$popup_info);  
                       
                  
 }
    
    //������� ��������� �������� ��� ����� ������ �� ������, ������ ������ � ������ ������ � �� ��� ����� �����
    public function uploadVideo($dir='')
    {       
          
         $data=array();  
         $data['id_dir']=(int)$dir;
         $name='video/add';  
        
        
         //������� ������ �� ����� �� �� 
        $data['section']= $this->gallery_model->getSelection($dir);  
          //���� �����, � ������� �������� ����������?
        if(!empty($data['section']))
        {
                 
            //���� ������ ��� ����� ������?
              if($this->form_validation->run('addVid')==TRUE)
             {
                            //������� ��������   ������ temp       
                             $config['upload_path'] = './upload/temp/';
                            //$config['allowed_types'] = 'gif|jpg|png|jpeg';
                             $config['allowed_types'] = '*';
                             $config['max_size'] = '2000';
                             $config['max_width'] = '1025';
                             $config['max_height'] = '770';                           
                             
                            $this->load->library('upload', $config);                           
                            $array_img = $this->upload->multi_upload('foto');                            
                          
                            $result='';
                            $i=0; 
                          //������ �� ������������ ������ ����� � ����������?
                            if(!empty($array_img))
                            {                              
                              $array_name = $this->input->post('name');
                              $array_code = $this->input->post('code_video');
                                //������ ������ ����������� ��� ������� ����
                              $this->load->library('image_lib'); 
                              foreach($array_img as $item)
                                {
                                  if(empty($item['error']))
                                  {
                                  $result.="���������� '".$array_name[$i]."' ����������� �������!<br>";  
                                         //�������� ���������
                                         $unic_pre=time().mt_rand(0,1000);
                                         $small_file = $item['raw_name']."_".$unic_pre."_thumb".$item['file_ext'];
                                         $config['image_library'] = 'gd2'; 
                                         $config['source_image'] = $item['full_path']; 
                                         $config['new_image'] = FCPATH.'/upload/_tumb/'.$small_file; 
                                         $config['maintain_ratio'] = TRUE;                                              
                                         $config['width'] = $this->config->item('gallery_thumb_w'); 
                                         $config['height'] = $this->config->item('gallery_thumb_h');                          
                                         
                                         $this->image_lib->initialize($config);
                                         $this->image_lib->resize();     
                                       
                                        //�������� ������ ��� ������ � �� 
                                        $dataInsert=array(
                                        'section_id'=>$dir,
                                        'type'=> $data['section']['type'],
                                        'name'=>$array_name[$i],                                             
                                        'file'=>'',
                                        'file_tmp'=>$small_file,
                                        'code'=>$array_code[$i],
                                        'mod'=>1
                                        );
                                        
                                        //����� ������ � ��
                                       $this->gallery_model->addImg($dataInsert);   
                                       
                                        //������ �������� ��������                                     
                                        unlink($item['full_path']); 
                                        
                                            
                                  } 
                                  else
                                  {
                                    $result.="���������� '".$array_name[$i]." '�� �����������!<br>";
                                  } 
                                  $i++;
                                }
                          
                              $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$result);  
                          
                          }
                          else
                          {
                            //���������, ��� ������ �� �������� �� ����� ��������
                            $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_rules'));   
                          }
             }
             else
             {
             //���������, ��� ������ ��� ����� ������
             $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_rules'));  
             }                
         //������� ������� �� �����. 
          $data['content_list'] = $this->gallery_model->getGallery($dir);  
       }
       else
       {  
                
       //���������, ��� ��� �����, ���� ����� ���������� ����
       $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_dir'));
       $name='gallery_dir';
       $data['dir_list']=$this->gallery_model->get_dir('img');
       }
      $this->display_lib->page($data,$name,$popup_info);      
    }
    
    
    
        
    //������� ������� ��������/�����/�������
  	public function delete($content_id)
    {   
        $data = array();
       
        //������� ��� ������ �� ������� ��������, ��� �� ������ ��� section_id 
        //(ID ����� � ������� �� ���������, ��� �� � ���������� 
        //������� ���� ������� �����)
        $dataById = $this->gallery_model->getDataById($content_id);
       
        //�����  �������� �� ��� �������, ��� ������� ������������    ���
             if(!empty($dataById['type']) && !empty($dataById['section_id']))
                {
                    switch($dataById['type']){
                      case 'img':
                      {
                        $name='foto/add';
                        break;
                      }
                      case 'video':
                      {
                        $name='video/add';
                        break;
                      }
                     default:{exit();}
                    }
                     $this->gallery_model->deleteById($content_id);
                      unlink(DROOT.'/upload/_tumb/'.$dataById['file_tmp']);
                     ($dataById['type']=='img')? 
                     unlink(DROOT.'/upload/real/'.$dataById['file']):false;
                    $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_result_title'),'popup_text'=>$this->lang->line('gallery_msg_true_delete'));    
                 //������� ������� �� �����.
                
                 $data['section']= $this->gallery_model->getSelection($dataById['section_id']);
                  $data['id_dir']=$dataById['section_id'];                 
                 $data['content_list'] = $this->gallery_model->getGallery($data['id_dir']); 
             }
              else
               {
                //���������, ��� ��� ����� ������ ��� ��������
                $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_content') ); 
                $name='gallery_dir';
                $data['dir_list']=$this->gallery_model->get_dir('img');     
               }
         $this->display_lib->page($data,$name,$popup_info);
     
          
    }    

    
}
/* End of file gallery.php */
/* Location: ./application/controllers/gallery.php */

