<?php 
/**
 * Gallery Class
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
  
 /*****************************РАБОТА С ПАПКАМИ******************************************************/ 
     
     //Функция, которая выводит папки из галлереи
     //Параметр img - папки только с картинками
     //Параметр video - папки с видеороликами      
     public function viewDir($type='img')
        {       
         
         $data=array();  
         $data['type'] = $type;  
         $data['name_admin_cat']=($type=='img')?'Галлерея фото':'Гллерея видео'; 
         $data['dir_list']=$this->gallery_model->get_dir($type); 
         $name='gallery_dir';  
         $this->display_lib->page($data,$name);
          
      }
      
      
      
    //Функция, которая добавляет папку в галлерею
     //Параметр img - папки только с картинками
     //Параметр video - папки с видеороликами     
     public function addDir($type='img')
       {
         $result_text='';
         $data['name_admin_cat']='Галлерея';  
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
                           $result_text.="Фотография '".$_POST['name']."' загрузилась успешно!<br>";  
                           //Формирую превьюшку              
                                 $this->load->library('image_lib'); 
                                 $unic_pre=time().mt_rand(0,1000);
                                 $small_file = $array_img[0]['raw_name']."_".$unic_pre."_thumb".$array_img[0]['file_ext']; 
                                 $config['image_library'] = 'gd2'; // выбираем библиотеку
                                 $config['source_image'] = $array_img[0]['full_path']; 
                                 $config['new_image'] = FCPATH.'/upload/_tumb/'.$small_file; 
                                 $config['maintain_ratio'] = TRUE; // сохранять пропорции
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
                        $result_text.="Фотография '".$_POST['name']." 'не загрузилась!<br>";
                          } 
         }
         else
             {
             $result_text.= '<br>У Вас ошибки при вводе информации. <br>Будьте внимательны!';  
             }
             
            
             $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_result_title'),'popup_text'=>$result_text);  
             
             
                          
             $data['dir_list']=$this->gallery_model->get_dir($type); 
             $this->display_lib->page($data,$name,$popup_info);  
   
      }  


  
    //Функция показывает содержимое папок
    public function view($idDir)
    { 
        $data = array();      
        
        $data['id_dir']=(int)$idDir;
       
       //Выбираю данные по папке из БД
        $data['section']= $this->gallery_model->getSelection($idDir);    
       
  
        if(!empty($data['section']))
        {
            //Выбираю контент из папки.
             $data['content_list'] = $this->gallery_model->getGallery($idDir);
              
            //Вывожу в разных видах в зависимости от папки 
            //Если ы папке нет, также выводу в разном виде       
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
  
  
  
   //функция возвразает данные об папке 
  //в виде формы для редактирования  
   public function getNameDir($content_id)
   {
    
     settype($content_id,'integer');
    $data = $this->gallery_model->getSelection($content_id);   

    if(!empty($data))
    {
        printf(' 
        <div id="forma">
        <h1 class="title" >Редактирование</h1>
             <form action="%sgallery/reNameDir/%s" name="newDir" method="post">
             <label> Название</label></br>
             <input type="text" name="name"  value="%s" /></br></br> 
             
             <input type="submit" value="Изменить" name="send_new_dir"/>         
               </form><br><br>
                <a href="%sgallery/deleteDir/%s" style="color:red">Удалить папку</a></div>',
                 base_url(),$data['section_id'],$data['name'],base_url(),$data['section_id'] );   
              
   }
   else
   {
    echo "У нас нет такой папки!";
   }
   }  
    
   
   //Функция изменяет название и описание папки 
  public function reNameDir($section_id)
      {
        $data = array();            
        
        //Выбираю все данные по этой папке, что бы узнать ее тип
        // и в результате вывести все папки этого типа
        $dataById = $this->gallery_model->getSelection($section_id); 
        $data['name_admin_cat']=($dataById['type']=='img')?'Галлерея фото':'Галлерея видео';
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
         //Выбираю папки из галлереи.
         $data['dir_list']=$this->gallery_model->get_dir($dataById['type']);    
         $name='gallery_dir'; 
          $this->display_lib->page($data,$name,$popup_info);  
         
        
    }   
        
         
         //функция удаляет целую папку с видео/картинками/контентом
     	public function deleteDir($section_id)
            {
                 $data = array();
               
                 //Выбираю все данные по этой папке, что бы узнать ее тип
                // и в результате вывести все папки этого типа
                $dataById = $this->gallery_model->getSelection($section_id);
               //есть ли такая папка?
                if(!empty($dataById['type']))
                {
                 //Выбираю все записи, что бы в цикле удалить все картинки c сервера
                $dataAllCont = $this->gallery_model->getGallery($section_id);
                   //удаляю картинки
                   foreach($dataAllCont as $item)
                        {
                         unlink(DROOT.'/upload/_tumb/'.$item['file_tmp']);
                         ($dataById['type']=='img')?unlink(DROOT.'/upload/real/'.$item['file']):false;         
                        }
                   //удаляю записи в БД
                      $this->gallery_model->deleteAllById($section_id); 
                    //удаляю картинку папки
                     unlink(DROOT.'/upload/_tumb/'.$dataById['file_tmp']);  
                    //удаляю запись о папке изБД
                    $this->gallery_model->deleteDir($section_id);          
                   
                    $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_result_title'),'popup_text'=>$this->lang->line('gallery_msg_true_delete_dir'));
                    $data['name_admin_cat']=($dataById['type']=='img')?'Галлерея фото':'Галлерея видео';
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
    


 /*****************************РАБОТА С КОНТЕНТОМ(ФОТО, ВИДЕО)****************Ы******************************/ 
  
     //функция возвразает данные об картинке или видео файле
   //в виде формы для редактирования.   
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
             <label> Название </label></br>
             <input type="text" name="name" value="%s" /></br></br>
             
             <input type="submit" value="Изменить" name="send_new_dir"/>         
               </form><br><br>
                <a href="%sgallery/delete/%s" style="color:red">Удалить картинку</a></div>',
                base_url(),$data['gid'],$data['name'],base_url(),$data['gid'] );   
                break;
            }
           
           case 'video':
           {  
            printf(' <div id="forma">
            <h1 class="title" >Редактирование</h1>
             <form action="%sgallery/reNameContent/%s" name="newDir" method="post">
             <label> Название</label></br>
             <input type="text" name="name" value="%s" /></br></br>            
             
             <label> Ссылка на видео</label></br>
             <textarea cols="20" rows="4" name="code" >%s</textarea></br></br>
           
            <input type="submit" value="Изменить" name="send_new_dir"/>         
            </form><br><br>
            <a href="%sgallery/delete/%s" style="color:red">Удалить видеоролик</a></div>',
            base_url(),$data['gid'],$data['name'],$data['code'],base_url(),$data['gid'] );      
       break; 
           }
           
        }
   }
 else
   {
    echo "У нас нет такой контента!";
   }
   }  
   
   	
       
       
       public function reNameContent($content_id)
    {
        
        $data = array(); 
        $content_id = (int)$content_id;
        //Выбираю все данные по данному контенту, что бы узнать его section_id 
        //(ID папки в которой он находится, что бы в результате 
        //вывести весь контент папки)
        $dataById = $this->gallery_model->getDataById($content_id);       
       
        //есть ли такая запись в галлереи?
        if(!empty($dataById['section_id']))
              {
                //Выбираю данные по папке из БД
                 $data['section']= $this->gallery_model->getSelection($dataById['section_id']);  
                //есть ли такая папка?
                if(!empty($data['section']['type']))
                   {
                        //делаю  проверку на тип котента, что после переименования выводит  определенный   вид
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
            
                             //Выбираю контент из папки.                     
                             $data['content_list'] = $this->gallery_model->getGallery($dataById['section_id']); 
                             $data['id_dir'] = $dataById['section_id'];
                   
                     }
                     else
                     {
                        //сообщение, что нет папки
                        $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_dir') );  
                        $name='gallery_dir';
                        //выбираю папки с картинками
                        $data['dir_list']=$this->gallery_model->get_dir('img'); 
                     }
    
         }
         else
          {
           //сообщение, что нет указанного контента
           $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_content') );   
           $name='gallery_dir';
           //выбираю папки с картинками
           $data['dir_list']=$this->gallery_model->get_dir('img');   
          }
         
         $this->display_lib->page($data,$name,$popup_info);
        
    }
    
    
    
  //Функция загружает фото на сервер, делает превьи и пишет  запись в БД
    public function uploadImg($dir='')
    {     
         $data=array();            
         $data['id_dir']=(int)$dir;
         $name='foto/add';  
       
        //Выбираю данные по папке из БД
         $data['section']= $this->gallery_model->getSelection($dir);  
        //есть папка, в которую заливать картинки?
        if(!empty($data['section']))
             {
             //есть ошибки при вводе данных?
             if($this->form_validation->run('addImg')==TRUE)
                    {
                          //Заливаю картинку   впапку temp       
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
                            //указал ли пользователь вообще файлы с картинками?
                            if(!empty($array_img))
                            {                              
                              $array_name=$this->input->post('name');
                                //смотрю массив результатов при заливке фото
                                $this->load->library('image_lib'); 
                                foreach($array_img as $item)
                                {
                                  
                                  if(empty($item['error']))
                                  {
                                    $result.="Фотография '".$array_name[$i]."' загрузилась успешно!<br>";  
                                         //Формирую превьюшку
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
                                       
                                       
                                        //Формирую  обычную картинку                                         
                                         $real_file = $item['raw_name']."_".$unic_pre.$item['file_ext'];
                                         $config['new_image'] = FCPATH.'/upload/real/'.$real_file; 
                                         $config['maintain_ratio'] = TRUE; // сохранять пропорции   
                                         $config['width'] = $this->config->item('gallery_real_w'); 
                                         $config['height'] = $this->config->item('gallery_real_h');  
                                         $this->image_lib->initialize($config);
                                         $this->image_lib->resize();  
                                       
                                        //Формирую данные для записи в БД 
                                        $dataInsert=array(
                                        'section_id'=>$dir,
                                        'type'=> $data['section']['type'],
                                        'name'=>$array_name[$i],                                             
                                        'file'=>$real_file,
                                        'file_tmp'=>$small_file,
                                        'mod'=>1
                                        );
                                        //Делаю запись в БД
                                       $this->gallery_model->addImg($dataInsert);   
                                        //Удаляю исходнюю картинку
                                     
                                        unlink($item['full_path']); 
                                                                  
                                  } 
                                  else
                                  {
                                    $result.="Фотография '".$array_name[$i]." 'не загрузилась!<br>";
                                  } 
                                 $i++;  
                                }
                            $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_result_title'),'popup_text'=>$result);
                       }
                       else
                       {
                      //сообщение, что ошибки не указанно ни одной картинки
                     $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_rules'));
                       } 
                   }
                    else
                    { 
                      //сообщение, что ошибки при вводе данных
                     $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_rules'));
                     
                    }   
                 //Выбираю контент из папки. 
                 $data['content_list'] = $this->gallery_model->getGallery($dir);        
             }
             else
             { 
             //сообщение, что нет папки, куда будут заливаться фото
             $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_dir'));
             $name='gallery_dir';
             $data['dir_list']=$this->gallery_model->get_dir('img');  
            }
             
      $this->display_lib->page($data,$name,$popup_info);  
                       
                  
 }
    
    //Функция добавляет картинку для видео превью на сервер, делает превью и делает запись в БД про видео ролик
    public function uploadVideo($dir='')
    {       
          
         $data=array();  
         $data['id_dir']=(int)$dir;
         $name='video/add';  
        
        
         //Выбираю данные по папке из БД 
        $data['section']= $this->gallery_model->getSelection($dir);  
          //есть папка, в которую заливать видеоролик?
        if(!empty($data['section']))
        {
                 
            //есть ошибки при вводе данных?
              if($this->form_validation->run('addVid')==TRUE)
             {
                            //Заливаю картинку   впапку temp       
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
                          //указал ли пользователь вообще файлы с картинками?
                            if(!empty($array_img))
                            {                              
                              $array_name = $this->input->post('name');
                              $array_code = $this->input->post('code_video');
                                //смотрю массив результатов при заливке фото
                              $this->load->library('image_lib'); 
                              foreach($array_img as $item)
                                {
                                  if(empty($item['error']))
                                  {
                                  $result.="Видеоролик '".$array_name[$i]."' загрузилась успешно!<br>";  
                                         //Формирую превьюшку
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
                                       
                                        //Формирую данные для записи в БД 
                                        $dataInsert=array(
                                        'section_id'=>$dir,
                                        'type'=> $data['section']['type'],
                                        'name'=>$array_name[$i],                                             
                                        'file'=>'',
                                        'file_tmp'=>$small_file,
                                        'code'=>$array_code[$i],
                                        'mod'=>1
                                        );
                                        
                                        //Делаю запись в БД
                                       $this->gallery_model->addImg($dataInsert);   
                                       
                                        //Удаляю исходнюю картинку                                     
                                        unlink($item['full_path']); 
                                        
                                            
                                  } 
                                  else
                                  {
                                    $result.="Видеоролик '".$array_name[$i]." 'не загрузилась!<br>";
                                  } 
                                  $i++;
                                }
                          
                              $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$result);  
                          
                          }
                          else
                          {
                            //сообщение, что ошибки не указанно ни одной картинки
                            $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_rules'));   
                          }
             }
             else
             {
             //сообщение, что ошибки при вводе данных
             $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_rules'));  
             }                
         //Выбираю контент из папки. 
          $data['content_list'] = $this->gallery_model->getGallery($dir);  
       }
       else
       {  
                
       //сообщение, что нет папки, куда будут заливаться фото
       $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_dir'));
       $name='gallery_dir';
       $data['dir_list']=$this->gallery_model->get_dir('img');
       }
      $this->display_lib->page($data,$name,$popup_info);      
    }
    
    
    
        
    //функция удаляет картинку/видео/контент
  	public function delete($content_id)
    {   
        $data = array();
       
        //Выбираю все данные по данному контенту, что бы узнать его section_id 
        //(ID папки в которой он находится, что бы в результате 
        //вывести весь контент папки)
        $dataById = $this->gallery_model->getDataById($content_id);
       
        //делаю  проверку на тип котента, что выводит определенный    вид
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
                 //Выбираю контент из папки.
                
                 $data['section']= $this->gallery_model->getSelection($dataById['section_id']);
                  $data['id_dir']=$dataById['section_id'];                 
                 $data['content_list'] = $this->gallery_model->getGallery($data['id_dir']); 
             }
              else
               {
                //сообщение, что нет такой записи для удаления
                $popup_info=array('popup_title'=>$this->lang->line('gallery_msg_error_title'),'popup_text'=>$this->lang->line('gallery_msg_false_content') ); 
                $name='gallery_dir';
                $data['dir_list']=$this->gallery_model->get_dir('img');     
               }
         $this->display_lib->page($data,$name,$popup_info);
     
          
    }    

    
}
/* End of file gallery.php */
/* Location: ./application/controllers/gallery.php */

