<div class="admin_right">
          <h1  class="title"><a href="<?=base_url()?>gallery/viewDir/img/">�������� ����������</a> : <?=$section['name']?></h1><br /> 
          
          <form action="<?=base_url()?>gallery/uploadImg/<?=$id_dir?>" method="post" enctype="multipart/form-data" name="get_img"> 
                <table border="0"  cellpadding="5" cellspacing="5" id="addContent">
                         <tr class="head">
                        	<td>����������</td>                        
                        	<td>�������</td>
                        	<td><img src="<?=base_url()?>i/admin/add.png"  class="addTrImg"/></td>
                        </tr>
                        <tr>
                        	<td> <input type="file" size="25" name="foto[]" /></td>
                        	<td><input name="name[]" type="text" size="20" value="" /></td>                        
                        	<td width="20"><img  src="<?=base_url()?>i/admin/del.png" class="delTr" /></td>
                        </tr> 
                              
                   </table>                  
 <div class="hint">������ ���� ������ ���� �� ����� 1024*768 � �������� �� ����� 1 ��</div>
                 <br />
                 <input type="submit" value="��������" />          
          </form>
          <div class="line"></div>
        
        
     
       <?if(!empty($content_list)){?> 
          <ul class="gallery clearfix">
          <?foreach($content_list as $item):?>
          	<li><a href='<?=base_url()?>upload/real/<?=$item['file']?>' rel="prettyPhoto[gallery2]"   class="foto-cont-mini">
              <img src="<?=base_url()?>upload/_tumb/<?=$item['file_tmp']?>"  rel="<?=$item['gid']?>" /><br> <?=$item['name']?>
           </a> </li>           
          <?endforeach;?> 
        </ul>        
        <div id="editImg"  style="display: none; position: absolute;"><a href="" rel='facebox'>
         <img src="<?=base_url()?>i/admin/edit.png"/></a>
        </div>
        
         <div id="forma" style="display: block; "> 
         </div>
         <?}else{?>
          <div class="nowrap-content">� ���� ����� ��� ��� ����������!</div> 
         <?}?>

     <div class="clear"> </div>     
          
          
         </div>
 <div class="clear"></div>