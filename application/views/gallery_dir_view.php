   <div class="admin_right">         
          <h1  class="title"><a href="<?=base_url()?>gallery/">Галлереи</a> : <?=$name_admin_cat?></h1><br />
          
          <ul id="gallery-dir">
          <?foreach($dir_list as $item):
          if(empty($item['file_tmp']))$item['file_tmp']=DEFAULT_DIR_IMG;
          ?>
        	<li>
                <a href="<?=base_url()?>gallery/view/<?=$item['section_id']?>" class="edit">
                   <img src="<?=base_url()?>upload/_tumb/<?=$item['file_tmp']?>" rel="<?=$item['section_id']?>"   /><br> <?=$item['name']?>
                </a>
            </li>                  
        
        
         <?endforeach?> 
         <li>
         <a href="#forma" rel='facebox'  >
          <img src="<?=base_url()?>i/admin/add_dir60_60.png"  /><br>Добавить</a>
         </li> 
        </ul>

     <div class="clear"> </div> 
 
     <div id="forma" style="display:none; ">
     <h1 class="title" >Добавить новую папку</h1>

      <form action="<?=base_url()?>gallery/addDir/<?=$type?>" method="post" enctype="multipart/form-data"  name="newDir">
      
         <label> Название</label></br>
         <input type="text" name="name" /></br></br>         
        
        <label> Фотография</label></br>        
         <input type="file" size="25" name="userfield[]" /></br></br>                     
         <input type="submit" value="Создать" name="send_new_dir"/>         
        </form>
    
    
     </div>  
           <div id="editImg"  style="display: none; position: absolute;"><a href="<?=base_url()?>gallery/getNameDir" rel='facebox'>
         <img src="<?=base_url()?>i/admin/edit.png"/></a>
        </div>
        
       
         </div>
          
         </div>
 <div class="clear"></div>