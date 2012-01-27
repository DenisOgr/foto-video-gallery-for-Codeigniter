         <div class="admin_right">          
         <h1  class="title"><a href="<?=base_url()?>gallery/viewDir/video/">Галлерея видео</a> : <?=$section['name']?></h1><br />          
             
    <form action="<?=base_url()?>gallery/uploadVideo/<?=$id_dir?>" method="post" enctype="multipart/form-data" name="get_img">
            <table border="0"  cellpadding="5" cellspacing="5" id="addContent">
                     <tr class="head">
                    	<td>Ссылка на видео</td>
                    	<td>Название/картинка</td>            	
                    	<td><img src="<?=base_url()?>i/admin/add.png"  class="addTrVid"/></td>
                    </tr>
                    <tr>
                    	<td> <textarea cols="40" rows="5" name="code_video[]" ></textarea> </td>
                        <td> <input type="file" size="25" name="foto[]" /></br></br>
                    	<input name="name[]" type="text" size="25" value="" /></br></br>                    	
                    	<td width="20"><img  src="<?=base_url()?>i/admin/del.png" class="delTr" /></td>
                    </tr> 
                              
                </table>
                <div class="hint">Ссылка на видео должна бы таких форматов: <strong>http://vimeo.com/32646874 </strong></span> 
                или <strong>http://www.youtube.com/watch?v=Cxvh9qwSqLQ</strong></div><br />
                 <input type="submit" value="Добавить" />          
   </form>
          
          
    <div class="line"></div>      
        
             <?if(!empty($content_list)){?> 
               <script type="text/javascript" charset="utf-8">
				api_gallery=['images/fullscreen/1.JPG','images/fullscreen/2.jpg','images/fullscreen/3.JPG'];
				api_titles=['API Call Image 1','API Call Image 2','API Call Image 3'];
				api_descriptions=['Description 1','Description 2','Description 3'];
			</script>
           
            <ul class="gallery clearfix">			
			</ul>
   <ul class="gallery clearfix">
          <?foreach($content_list as $item):?>
               <li><a href="<?=$item['code']?>&width=800" rel="prettyPhoto" title="<?=$item['name']?>"  class="foto-cont-mini">
             <img src="<?=base_url()?>upload/_tumb/<?=$item['file_tmp']?>"   rel="<?=$item['gid']?>" /><br><?=$item['name']?></a>  </li>              
          <?endforeach;?>  
   </ul>    
    
    <div id="editImg"  style="display: none; position: absolute;"><a href="<?=base_url()?>gallery/getName/" rel='facebox'>
         <img src="<?=base_url()?>i/admin/edit.png"/></a>
    </div>
        
    <div id="forma" style="display: block; "> </div>       
    <?}else{?>
          <div class="nowrap-content">В этом разделе еще нет видео!</div> 
         <?}?>
        
          
        <div class="clear"></div>
    </div> 
    <div class="clear"></div>	