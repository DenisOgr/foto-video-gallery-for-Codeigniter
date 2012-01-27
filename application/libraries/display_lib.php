<?php
/**
 * Display_lib class
 * 
 * 
 * @category Controllers
 * @author	Porplenko Denis
 * @email	denis.porplenko@gmail.com
 */

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Display_lib {


    //data - массив с переменными, 
    //name - начало имени файла вида 
    //функция выводит обычную страницу
    public function page($data='',$name,$popup_info='')
    {
        $CI =& get_instance ();        
       
        $CI->load->view('header_view');        
        $CI->load->view($name.'_view',$data);          
        $CI->load->view('footer_view');    
        if(!empty($popup_info))$CI->load->view('popup_view',$popup_info);             
    } 
 
}
// END Display_lib class

/* End of file display_lib.php */
/* Location: ./system/libraries/display_lib.php */