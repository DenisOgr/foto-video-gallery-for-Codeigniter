$(document).ready(function(){
 //������ ����
 
   //����� ����� ������� ����� �����. �������� http://www.site.com/
   var baseUrl="";
 
    


//������� ��� ������������ ����
$('a[rel*=facebox]').facebox({loading_image : 'loading.gif',close_image   : 'closelabel.gif'});
 
 
 
//������� ���������/������� +1 ���� ��� �������� ����  
 $('.addTrImg').click(function() {
    $('#addContent').append(' <tr><td> <input type="file" size="25" name="foto[]"/></td><td><input name="name[]" type="text" size="20" value="" /></td><td width="20"><img  src="/i/admin/del.png" class="delTr" /></td></tr> ');
    
 });
 
 //������� ���������/������� +1 ���� ��� �������� �����
 $('.addTrVid').click(function() {
    $('#addContent').append('<tr><td> <textarea cols="40" rows="5" name="code_video[]" ></textarea> </td><td> <input type="file" size="25" name="foto[]" /></br></br><input name="name[]" type="text" size="25" value="" /></br></td><td width="20"><img  src="/i/admin/del.png" class="delTr" /></td></tr>');
    
 });
 
  
  $('body').delegate('.delTr','click',function() {
    $(this).parent().parent().remove();
    
 });
 
 $('body').delegate('form[name=get_img] :input','focus',function() {$(this).addClass('hight-input');});
 
 $('body').delegate('form[name=get_img] :input','blur',function() {$(this).removeClass('hight-input');})
 


//����������� �������� ��� ��������
$('a.foto-cont-mini').hover(function() {
 
    $('.gallery img').hover(function() {    
        
       var thisRel = $(this).attr('rel');  
       var left = $(this).offset().left-20;
       var top = $(this).offset().top-20;   
      
          
       $('#editImg').css({'display':'block','top':top,'left':left});
       /***************************����� �������� �������� ���******************************/
       $('#editImg a').attr('href',baseUrl+'gallery/getName/'+thisRel);
       /**********************************************************************/
       
    });    

});


//����������� �������� ��� �����. 
//������ ���� ����, ��� ����� ����������� ������ ���������.
//����� ������������ ������
$('#gallery-dir a.edit').hover(function() {
    $('img',this).hover(function() {    
         //alert("!!!");
       var thisRel = $(this).attr('rel');  
       var left = $(this).offset().left-20;
       var top = $(this).offset().top-20;  
       $('#editImg').css({'display':'block','top':top,'left':left});
       $('#editImg a').attr('href',baseUrl+'gallery/getNameDir/'+thisRel);
    });    

});


/*����������� ����*/
 $('#myPopUp').addClass('myPopup');
 setTimeout(function(){
    $('#myPopUp').fadeOut(2000);    
  },3000);  
    
 




    
});//����� ready