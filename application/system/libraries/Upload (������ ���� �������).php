<?
    
    //Multi upload
    function multi_upload($field='userfield')
	{

    	if (!empty($_FILES[$field]))
		{
		  	
		$multi_data=array();	   
		foreach ($_FILES[$field]['name'] AS $index => $val)
			{
				if(!empty($_FILES[$field]['name'][$index])) {
					foreach ($_FILES[$field] AS $key => $val_arr)
						{
							$_FILES[$field.$index][$key] = $val_arr[$index];
						}
					self::do_upload($field.$index);
					$multi_data[$index]=self::data();
					$multi_data[$index]["error"]=self::display_errors();
					$this->file_name ="";
				}
			}
			unset($_FILES[$field]);
			return $multi_data;
		}
	}

?>