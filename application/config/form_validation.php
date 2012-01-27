<?php
/* 
| -------------------------------------------------------------------
| FORM_VALIDATION 
| -------------------------------------------------------------------
 */
  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array(
                             
                  'addDir' => array(
                                     array(
                                             'field' => 'name',
                                             'label' => 'name',
                                             'rules' => 'required||min_length[1]|trim|xss_clean|mysql_real_escape_string'
                                          )
                                    ),
                  'addImg' => array(
                                     array(
                                             'field' => 'name[]',
                                             'label' => 'name[]',
                                             'rules' => 'max_length[100]|trim|strip_tags|mysql_real_escape_string|xss_clean'
                                          )
                               ),
                               
                 'addVid' => array(
                                     array(
                                             'field' => 'name[]',
                                             'label' => 'name[]',
                                             'rules' => 'max_length[100]|trim|strip_tags|mysql_real_escape_string|xss_clean'
                                          ),
                                      array(
                                             'field' => 'code_video[]',
                                             'label' => 'code_video[]',
                                             'rules' => 'max_length[200]|trim|strip_tags|mysql_real_escape_string|xss_clean'
                                          )
                               )             
              );


/* End of file form_validation.php.php */
/* Location: ./application/config/form_validation.php */