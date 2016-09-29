<?php
class Rooms extends CI_Controller
{

 public function Rooms()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->path = realpath(APPPATH . '../images');
			$this->gallery_path_url = base_url().'images/';
			$this->logopath = realpath(APPPATH . '../');
		$this->load->library('DX_Auth');  	
		
		$this->load->model('Users_model'); 
		$this->load->model('Email_model');
		$this->load->model('Message_model');
		$this->load->model('Trips_model');
		$this->load->model('Rooms_model');
		
		$this->facebook_lib->enable_debug(TRUE);
		$this->load->library('image_lib');
		$this->path = realpath(APPPATH . '../images');
		}

public function add1()
	{
		extract($this->input->get());
		$data['user_id']   		= $user_id;
		$data['address']   		= $address;
					$level = explode(',', $data['address']);
        $keys = array_keys($level);
        $country = $level[end($keys)];
        if(is_numeric($country) || ctype_alnum($country))
        $country = $level[$keys[count($keys)-1]];
        if(is_numeric($country) || ctype_alnum($country))
        $country = $level[$keys[count($keys)-1]];
        $data['country'] = trim($country);
		
		/*$state = $level[end($keys)-1];
        if(is_numeric($state) || ctype_alnum($state))
        $state = $level[$keys[count($keys)-2]];
        $data['state'] = trim($state);
		
		$city = $level[end($keys)-2];
        if(is_numeric($city) || ctype_alnum($city))
        $city = $level[$keys[count($keys)-1]];
        $data['city'] = trim($city);*/
		$data['street_address']   		= $data['address'];
		$data['lat'] 								= $this->input->get("latitude");
		$data['long'] 							= $this->input->get("longitude");
		$property_type = $this->input->get('property_id');  // it's a property type
		$data['property_id'] = $this->db->get_where('property_type', array('type' => $property_type))->row()->id;
		$data['room_type'] 		= $room_type;
		$data['bedrooms']			 = $bedrooms;
		$data['beds']			 = $beds;
		$data['amenities']			 = $this->input->get("amenities");
		$data['bathrooms']			 = $bathrooms;
		$data['bed_type']			 = $this->input->get("bed_type");
		$data['status']			 = '0';
	
		$data['capacity'] 			= $capacity;
		//$data['imageurl'] 			= $this->input->get('imageurl');
		$data['phone'] 						= $this->input->get("phone");
	    $data['list_pay']   = 1;
		$data['is_enable']   = 1;
		//$data['cancellation_policy'] = $this->input->get('cancellation_policy'); 
 		$data['cancellation_policy'] = 1;
		$amenities = $this->input->get('amenities');
		
	 	$in_arr = explode(',', $amenities);

	    $result = $this->db->get('amnities');
		
	    $amenities_id  = '';
		
		if($result->num_rows() != 0) 
		{
	       if($amenities)
			   {
			    foreach($result->result() as $row)
	              {
	                 if(in_array($row->name, $in_arr))
						{
							$json[] = $row->id.",";
						}
				  }
				  
		$count = count($json);
		$end = $count-1;
		$slice = array_slice($json,0,$end);
		
			$comma = end($json);
			$json = substr_replace($comma ,"",-1);
			$amenities_id = $json;
         $row1 = '';
	  foreach($slice as $row)
		{
			$row1 .= $row; 
		}
			   }
			   }
	else {
		$amenities_id ='';
		}
	
	if($amenities)
			   {
		$amenities_id = $row1.$amenities_id;
       }
		
	    $data['amenities'] = $amenities_id;
		if($data['user_id'] == ''){
		  echo '[{"status":"You should provide userid."}]';exit;
		}
		else{
		$this->db->insert('list', $data);
		$insert_id = $this->db->insert_id();
		//Getting the info just entered
		$this->db->where('id',$insert_id);
		//$this->db->where('title',$data['title']);
		//$this->db->where('desc',$data['desc']);
		//$this->db->where('imageurl',$data['imageurl']);
		
		$query  = $this->db->get('list');
		
		$result = $query->result();
		
		//$data2['id']       = $result[0]->id;
		/*$data2['id']       = $insert_id;
		$data2['night']    = $data['price'];
		$data2['currency'] = $data['currency'];
		$this->db->insert('price', $data2);*/
		$lys_status['id'] = $insert_id;
		$lys_status['user_id'] = $data['user_id'];
		$lys_status['calendar'] = '0';
		$lys_status['price'] = '0';
		$lys_status['overview'] = '0';
		$lys_status['title'] = '0';
		$lys_status['summary'] = '0';
        $lys_status['photo'] = '0';

		$lys_status['amenities'] = '0';
		$lys_status['address'] = '0';
		$lys_status['listing'] = '1';
		$lys_status['beds'] = '1';
		$lys_status['bathrooms'] = '1';
        $lys_status['bedscount'] = '1';
        $lys_status['bedtype'] = '1';
		
		//$lys_status['bed_type'] = '1';
		//$lys_status['bathrooms'] = '1';
		
		$this->db->insert('lys_status',$lys_status);
		//print_r($this->db->last_query());exit;
		echo '[{"reason_message":"List added successfully.", "room_id":'.$insert_id.'}]';exit;
		}	
	}





	public function add()
	{
		extract($this->input->get());
		$data['user_id']   		= $user_id;
		
		$data['street_address']   	= $address;
		$data['address']   		= $address;
		
		$data['lat'] 			= $this->input->get("latitude");
		$data['long'] 			= $this->input->get("longitude");
		$property_type = $this->input->get('property_id');  // it's a property type
		$data['property_id'] = $this->db->get_where('property_type', array('type' => $property_type))->row()->id;
		$data['room_type'] 		= $room_type;
		$data['bedrooms']		= $bedrooms;
		$data['beds']			= $beds;
		$data['amenities']		= $this->input->get("amenities");
		$data5['map']			= $this->input->get("map");
		$data['bathrooms']		= $bathrooms;
		$data['bed_type']		= $this->input->get("bed_type");
		$data['city']           	= $this->input->get('city');
		$data['state']        		= $this->input->get('state');
		$data['country']    		= $this->input->get('country'); 
	
		$data['capacity'] 		= $capacity;
		//$data['imageurl'] 		= $this->input->get('imageurl');
		$data['phone'] 			= $this->input->get("phone");
        $data['list_pay']   = 0;
		$data['is_enable']   = 0;
		//$data['cancellation_policy'] 	= $this->input->get('cancellation_policy');
		$data['cancellation_policy'] 	= 1;
		$data['home_type']		= $home_type;
        
        $default_title = "Entire Home/Apt in";
        $city_name = $data['city'];
        
        $data['title'] = $default_title.' '.$city_name;
        
        $data['currency']		= "USD";
 		
		$amenities = $this->input->get('amenities');
		
	 	$in_arr = explode(',', $amenities);

	    $result = $this->db->get('amnities');
		
	    $amenities_id  = '';
		
		if($result->num_rows() != 0) 
		{
	       if($amenities)
			   {
			    foreach($result->result() as $row)
	              {
	                 if(in_array($row->name, $in_arr))
						{
							$json[] = $row->id.",";
						}
				  }
				  
		$count = count($json);
		$end = $count-1;
		$slice = array_slice($json,0,$end);
		
			$comma = end($json);
			$json = substr_replace($comma ,"",-1);
			$amenities_id = $json;
         $row1 = '';
	  foreach($slice as $row)
		{
			$row1 .= $row; 
		}
			   }
			   }
	else {
		$amenities_id ='';
		}
	
	if($amenities)
			   {
		$amenities_id = $row1.$amenities_id;
       }
		
	    $data['amenities'] = $amenities_id;
		if($data['user_id'] == ''){
		  echo '[{"reason_message":"You must provide userid."}]';exit;
		}
		else{
		$this->db->insert('list', $data);
			
		$insert_id = $this->db->insert_id();
		//Getting the info just entered
		$this->db->where('id',$insert_id);
		//$this->db->where('title',$data['title']);
		//$this->db->where('desc',$data['desc']);
		//$this->db->where('imageurl',$data['imageurl']);
		$data_final = $this->db->where('user_id', $data['user_id'])->get('list');
				 
		$data5['user_id'] = $data_final->row()->user_id;
		$this->db->insert('list_photo', $data5);

		$query  = $this->db->get('list');
		
		$result = $query->result();
		$price_data['id'] = $insert_id;
		$this->db->insert('price', $price_data);
		//$this->db->insert('list', $data5);
		$query  = $this->db->get('list');
		
		$result = $query->result();
		$lys_status['id'] = $insert_id;
		$lys_status['user_id'] = $data['user_id'];
		$lys_status['calendar'] = '0';
		$lys_status['price'] = '0';
		$lys_status['overview'] = '0';
		$lys_status['title'] = '0';
		$lys_status['summary'] = '0';
        $lys_status['photo'] = '0';
		$lys_status['amenities'] = '0';
		$lys_status['address'] = '0';
		$lys_status['listing'] = '1';
		$lys_status['beds'] = '1';
		$lys_status['bathrooms'] = '1';
        $lys_status['bedscount'] = '1';
        $lys_status['bedtype'] = '1';
		
		//$lys_status['bed_type'] = '1';
		//$lys_status['bathrooms'] = '1';
		
		$this->db->insert('lys_status',$lys_status);
		echo '[{"reason_message":"List added successfully.", "room_id":"'.$insert_id.'"}]';exit;
		}		
	}
	public function delete_image1()
{
	$room_id = $this->input->get('room_id');
	$image_id = $this->input->get('image_id');//print_r($image_id);exit;
	$image =  $this->db->where('id',$image_id)->get('list_photo');
	//print_r($this->db->last_query());exit;
	
	if($image->num_rows() != 0)
	{	
	$data5['photo'] = 0;
	$data2['status']= 0 ;
	$this->db->where('id',$room_id)->update('lys_status',$data5);
	$this->db->where('id',$room_id)->update('list',$data2);
	$image_data =$this->db->where('id',$image_id)->delete('list_photo');
	echo '[{"status":"Deleted Successfully"}]';
	}
	else 
	{
		echo '[{"status":"No Data Found"}]';
	}
}

public function delete_image()
{
	$room_id = $this->input->get('room_id');
	$image_id = $this->input->get('image_id');
	$image =  $this->db->where('id',$image_id)->get('list_photo');
	if($image->num_rows() != 0)
	{
	$data5['photo'] = 0;
	$data2['status']= 0 ;
	$this->db->where('id',$room_id)->update('lys_status',$data5);
	$this->db->where('id',$room_id)->update('list',$data2);
	$image_data =$this->db->where('id',$image_id)->delete('list_photo');
	echo '[{"status":"Deleted Successfully"}]';
	}
	else 
	{
		echo '[{"status":"No Data Found"}]';
	}
}

public function your_listing()
{
		$user_id   = $this->input->get('user_id');
		//$query = $this->db->query('SELECT * FROM `list` WHERE user_id = '.$user_id.' ORDER BY `id` DESC');
		if($this->input->get('start'))
		$this->db->limit(5,$this->input->get('start')-1);
		else
		$this->db->limit(5,0);
	
        //$step_count_query = $this->db->distinct()->select('list.*,lys_status.title as title_status, lys_status.summary as summary_status, lys_status.price as price_status, lys_status.photo as photo_status, lys_status.address as address_status, lys_status.listing as listing_status')->join('lys_status',"lys_status.id=list.id")->where("list.user_id", $user_id)->order_by('id', 'desc')->get('list');

	//if($step_count_query->num_rows()!=0)
	if($result = $this->db->from('list')->distinct()->select('list.*,lys_status.title as title_status, lys_status.summary as summary_status, lys_status.price as price_status, lys_status.photo as photo_status, lys_status.address as address_status, lys_status.listing as listing_status')->join('lys_status',"lys_status.id=list.id")->where("list.user_id", $user_id)->order_by('id', 'desc')->get()->result())
	{
	//foreach($step_count_query->result() as $row)
	foreach($result as $row)
	{
		$data['id']=$row->id;
		$data['user_id'] = $row->user_id;
	    $data['country'] = $row->country;
		$data['room_type'] = $row->room_type;
		if(!empty($row->address))
		{
		$data['address'] = $row->address;
		}
		else 
		{
			$data['address'] = $row->street_address;
		}
				$data['title'] = $row->title;
				$data['desc'] = $row->desc;
				$data['step_status']   = $row->step_status;
				$data['check_status']   = $row->check_status;
				$resize =  $this->db->where('list_id',$data['id'])->get('list_photo')->row('resize');
				$image =  $this->db->where('list_id',$data['id'])->get('list_photo')->row('image');
				$resize1 =  $this->db->where('list_id',$data['id'])->get('list_photo')->row('resize1');
				//print_r($this->db->last_query());
				if(!empty($resize) && !empty($resize1) && !empty($image)){
					$data['image']   = $image;
					$data['resize']   = $resize;
					$data['resize1']   = $resize1;
				}
				else {
					$data['image']   = '';
					$data['resize']   = '';
					$data['resize1']   = '';
				}
        
            if($row->overview != "null")
            {
            $total_status = $row->title_status + $row->summary_status + $row->price_status + $row->photo_status + $row->address_status;
            
            $data['step_count'] = 5 - $total_status;
            }
            else
            {
            $data['step_count'] = -1;
            }
        $data['status'] = "success";
      
				$listing[]=$data;
				
	}
	echo json_encode($listing, JSON_UNESCAPED_SLASHES);
	}
    
    else
    {
        echo '[{"status":"No data"}]';
    }
}
    
    public function checkstatuscount()
    {
        $room_id   = $this->input->get('room_id');
        //$query = $this->db->query('SELECT * FROM `list` WHERE user_id = '.$user_id.' ORDER BY `id` DESC');
        
        $step_count_query = $this->db->distinct()->select('list.*,lys_status.title as title_status, lys_status.summary as summary_status, lys_status.price as price_status, lys_status.photo as photo_status, lys_status.address as address_status, lys_status.listing as listing_status')->join('lys_status',"lys_status.id=list.id")->where("list.id", $room_id)->order_by('id', 'desc')->get('list');
        
        if($step_count_query->num_rows()!=0)
        {
            foreach($step_count_query->result() as $row)
            {
                $data['id']=$row->id;
                $data['user_id'] = $row->user_id;
                $data['check_status']   = $row->check_status;
                
                if($row->overview != "null")
                {
                    $total_status = $row->title_status + $row->summary_status + $row->price_status + $row->photo_status + $row->address_status;
                    
                    $data['step_count'] = 5 - $total_status;
                }
                else
                {
                    $data['step_count'] = "wrong data";
                }
                $data['status'] = "success";
                
                $listing[]=$data;
                
            }
            echo json_encode($listing, JSON_UNESCAPED_SLASHES);
        }
        
        else
        {
            echo '[{"status":"No data"}]';
        }
    }

	public function photo_upload()
	{
		    $status = "";
			$msg = "";
			$file_element_name = 'uploadedfile';
			
			$this->load->model('common_model');
			
			if ($status != "error")	
			{
			
			$post_id = $this->common_model->list_post_id();
				
			$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images'; //Set the upload path
			
			$config['allowed_types'] = 'gif|jpg|png|jpeg'; // Set image type
			
			$config['encrypt_name']	= TRUE; // Change image name
			
			$this->load->library('upload', $config);
			
			$this->upload->initialize($config);
			
			if(!$this->upload->do_upload($file_element_name))
			{
				$status = 'error';
				$msg = $this->upload->display_errors('','');
				$data = "";
				
				echo '[{"status":"'.$msg.'"}]'; 
			}
			else 
			{
				$data = $this->upload->data(); // Get the uploaded file information
				
                $image = base_url().'images/'.$data['raw_name'].$data['file_ext'];   
							
				$config1['image_library'] = 'gd2';
				$config1['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$data['raw_name'].$data['file_ext'];
				$config1['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$data['raw_name'].'_100_100'.$data['file_ext'];
				//$config1['create_thumb'] = TRUE;
				$config1['maintain_ratio'] = TRUE;
				$config1['width'] = 100;
				$config1['height'] = 100;

				$this->load->library('image_lib');
				
				$this->image_lib->initialize($config1);

				if ( ! $this->image_lib->resize())
				{
   				 $resize = $this->image_lib->display_errors();
				}
				
				/*$resize = base_url().'images/'.$data['raw_name'].'_100_100'.$data['file_ext'];
				
				$config2['image_library'] = 'gd2';
				$config2['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$data['raw_name'].$data['file_ext'];
				$config2['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$data['raw_name'].'_320_320'.$data['file_ext'];
				//$config1['create_thumb'] = TRUE;
				$config2['maintain_ratio'] = TRUE;
				$config2['width'] = 320;
				$config2['height'] = 320;

				$this->image_lib->initialize($config2);

				if ( ! $this->image_lib->resize())
				{
   				 $resize1 = $this->image_lib->display_errors();
				}
				
				$resize1 = base_url().'images/'.$data['raw_name'].'_320_320'.$data['file_ext'];*/
		        
				echo '[{"photo":"'.$image.'"}]';exit;				
			}
					
			@unlink($_FILES[$file_element_name]);
	        }
    }
public function edit_list()
	{
		$roomid=$this->input->get('roomid');
		$data1['list_id'] 						= $this->input->get("roomid");
		$data1['created']  = time();
		$data['title'] 						= $this->input->get("title");
		$data['desc'] 					= $this->input->get("desc");
		$data['price'] 						= $this->input->get("price");
		$data['currency'] 						= $this->input->get("currency");
		$data1['name'] 						= $this->input->get("photo");
		$data['address']   		= $this->input->get("address");
		$data['city']           = $this->input->get('city');
		$data['state']        = $this->input->get('state');
		$level = explode(',', $data['address']);
        $keys = array_keys($level);
        $country = $level[end($keys)];
        if(is_numeric($country) || ctype_alnum($country))
        $country = $level[$keys[count($keys)-1]];
        if(is_numeric($country) || ctype_alnum($country))
        $country = $level[$keys[count($keys)-1]];
        $data['country'] = trim($country);
		$this->db->where('list_id',$roomid)->update('list_photo',$data1);
		$this->db->where('id',$roomid)->update('list',$data);
		echo '[{"reason_message":"Updated Successfully"}]';
	}
public function img_upload()
	{
		
			$status = "";
			$msg = "";
			$file_element_name = 'uploadedfile';
			$room_id = $this->input->get('room_id');
			 $this->path     = realpath(APPPATH . '../images');
			if ($status != "error")	
			{			
		$config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['encrypt_name']	= TRUE; 
				
				$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if(!$this->upload->do_upload($file_element_name)){
						$status = 'error';
						$msg = $this->upload->display_errors('','');
						$data = "";
					}
					else {
							
						$data = $this->upload->data();
						
						$this->load->library('image_lib');
						
						$config['image_library'] = 'gd2';
						$config['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
						$config['new_image']    = 'images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
						$config['create_thumb'] = FALSE;
						$config['maintain_ratio'] = TRUE;
						
						$config['width']     = 125;
    					$config['height']   = 125;
						
    				   $this->image_lib->initialize($config);
    				   $this->image_lib->resize();
					   
						echo $this->config->item('base_url').'images/'.$room_id.'/'.$data['raw_name'].$data['file_ext'];
						
					}
					
				@unlink($_FILES[$file_element_name]);
			}
			
	}
public function image_upload()
    {
        $id = $this->input->get('user_id');
		
        $file_element_name = 'uploadedfile';
		
        $this->path     = realpath(APPPATH . '../images/users/');
               $status = "";
            $msg = "";
            $file_element_name = 'uploadedfile';
            
            if ($status != "error")    
            {
                if(!is_dir($this->path.'/'.$id))
            {
                    mkdir($this->path.'/'.$id, 0777, true);
                    
            }

            $config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id;
       
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name'] = 'userpic.jpg';
                $config['overwrite'] = TRUE;
                $this->load->library('upload', $config);
              
                    $this->upload->initialize($config);
            if(!$this->upload->do_upload($file_element_name)){
                        
                        $status = 'error';  
                        $msg = $this->upload->display_errors('','');
                        $data = "";
                                        echo '[{"status":"'.$msg.'"}]'; 
                        
                    }
                    else {
                    	                $data = $this->upload->data();
                $image = base_url().'/images/'.$id.'/userpic_thumb.jpg';
                $config1['image_library'] = 'gd2';
                $config1['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id.'/userpic.jpg';
                $config1['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id.'/userpic_thumb.jpg';
                $config1['maintain_ratio'] = TRUE;
                $config1['width'] = 107;
                $config1['height'] = 78;

                $this->load->library('image_lib');
                
                $this->image_lib->initialize($config1);

                if ( ! $this->image_lib->resize())
                {
                    $resize = $this->image_lib->display_errors();
                }
                //$image = base_url().'/images/'.$id.'/pic_file.jpg'; 
				$image = base_url().'images/users/'.$id.'/userpic_profile.jpg';
                $config2['image_library'] = 'gd2';
                $config2['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id.'/userpic.jpg';
                $config2['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id.'/userpic_profile.jpg';
                $config2['maintain_ratio'] = TRUE;
                $config2['width'] = 209;
                $config2['height'] = 209;

                $this->load->library('image_lib');
                
                $this->image_lib->initialize($config2);

                if ( ! $this->image_lib->resize())
                {
                    $resize = $this->image_lib->display_errors();
                }
                //$image = base_url().'/images/'.$id.'/pic_home.jpg';
				$image = base_url().'images/users/'.$id.'/userpic_home.jpg';
                $config3['image_library'] = 'gd2';
                $config3['source_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id.'/userpic.jpg';
                $config3['new_image'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$id.'/userpic_home.jpg';
                $config3['maintain_ratio'] = TRUE;
                $config3['width'] = 320;
                $config3['height'] = 320;

                $this->load->library('image_lib');
                
                $this->image_lib->initialize($config3);

                if ( ! $this->image_lib->resize())
                {
                    $resize = $this->image_lib->display_errors();
                }
                        $data = $this->upload->data();
                       
                        $resize = base_url().'images/users/'.$id.'/userpic.jpg';
                        //$resize = base_url().'images/'.$id.'/pic.jpg';   
    
                echo '[{"image":"'.$image.'","resize":"'.$resize.'"}]';exit;
                        
                    }
                    
                @unlink($_FILES[$file_element_name]);
            }
    }
public function profile_image(){
	
	$emai_id = $this->input->get('email');
	$resize = $this->input->get('resize');
	$data['email']  = $emai_id;
	$data['src']  = $resize;
    $fb_query = $this->db->where('email', $emai_id)->get('users')->row()->fb_id;
    
    if($fb_query != 0)
    {
	$query   = $this->db->where('email',$emai_id)->get('profile_picture');
		
	  if($query->num_rows() == 0)
			{
                $this->db->insert('profile_picture',$data);
                echo '[{"reason_message":"Your profiles added picture Successfully"}]';
            }
      else
            {
                $this->db->where('email',$emai_id)->update('profile_picture',$data);
                echo '[{"reason_message":"Your profiles picture updated Successfully"}]';
            }
    }
    else
    {
        // echo '[{"reason_message":"Your profiles picture updated Successfully"}]';
     $query = $this->db->where('email', $emai_id)->get('profile_picture');
    if($query->num_rows() == 0)
       {
         $this->db->insert('profile_picture',$data);
         echo '[{"reason_message":"Your profiles added picture Successfully"}]';
       }   
    else
       {
       $this->db->where('email',$emai_id)->update('profile_picture',$data);
       echo '[{"reason_message":"Your profiles picture updated Successfully"}]';
    } 
        
    }
    /*new edited code
    $emai_id = $this->input->get('email');
    $resize = $this->input->get('resize');
    $data['email']  = $emai_id;
    $data['src']  = $resize;
    $query = $this->db->where('email', $emai_id)->get('profile_picture');
    if($query->num_rows() == 0)
       {
         $this->db->insert('profile_picture',$data);
         echo '[{"reason_message":"Your profiles added picture Successfully"}]';
       }   
    else
       {
       $this->db->where('email',$emai_id)->update('profile_picture',$data);
       echo '[{"reason_message":"Your profiles picture updated Successfully"}]';
    } 
	*/
	
}
/*public function bookit_date()
{
	$room_id = $this->input->get('room_id');
	$query   = $this->db->where('list_id',$room_id)->get('reservation');
//	print_r($query);exit;
	if($query->num_rows() != 0)
	{
		foreach($query->result() as $row)
		{
		$data['room_id']   = $row->list_id;
		$start_date   = $row->checkin;
		$data['checkin']  = gmdate('d-m-y',$start_date);
		$end_date =  $row->checkout;
		$data['checkout']  = gmdate('d-m-y',$end_date);
		$list_date[]   = $data;
	}
		echo json_encode($list_date);
	}
		else {
			echo '[{"status":"This room not available"}]';
	}
}	
		public function bookit_page()
{
	$room_id = $this->input->get('room_id');
	$query   = $this->db->where('id',$room_id)->get('list');
//	print_r($query);exit;
	if($query->num_rows() != 0)
	{
		foreach($query->result() as $row)
		{
		$data['id']   = $row->id;
		$data['title']   = $row->title;
		$data['room_type']   = $row->room_type;
		$data['bedrooms']   = $row->bedrooms;
		$data['bathrooms']   = $row->bathrooms;
		$data['address']   = $row->address;
		$data['city']   = $row->city;
		$data['country']   = $row->country;
		$data['capacity']   = $row->capacity;
		$data['price']   = $row->price;
	$query   = $this->db->where('list_id',$room_id)->get('reservation');
		if($query->num_rows() != 0)
	{
		$start_date   = $query->row()->checkin;
		$data['checkin']  = gmdate('d-m-y',$start_date);
		$end_date =  $query->row()->checkout;
		$data['checkout']  = gmdate('d-m-y',$end_date);
	}
		else
			{
				$data['checkin']='';
				$data['checkout']='';
			}
		$list_date[]   = $data;
	}
		echo json_encode($list_date);
	}
		else {
			echo '[{"status":"This room not available"}]';
	}
}	*/
public function add_optional()
	{
	$roomid=$this->input->get('roomid');
	//$data1['id'] 						= $this->input->get("roomid");
	//$data1['week'] = $this->input->get('week');
	//$data1['month'] = $this->input->get('month');
	//$data['amenities']			 = $this->input->get("amenities");
	//$data1['currency'] 			= trim($this->input->get('currency'));	
	$amenities = $this->input->get('amenities');
		
	 	$in_arr = explode(',', $amenities);

//print_r($in_arr);exit;
	    $result = $this->db->get('amnities');
		
		//$json= array();
	    $amenities_id  = '';
		
		if($result->num_rows() != 0) 
		{
	       if($amenities)
			   {
			    foreach($result->result() as $row)
	              {
	       
	                 if(in_array(trim($row->name), $in_arr))
						{   
							$json[] = $row->id.",";
						}
						
				  }
	
				
		$count = count($json);
		$end = $count-1;
		$slice = array_slice($json,0,$end);
		
			$comma = end($json);
			$json = substr_replace($comma ,"",-1);
			$amenities_id = $json;
         $row1 = '';
	  foreach($slice as $row)
		{
			$row1 .= $row; 
		}
			   }
			   }
	else {
		$amenities_id ='';
		}
	
	if($amenities)
			   {
		$amenities_id = $row1.$amenities_id;
       }
		
		$data2['amenities'] = $amenities_id;
		$query  = $this->db->get('list');
		
		$result = $query->result();
		$lys_status['amenities'] = '1';
		$this->db->insert('lys_status',$lys_status);
		$this->db->where('id',$roomid)->update('list',$data2);
		//print_r($this->db->last_query());exit;
		echo '[{"reason_message":"Updated Successfully."}]';exit;	
	
	}
	
public function add_price1()
{
//$roomid=$this->input->get('roomid');
	$this->load->model('Common_model');
 	$roomid	= $this->input->get("roomid");
	$week = $this->input->get('week');
	$month = $this->input->get('month');
	$currency = trim($this->input->get('currency'));
	$data1['id']	= $roomid;
	$data1['week']  = $week;
	$data1['month'] = $month;
	$data1['currency'] = $currency;
	$price_id = $this->Common_model->check_price_id($roomid);
	if(!$price_id)
		{
			$this->db->set('week',$week)->where('id',$roomid)->update('price');
			$this->db->set('month',$month)->where('id',$roomid)->update('price');
			$this->db->set('currency',$currency)->where('id',$roomid)->update('price');
			$result = $this->Common_model->get_data1('price', array('id' => $roomid));
		
			$row = $result->row();
			
			echo '[{"status":"Updated Successfully","room_id":"'.$row->id.'","week":"'.$row->week.'","month":"'.$row->month.'","currency":"'.$row->currency.'"}]';
		}
else
	{
	$this->db->where('id',$roomid)->insert('price',$data1);
	echo '[{"reason_message":"Successfully Added."}]';exit;
	}	
}
	
	
	
/*public function add_price()
{

	$this->load->model('Common_model');
 	$roomid	= $this->input->get("roomid");
	$week = $this->input->get('week');
	$month = $this->input->get('month');
	$currency = trim($this->input->get('currency'));
	$check_id = $this->db->where('id',$roomid)->get('price');
	//$check_id = $this->db->get('price');

	
	if($check_id->num_rows() > 0){
		
		  echo '[{"reason_message":"You should provide roomid."}]';exit;
		}
		
	else {
		

	$data['id']	= $roomid;
	$data['week']  = $week;
	$data['month'] = $month;
	$data['currency'] = $currency;
	$this->Common_model->insertData('price', $data);
	echo '[{"reason_message":"Successfully Added."}]';exit;
	}
	//print_r($check_id);exit;
}

public function edit(){
	$roomid  = $this->input->get('roomid');
	
	$data1['id'] = $roomid;
	$data1['week']  = $this->input->get('week');
	$data1['month'] = $this->input->get('month');
	$data1['currency']  = $this->input->get('currency');
	$this->db->where('id',$roomid)->update('price',$data1);
	
	echo '[{"reason_message":"Updated Successfully"}]';
}*/

public function add_description()
{
		$roomid=$this->input->get('roomid');
	
		$data['space'] 		= $this->input->get("space");
		$data['guests_info']			 = $this->input->get("guests_info");
		$data['interaction']			 = $this->input->get("interaction");
		$data['overview']			 = $this->input->get("overview");
		$data['getting_around']			 = $this->input->get("getting_around");
		$data['othert_thing']			 = $this->input->get("othert_thing");
		$data['house_rule']			 = $this->input->get("house_rule");
		
		$this->db->where('id',$roomid)->update('list',$data);
		
		//print_r($this->db->last_query());
		echo '[{"reason_message":"Description added Successfully"}]';
	 
}

public function view_description()
{
		$roomid=$this->input->get('room_id');
	$query = $this->db->where('id',$roomid)->get('list');
	if($query->num_rows != 0)
	{
		$data['id'] 		= $query->row()->id;
		$data['space'] 		= $query->row()->space;
		$data['guests_info']			 = $query->row()->guests_info;
		$data['interaction']			 = $query->row()->interaction;
		$data['overview']			 = $query->row()->overview;
		$data['getting_around']			 = $query->row()->getting_around;
		$data['other_thing']			 = $query->row()->othert_thing;
		$data['house_rule']			 = $query->row()->house_rule;
		$history[]  = $data;
		echo json_encode($history);
	}
else
	{
		echo '[{"reason_message":"No Data Found"}]';
	}	
	 
}

public function edit_room()
{
	$this->load->model('common_model');
		$roomid=$this->input->get('roomid');
	
		$data['beds']			 = $this->input->get("beds");
		$data['bedrooms']			 = $this->input->get("bedrooms");
		$data['bathrooms']			 = $this->input->get("bathrooms");
		$data['capacity']			 = $this->input->get("guest");
		$data['room_type']			 = $this->input->get("room_type");
		$data['home_type']			 = $this->input->get("home_type");
		
		$this->db->where('id',$roomid)->update('list',$data);
		
		$result = $this->common_model->get_data('list',$data);
		$row = $result->row();
		
	echo '[{"status":"Updated Successfully","user_id":"'.$row->user_id.'","room_id":"'.$row->id.'","beds":"'.$row->beds.'","bedrooms":"'.$row->bedrooms.'","bathrooms":"'.$row->bathrooms.'","guest":"'.$row->capacity.'","room_type":"'.$row->room_type.'","home_type":"'.$row->home_type.'"}]';
			exit;
		
		echo '[{"reason_message":"Updated Successfully"}]';
	 
}


public function view_room()
{
		$roomid=$this->input->get('roomid');
	
		$result = $this->db->where('id',$roomid)->get('list');
		
        if($result->num_rows()!=0)
	    {
	    	$row = $result->row();
	           echo '[{"status":"Edit Your Details","user_id":"'.$row->user_id.'","room_id":"'.$row->id.'","beds":"'.$row->beds.'","bedrooms":"'.$row->bedrooms.'","amenities":"'.$row->amenities.'","bathrooms":"'.$row->bathrooms.'","guest":"'.$row->capacity.'","room_type":"'.$row->room_type.'","home_type":"'.$row->home_type.'"}]';
			exit;
	     }
	     else {
			echo '[{"status":"This roomid is not available"}]';
	    }	
}
public function view_price()
{
		$roomid=$this->input->get('roomid');
	
		$result = $this->db->where('id',$roomid)->get('price');
		if($result->num_rows() != 0)
		{
		$row = $result->row();
		
		echo '[{"room_id":"'.$row->id.'","month":"'.$row->month.'","week":"'.$row->week.'","currency":"'.$row->currency.'"}]';
			exit;
			}
			else {
			echo '[{"status":"This roomid is not available"}]';
	    }
		
}

public function view_listing()
{
		$roomid=$this->input->get('roomid');
		//$user_id=$this->input->get('user_id');
		$final_value = $this->db->where('id',$roomid)->get('list');
    
		$image_list = $this->db->where('list_id',$roomid)->get('list_photo');
    
        //if($step_count_query->num_rows() !=0)
        //{
        //    foreach ($step_count_query->result() as $row)
        //    {
        //        $total_status = $row->address_status+$row->overview_status+$row->price_status+$row->photo_status+$row->calendar_status+$row->listing_status;
        //    }
        //}
    
		if($final_value->num_rows() !=0)
        {
		foreach ($final_value->result() as $row)
		{
			$data['id'] = $row->id;
			$data['address'] = $row->address;
			$data['currency'] = $row->currency;
			//$data['user_id'] = $row->user_id;
			$data['price'] = $row->price;
			$data['desc'] = $row->desc;
			if(!empty($image_list->row()->image))
			{
			$data['image'] = $image_list->row()->image;
			}
			else
            {
				$data['image'] = '';
			}
            
			$data['title'] = $row->title;
			
				if(!empty($image_list->row()->id))
			{
				 $data['imageid'] = $image_list->row()->id;
			}
			else
            {
				$data['imageid'] = '';
			}
			
			$history[]  = $data;
			
		}
		echo json_encode($history, JSON_UNESCAPED_SLASHES);
		}
		else {
			echo '[{"status":"No Data Found"}]';
		}
		
}


public function add_price2()
	{
		$roomid=$this->input->get('roomid');
		$query = $this->db->where('id',$roomid)->get('list');
	if($query->num_rows()!=0)
	{
      $data['price'] = $this->input->get('price');
	$data['currency'] = $this->input->get('currency');  
      $a_price=$data['price'];
	$a_currency=$data['currency'];
     if(!empty($a_price) && !empty($a_currency))
					{
					$data5['night']	= $a_price;
					$data5['currency'] = $a_currency;
					$this->db->where('id',$roomid)->update('price',$data5);
					}   
echo '[{"reason_message":"Price added successfully"}]';
	}
else {
	echo '[{"reason_message":"Not valid room id"}]';
}
	}
	
/*public function add_room1()
	{
		$roomid=$this->input->get('roomid');
		$image = $this->input->get('image');
		$image_id = $this->input->get('image_id');
			$title_status	= $this->input->get("title");
			
			if(!empty($title_status))
			{
			
			$data5['title'] = 1 ;
			}
			else {
				
				$data5['title'] = 0;
			}
			$data['title'] = $title_status;
			$summary_status				= $this->input->get("description");
			
			if(!empty($summary_status))
			{
			
			$data5['summary'] = 1 ;
			}
			else {
				
				$data5['summary'] = 0;
			}
		$data['desc'] = $summary_status;
		$price_status 						= $this->input->get("price");
		
		if($price_status != 0)
			{
			
			$data5['price'] = 1 ;
			}
			else {
				$data5['price'] = 0;
			}
			$data['price'] = $price_status;
			$photo_status 						= $this->input->get("photo");
				
			if((!empty($image)))
			{
			
			$data5['photo'] = 1 ;
			}
			else {
				$data5['photo'] = 0;
			}
			
		
			//$data1['name'] = $photo_status;
			$address_status   		= $this->input->get("address");
			
			if(!empty($address_status))
			{
			
			$data5['address'] = 1 ;
			}
			else {
				$data5['address'] = 0;
			}
			$data['address'] = $address_status;
					$data['city']           = $this->input->get('city');
					$data['state']        = $this->input->get('state');
					$data['country']        = $this->input->get('country');
					$data['street_address']   		= $address_status;
							$data['currency'] 			= trim($this->input->get('currency'));	
					
		
		$data['lat'] 								= $this->input->get("latitude");
		$data['long'] 							= $this->input->get("langtitude");
		$data1['image'] = $image;
		if((!empty($image)))
				{
				$this->db->where('id',$image_id)->update('list_photo',$data1);
				//$this->db->where('list_id',$roomid)->order_by('id','asc')->insert('list_photo',$data1);
				}
		//$this->db->where('id',$roomid)->update('list_photo',$data1);
		//print_r($this->db->last_query());exit;
			
		if((!empty($image)) && (!empty($title_status)) && (!empty($summary_status)) && ($price_status != 0) && (!empty($address_status)))
		{
		
			$data['status']= 1 ;
			
		}
		else 
		{
				$data['status']= 0 ;
			}
	 
		
		
		$this->db->where('id',$roomid)->update('list',$data);
		$this->db->where('id',$roomid)->update('lys_status',$data5);
		
		
		
		echo '[{"reason_message":"Updated Successfully"}]';
	}*/
             
        public function add_room1()
	{
		$roomid=$this->input->get('roomid');
		$query = $this->db->where('id',$roomid)->get('list');

		//$query1 = $this->db->where('id',$roomid)->get('price');
	if($query->num_rows()!=0)
	{
            $data1['list_id'] 		= $this->input->get('roomid');
			$data['title'] 						= $this->input->get('title');
			$data['desc'] 					= $this->input->get('description');
				$data['price'] 				= $this->input->get('price');
			
					$a_add   		= $this->input->get('address');
					$a_city           = $this->input->get('city');
					$a_state        = $this->input->get('state');
					$a_country        = $this->input->get('country');
				    $data['street_address']  =       $a_add;
					
					if(!empty($a_add))
					{
					$data5['address']	=$a_add;
					$this->db->where('id',$roomid)->update('list',$data5);
					}
					if(!empty($a_city))
					{
						$data5['city']       = $a_city;
						$this->db->where('id',$roomid)->update('list',$data5);
						
					}
					if(!empty($a_state))
					{
					$data5['state'] = $a_state;
					$this->db->where('id',$roomid)->update('list',$data5);
						
					}
					if(!empty($a_country))
					{
						$data5['country'] =$a_country;
						$this->db->where('id',$roomid)->update('list',$data5);
						
					}
        
                    $lys_status['calendar'] = '1';
                    $lys_status['amenities'] = '1';
        
                    $a_price_step = $this->input->get('price_step');
                    $a_addphoto_step = $this->input->get('addphoto_step');
                    $a_address_step = $this->input->get('address_step');
                    $a_title_step = $this->input->get('title_step');
                    $a_summary_step = $this->input->get('summary_step');
        
                    $lys_status['price'] = $a_price_step;
                    $lys_status['photo'] = $a_addphoto_step;
                    $lys_status['address'] = $a_address_step;
        
                    if($a_title_step == 1 && $a_summary_step == 1)
                    {
                    $lys_status['overview'] = '1';
                    $lys_status['title'] = '1';
                    $lys_status['summary'] = '1';
                    }
                    else
                    {
                    $lys_status['overview'] = '0';
                    $lys_status['title'] = '0';
                    $lys_status['summary'] = '0';
                    }
        
        
                    //$lys_status['imageurl'] = '1';
        
                   $lys_status['listing'] = '1';
                   $lys_status['beds'] = '1';
                   $lys_status['bathrooms'] = '1';
		
					$this->db->where('id',$roomid)->update('lys_status',$lys_status);

					$data7['list_pay']   = 1;
					$data7['is_enable']   = 1;
					//$data['cancellation_policy'] 	= $this->input->get('cancellation_policy');
					$data7['cancellation_policy'] 	= 1;
					$this->db->where('id',$roomid)->update('list',$data7);
					
			
				$data['currency'] = trim($this->input->get('currency'));	
		
				$data['lat'] 	= $this->input->get("latitude");
				$data['long'] 	= $this->input->get("longitude");
				$data['step_status']     = $this->input->get("step_status");
		
		//print_r($this->db->last_query());
		$this->db->where('id',$roomid)->update('list',$data);
		//echo '[{"reason_message":"Updated Successfully"}]';
	  
     		$a_price=$data['price'];
		$a_currency=$data['currency'];
     		if(!empty($a_price) && !empty($a_currency))
		{
		$data6['night']	= $a_price;
		$data6['currency'] = $a_currency;
		$this->db->where('id',$roomid)->update('price',$data6);
		}   
		echo '[{"reason_message":"List and Price updated successfully"}]';
		}
	else {
	echo '[{"reason_message":"Not valid room id"}]';
	}
	}
	public function add_room()
	{
		$roomid=$this->input->get('roomid');
		$query = $this->db->where('id',$roomid)->get('list');

		//$query1 = $this->db->where('id',$roomid)->get('price');
	if($query->num_rows()!=0)
	{
                    $data1['list_id'] = $this->input->get('roomid');
					$data['title'] 	  = $this->input->get('title');
					$data['desc'] 	  = $this->input->get('description');
					$data['price'] 	  = $this->input->get('price');
			
					$a_add   	  = $this->input->get('address');
					$a_city           = $this->input->get('city');
					$a_state          = $this->input->get('state');
					$a_country        = $this->input->get('country');

                    $data['street_address']  =       $a_add;
					
					if(!empty($a_add))
					{
					$data5['address']	=$a_add;
					$this->db->where('id',$roomid)->update('list',$data5);
					}
					if(!empty($a_city))
					{
						$data5['city']       = $a_city;
						$this->db->where('id',$roomid)->update('list',$data5);
						
					}
					if(!empty($a_state))
					{
					$data5['state'] = $a_state;
					$this->db->where('id',$roomid)->update('list',$data5);
						
					}
					if(!empty($a_country))
					{
						$data5['country'] =$a_country;
						$this->db->where('id',$roomid)->update('list',$data5);
						
					}

					$lys_status['calendar'] = '1';
                    $lys_status['amenities'] = '1';
        
                    $a_price_step = $this->input->get('price_step');
                    $a_addphoto_step = $this->input->get('addphoto_step');
                    $a_address_step = $this->input->get('address_step');
                    $a_title_step = $this->input->get('title_step');
                    $a_summary_step = $this->input->get('summary_step');
        
                    $a_check_status = $this->input->get('check_status_count');
                    $list['check_status'] = $a_check_status;
                    $list['status'] = $a_check_status;
        
					$lys_status['price'] = $a_price_step;
                    $lys_status['photo'] = $a_addphoto_step;
                    $lys_status['address'] = $a_address_step;
                    $lys_status['title'] = $a_title_step;
                    $lys_status['summary'] = $a_summary_step;
        
                    if($a_title_step == 1 && $a_summary_step == 1)
                    {
					$lys_status['overview'] = '1';
                    }
                    else
                    {
                        $lys_status['overview'] = '0';
                        
                    }
        
					
                    //$lys_status['imageurl'] = '1';

					$lys_status['listing'] = '1';
					$lys_status['beds'] = '1';
					$lys_status['bathrooms'] = '1';
					
					//$lys_status['bed_type'] = '1';
					//$lys_status['bathrooms'] = '1';
		
					$this->db->where('id',$roomid)->update('lys_status',$lys_status);
                    $this->db->where('id',$roomid)->update('list',$list);

                    $data7['list_pay']   = $this->input->get('listpay');
					$data7['is_enable']   = 1;
					//$data['cancellation_policy'] 	= $this->input->get('cancellation_policy');
					$data7['cancellation_policy'] 	= 1;
					$this->db->where('id',$roomid)->update('list',$data7);
					
			
				$data['currency'] = trim($this->input->get('currency'));	
		
			   $data['lat'] 	= $this->input->get("latitude");
				$data['long'] 	= $this->input->get("longitude");
				$data['step_status']     = $this->input->get("step_status");	
		
		//print_r($this->db->last_query());
		$this->db->where('id',$roomid)->update('list',$data);
		//echo '[{"reason_message":"Updated Successfully"}]';
	  
     		$a_price=$data['price'];
		$a_currency=$data['currency'];
     		if(!empty($a_price) && !empty($a_currency))
		{
		$data6['night']	= $a_price;
		$data6['currency'] = $a_currency;
		$this->db->where('id',$roomid)->update('price',$data6);
		}   
		echo '[{"reason_message":"List updated successfully"}]';
		}
	else {
	echo '[{"reason_message":"Not valid room id"}]';
	}
	}
    
    public function setcheckstatus()
    {
        $roomid=$this->input->get('roomid');
        $query = $this->db->where('id',$roomid)->get('list');
        
        if($query->num_rows()!=0)
        {
            $a_check_status = $this->input->get('check_status_count');
            $list['check_status'] = $a_check_status;
            $list['status'] = $a_check_status;
	    $list['is_enable'] = $a_check_status;
            
            $this->db->where('id',$roomid)->update('list',$list);
            echo '[{"reason_message":"List and Price updated successfully"}]';
        }
        else
        {
            echo '[{"reason_message":"Not valid room id"}]';
        }
    }
	public function update($param = '')
	{
	
	  if($param != '')
			{
			 $amenity   = $this->input->get('amenities');
				$aCount    = count($amenity);
				
				$amenities = '';	
				if(is_array($amenity))
				{
					if(count($amenity) > 0)
					{
						$i = 1;
						foreach($amenity as $value)
						{
								if($i == $aCount) $comma = ''; else $comma = ',';
								
								$amenities .= $value.$comma;
								$i++;
						}
					}
			 }
				
				$updateData = array(
						'property_id'  					=> $this->input->get('property_id'),
						'room_type'   		     		 => $this->input->get('room_type'),
						'title'    						 => $this->input->get('hosting_descriptions'),
						'desc'         					=> $this->input->get('desc'),
						'capacity'     					=> $this->input->get('capacity'),
						'bedrooms'    	     			 => $this->input->get('bedrooms'),
						'beds'    						=> $this->input->get('beds'),
						'bed_type'     					=> $this->input->get('hosting_bed_type'),
						'bathrooms'     				=> $this->input->get('hosting_bathrooms'),
						'manual'     					=> $this->input->get('manual'),
					    'street_view'     				=> 0,
				     	'directions'     		     	=> $this->input->get('hosting_directions')
																	);
																	
				if(isset($_POST['address']['formatted_address_native']))
				{												
					$address = $_POST['address']['formatted_address_native'];
					if(!empty($address))
					{
					$address = urlencode($address);
					$address = str_replace('+','%20',$address); 
					$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');
					$output= json_decode($geocode);
					
					$updateData['address'] = $_POST['address']['formatted_address_native'];
					
					$level = explode(',', $updateData['address']);
        $keys = array_keys($level);
        $country = $level[end($keys)];
        if(is_numeric($country) || ctype_alnum($country))
        $country = $level[$keys[count($keys)-1]];
        if(is_numeric($country) || ctype_alnum($country))
        $country = $level[$keys[count($keys)-1]];
        $updateData['country'] = trim($country);
					
					$updateData['lat'] 				= $output->results[0]->geometry->location->lat;
					$updateData['long'] 			= $output->results[0]->geometry->location->lng;
					}
				}
																	
			if($amenities != '')
			{
			$updateData['amenities'] = $amenities;
			}
																	
			$updateKey = array('id' => $param);									
		 $this->Rooms_model->update_list($updateKey, $updateData);
			
		 //echo $this->db->last_query();exit;
																
			echo '{"redirect_to":"'.base_url().'rooms/'.$param.'","result":"success"}';
			
			}
	}
	
		public function edit_photo($param  = '')
	{

				$this->load->model('Gallery');
				$listId           = $param;
				$images           = $this->input->get('image');
				if(!empty($images))
				{
					foreach($images as $image)
					{
							unlink($image);
					}
				}
		
					if(isset($_FILES["userfile"]["name"]))
					{
						if(!is_dir($this->path.'/'.$listId))
						{
							//echo $this->path.'/'.$id;
							mkdir($this->path.'/'.$listId, 0777, true);
						}
						$config = array(
							'allowed_types' => 'jpg|jpeg|gif|png',
							'upload_path' => $this->path.'/'.$listId
						);
						//echo $this->path.'/'.$id;
						$this->load->library('upload', $config);
						$this->upload->do_upload();
					}
					
					$rimages = $this->Gallery->get_images($listId);
					$i = 1;
					$replace = '<ul class="clearfix">';
					foreach ($rimages as $rimage)
					{		
						$replace .= '<li><p><label><input type="checkbox" name="image[]" value="'.$rimage['path'].'" /></label>';
						$replace .= '<img src="'.$rimage['url'].'" width="150" height="150" /></p></li>';
								$i++;
					}
					$replace .= '</ul>';
					
					echo $replace;
		
	}
	
	
		public function update_price()
	{
				$data = array(
				'currency' 	=> $this->input->get('currency'),
				'night' 	=> $this->input->get('nightly'),
				'week' 		=> $this->input->get('weekly'),
				'month' 	=> $this->input->get('monthly'),
				'addguests' => $this->input->get('extra'),
				'guests'    => $this->input->get('guests'),
				'security' 	=> $this->input->get('security'),
				'cleaning' 	=> $this->input->get('cleaning')
				);

			$this->db->where('id', $this->uri->segment(3));
			$this->db->update('price', $data);
			
		redirect ('rooms/edit_price/'.$this->uri->segment(3),'refresh'); 
	}
	
	
	public function edit_price()
	{
		if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
		{	
			$data['title'] = "Edit the price information for your site";
			$data['message_element'] = 'rooms/view_edit_price';
			$this->load->view('template',$data);
		}
		else
		{
			redirect('users/signin');
		}
	}
	
		
	public function change_status()
	{
			$sow_hide = $this->input->get('stat'); 
			$row_id   = $this->input->get('rid');
			
			if($sow_hide == 1)
			{
				$data['status'] = 0;
				$this->db->where('id',$row_id);
				$this->db->update('list',$data);
				redirect('hosting');
			}
			else
			{
				$data['status'] = 1;
				$this->db->where('id',$row_id);
				$this->db->update('list',$data);
				redirect('hosting');
			}	
 }
		
	public function change_availability($param = '')
	{
	if($param != '')
	{ 
	 $is_available = $this->input->post('is_available');
	 if($is_available == 0)
		{
  	echo '{"result":"unavailable","message":"Your listing will be hidden from public search results.","available":false,"prompt":"Your listing can now be activated!"}';
		}
		else
		{	
   echo	'{"result":"available","message":"Your listing will now appear in public search results.","available":true,"prompt":"Your listing is active."}';
		}
	}
		
	}

public function recent_view()
    {
		//$conditions     = array("list.is_enable" => 1, "list.status" => 1);
//$limit          = array(12);
//$orderby        = array("page_viewed", "desc");
//$mosts  = $this->Rooms_model->get_rooms($conditions, NULL, $limit, $orderby);
$mosts = $this->db->select('*')->order_by('page_viewed','desc')->limit('12')->from('list')->get();
if($mosts->num_rows() != 0)
	{
		echo "[ ";
	foreach($mosts->result() as $row)
	{
	
              $image_query = $this->db->select('name')->where('list_id',$row->id)->where('is_featured',1)->from('list_photo')->get();
			if($image_query->num_rows() != 0)
			{
			foreach($image_query->result() as $rows)
			{
				$image_name = $rows->name;
		    }
			$images = base_url().'images/'.$row->id.'/'.$image_name;
			}
			else
				{
					$images=base_url().'images/no_image.jpg';
					
			    }
	       //$json[] = "{ \"id\":".$id.",\"title\":\"".$row->title."\",\"country\":\"".$country."\",\"image_url\":\"".$image."\" },";
		   $json[] = "{ \"id\":".$row->id.",\"title\":\"".$row->title."\",\"country\":\"".$row->country."\",\"image_url\":\"".base_url()."files/timthumb.php?src=".$images."&h=309&w=598&zc=&q=100\",\"address\":\"".
		   $row->address."\",\"price\":\"".$row->price."\"},";
		 
	}
	      $count = count($json);
		  $end = $count-1;
					$slice = array_slice($json,0,$end);
					foreach($slice as $row)
					{
						echo $row;
					}
					$comma = end($json);
					$json = substr_replace($comma ,"",-1);
					echo $json;
					echo " ]";
				
	}
	}

	public function neighborhoods()
    {
	$conditions     = array("list.is_enable" => 1, "list.status" => 1, "list.list_pay" => 1);
	$conditions_lys_status = array("lys_status.photo" => 1, "lys_status.calendar" => 1, "lys_status.price" => 1, "lys_status.overview" => 1, "lys_status.address" => 1, "lys_status.listing" => 1);
//$limit          = array(12);
$orderby        = array("page_viewed", "desc");
$distinct = 'country';
//$mosts  = $this->Rooms_model->get_rooms($conditions, NULL, NULL, $orderby, NULL, NULL, NULL, $distinct);
$mosts = $this->db->distinct('list.country')->where($conditions)->order_by('list.id','asc')->where($conditions_lys_status)->join('lys_status','lys_status.id = list.id')->get('list');
//$items= '';
//echo $this->db->last_query();exit;
if($mosts->num_rows() != 0)
	{
		echo "[ ";
	foreach($mosts->result() as $row)
	{
		$items[] = $row->country;
		//$itemsid[] = $row->id;
		}
	//echo'<pre>';print_r(array_unique($items));exit;
	$result_id = array();
	$i=0;
	$result_country = array_unique($items);
	//print_r($result_country);exit;
	$final_country = array();
	$i=0;
	foreach($result_country as $row_country)
	{
		if($i < 7)
		{
		$final_country[] = $row_country;
		$i++;
		}
	}		
	//print_r($final_country);
				  foreach($final_country as $rows_country)
				 {
 				 	$conditions     = array("country" => $rows_country,"list.is_enable"=>1);
					//$distinct = 'country';
				 	$mosts1  = $this->Rooms_model->get_rooms($conditions, NULL, NULL, $orderby);
					foreach($mosts1->result() as $row_ids)
					{
						$list_id[] = $row_ids->id;
					}				
					$count = count($list_id);
	       //$json[] = "{ \"id\":".$id.",\"title\":\"".$row->title."\",\"country\":\"".$country."\",\"image_url\":\"".$image."\" },";
		 
		 $condition    = array("is_featured" => 1);
$list_image   = $this->Gallery->get_imagesG($list_id[$count-1], $condition)->row();

if(isset($list_image->name))
{
$image_url_ex = explode('.',$list_image->name);

$image_url = base_url().'images/'.$list_id[$count-1].'/'.$image_url_ex[0].'_crop.jpg';
}
else
{
$image_url = base_url().'images/no_image.jpg';
}
		  
		   $json[] = "{\"country\":\"".$rows_country."\",\"image_url\":\"".$image_url."\"},";
		 
	}
	      $count = count($json);
		  $end = $count-1;
					$slice = array_slice($json,0,$end);
					foreach($slice as $row)
					{
						echo $row;
					}
					$comma = end($json);
					$json = substr_replace($comma ,"",-1);
					echo $json;
					echo " ]";
				
	}
	}


 public function selected_view()
	{
		$room_id = $this->input->get('room_id');
		
     $conditions             = array("id" => $room_id, "list.is_enable" => 1, "list.status" => 1);
     $result                 = $this->Common_model->getTableData('list', $conditions);
		
		if($result->num_rows() != 0)
	{
	foreach($result->result() as $row)
	{
		$id = $row->id;
		$user_id = $row->user_id;
		$address=$row->address;
		$country='';
		$city='';
		$state='';
		$cancellation_policy = $row->cancellation_policy; 	
		$room_type=$row->room_type;
		$bedrooms=$row->bedrooms;
		$beds=$row->beds;
		$bed_type=$row->bed_type;
		$bathrooms=$row->bathrooms;
		$title=$row->title;
		$desc=$row->desc;
		$capacity=$row->capacity;  
		$price=$row->price; 
		$email=$row->email;
		$phone=$row->phone;
		$review=$row->review;
		$lat=$row->lat;
		$long=$row->long;
		$property_id=$row->property_id;
		$street_view=$row->street_view;
		$sublet_price=$row->sublet_price;
		$sublet_status=$row->sublet_status;
		$sublet_startdate=$row->sublet_startdate;
		$sublet_enddate=$row->sublet_enddate;
		$currency=$row->currency;
		$manual=$row->manual;
		$page_viewed=$row->page_viewed;
		$neighbor=$row->neighbor;
		$amenities = $row->amenities;
		$hometype=$row->home_type;
		
		$price_query=$this->db->where('id',$room_id)->from('price')->get();
		
		if($price_query->num_rows() != 0)
	   { 
		foreach($price_query->result() as $row)
	   {
	if($currency == 'USD' || $currency == '0')
			{
				$price = $price;
				$cleaning_fee = $row->cleaning;
	            $extra_guest_fee = $row->addguests.'/guest after'.$row->guests;
		        $Wprice = $row->week;
		        $Mprice = $row->month;
		    }
			else {
				
			    $params_price  = array('amount' => $price, 'currFrom' => $currency,'currInto' => 'USD');
				$params_clean  = array('amount' => $row->cleaning, 'currFrom' => $currency,'currInto' => 'USD');
				$params_guest  = array('amount' => $row->addguests, 'currFrom' => $currency,'currInto' => 'USD');
				$params_week   = array('amount' => $row->week, 'currFrom' => $currency,'currInto' => 'USD');
				$params_month  = array('amount' => $row->month, 'currFrom' => $currency,'currInto' => 'USD');
						
			$price = round(google_convert($params_price));
			$cleaning_fee = round(google_convert($params_clean));
			$extra_guest_fee = round(google_convert($params_guest));
			$Wprice = round(google_convert($params_week));
			$Mprice = round(google_convert($params_month));

			}
	   }
	   }
else
	{
		$Wprice='';
		$Mprice='';
		$cleaning_fee='';
		$price='';
		$extra_guest_fee='';
	}
			
	
     $conditions             = array("id" => $room_id, "list.is_enable" => 1, "list.status" => 1);
	 $result                 = $this->Common_model->getTableData('list', $conditions);
 	 	$today_month=date("F");
		$today_date=date("j");
		$today_year=date("Y");
		$conditions_statistics = array("list_id" => $room_id,"date"=>trim($today_date),"month"=>trim($today_month),"year"=>trim($today_year));
		$result_statistics = $this->Common_model->add_page_statistics($room_id,$conditions_statistics);
		
		$list                   = $list = $result->row();
		$title                  = $list->title;
		$page_viewed            = $list->page_viewed;
		
		$page_viewed = $this->Trips_model->update_pageViewed($room_id, $page_viewed);
		
			
		$id                     = $room_id;
		$checkin                = $this->session->userdata('Vcheckin');
		$checkout               = $this->session->userdata('Vcheckout');
		$guests         = $this->session->userdata('Vnumber_of_guests');
	
		$ckin                   = explode('/', $checkin);
		$ckout                  = explode('/', $checkout);
		
		//check admin premium condition and apply so for
		$query                  = $this->Common_model->getTableData( 'paymode', array('id' => 2));
		$row                    = $query->row();	

		
		if(($ckin[0] == "mm" && $ckout[0] == "mm") or ($ckin[0] == "" && $ckout[0] == ""))
		{
      			
			 if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
			
			 if($row->is_premium == 1)
					{
			    if($row->is_fixed == 1)
							{
										$fix            = $row->fixed_amount; 
										$amt            = $price + $fix;
										$commission = $fix;
										$Fprice         = $amt;
							}
							else
							{  
										$per            = $row->percentage_amount; 
										$camt           = floatval(($price * $per) / 100);
										$amt            = $price + $camt;
										$commission = $camt;
										$Fprice         = $amt;
							}
							
						if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
		
		   }
			} 
		else
		{	
			$diff                  = strtotime($ckout[2].'-'.$ckout[0].'-'.$ckout[1]) - strtotime($ckin[2].'-'.$ckin[0].'-'.$ckin[1]);
			$days                  = ceil($diff/(3600*24));
			
			if($guests > $guests)
			{
			  $price               = ($price * $days) + ($days * $xprice->addguests);
			}
			else
			{
			  $price               = $price * $days;
			}
					
			if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
			
			$commission    = 0;
			
			 if($row->is_premium == 1)
					{
			    if($row->is_fixed == 1)
							{
										$fix             = $row->fixed_amount; 
										$amt             = $price + $fix;
										$commission = $fix;
										$Fprice          = $amt;
							}
							else
							{  
										$per             = $row->percentage_amount; 
										$camt            = floatval(($price * $per) / 100);
										$amt             = $price + $camt;
										$commission = $camt;
										$Fprice          = $amt;
							}
							
						if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
		
		   }
					}
		
			$conditions              = array('list_id' => $room_id);
			$image_query = $this->db->select('name')->where('list_id',$room_id)->where('is_featured',1)->from('list_photo')->get();
			
			if($image_query->num_rows() != 0)
			{
			foreach($image_query->result() as $row)
			{
				$image_name = $row->name;
		    }
			$image = base_url().'images/'.$room_id.'/'.$image_name;
			}
			else
				{
			 $image=base_url().'images/no_image.jpg';
				}
			
			$conditions    			        = array('list_id' => $room_id, 'userto' => $list->user_id);
			$result			     	  = $this->Trips_model->get_review($conditions);
			
			$conditions    			     	  = array('list_id' => $room_id, 'userto' => $list->user_id);
			$stars			        	= $this->Trips_model->get_review_sum($conditions)->row();	
			 
			$title            = substr($title, 0, 70);
			
			$level = explode(',', $address);
		$keys = array_keys($level);
		$country = $level[end($keys)];
		if(is_numeric($country) || ctype_alnum($country))
		$country = $level[$keys[count($keys)-2]];
		if(is_numeric($country) || ctype_alnum($country))
		$country = $level[$keys[count($keys)-3]];
		   
		   $search=array('\'','"','(',')','!','{','[','}',']');
			$replace=array('&sq','&dq','&obr','&cbr','&ex','&obs','&oabr','&cbs','&cabr');
		    $desc_replace = str_replace($search, $replace, $desc);
			$desc_tags = stripslashes($desc_replace);
							 
			$amenities = $this->db->get_where('list', array('id' => $room_id))->row()->amenities;
				 $property_type = $this->db->get_where('property_type', array('id' => $property_id))->row()->type;
    $in_arr = explode(',', $amenities);
	$result = $this->db->get('amnities');				 
							 $user_name = $this->db->where('id',$user_id)->select('username')->from('users')->get();
							 if($user_name->num_rows()!=0)
							 {
							 	foreach($user_name->result() as $row)
								{
									$hoster_name = $row->username;
								}
							 }
							 else
							 	{
							 		$hoster_name = 'No Username';
							 	}
							
							
								
            echo "[ { \"id\":".$room_id.",\"user_id\":".$user_id.",\"hoster_name\":\"".$hoster_name."\",\"title\":\"".$title."\",\"country\":\"".$country.
			    "\",\"city\":\"".$city."\",\"state\":\"".$state."\",\"cancellation_policy\":\"".$cancellation_policy.
	           "\",\"address\":\"".$address."\",\"image_url\":\"".base_url()."files/timthumb.php?src=".$image."&h=309&w=598&zc=&q=100\",
	           \"room_type\":\"".$room_type."\",\"bedrooms\":".$bedrooms.",\"bathrooms\":".$bathrooms.",\"bed_type\":\"".$bed_type."\",
	           \"desc\":\"".$desc_tags."\",\"capacity\":".$capacity.",\"price\":\"".$price.
	           "\",\"cleaning_fee\":\"".$cleaning_fee."\",\"extra_guest_fee\":\"".$extra_guest_fee."\",\"weekly_price\":\"".$Wprice.
	           "\",\"monthly_price\":\"".$Mprice."\",\"email\":\"".$email."\",\"phone\":\"".$phone."\",\"review\":\"".$review.
	           "\",\"lat\":".$lat.",\"long\":".$long.",\"property_type\":\"".$property_type."\",\"street_view\":".$street_view.
	           ",\"sublet_price\":".$sublet_price.",\"sublet_status\":".$sublet_status.",\"sublet_startdate\":\"".$sublet_startdate.
	           "\",\"sublet_enddate\":\"".$sublet_enddate."\",\"currency\":\"".$currency."\",\"manual\":\"".$manual."\",\"page_viewed\":".$page_viewed
	           .",\"neighbor\":\"".$neighbor."\",\"amenities\":\"";if($result->num_rows() != 0) {
	           if($amenities)
			   {
			    foreach($result->result() as $row)
	{
	    if(in_array($row->id, $in_arr))
		{
			$json[] = $row->name.",";
		}
	}
	$count = count($json);
		  $end = $count-1;
					$slice = array_slice($json,0,$end);
					foreach($slice as $row)
					{
						echo $row; 
					}
					$comma = end($json);
					$json = substr_replace($comma ,"",-1);
					echo $json."\""; echo "} ]";exit;
			   }
			   }
else {
	$json[] ='';
	
}
		echo "\"} ]";			
			  
	}
	}
	
	else {
	echo "[ { \"status\":\"Access Denied\" } ]";
}
	}
 function availability()
 {
 	            $checkin = $this->input->get('checkin');
 	            $checkin_time = $checkin;
 	            
				$checkin_time=get_gmt_time(strtotime($checkin_time)); 
				
				$checkout = $this->input->get('checkout');
				$checkout_time= $checkout;
				
                $checkout_time=get_gmt_time(strtotime($checkout_time)); 
				
				$id = $this->input->get('room_id');
				
				
				 $conditions             = array("id" => $id);
                 $result                 = $this->Common_model->getTableData('list', $conditions);
				 if($result->num_rows() == 0)
	               {
	                 	echo "[ { \"status\":\"Access Denied\" } ]";
	               }
				 else{
             $status=1;	
			$daysexist = $this->db->query("SELECT id,list_id,booked_days FROM `calendar` WHERE `list_id` = '".$id."' AND (`booked_days` >= '".$checkin_time."' AND `booked_days` <= '".$checkout_time."') GROUP BY `id`");
			$rowsexist = $daysexist->num_rows();
		    if($rowsexist > 0)
			{
				$status=0;
				
			} 	
			if($status == 0)
			{
		      echo "[ { \"status\":\"NO\" } ]";
		           
		   }	
			else 
			{
				echo "[ { \"status\":\"YES\" } ]";
			}
			}
 }

function property_type()
{
	$property_type = $this->db->select('*')->from('property_type')->get();
	echo json_encode($property_type->result());
	
}

function amenities()
{
	$amenities = $this->db->select('*')->from('amnities')->get();
	echo json_encode($amenities->result());
}

function calendar()
{
	extract($this->input->get());
	
	$result = $this->db->where('calendar.list_id',$list_id)->get('calendar');
	
	if($result->num_rows() != 0)
	{
	 echo json_encode($result->result());	
	}
	else
		{
			echo "[ { \"status\":\"No data\" } ]";
		}
	
}

function seasonal_price_calendar()
{
	extract($this->input->get());
	
	$result = $this->db->where('seasonalprice.list_id',$list_id)->get('seasonalprice');
	
	if($result->num_rows() != 0)
	{
		echo '[';
		foreach($result->result() as $row)
		{
			$price = $this->get_currency_value1($row->list_id, $row->price, $currency);
			$json[] = '{"id":"'.$row->id.'","list_id":"'.$row->list_id.'","price":"'.$price.'","start_date":"'.$row->start_date.'","end_date":"'.$row->end_date.'"},';			
		}
        $count = count($json);
		  $end = $count-1;
					$slice = array_slice($json,0,$end);
					foreach($slice as $row)
					{
						echo $row; 
					}
					$comma = end($json);
					$json = substr_replace($comma ,"",-1);
					echo $json;
		echo ']';	
	}
	else
		{
			echo "[ { \"status\":\"No data\" } ]";
		}
	
}

function get_currency_value1($id,$amount,$currency)
		{
			$rate=0;
						
			$this->load->helper("cookie");
						
			$current = $currency;
			
			$list_currency     = $this->db->where('id',$id)->get('list')->row()->currency;
			
			if($current == '')
			{
				$list_currency1 = $this->db->where('default',1)->get('currency')->row()->currency_code;
				
				$params  = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $list_currency1);
						
			$rate=round(google_convert($params));
				
			if($rate!=0)
				return $rate;
			else
				return 0;
			
			}
			
			$params  = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $current);
			
			$rate=round(google_convert($params));
						
			if($rate!=0)
				return $rate;
			else
				return 0;
	}
	function google_convert($params)
	{
		$amount    = $params["amount"];
		
		$currFrom  = $params["currFrom"];
		
		$currInto  = $params["currInto"];
		
		if (trim($amount) == "" ||!is_numeric($amount)) {
			trigger_error("Please enter a valid amount",E_USER_ERROR);         	
		}
		$return=array();
			
		if($currFrom == 'USD')
		{
			$currInto_result = $this->db->where('currency_code',$currInto)->get('currency_converter')->row();
			$rate = $amount * $currInto_result->currency_value;
		}
		else 	
		{
			
		$currFrom_result = $this->db->where('currency_code',$currFrom)->get('currency_converter')->row();
		
		$from_usd = 1/$currFrom_result->currency_value; 
		
		$from_usd_amt = $amount * $from_usd;
		
		$currInto_result = $this->db->where('currency_code',$currInto)->get('currency_converter')->row();
		
		$rate = $currInto_result->currency_value * $from_usd_amt;
		
		}
		
		return $rate;
	}

function edit_listing()
{
	extract($this->input->get());
	
	$check_list = $this->db->where('id',$list_id)->where('user_id',$user_id)->get('list');
	
	$room_id = $list_id;
	
	if($check_list->num_rows() != 0)
	{
		foreach($check_list->result() as $row)
		{
		$id = $row->id;
		$user_id = $row->user_id;
		$address=$row->address;
		$country=$row->country;
		$city=$row->city;
		$state=$row->state;
		$cancellation_policy = $row->cancellation_policy; 	
		$room_type=$row->room_type;
		$bedrooms=$row->bedrooms;
		$beds=$row->beds;
		$bed_type=$row->bed_type;
		
		if($row->bathrooms == NULL)
		{
			$bathrooms = '""';
		}
		else
		$bathrooms=$row->bathrooms;
		
		$title=$row->title;
		$desc=$row->desc;
		$capacity=$row->capacity;  
		$price=$row->price; 
		$email=$row->email;
		$phone=$row->phone;
		$review=$row->review;
		$lat=$row->lat;
		$long=$row->long;
		$property_id=$row->property_id;
		$street_view=$row->street_view;
		$sublet_price=$row->sublet_price;
		$sublet_status=$row->sublet_status;
		$sublet_startdate=$row->sublet_startdate;
		$sublet_enddate=$row->sublet_enddate;
		$currency=$row->currency;
		$manual=$row->house_rule;
		
		$page_viewed=$row->page_viewed;
		$neighbor=$row->neighbor;
		$amenities = $row->amenities;
		
		$price_query=$this->db->where('id',$room_id)->from('price')->get();
		
		if($price_query->num_rows() != 0)
	   { 
		foreach($price_query->result() as $row)
	   {
	//if($currency == 'USD' || $currency == '0')
			//{
				$price = $price;
				$cleaning_fee = $row->cleaning;
	            $extra_guest_fee = $row->addguests.'/guest after'.$row->guests;
		        $Wprice = $row->week;
		        $Mprice = $row->month;
		    //}
			/*else {
				
			    $params_price  = array('amount' => $price, 'currFrom' => $currency,'currInto' => 'USD');
				$params_clean  = array('amount' => $row->cleaning, 'currFrom' => $currency,'currInto' => 'USD');
				$params_guest  = array('amount' => $row->addguests, 'currFrom' => $currency,'currInto' => 'USD');
				$params_week   = array('amount' => $row->week, 'currFrom' => $currency,'currInto' => 'USD');
				$params_month  = array('amount' => $row->month, 'currFrom' => $currency,'currInto' => 'USD');
						
			$price = round(google_convert($params_price));
			$cleaning_fee = round(google_convert($params_clean));
			$extra_guest_fee = round(google_convert($params_guest));
			$Wprice = round(google_convert($params_week));
			$Mprice = round(google_convert($params_month));

			}*/
			$after_guest_fee = $row->addguests;
	   }
	   }
else
	{
		$Wprice='';
		$Mprice='';
		$cleaning_fee='';
		$price='';
		$extra_guest_fee='';
	}
			
	
     $conditions             = array("id" => $room_id);
	 $result                 = $this->Common_model->getTableData('list', $conditions);
	 
	 	$today_month=date("F");
		$today_date=date("j");
		$today_year=date("Y");
		$conditions_statistics = array("list_id" => $room_id,"date"=>trim($today_date),"month"=>trim($today_month),"year"=>trim($today_year));
		$result_statistics = $this->Common_model->add_page_statistics($room_id,$conditions_statistics);
		
		$list                   = $list = $result->row();
		$title                  = $list->title;
		$page_viewed            = $list->page_viewed;
		
		$page_viewed = $this->Trips_model->update_pageViewed($room_id, $page_viewed);
		
			
		$id                     = $room_id;
		$checkin                = $this->session->userdata('Vcheckin');
		$checkout               = $this->session->userdata('Vcheckout');
		$guests         = $this->session->userdata('Vnumber_of_guests');
	
		$ckin                   = explode('/', $checkin);
		$ckout                  = explode('/', $checkout);
		
		//check admin premium condition and apply so for
		$query                  = $this->Common_model->getTableData( 'paymode', array('id' => 2));
		$row                    = $query->row();	

		
		if(($ckin[0] == "mm" && $ckout[0] == "mm") or ($ckin[0] == "" && $ckout[0] == ""))
		{
      			
			 if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
			
			 if($row->is_premium == 1)
					{
			    if($row->is_fixed == 1)
							{
										$fix            = $row->fixed_amount; 
										$amt            = $price + $fix;
										$commission = $fix;
										$Fprice         = $amt;
							}
							else
							{  
										$per            = $row->percentage_amount; 
										$camt           = floatval(($price * $per) / 100);
										$amt            = $price + $camt;
										$commission = $camt;
										$Fprice         = $amt;
							}
							
						if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
		
		   }
			} 
		else
		{	
			$diff                  = strtotime($ckout[2].'-'.$ckout[0].'-'.$ckout[1]) - strtotime($ckin[2].'-'.$ckin[0].'-'.$ckin[1]);
			$days                  = ceil($diff/(3600*24));
			
			if($guests > $guests)
			{
			  $price               = ($price * $days) + ($days * $xprice->addguests);
			}
			else
			{
			  $price               = $price * $days;
			}
					
			if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
			
			$commission    = 0;
			
			 if($row->is_premium == 1)
					{
			    if($row->is_fixed == 1)
							{
										$fix             = $row->fixed_amount; 
										$amt             = $price + $fix;
										$commission = $fix;
										$Fprice          = $amt;
							}
							else
							{  
										$per             = $row->percentage_amount; 
										$camt            = floatval(($price * $per) / 100);
										$amt             = $price + $camt;
										$commission = $camt;
										$Fprice          = $amt;
							}
							
						if($Wprice == 0)
			{
				$data['Wprice']  = $price * 7;
			}
			else
			{
				$data['Wprice']  = $Wprice;
			}
			if($Mprice == 0)
			{
				$data['Mprice']  = $price * 30;
			}
			else
			{
				$data['Mprice']  = $Mprice;
			}
		
		   }
					}
		
			$conditions              = array('list_id' => $room_id);
			$condition    = array("is_featured" => 1);
						$list_image   = $this->Gallery->get_imagesG($room_id, $condition)->row();

					if(isset($list_image->name))
					{
						$image_url_ex = explode('.',$list_image->name);

						$image = base_url().'images/'.$room_id.'/'.$image_url_ex[0].'_crop.jpg';
					}
					else
					{
						$image = base_url().'images/no_image.jpg';
					}
			
			$conditions    			        = array('list_id' => $room_id, 'userto' => $list->user_id);
			$result			     	  = $this->Trips_model->get_review($conditions);
			
			$conditions    			     	  = array('list_id' => $room_id, 'userto' => $list->user_id);
			$stars			        	= $this->Trips_model->get_review_sum($conditions)->row();	
			 
			$title            = substr($title, 0, 70);
			
			
		   $search=array('\'','"','(',')','!','{','[','}',']');
			$replace=array('&sq','&dq','&obr','&cbr','&ex','&obs','&oabr','&cbs','&cabr');
		    $desc_replace = str_replace($search, $replace, $desc);
			$desc_tags = stripslashes($desc_replace);
							 
			$amenities = $this->db->get_where('list', array('id' => $room_id))->row()->amenities;
				 $property_type = $this->db->get_where('property_type', array('id' => $property_id))->row()->type;
    $in_arr = explode(',', $amenities);
	$result = $this->db->get('amnities');				 
							 $user_name = $this->db->where('id',$user_id)->select('username')->from('users')->get();
							 if($user_name->num_rows()!=0)
							 {
							 	foreach($user_name->result() as $row)
								{
									$hoster_name = $row->username;
								}
							 }
							 else
							 	{
							 		$hoster_name = 'No Username';
							 	}
							
				 $currency1 = $this->input->get('currency');
					
					$Mprice = $Mprice;
					$price = $price;
					$extra_guest_fee = $after_guest_fee;
					$Wprice = $Wprice;
					$sublet_price = $sublet_price;
					$cleaning_fee = $cleaning_fee;
                 /*$price = $this->get_currency_value1($room_id, $price, $currency1);	
				 $Mprice = $this->get_currency_value1($room_id, $Mprice, $currency1);
				 $extra_guest_fee = $this->get_currency_value1($room_id, $after_guest_fee, $currency1);
				 $Wprice = $this->get_currency_value1($room_id, $Wprice, $currency1);
				 $sublet_price = $this->get_currency_value1($room_id, $sublet_price, $currency1);
				 $cleaning_fee = $this->get_currency_value1($room_id, $cleaning_fee, $currency1);	*/	
								
            echo "[ { \"id\":".$room_id.",\"user_id\":".$user_id.",\"hoster_name\":\"".$hoster_name."\",\"title\":\"".$title."\",\"country\":\"".$country.
			    "\",\"city\":\"".$city."\",\"state\":\"".$state."\",\"cancellation_policy\":\"".$cancellation_policy.
	           "\",\"address\":\"".$address."\",\"image_url\":\"".$image."\",
	           \"room_type\":\"".$room_type."\",\"bedrooms\":".$bedrooms.",\"bathrooms\":".$bathrooms.",\"bed_type\":\"".$bed_type."\",\"beds\":\"".$beds."\",
	           \"desc\":\"".$desc_tags."\",\"capacity\":".$capacity.",\"price\":\"".$price.
	           "\",\"cleaning_fee\":\"".$cleaning_fee."\",\"extra_guest_fee\":\"".$extra_guest_fee."\",\"weekly_price\":\"".$Wprice.
	           "\",\"monthly_price\":\"".$Mprice."\",\"email\":\"".$email."\",\"phone\":\"".$phone."\",\"review\":\"".$review.
	           "\",\"lat\":".$lat.",\"long\":".$long.",\"property_type\":\"".$property_type."\",\"street_view\":".$street_view.
	           ",\"sublet_price\":".$sublet_price.",\"sublet_status\":".$sublet_status.",\"sublet_startdate\":\"".$sublet_startdate.
	           "\",\"sublet_enddate\":\"".$sublet_enddate."\",\"currency\":\"".$currency."\",\"manual\":\"".$manual."\",\"page_viewed\":".$page_viewed
	           .",\"neighbor\":\"".$neighbor."\",\"amenities\":\"";if($result->num_rows() != 0) {
	           if($amenities)
			   {
			    foreach($result->result() as $row)
	{
	    if(in_array($row->id, $in_arr))
		{
			$json[] = $row->id.",";
		}
	}
	$count = count($json);
		  $end = $count-1;
					$slice = array_slice($json,0,$end);
					foreach($slice as $row)
					{
						echo $row; 
					}
					$comma = end($json);
					$json = substr_replace($comma ,"",-1);
					echo $json."\""; echo "} ]";exit;
			   }
			   }
else {
	$json[] ='';
	
}
		echo "\"} ]";			
			  
	}
	}
	else {
		echo '[{"status":"Access denied"}]';
	}
}

public function cancellation_policy()
{
	$result = $this->db->get('cancellation_policy');
	echo '[';
	foreach($result->result() as $row)
	{
		$cancellation_content = str_replace('<p>', '&op&', $row->cancellation_content);
		$cancellation_content = str_replace('</p>', '&cp&', $cancellation_content);
		$json[] = '{"id":"'.$row->id.'","cancellation_title":"'.$row->cancellation_title.'","cancellation_content":"'.$cancellation_content.'"},';
	}
	$count = count($json);
		  $end = $count-1;
					$slice = array_slice($json,0,$end);
					foreach($slice as $row)
					{
						echo $row; 
					}
					$comma = end($json);
					$json = substr_replace($comma ,"",-1);
					echo $json;
  	echo ']';
}

public function list_photo()
{
	$room_id = $this->input->get('room_id');
		
		$filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id;
		
	    if(!file_exists($filename)) 
		{
     	mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$room_id, 0777, true);
		}
		
	  $file_element_name = "uploadedfile";
			
	  $config['upload_path'] = "./images/{$room_id}";
      $config['allowed_types'] = 'gif|jpg|png';
	  $config['remove_spaces'] = TRUE;
      $config['max_size']  = 102400 * 8;
      $config['encrypt_name'] = FALSE;
 
      $this->load->library('upload', $config);
 
      if (!$this->upload->do_upload($file_element_name))
      {
      	echo $this->upload->display_errors();
      }
      else
      {
      	 	    $upload_data = $this->upload->data(); 				
			      $image_name    = $upload_data['file_name'];
				        $insertData['list_id']    = $room_id;
      	                $insertData['name']       = $image_name;
						$insertData['created']    = local_to_gmt();
						
						$check = $this->db->where('list_id',$room_id)->get('list_photo');
						
						$photo_status['photo'] = 1;
			            $this->db->where('id',$room_id)->update('lys_status',$photo_status);
						
						if($check->num_rows() == 0)
						{
							$insertData['is_featured'] = 1;
				     	}
                        else 
                        {
	                       $insertData['is_featured'] = 0;
                        }
						if($image_name != '')
						$this->Common_model->insertData('list_photo', $insertData);
						//$this->watermark($room_id,$image_name);
						
      	  $list_photo = $this->db->where('list_id',$room_id)->order_by('id','asc')->get('list_photo');
	
   	if($list_photo->num_rows() != 0)
	{
   	foreach($list_photo->result() as $row)
     {
     	    
      $data['image_url'] = base_url()."images/".$room_id."/".$row->name;

     }
	}
 //$this->watermark($room_id,$image_name);  
 //$this->watermark1($room_id,$image_name); 
 
 echo json_encode($data);exit;
	  }
}

function watermark($list_id,$image_name)
	{
   $image_path =  dirname($_SERVER['SCRIPT_FILENAME']);
  
  $main_imgc		= $image_path."/images/$list_id/$image_name"; // main big photo / picture
  
// using the function to crop an image
$source_image = $main_imgc;
$main_img_ext = explode('.', $image_name);
$main_imgc = $image_path."/images/$list_id/$main_img_ext[0]";
$target_image = $main_imgc.'_crop.jpg';

$return = $this->resize_image($source_image, $target_image); 

if($return != 1)
{
	exit;
}
$main_img = $target_image;
$logo = $this->db->get_where('settings', array('code' => 'SITE_LOGO'))->row()->string_value;
$watermark_img	= $image_path."/logo/$logo"; // use GIF or PNG, JPEG has no tranparency support
$padding 		= 3;     // distance to border in pixels for watermark image
$opacity		= 50;	// image opacity for transparent watermark

$watermark 	    = imagecreatefrompng($watermark_img); // create watermark
$image_water 	= imagecreatefromjpeg($main_img); // create main graphic

if(!$image_water || !$watermark) die("Error: main image or watermark could not be loaded!");


$watermark_size 	= getimagesize($watermark_img);
$watermark_width 	= $watermark_size[0];  
$watermark_height 	= $watermark_size[1]; 

$image_size 	= getimagesize($main_img);  
$dest_x 		= $image_size[0] - $watermark_width - $padding;  
$dest_y 		= $image_size[1] - $watermark_height - $padding;


// copy watermark on main image
imagecopy($image_water, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);


// print image to screen
//header("content-type: image/jpeg");   
imagejpeg($image_water,$main_imgc.'.'.$main_img_ext[1].'_watermark.jpg',100);  
//imagejpeg($image_water); 
imagedestroy($image_water);  
imagedestroy($watermark); 
return true;

	 } 

function watermark1($list_id,$image_name)
	{
   $image_path =  dirname($_SERVER['SCRIPT_FILENAME']);
  
  $main_imgc		= $image_path."/images/$list_id/$image_name"; // main big photo / picture
  
$watermark_size 	= getimagesize($main_imgc);
$watermark_width 	= $watermark_size[0];  
$watermark_height 	= $watermark_size[1]; 

$config['image_library'] = 'gd2';
$config['source_image'] = $main_imgc;
$config['new_image'] = $image_path."/images/$list_id/$image_name"."_home.jpg";
$config['maintain_ratio'] = TRUE;
$config['width'] = 1355;
$config['height'] = 500;

$this->load->library('image_lib');
$this->image_lib->initialize($config);

if ( ! $this->image_lib->resize())
{
    echo $this->image_lib->display_errors();exit;
}
else {
	//echo 'resized';exit;
}
// using the function to crop an image
$main_img = $config['new_image'];
$watermark_img	= $image_path."/images/banner_black_watermark_right.png"; // use GIF or PNG, JPEG has no tranparency support
$padding 		= 0;     // distance to border in pixels for watermark image
$opacity		= 50;	// image opacity for transparent watermark

$watermark 	    = imagecreatefrompng($watermark_img); // create watermark
$image_water 	= imagecreatefromjpeg($main_img); // create main graphic

if(!$image_water || !$watermark) die("Error: main image or watermark could not be loaded!");


$watermark_size 	= getimagesize($watermark_img);
$watermark_width 	= $watermark_size[0];  
$watermark_height 	= $watermark_size[1]; 

$image_size 	= getimagesize($main_img);  
$dest_x 		= $image_size[0] - $watermark_width - $padding;  
$dest_y 		= $image_size[1] - $watermark_height - $padding;

//echo $image_size[0].' - '.$watermark_width.'-'.$dest_x;
// copy watermark on main image
imagecopy($image_water, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);


// print image to screen
//header("content-type: image/jpeg");   
imagejpeg($image_water,$main_img.'_watermark.jpg',100);  
//imagejpeg($image_water); 
imagedestroy($image_water);  
imagedestroy($watermark); 

$main_img = $main_img.'_watermark.jpg';
$watermark_img	= $image_path."/images/banner_black_watermark_left.png"; // use GIF or PNG, JPEG has no tranparency support
$padding 		= 0;     // distance to border in pixels for watermark image
$opacity		= 50;	// image opacity for transparent watermark

$watermark 	    = imagecreatefrompng($watermark_img); // create watermark
$image_water 	= imagecreatefromjpeg($main_img); // create main graphic

if(!$image_water || !$watermark) die("Error: main image or watermark could not be loaded!");


$watermark_size 	= getimagesize($watermark_img);
$watermark_width 	= $watermark_size[0];  
$watermark_height 	= $watermark_size[1]; 

$image_size 	= getimagesize($main_img);  
$dest_x 		= $image_size[0] - $watermark_width - $padding;  
$dest_y 		= $image_size[1] - $watermark_height - $padding;

//echo $image_size[0].' - '.$watermark_width.'-'.$dest_x;
// copy watermark on main image
imagecopy($image_water, $watermark, 0, $dest_y, 0, 0, $watermark_width, $watermark_height);


// print image to screen
//header("content-type: image/jpeg");   
imagejpeg($image_water,$main_img,100);  
//imagejpeg($image_water); 
imagedestroy($image_water);  
imagedestroy($watermark); 

return true;

	 } 

	/**
 * Resize Image
 *
 * Takes the source image and resizes it to the specified width & height or proportionally if crop is off.
 * @access public
 * @author Jay Zawrotny <jayzawrotny@gmail.com>
 * @license Do whatever you want with it.
 * @param string $source_image The location to the original raw image.
 * @param string $destination_filename The location to save the new image.
 * @param int $width The desired width of the new image
 * @param int $height The desired height of the new image.
 * @param int $quality The quality of the JPG to produce 1 - 100
 * @param bool $crop Whether to crop the image or not. It always crops from the center.
 */
function resize_image($source_image, $destination_filename, $width = 400, $height = 350, $quality = 70, $crop = true)
{

        if( ! $image_data = getimagesize( $source_image ) )
        {
                return false;
        }

        switch( $image_data['mime'] )
        {
                case 'image/gif':
                        $get_func = 'imagecreatefromgif';
                        $suffix = ".gif";
                break;
                case 'image/jpeg';
                        $get_func = 'imagecreatefromjpeg';
                        $suffix = ".jpg";
                break;
                case 'image/png':
                        $get_func = 'imagecreatefrompng';
                        $suffix = ".png";
                break;
        }

        $img_original = call_user_func( $get_func, $source_image );
        $old_width = $image_data[0];
        $old_height = $image_data[1];
        $new_width = $width;
        $new_height = $height;
        $src_x = 0;
        $src_y = 0;
        $current_ratio = round( $old_width / $old_height, 2 );
        $desired_ratio_after = round( $width / $height, 2 );
        $desired_ratio_before = round( $height / $width, 2 );

      //  if( $old_width < $width || $old_height < $height )
      //  {
                /**
                 * The desired image size is bigger than the original image. 
                 * Best not to do anything at all really.
                 */
      //          return false;
     //   }


        /**
         * If the crop option is left on, it will take an image and best fit it
         * so it will always come out the exact specified size.
         */
        if( $crop )
        {
                /**
                 * create empty image of the specified size
                 */
                $new_image = imagecreatetruecolor( $width, $height );

                /**
                 * Landscape Image
                 */
                if( $current_ratio > $desired_ratio_after )
                {
                        $new_width = $old_width * $height / $old_height;
                }

                /**
                 * Nearly square ratio image.
                 */
                if( $current_ratio > $desired_ratio_before && $current_ratio < $desired_ratio_after )
                {
                        if( $old_width > $old_height )
                        {
                                $new_height = max( $width, $height );
                                $new_width = $old_width * $new_height / $old_height;
                        }
                        else
                        {
                                $new_height = $old_height * $width / $old_width;
                        }
                }

                /**
                 * Portrait sized image
                 */
                if( $current_ratio < $desired_ratio_before  )
                {
                        $new_height = $old_height * $width / $old_width;
                }

                /**
                 * Find out the ratio of the original photo to it's new, thumbnail-based size
                 * for both the width and the height. It's used to find out where to crop.
                 */
                $width_ratio = $old_width / $new_width;
                $height_ratio = $old_height / $new_height;

                /**
                 * Calculate where to crop based on the center of the image
                 */
                $src_x = floor( ( ( $new_width - $width ) / 2 ) * $width_ratio );
                $src_y = round( ( ( $new_height - $height ) / 2 ) * $height_ratio );
        }
        /**
         * Don't crop the image, just resize it proportionally
         */
        else
        {
                if( $old_width > $old_height )
                {
                        $ratio = max( $old_width, $old_height ) / max( $width, $height );
                }else{
                        $ratio = max( $old_width, $old_height ) / min( $width, $height );
                }

                $new_width = $old_width / $ratio;
                $new_height = $old_height / $ratio;

                $new_image = imagecreatetruecolor( $new_width, $new_height );
        }

        /**
         * Where all the real magic happens
         */
        imagecopyresampled( $new_image, $img_original, 0, 0, $src_x, $src_y, $new_width, $new_height, $old_width, $old_height );

        /**
         * Save it as a JPG File with our $destination_filename param.
         */
          /*  $image_path =  dirname($_SERVER['SCRIPT_FILENAME']);
  
  $destination_filename		= $image_path."/images/85/crop";*/
        imagejpeg( $new_image, $destination_filename, $quality  );

        /**
         * Destroy the evidence!
         */
        imagedestroy( $new_image );
        imagedestroy( $img_original );

        /**
         * Return true because it worked and we're happy. Let the dancing commence!
         */
        return true;
} 



public function viewlist()
{
	$user_id=$this->input->get('user_id');
	
	$query=$this->db->where('user_id',$user_id)->get('list');
	if($query->num_rows()!=0)
	{
	foreach($query->result() as $row)
	{
		$data['id']=$row->id;
		$data['user_id']=$row->user_id;
		$data['address']=$row->address;
		$data['country']=$row->country;
		$data['street_address']=$row->street_address;
		$data['city']=$row->city;
		$data['state']=$row->state;
		$data['zip_code']=$row->zip_code;
		$data['lat']=$row->lat;
		$data['long']=$row->long;
		$data['property_id']=$row->property_id;
		$data['room_type']=$row->room_type;
		$data['bedrooms']=$row->bedrooms;
				$data['beds']=$row->beds;
				$data['bathrooms']=$row->bathrooms;
				$data['amenities']=$row->amenities;
				$data['title']=$row->title;
				$data['desc']=$row->desc;
				$data['capacity']=$row->capacity;
				$data['price']=$row->price;
				$data['currency']=$row->currency;
				$data['currency_symbol']=$this->db->where('currency_code',$data['currency'])->get('currency')->row('currency_symbol');
				$country_symbol =$this->db->where('currency_code',$data['currency'])->get('currency')->row('country_symbol');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
	                $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				$data['home_type']=$row->home_type;
				$final[]=$data;	
				
		
	}
	echo json_encode($final);
	}
	else {
			echo '[{"Status":"No Listings Found"}]';
	}
	
}
public function your_listing1(){
	$user_id   = $this->input->get('user_id');
	//$query = $this->db->where('user_id',$user_id)->get('list');
    
    $step_count_query = $this->db->distinct()->select('list.*,lys_status.overview,lys_status.price,lys_status.photo,lys_status.address,lys_status.listing')->join('lys_status',"lys_status.id=list.id")->where("list.user_id",$user_id)->order_by('desc')->get('list');
	
	if($step_count_query->num_rows()!=0)
	{
	foreach($step_count_query->result() as $row)
	{
		$data['id']=$row->id;
		$data['user_id']=$row->user_id;
		$status_details = $row->status;
		$phtoto_status =$this->db->where('list_id',$data['id'])->get('list_photo')->row('image');
		if($status_details == 1)
		{
			$data['status']= 1 ;
			
		}
		else {
	$data['status']= 0 ;
			}
		
		
	    $data['country']=$row->country;
		 
		$data['room_type']=$row->room_type;
				$data['title']=$row->title;
				$data['desc']=$row->desc;
				$data['image'] = $phtoto_status;
				$data['reason_message']="list found";
        
        if($row->overview != "null")
        {
            $total_status = $row->overview + $row->price + $row->photo + $row->address + $row->listing;
            
            $data['step_count'] = 5 - $total_status;
        }
        else
        {
            $data['step_count'] = "-1";
        }

				$listing[]=$data;
				
	}
	echo json_encode($listing, JSON_UNESCAPED_SLASHES);
	}
	else {
	echo '[{"reason_message":"No data found"}]';
}
}


public function preview(){
	$roomid = $this->input->get('roomid');
	$query = $this->db->where('id',$roomid)->get('list');
	if($query->num_rows()!=0)
	{
	foreach($query->result() as $row)
	{
		$data['id']=$row->id;
		$data['user_id'] = $row->user_id;
		$query_data = $this->db->where('id',$data['user_id'])->get('profiles')->row()->join_date;
		
		if(!empty($query_data))
		{
		$final_date = gmdate('F Y',$query_data);
		}
		else {
			$final_date = '';
			}
		//$date_in = get_user_times(get_gmt_time(strtotime($final_date)), get_user_timezone1());
	//$days[]       = $query_data;
 	//$dateinfo     = date ( 'd/m/Y' ,$query_data);
	//$aDate = explode("/", $dateinfo);
	//$imonth = $aDate[1];
	//$iYear = $aDate[2];
		//print_r($final_date);exit;
		$data['country']=$row->country;
		$data['state']  = $row->state;
		if(!empty($row->address))
		{
		$data['address'] = $row->address;
		}
		else 
		{
			$data['address'] = $row->street_address;
		}
		$data['city']  = $row->city;
		$data['room_type']=$row->room_type;
		$data['bedrooms']=$row->bedrooms;
				$data['beds']=$row->beds;
				$data['bathrooms']=$row->bathrooms;
				$data['bed_type']=$row->bed_type;
				$data['amenities']=$row->amenities;
				$data['title']=$row->title;
				$data['desc']=$row->desc;
				$data['price']=$row->price;
				$created = $row->created;
				$data['join_date']=$final_date;
				$data['create']  = gmdate('F Y',$created);
				$data['capacity']=$row->capacity;
				$data['week']=$this->db->where('id',$data['id'])->get('price')->row('week');
				$data['month']=$this->db->where('id',$data['id'])->get('price')->row('month');
				$data['Fname']=$this->db->where('id',$data['user_id'])->get('profiles')->row('Fname');
				$data['email']=$this->db->where('id',$data['user_id'])->get('profiles')->row('email');
				$src =$this->db->where('email',$data['email'])->get('profile_picture')->row('src');
				
				if(!empty($src)){
					$data['src']   = $src;
				}
                else{
	                 $data['src']  = 'null';
                }
				$currency  = $row->currency;
				$data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
				$country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
				$country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
                	$check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				if(!empty($country_code)){
					$data['currency_code']   = $country_code;
				}
                else{
                	$check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
					//print_r($this->db->last_query());exit;
	                 $data['currency_code']  = $check_default1;
                }
				$image =$this->db->where('list_id',$data['id'])->get('list_photo')->row('image');
				if(!empty($image)){
					$data['image']   = $image;
				}
                else{
	                 $data['image']  = 'null';
                }
				$resize =$this->db->where('list_id',$data['id'])->get('list_photo')->row('resize');
				if(!empty($resize)){
					$data['resize']   = $resize;
				}
                else{
	                 $data['resize']  = 'null';
                }
				$resize1 =$this->db->where('list_id',$data['id'])->get('list_photo')->row('resize1');
				if(!empty($resize1)){
					$data['resize1']   = $resize1;
				}
                else{
	                 $data['resize1']  = 'null';
                }
				$map =$this->db->where('list_id',$data['id'])->get('map_photo')->row('map');
				if(!empty($map)){
					$data['map']   = $map;
				}
                else{
	                 $data['map']  = 'null';
                }
				
				$prev[]=$data;	
				
		
	}
	echo json_encode($prev, JSON_UNESCAPED_SLASHES);
	}
}
public function display_amnities()
{
	$roomid = $this->input->get('roomid');
	if ($roomid!=null)
	{
		$query   = $this->db->where('id',$roomid)->get('list');
		if($query->num_rows()!=0)
	{
	foreach($query->result() as $row){
		$data['amenities']   = $row->amenities;
	}
	}
    $details[] = $data;
	echo json_encode($details, JSON_UNESCAPED_SLASHES);
	}
	else if ($roomid==null)
	{
	$amnities = $this->db->query('select * from amnities');
	foreach ($amnities->result() as $row) {
		$data['id'] = $row->id;
		$data['name'] = $row->name;
		$data['description'] = $row->description;
		$amenities[] = $data;
	}
	echo json_encode($amenities);
	}
}
	
public function display_am()
{
	$roomid = $this->input->get('roomid');
	
	$amnities = $this->db->where('id',$roomid)->get('amnities');
	
	foreach ($amnities->result() as $row) {
		print_r($row);
		
	}
}	
	
	
	
	
public function amnities(){
	$roomid = $this->input->get('roomid');
	$query = $this->db->where('id',$roomid)->get('list');
	if($query->num_rows()!=0)
	{
	foreach($query->result() as $row)
	{
		$data['id']=$row->id;
		$data['amenities']=$row->amenities;
		$select[] = $data;
	}
	echo json_encode($select);
	
	}
}

public function edit_amnities(){
	$this->load->model('common_model');
		$roomid=$this->input->get('roomid');
	
		$data['amenities']			 = $this->input->get("amenities");
		$level = explode(',', $data['amenities']);
		if($roomid == ''){
		  echo '[{"status":"You should provide roomid."}]';exit;
		}
		else{
		$this->db->where('id',$roomid)->update('list',$data);
		
		echo '[{"reason_message":"Updated Successfully"}]';
		}
}

public function other(){
	$query = $this->db->query('select * from property_type');
	foreach($query->result() as $row)
	{
	$id = $row->id;
	if($id > 3){
		$data['id'] = $row->id;
		$data['type'] = $row->type;
		$other[] =$data;
	}
	}
	echo json_encode($other);
	
}
public function other_page(){
	$user_id = $this->input->get('user_id');
	$query1 = $this->db->where('id',$user_id)->get('profiles');
	foreach($query1->result() as $row){
		$data['id']       = $row->id;
		$data['Fname']    = $row->Fname;
		$data['Lname']    = $row->Lname;
		$data['gender']   = $row->gender;
		$data['dob']      = $row->dob;
		$data['email']    = $row->email;
		$data['live']     = $row->live;
		$data['school']   = $row->school;
		$data['work']     = $row->work;
		$data['phnum']    = $row->phnum;
		$data['describe'] = $row->describe;
		$src =$this->db->where('email',$data['email'])->get('profile_picture')->row('src');
		if(!empty($src)){
					$data['src']   = $src;
				}
                else{
	                 $data['src']  = 'null';
                }
		$page[]=$data;
	}
	echo json_encode($page);
}
public function apartment_detail1(){
	$roomid = $this->input->get('roomid');
	$query = $this->db->where('id',$roomid)->get('list');
	if($query->num_rows()!=0)
	{
	foreach($query->result() as $row)
	{
		$data['id']=$row->id;
		$data['user_id'] = $row->user_id;
		$data['country']=$row->country;
		$data['state']  = $row->state;
		$data['address'] = $row->address;
		$data['city']  = $row->city;
		$data['room_type']=$row->room_type;
		$data['bedrooms']=$row->bedrooms;
				$data['beds']=$row->beds;
				$data['bathrooms']=$row->bathrooms;
				$data['bed_type']=$row->bed_type;
				$data['amenities']=$row->amenities;
				$data['title']=$row->title;
				$data['desc']=$row->desc;
				$data['price']=$row->price;
				$data['capacity']=$row->capacity;
				$data['week']=$this->db->where('id',$data['id'])->get('price')->row('week');
				$data['month']=$this->db->where('id',$data['id'])->get('price')->row('month');
				$data['Fname']=$this->db->where('id',$data['user_id'])->get('profiles')->row('Fname');
				$data['Lname']=$this->db->where('id',$data['user_id'])->get('profiles')->row('Lname');
				$data['email']=$this->db->where('id',$data['user_id'])->get('profiles')->row('email');
				$data['src']=$this->db->where('email',$data['email'])->get('profile_picture')->row('src');
				$currency  = $row->currency;
				$data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
					$country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
				$country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
	                $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				if(!empty($country_code)){
					$data['currency_code']   = $country_code;
				}
                else{
                	$check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
					//print_r($this->db->last_query());exit;
	                 $data['currency_code']  = $check_default1;
                }
			    	//$data['list_id'] = $this->db->where('list_id',$roomid)->get('list_photo')->result_array();
			    
				$data['image']=$this->db->where('list_id',$data['id'])->get('list_photo')->row('image');
				$data['map']=$this->db->where('list_id',$data['id'])->get('map_photo')->row('map');
				$apart[]=$data;	
				
		
	}
	echo json_encode($apart, JSON_UNESCAPED_SLASHES);
	}
}

public function apartment_detail(){
	$roomid = $this->input->get('roomid');
    $common_currency = $this->input->get('common_currency');
    
	$query = $this->db->where('id',$roomid)->get('list');
	if($query->num_rows()!=0)
	{
	foreach($query->result() as $row)
	{
		$data['id']=$row->id;
		$data['user_id'] = $row->user_id;
		$data['country']=$row->country;
		$data['state']  = $row->state;
		$data['address'] = $row->address;
		$data['check_status'] = $row->check_status;
		$data['step_status'] = $row->step_status;
		$data['city']  = $row->city;
		$data['room_type']=$row->room_type;
		$data['bedrooms']=$row->bedrooms;
        $data['listpay'] = $row->list_pay;
				$data['beds']=$row->beds;
				$data['bathrooms']=$row->bathrooms;
				$data['bed_type']=$row->bed_type;
				$data['amenities']=$row->amenities;
				$data['title']=$row->title;
				$data['desc']=$row->desc;
				$data['price']=$row->price;
				$data['capacity']=$row->capacity;
				$data['week']=$this->db->where('id',$data['id'])->get('price')->row('week');
				$data['month']=$this->db->where('id',$data['id'])->get('price')->row('month');
				//$data['Fname']=$this->db->where('id',$data['user_id'])->get('profiles')->row('Fname');
				$data['email']=$this->db->where('id',$data['user_id'])->get('profiles')->row('email');
				$src =$this->db->where('email',$data['email'])->get('profile_picture')->row('src');
				
				if($data['Fname']=$this->db->where('id',$data['user_id'])->get('profiles')->row('Fname')==null)
                {
                    $data['Fname']='null';
                }
                else 
                    {
                        $data['Fname']=$this->db->where('id',$data['user_id'])->get('profiles')->row('Fname');
                    }
                
				
				if(!empty($src)){
					$data['src']   = $src;
				}
                else{
	                 $data['src']  = 'null';
                }
		
				$currency  = $row->currency;
				$data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
				$currency_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
				$country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
				if(!empty($currency_code)){
					$data['currency_code'] = $currency_code;
                    
                    $currencycode = $currency_code;
				}
				else {
					$check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
					//print_r($this->db->last_query());exit;
	                 $data['currency_code']  = $check_default1;
                    
                    $currencycode = $check_default1;
				}
				
				
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
	                $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				//$data['list_id'] = $this->db->where('list_id',$roomid)->get('list_photo')->result_array();
        
                // $json = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$currencycode."_".$common_currency);
//         
                // $obj = json_decode($json);
//         
                // foreach($obj->results as $results)
                // {
                    // $value = $results->val;
            // //echo $value;
                    // $data['common_currency_code'] = $common_currency;
                    // $data['common_currency_value'] = $value * $row->price;
                // }
                     $data['common_currency_code'] = $common_currency;
            $data['common_currency_value'] = (get_currency_value_lys($currencycode,$common_currency,1)) * $row->price;
			    
				$image =$this->db->where('list_id',$data['id'])->get('list_photo')->row('image');
				if(!empty($image)){
					$data['image']   = $image;
				}
                else{
	                 $data['image']  = 'null';
                }
				$resize =$this->db->where('list_id',$data['id'])->get('list_photo')->row('resize');
				if(!empty($resize)){
					$data['resize']   = $resize;
				}
                else{
	                 $data['resize']  = 'null';
                }
				$resize1 =$this->db->where('list_id',$data['id'])->get('list_photo')->row('resize1');
				if(!empty($resize1)){
					$data['resize1']   = $resize1;
				}
                else{
	                 $data['resize1']  = 'null';
                }
				$map =$this->db->where('list_id',$data['id'])->get('map_photo')->row('map');
				
				if(!empty($map)){
					$data['map']   = $map;
				}
                else{
	                 $data['map']  = 'null';
                }
				$prev[]=$data;	
				
		
	}
	echo json_encode($prev, JSON_UNESCAPED_SLASHES);
	}
else {
	echo '[{"reason_message":"No Data Found"}]';
}
}
    
    public function change_currency()
    {
        $common_currency = $this->input->get('common_currency');
        $low_value = $this->input->get('low_currency_value');
        $high_value = $this->input->get('high_currency_value');
        $currencycode = "USD";
        
        // $json = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$currencycode."_".$common_currency);
//         
        // $obj = json_decode($json);
//         
        // foreach($obj->results as $results)
        // {
            // $value = $results->val;
            // //echo $value;
            // $data['lower_value'] = $value * $low_value;
            // $data['higher_value'] = $value * $high_value;
            // $data['currency_code'] = $common_currency;
            // $result[] = $data;
        // }
             $data['common_currency_code'] = $common_currency;
             $data['lower_value']  = $value * $low_value;
             $data['higher_value']  = $value * $high_value;
            
        echo json_encode($result, JSON_UNESCAPED_SLASHES);
        
    }

public function status(){
	$roomid   = $this->input->get('roomid');
	  $status = $this->input->get('check_status');
	if($status == 1)
	{
	$data['check_status'] = $status;
	$data['status'] = 1;
	$data['is_enable'] = 1;
	$this->db->where('id',$roomid)->update('list',$data);
	
	echo '[{"reason_message":"List created Successfully"}]';
	}
	else
		{
			echo '[{"reason_message":"List is Incompleted"}]';
		}
		
}

public function getlatlong()
{
	$room_id = $this->input->get('roomid');
	
	$result = $this->db->distinct()->where('id', $room_id)->get('list');
	
	if($result->num_rows() != 0)
	{
		foreach($result->result() as $row)
		{
			$data['status'] = "success";
			$data['lat'] = $row->lat;
			$data['long'] = $row->long;
			
			$resultarray[] = $data;
		}
		
		echo json_encode($resultarray, JSON_UNESCAPED_SLASHES);
	}
	else 
		{
			echo '[{"status":"No List Found"}]';
	}
}

    public function upcomingtrips(){
        
        $user_id   = $this->input->get('user_id');
        
        $common_currency = $this->input->get('common_currency');
        
        if($this->input->get('start'))
		$this->db->limit(5,$this->input->get('start')-1);
		else
		$this->db->limit(5,0);
		
        //$step_count_query = $this->db->distinct()->select('list.*,reservation.id as resid,reservation.list_id, reservation.checkin, reservation.no_quest, reservation.checkout, reservation.status as resstatus, reservation.price as resprice, reservation.topay, reservation.userby, reservation.userto')->join('reservation',"reservation.list_id=list.id")->where("reservation.userby",$user_id)->where("(reservation.status = 3 OR reservation.status = 1)")->order_by('list.id', 'desc')->get('list');
        //echo $this->db->last_query();
        //exit;
        //if($step_count_query->num_rows() !=0 )
        if($result = $this->db->from('list')->distinct()->select('list.*,reservation.id as resid,reservation.list_id, reservation.checkin, reservation.no_quest, reservation.checkout, reservation.status as resstatus, reservation.price as resprice, reservation.topay, reservation.userby, reservation.userto')->join('reservation',"reservation.list_id=list.id")->where("reservation.userby",$user_id)->where("(reservation.status = 3 OR reservation.status = 1 OR reservation.status = 7)")->order_by('reservation.book_date', 'desc')->get()->result())
        {
            //foreach($step_count_query->result() as $row)
            foreach($result as $row)
            {
                $id = $row->resid;
                $user_id = $row->user_id;
                $email=$this->db->where('id',$user_id)->get('profiles')->row('email');
                $data['no_quest'] = $row->no_quest;
                $data['id']  = $row->resid;
                $data['user_id'] = $row->user_id;
                $data['title']=$row->title;
                $data['price']=$row->price;
                $data['country'] = $row->country;
                $data['city']  = $row->city;
                $data['address']  = $row->address;
                $data['room_type'] = $row->room_type;
                $currency  = $row->currency;
                $data['list_id'] = $row->list_id;
                $data['user_by'] = $row->userby;
                $data['user_to'] = $row->userto;
                
                $temp = $row->checkin;
                $data['checkin']=gmdate('F d, Y',$temp);
                
                $temp1 = $row->checkout;
                $data['checkout']=gmdate('F d, Y',$temp1);
                
                $data['resstatus'] = $row->resstatus;
                $data['price'] = $row->price;
                $data['topay'] = $row->topay;
                
                
                //$data['id']  = $this->db->where('id',$user_id)->get('reservation')->row('id');
                //$data['list_id']  = $this->db->where('list_id',$list_id)->get('reservation')->row('list_id');
                //$data['status']  = $this->db->where('status',$status)->get('reservation')->row('status');
                
                $data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
                
                $country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
                
                if(!empty($country_code))
                {
                    $data['currency_code']   = $country_code;
                    $currencycode = $country_code;
                }
                else
                {
                    $check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
                    //print_r($this->db->last_query());exit;
                    $data['currency_code'] = $check_default1;
                    $currencycode = $check_default1;
                }
                
                // $json = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$currencycode."_".$common_currency);
//                 
                // $obj = json_decode($json);
//                 
                // foreach($obj->results as $results)
                // {
                    // $value = $results->val;
                    // //echo $value;
                    // $data['common_currency_code'] = $common_currency;
                    // $data['common_currency_value'] = $value * $row->price;
                // }
//                 
                
                     $data['common_currency_code'] = $common_currency;
            $data['common_currency_value'] = (get_currency_value_lys($currencycode,$common_currency,1)) * $row->price;
                
                
                $image =$this->db->where('list_id',$row->list_id)->get('list_photo')->row('image');
                $empty_image = base_url().'images/no_avatar.jpg';
                $empty_image1 = base_url().'images/no_image.jpg';
                
                
                
                if(!empty($image)){
                    $data['image']   = $image;
                }
                else{
                    $data['image']  = $empty_image1;
                }
                $src = $this->db->where('email',$email)->get('profile_picture')->row('src');
                //print_r($this->db->last_query());exit;
                /*if(!empty($src)){
                    $data['src']   = $src;
                }
                else{
                    $data['src']  = $empty_image;
                }*/
                $query2 = $this->db->where('id',$row->user_id)->get('users');
                $fbid = $query2->row()->fb_id;
                
                $query3 = $this->db->where('email',$email)->get('profile_picture');
                
                if($fbid!=0)
                {
                    $data['profile_pic']  = $query3->row()->src;
                }
                else
                {
                    //srikrishnan
                $data['profile_pic']= $this->Gallery->profilepic($row->user_id, 2);
                 //Ragul   $data['profile_pic']  = $query3->row()->src;
                }
                
                $resize = $this->db->where('list_id',$row->list_id)->get('list_photo')->row('resize');
                if(!empty($resize)){
                    $data['resize']   = $resize;
                }
                else{
                    $data['resize']  = $empty_image1;
                }
                $resize1=$this->db->where('list_id',$row->list_id)->get('list_photo')->row('resize1');
                if(!empty($resize1)){
                    $data['resize1']   = $resize1;
                }
                else{
                    $data['resize1']  = $empty_image1;
                }
                $disc[] = $data;
            }
            
            
            echo json_encode($disc, JSON_UNESCAPED_SLASHES);
        }
        else {
            echo '[{"resstatus":"No Data Found"}]';
        }
    }
    
    public function previoustrips()
    {
        
        $user_id   = $this->input->get('user_id');
        $common_currency = $this->input->get('common_currency');
        $checkout = $this->input->get('checkout');
        
        $status = $this->input->get('status');
		
		if($this->input->get('start'))
		$this->db->limit(5,$this->input->get('start')-1);
		else
		$this->db->limit(5,0);
		
        //$step_count_query = $this->db->distinct()->select('list.*,reservation.id as resid,reservation.list_id, reservation.checkin, reservation.no_quest, reservation.checkout, reservation.status as resstatus, reservation.price as resprice, reservation.topay, reservation.userby, reservation.userto')->join('reservation',"reservation.list_id=list.id")->where('reservation.userby',$user_id)->where("reservation.status != 1 AND reservation.status != 3")->order_by('list.id', 'desc')->get('list');
        //if($step_count_query->num_rows() !=0 )
        if($result = $this->db->from('list')->distinct()->select('list.*,reservation.id as resid,reservation.list_id, reservation.checkin, reservation.no_quest, reservation.checkout, reservation.status as resstatus, reservation.price as resprice, reservation.topay, reservation.userby, reservation.userto')->join('reservation',"reservation.list_id=list.id")->where('reservation.userby',$user_id)->where("reservation.status != 1 AND reservation.status != 3 AND reservation.status != 7")->order_by('reservation.book_date', 'desc')->get()->result())
        {
            //foreach($step_count_query->result() as $row)
            foreach($result as $row)
            {
                $id = $row->resid;
                $user_id = $row->user_id;
                $email=$this->db->where('id',$user_id)->get('profiles')->row('email');
                $data['no_quest'] = $row->no_quest;
                $data['id']  = $row->resid;
                $data['user_id'] = $row->user_id;
                $data['title']=$row->title;
                $data['price']=$row->price;
                $data['country'] = $row->country;
                $data['city']  = $row->city;
                $data['address']  = $row->address;
                $data['room_type'] = $row->room_type;
                $currency  = $row->currency;
                $data['list_id'] = $row->list_id;
                $data['user_by'] = $row->userby;
                $data['user_to'] = $row->userto;
                $temp = $row->checkin;
                $data['checkin']=gmdate('F d, Y',$temp);
                
                $temp1 = $row->checkout;
                $data['checkout']=gmdate('F d, Y',$temp1);
                
                $data['resstatus'] = $row->resstatus;
                $data['price'] = $row->price;
                $data['topay'] = $row->topay;
                
                
                //$data['id']  = $this->db->where('id',$user_id)->get('reservation')->row('id');
                //$data['list_id']  = $this->db->where('list_id',$list_id)->get('reservation')->row('list_id');
                //$data['status']  = $this->db->where('status',$status)->get('reservation')->row('status');
                
                $data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
                $country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
                if(!empty($country_code)){
                    $data['currency_code']   = $country_code;
                    $currencycode = $country_code;
                }
                else{
                    $check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
                    //print_r($this->db->last_query());exit;
                    $data['currency_code']  = $check_default1;
                    $currencycode = $check_default1;
                }
                
                // $json = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$currencycode."_".$common_currency);
//                 
                // $obj = json_decode($json);
//                 
                // foreach($obj->results as $results)
                // {
                    // $value = $results->val;
                    // //echo $value;
                    // $data['common_currency_code'] = $common_currency;
                    // $data['common_currency_value'] = $value * $row->price;
                // }
                     $data['common_currency_code'] = $common_currency;
            $data['common_currency_value'] = (get_currency_value_lys($currencycode,$common_currency,1)) * $row->price;
                
                $image =$this->db->where('list_id',$row->list_id)->get('list_photo')->row('image');
                $empty_image = base_url().'images/no_avatar.jpg';
                $empty_image1 = base_url().'images/no_image.jpg';
                
                
                
                if(!empty($image)){
                    $data['image']   = $image;
                }
                else{
                    $data['image']  = $empty_image1;
                }
                $src = $this->db->where('email',$email)->get('profile_picture')->row('src');
                //print_r($this->db->last_query());exit;
                /*if(!empty($src)){
                    $data['src']   = $src;
                }
                else{
                    $data['src']  = $empty_image;
                }*/
                $query2 = $this->db->where('id',$row->user_id)->get('users');
                $fbid = $query2->row()->fb_id;
                
                $query3 = $this->db->where('email',$email)->get('profile_picture');
                
                if($fbid!=0)
                {
                    $data['profile_pic']  = $query3->row()->src;
                }
                else
                {
                    $data['profile_pic']= $this->Gallery->profilepic($row->user_id, 2);
                    //$data['profile_pic']  = $query3->row()->src;
                }
                $resize = $this->db->where('list_id',$row->list_id)->get('list_photo')->row('resize');
                if(!empty($resize)){
                    $data['resize']   = $resize;
                }
                else{
                    $data['resize']  = $empty_image1;
                }
                $resize1=$this->db->where('list_id',$row->list_id)->get('list_photo')->row('resize1');
                if(!empty($resize1)){
                    $data['resize1']   = $resize1;
                }
                else{
                    $data['resize1']  = $empty_image1;
                }
                $disc[] = $data;
            }
            
            
            echo json_encode($disc, JSON_UNESCAPED_SLASHES);
        }
        else {
            echo '[{"resstatus":"No Data Found"}]';
        }
    }
    
    public function myreservations(){
    	$this->load->model('Trips_model');
        
        $user_id   = $this->input->get('user_id');
        
        $common_currency = $this->input->get('common_currency');
        
        
        if($this->input->get('start'))
		$this->db->limit(5,$this->input->get('start')-1);
		else
		$this->db->limit(5,0);
        
        
        //$step_count_query = $this->db->distinct()->select('list.*,reservation.id as resid,reservation.list_id, reservation.checkin, reservation.no_quest, reservation.checkout, reservation.status as resstatus, reservation.price as resprice, reservation.topay, reservation.userby, reservation.userto')->join('reservation',"reservation.list_id=list.id")->where("reservation.userto",$user_id)->order_by('list.id', 'desc')->get('list');
        //echo $this->db->last_query();
        //exit;
        //if($step_count_query->num_rows() !=0 )
        if($result = $this->db->from('list')->distinct()->select('list.*,reservation.id as resid,reservation.list_id, reservation.checkin, reservation.no_quest, reservation.checkout, reservation.status as resstatus, reservation.price as resprice, reservation.topay, reservation.userby, reservation.userto')->join('reservation',"reservation.list_id=list.id")->where("reservation.userto",$user_id)->order_by('reservation.id', 'desc')->get()->result())
        {
            //foreach($step_count_query->result() as $row)
            foreach($result as $row)
            {
                $id = $row->resid;
                $user_id = $row->user_id;
                $email=$this->db->where('id',$user_id)->get('profiles')->row('email');
                $data['no_quest'] = $row->no_quest;
                $data['id']  = $row->resid;
                $data['user_id'] = $row->user_id;
                $data['title']=$row->title;
                $data['price']=$row->price;
                $data['country'] = $row->country;
                $data['city']  = $row->city;
                $data['address']  = $row->address;
                $data['room_type'] = $row->room_type;
                $currency  = $row->currency;
                $data['list_id'] = $row->list_id;
                $data['user_by'] = $row->userby;
                $data['user_to'] = $row->userto;
                $temp = $row->checkin;
                $data['checkin']=gmdate('F d, Y',$temp);
				
				$conditions_starrev = array('list_id' => $row->id);
$result_rev			= $this->Trips_model->get_review($conditions_starrev);
$overall_review_count=$result_rev->num_rows();
$conditions_star    			    = array('list_id' => $row->id);
$data1['stars']			        	= $this->Trips_model->get_review_sum($conditions_star)->row();
if($overall_review_count > 0)
{

$accuracy      = (($data1['stars']->accuracy *2) * 10) / $overall_review_count;
$cleanliness   = (($data1['stars']->cleanliness *2) * 10) / $overall_review_count;
$communication = (($data1['stars']->communication *2) * 10) / $overall_review_count;
$checkin       = (($data1['stars']->checkin *2) * 10) / $overall_review_count;
$location      = (($data1['stars']->location *2) * 10) / $overall_review_count;
$value         = (($data1['stars']->value *2) * 10) / $overall_review_count;
$overall       = ($accuracy + $cleanliness + $communication + $checkin + $location + $value) / 6;
	
}else{
$overall = 0 ;	
}
        $data['overallreview']="$overall";
                
                $temp1 = $row->checkout;
                $data['checkout']=gmdate('F d, Y',$temp1);
                
                $data['resstatus'] = $row->resstatus;
                $data['price'] = $row->price;
                $data['topay'] = $row->topay;
                
                
                //$data['id']  = $this->db->where('id',$user_id)->get('reservation')->row('id');
                //$data['list_id']  = $this->db->where('list_id',$list_id)->get('reservation')->row('list_id');
                //$data['status']  = $this->db->where('status',$status)->get('reservation')->row('status');
                
                $data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
                
                $country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
                
                if(!empty($country_code))
                {
                    $data['currency_code']   = $country_code;
                    $currencycode = $country_code;
                }
                else
                {
                    $check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
                    //print_r($this->db->last_query());exit;
                    $data['currency_code'] = $check_default1;
                    $currencycode = $check_default1;
                }
                
                // $json = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=".$currencycode."_".$common_currency);
//                 
                // $obj = json_decode($json);
//                 
                // foreach($obj->results as $results)
                // {
                    // $value = $results->val;
                    // //echo $value;
                    // $data['common_currency_code'] = $common_currency;
                    // $data['common_currency_value'] = $value * $row->price;
                // }
                     $data['common_currency_code'] = $common_currency;
            $data['common_currency_value'] = (get_currency_value_lys($currencycode,$common_currency,1)) * $row->price;
                
                $image =$this->db->where('list_id',$row->list_id)->get('list_photo')->row('image');
                $empty_image = base_url().'images/no_avatar.jpg';
                $empty_image1 = base_url().'images/no_image.jpg';
                
                
                
                if(!empty($image)){
                    $data['image']   = $image;
                }
                else{
                    $data['image']  = $empty_image1;
                }
                $src = $this->db->where('email',$email)->get('profile_picture')->row('src');
                //print_r($this->db->last_query());exit;
                /*if(!empty($src)){
                    $data['src']   = $src;
                }
                else{
                    $data['src']  = $empty_image;
                }*/
                
                $query2 = $this->db->where('id',$row->user_id)->get('users');
                $fbid = $query2->row()->fb_id;
                
                $query3 = $this->db->where('email',$email)->get('profile_picture');
                
                if($fbid!=0)
                {
                    $data['profile_pic']  = $query3->row()->src;
                }
                else
                {
                       //srikrishnan
                $data['profile_pic']= $this->Gallery->profilepic($row->user_id, 2);
                 // $data['profile_pic']  = $query3->row()->src;
                }
                
                $resize = $this->db->where('list_id',$row->list_id)->get('list_photo')->row('resize');
                if(!empty($resize)){
                    $data['resize']   = $resize;
                }
                else{
                    $data['resize']  = $empty_image1;
                }
                $resize1=$this->db->where('list_id',$row->list_id)->get('list_photo')->row('resize1');
                if(!empty($resize1)){
                    $data['resize1']   = $resize1;
                }
                else{
                    $data['resize1']  = $empty_image1;
                }
                $disc[] = $data;
            }
            
            
            echo json_encode($disc, JSON_UNESCAPED_SLASHES);
        }
        else {
            echo '[{"resstatus":"No Data Found"}]';
        }
    }


public function trips(){
	
	$user_id   = $this->input->get('user_id');
	$reservation_id =$this->input->get('resid');
    $step_count_query = $this->db->distinct()->select('list.*,reservation.id,reservation.list_id, reservation.checkin, reservation.guest_count, reservation.checkout, reservation.status, reservation.price as reserv_price')->join('reservation',"reservation.list_id=list.id")->where("reservation.userby",$user_id)->order_by('desc')->get('list');
	
	if($step_count_query->num_rows() !=0 )
	{
	foreach($step_count_query->result() as $row){
		$id = $row->id;
		$user_id = $row->user_id;
		$email=$this->db->where('id',$user_id)->get('profiles')->row('email');	
        		
		$data['id']  = $row->id;
		$data['user_id'] = $row->user_id;
		$data['title']=$row->title;
		$data['price']=$row->price;
		$data['country'] = $row->country;
		$data['city']  = $row->city;
		$data['address']  = $row->address;
		$data['room_type'] = $row->room_type;
		$currency  = $row->currency;
		$data['list_id'] = $row->list_id;
		//$data['checkin']  = gmdate('F d, Y',$start_date);
		//$data['checkin']  = $row->checkin;
		//$end_date   = $step_count_query->row()->checkout;
		//$data['checkout']  = gmdate('F d, Y',$end_date);
		$temp = $row->checkin;
		$data['checkin']=gmdate('F d, Y',$temp);

		$temp1 = $row->checkout;
		$data['checkout']=gmdate('F d, Y',$temp1);
		$data['guest_count'] = $row->guest_count;
		$data['status'] = $row->status;
		$data['reserv_price'] = $row->reserv_price;
		
		
		
		
				
				$data['id']  = $this->db->where('id',$user_id)->get('reservation')->row('id');
				//$data['list_id']  = $this->db->where('list_id',$list_id)->get('reservation')->row('list_id');
				//$data['status']  = $this->db->where('status',$status)->get('reservation')->row('status');
				
				
				
				
				
				$data['currency_symbol']  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_symbol');
				$country_symbol  = $this->db->where('currency_code',$currency)->get('currency')->row('country_symbol');
				$country_code  = $this->db->where('currency_code',$currency)->get('currency')->row('currency_code');
				if(!empty($country_symbol)){
					$data['country_symbol']   = $country_symbol;
				}
                else{
	                $check_default  = $this->db->where('default = 1')->get('currency')->row('currency_symbol');
					//print_r($this->db->last_query());exit;
	                 $data['country_symbol']  = $check_default;
                }
				if(!empty($country_code)){
					$data['currency_code']   = $country_code;
				}
                else{
                	$check_default1  = $this->db->where('default = 1')->get('currency')->row('currency_code');
					//print_r($this->db->last_query());exit;
	                 $data['currency_code']  = $check_default1;
                }
		
		
		$image =$this->db->where('id',$data['id'])->get('list_photo')->row('image');
		$empty_image = base_url().'images/no_avatar.jpg';
		$empty_image1 = base_url().'images/no_image.jpg';
		
		
		
		if(!empty($image)){
					$data['image']   = $image;
				}
                else{
	                 $data['image']  = $empty_image1;
                }
		$src = $this->db->where('email',$email)->get('profile_picture')->row('src');
		//print_r($this->db->last_query());exit;
		if(!empty($src)){
					$data['src']   = $src;
				}
                else{
	                $data['src']  = $empty_image;
                }
            $data['profile_pic'] = $this->Gallery->profilepic($row->user_id, 2);
		$resize = $this->db->where('list_id',$data['id'])->get('list_photo')->row('resize');
		if(!empty($resize)){
					$data['resize']   = $resize;
				}
                else{
	                 $data['resize']  = $empty_image1;
                }
		$resize1=$this->db->where('list_id',$data['id'])->get('list_photo')->row('resize1');
		if(!empty($resize1)){
					$data['resize1']   = $resize1;
				}
                else{
	                 $data['resize1']  = $empty_image1;
                }		
		$disc[] = $data;
		}
	
	
	echo json_encode($disc, JSON_UNESCAPED_SLASHES);
	}
else {
	echo '[{"status":"No Data Fund"}]';
}
}

public function checkin()
	{
		
		$reservation_id=$this->input->get('id');
		$query = $this->db->where('id',$reservation_id)->get('reservation');
		

		//$query1 = $this->db->where('id',$roomid)->get('price');
		if($query->num_rows()!=0)
	{

                    $data['status']  = $this->input->get('status');
					$this->db->where('id',$reservation_id)->update('reservation',$data);
					
					$admin_email 						= $this->dx_auth->get_site_sadmin();
	 	$admin_name  						= $this->dx_auth->get_site_title();
	
		$conditions    				= array('reservation.id' => $reservation_id);
		$row           				= $this->Trips_model->get_reservation($conditions)->row();
		$transaction_id = $row->transaction_id;
		$checkin = $row->checkin;
		$checkout = $row->checkout;
		$price =  $row->price;
		$currency =  $row->currency;
		
		//print_r($currency);exit;
		/*$query1     						 = $this->Users_model->get_user_by_id($row->userby);
		$traveler_name 				= $query1->row()->username;
		$traveler_email 			= $query1->row()->email;*/
		
		$query1 = $this->db->where('id',$row->userby)->get('users');
		$traveler_name 				= $query1->row()->username;
		$traveler_email 			= $query1->row()->email;
		
		//$query2     						 = $this->Users_model->get_user_by_id($row->userto);
		$query2 = $this->db->where('id',$row->userto)->get('users');
		$host_name 								= $query2->row()->username;
		$host_email 							= $query2->row()->email;
		
		$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
		
		$traveler          = $this->Common_model->getTableData('profiles', array('id' => $row->userby))->row();
		$host             	= $this->Common_model->getTableData('profiles', array('id' => $row->userto))->row();
		
		$data_acceptpay['reservation_id'] = $reservation_id;
		$data_acceptpay['amount'] = $price;
		$data_acceptpay['currency'] = $currency;
		$data_acceptpay['created'] = time();
		$data_acceptpay['transaction_id'] = $transaction_id;
		
			//print_r($data_acceptpay['transaction_id']);exit;	
		$this->db->insert('accept_pay',$data_acceptpay);
		
		//Traveller Info
		if(!empty($traveler ))
		{
		$FnameT												=	$traveler->Fname;
		$LnameT												= $traveler->Lname;
		$liveT													= $traveler->live;
		$phnumT 											= $traveler->phnum;
		}
		else
		{
		$FnameT												=	'';
		$LnameT												= '';
		$liveT													= '';
		$phnumT 											= '';
		}
		
		//Host Info
		if(!empty($host ))
		{
		$FnameH												=	$host->Fname;
		$LnameH												= $host->Lname;
		$liveH													= $host->live;
		$phnumH 											= $host->phnum;
		}
		else
		{
		$FnameH												=	'';
		$LnameH												= '';
		$liveH													= '';
		$phnumH 											= '';
		}
	
	           $list_data = $this->db->where('id',$row->list_id)->get('list')->row();
			
			 //Send Message Notification To Traveler
			$insertData = array(
				'list_id'         => $row->list_id,
				'reservation_id'  => $reservation_id,
				'userby'          => $row->userby,
				'userto'          => $row->userto,
				'message'         => "$traveler_name checkin to $list_title",
				'created'         => local_to_gmt(),
				'message_type'    => 3
				);
				
			$this->Message_model->sentMessage($insertData, 1);
			
			$updateData['is_respond'] = 1;
			$updateKey['reservation_id'] = $reservation_id;
			$updateKey['userto'] = $row->userto;
			
			$this->Message_model->updateMessage($updateKey,$updateData);

   
			$updateKey      		  = array('id' => $reservation_id);
			$updateData               = array();
			$updateData['status ']    = 7;
			$updateData['is_payed']   = 0;    
			$this->Trips_model->update_reservation($updateKey,$updateData);
			
			if($list_data->optional_address == '')
			{
				$optional_address = ' -';
			}
			else {
				$optional_address = $list_data->address;
		    }
			if($list_data->state == '')
			{
				$state = ' -';
			}
			else {
				$state = $list_data->state;
		    }
			
			//Send Mail To Host
		$email_name = 'checkin_host';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name), "{guest_name}" => ucfirst($traveler_name), "{currency}" => $currency, "{price}" => $price, "{checkin}" => $checkin, "{checkout}" => $checkout);
		$this->Email_model->sendMail($host_email, $admin_email, ucfirst($admin_name),$email_name,$splVars);
		
		//Send Mail To Administrator
		$email_name = 'checkin_admin';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{traveler_email}" => $traveler_email, "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name), "{host_email}" => $host_email, "{guest_name}" => ucfirst($traveler_name), "{currency}" => $currency, "{price}" => $price, "{checkin}" => $checkin, "{checkout}" => $checkout);
		$this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
				
		//Send Mail To Administrator
		$email_name = 'checkin_traveller';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name), "{currency}" => $currency, "{price}" => $price, "{checkin}" => $checkin, "{checkout}" => $checkout);
		$this->Email_model->sendMail($traveler_email,$host_email,ucfirst($admin_name),$email_name,$splVars);
	
					
					echo '[{"status":"Updated Successfully"}]';
					
	}
else
{
	echo '[{"status":"Invalid reservation id"}]';
}

		
	}

public function checkout()
	{
		
		$reservation_id=$this->input->get('id');
		$query = $this->db->where('id',$reservation_id)->get('reservation');
		

		//$query1 = $this->db->where('id',$roomid)->get('price');
		if($query->num_rows()!=0)
	{

                    $data['status']  = $this->input->get('status');
					$this->db->where('id',$reservation_id)->update('reservation',$data);
					
					$admin_email 						= $this->dx_auth->get_site_sadmin();
	 	$admin_name  						= $this->dx_auth->get_site_title();
	
		$conditions    				= array('reservation.id' => $reservation_id);
		$row           				= $this->Trips_model->get_reservation($conditions)->row();
		$transaction_id = $row->transaction_id;
		$checkin = $row->checkin;
		$checkout = $row->checkout;
		$price =  $row->price;
		$currency =  $row->currency;
		
		//print_r($currency);exit;
		/*$query1     						 = $this->Users_model->get_user_by_id($row->userby);
		$traveler_name 				= $query1->row()->username;
		$traveler_email 			= $query1->row()->email;*/
		
		$query1 = $this->db->where('id',$row->userby)->get('users');
		$traveler_name 				= $query1->row()->username;
		$traveler_email 			= $query1->row()->email;
		
		//$query2     						 = $this->Users_model->get_user_by_id($row->userto);
		$query2 = $this->db->where('id',$row->userto)->get('users');
		$host_name 								= $query2->row()->username;
		$host_email 							= $query2->row()->email;
		
		$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
		
		$traveler          = $this->Common_model->getTableData('profiles', array('id' => $row->userby))->row();
		$host             	= $this->Common_model->getTableData('profiles', array('id' => $row->userto))->row();
		
		$data_acceptpay['reservation_id'] = $reservation_id;
		$data_acceptpay['amount'] = $price;
		$data_acceptpay['currency'] = $currency;
		$data_acceptpay['created'] = time();
		$data_acceptpay['transaction_id'] = $transaction_id;
		
			//print_r($data_acceptpay['transaction_id']);exit;	
		$this->db->insert('accept_pay',$data_acceptpay);
		
		//Traveller Info
		if(!empty($traveler ))
		{
		$FnameT												=	$traveler->Fname;
		$LnameT												= $traveler->Lname;
		$liveT													= $traveler->live;
		$phnumT 											= $traveler->phnum;
		}
		else
		{
		$FnameT												=	'';
		$LnameT												= '';
		$liveT													= '';
		$phnumT 											= '';
		}
		
		//Host Info
		if(!empty($host ))
		{
		$FnameH												=	$host->Fname;
		$LnameH												= $host->Lname;
		$liveH													= $host->live;
		$phnumH 											= $host->phnum;
		}
		else
		{
		$FnameH												=	'';
		$LnameH												= '';
		$liveH													= '';
		$phnumH 											= '';
		}
	
	           $list_data = $this->db->where('id',$row->list_id)->get('list')->row();
			
			 //Send Message Notification To Traveler
			$insertData = array(
				'list_id'         => $row->list_id,
				'reservation_id'  => $reservation_id,
				'userby'          => $row->userby,
				'userto'          => $row->userto,
				'message'         => "$traveler_name is checkedout from $list_title",
				'created'         => local_to_gmt(),
				'message_type'    => 4
				);
				
			$this->Message_model->sentMessage($insertData, 1);
			
			$updateData['is_respond'] = 1;
			$updateKey['reservation_id'] = $reservation_id;
			$updateKey['userto'] = $row->userto;
			
			$this->Message_model->updateMessage($updateKey,$updateData);

   
			$updateKey      		  = array('id' => $reservation_id);
			$updateData               = array();
			$updateData['status ']    = 8;
			$updateData['is_payed']   = 0;    
			$this->Trips_model->update_reservation($updateKey,$updateData);
			
			if($list_data->optional_address == '')
			{
				$optional_address = ' -';
			}
			else {
				$optional_address = $list_data->address;
		    }
			if($list_data->state == '')
			{
				$state = ' -';
			}
			else {
				$state = $list_data->state;
		    }
			
			//Send Mail To Host
		$email_name = 'checkout_host';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
		$this->Email_model->sendMail($host_email, $admin_email, ucfirst($admin_name),$email_name,$splVars);
		
		//Send Mail To Administrator
		$email_name = 'checkout_admin';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title);
		$this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);
				
		//Send Mail To Administrator
		$email_name = 'checkout_traveller';
		$splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title);
		$this->Email_model->sendMail($traveler_email,$host_email,ucfirst($admin_name),$email_name,$splVars);
	
					
					echo '[{"status":"Updated Successfully"}]';
					
	}
else
{
	echo '[{"status":"Invalid reservation id"}]';
}

		
	}

public function host_review()
	{
		
		$reservation_id=$this->input->get('id');
		$review = $this->input->get('review');
		$feedback = $this->input->get('feedback');
		$clean_value = $this->input->get('cleanvalue');
		$comm_value = $this->input->get('commvalue');
		$rules_value = $this->input->get('rulesvalue');
		$query = $this->db->where('id',$reservation_id)->get('reservation');
		

		//$query1 = $this->db->where('id',$roomid)->get('price');
		if($query->num_rows()!=0)
	{

                    //$data['status']  = $this->input->get('status');
					//$this->db->where('id',$reservation_id)->update('reservation',$data);
					
					$admin_email 						= $this->dx_auth->get_site_sadmin();
	 	$admin_name  						= $this->dx_auth->get_site_title();
	
		$conditions    				= array('reservation.id' => $reservation_id);
		$row           				= $this->Trips_model->get_reservation($conditions)->row();
		$transaction_id = $row->transaction_id;
		$checkin = $row->checkin;
		$checkout = $row->checkout;
		$price =  $row->price;
		$currency =  $row->currency;
		
		//print_r($currency);exit;
		/*$query1     						 = $this->Users_model->get_user_by_id($row->userby);
		$traveler_name 				= $query1->row()->username;
		$traveler_email 			= $query1->row()->email;*/
		
		$query1 = $this->db->where('id',$row->userby)->get('users');
		$traveler_name 				= $query1->row()->username;
		$traveler_email 			= $query1->row()->email;
		
		//$query2     						 = $this->Users_model->get_user_by_id($row->userto);
		$query2 = $this->db->where('id',$row->userto)->get('users');
		$host_name 								= $query2->row()->username;
		$host_email 							= $query2->row()->email;
		
		$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
		
		$traveler          = $this->Common_model->getTableData('profiles', array('id' => $row->userby))->row();
		$host             	= $this->Common_model->getTableData('profiles', array('id' => $row->userto))->row();
	
		
		//Traveller Info
		if(!empty($traveler ))
		{
		$FnameT												=	$traveler->Fname;
		$LnameT												= $traveler->Lname;
		$liveT													= $traveler->live;
		$phnumT 											= $traveler->phnum;
		}
		else
		{
		$FnameT												=	'';
		$LnameT												= '';
		$liveT													= '';
		$phnumT 											= '';
		}
		
		//Host Info
		if(!empty($host ))
		{
		$FnameH												=	$host->Fname;
		$LnameH												= $host->Lname;
		$liveH													= $host->live;
		$phnumH 											= $host->phnum;
		}
		else
		{
		$FnameH												=	'';
		$LnameH												= '';
		$liveH													= '';
		$phnumH 											= '';
		}
	
	           $list_data = $this->db->where('id',$row->list_id)->get('list')->row();
			
			 //Send Message Notification To Traveler
			$insertData = array(
				'list_id'         => $row->list_id,
				'reservation_id'  => $reservation_id,
				'userby'          => $row->userto,
				'userto'          => $row->userby,
				'message'         => "$host_name wants a review from you",
				'created'         => local_to_gmt(),
				'message_type'    => 5
				);
		
				$datarev['userby'] = $row->userto;
				$datarev['userto'] = $row->userby;
				$datarev['reservation_id'] = $reservation_id;
				$datarev['list_id'] = $row->list_id;
				$datarev['review'] = $review;
				$datarev['feedback'] = $feedback;
				$datarev['cleanliness'] = $clean_value;
				$datarev['communication'] = $comm_value;
				$datarev['house_rules'] = $rules_value;
				$datarev['accuracy'] = "0";
				$datarev['checkin'] = "0";
				$datarev['location'] = "0";
				$datarev['value'] = "0";
				$datarev['created'] = local_to_gmt();
				
				$this->db->insert('reviews', $datarev);
	
			$this->Message_model->sentMessage($insertData, 1);
			
			$updateData['is_respond'] = 1;
			$updateKey['reservation_id'] = $reservation_id;
			$updateKey['userto'] = $row->userby;
			
			$this->Message_model->updateMessage($updateKey,$updateData);

   
			$updateKey      		  = array('id' => $reservation_id);
			$updateData               = array();
			$updateData['status ']    = 9;
			$updateData['is_payed']   = 0;    
			$this->Trips_model->update_reservation($updateKey,$updateData);
		

			echo '[{"status":"Updated Successfully"}]';
					
	}
else
{
	echo '[{"status":"Invalid reservation id"}]';
}

		
	}
public function guest_review()
	{
		
		$reservation_id=$this->input->get('id');
		$review = $this->input->get('review');
		$feedback = $this->input->get('feedback');
		$clean_value = $this->input->get('cleanvalue');
		$comm_value = $this->input->get('commvalue');
		$accur_value = $this->input->get('accurvalue');
		$checkin_value = $this->input->get('checkinvalue');
		$location_value = $this->input->get('locationvalue');
		$value_value = $this->input->get('value');
		$query = $this->db->where('id',$reservation_id)->get('reservation');
		

		//$query1 = $this->db->where('id',$roomid)->get('price');
		if($query->num_rows()!=0)
	{

                    //$data['status']  = $this->input->get('status');
					//$this->db->where('id',$reservation_id)->update('reservation',$data);
					
					$admin_email 						= $this->dx_auth->get_site_sadmin();
	 	$admin_name  						= $this->dx_auth->get_site_title();
	
		$conditions    				= array('reservation.id' => $reservation_id);
		$row           				= $this->Trips_model->get_reservation($conditions)->row();
		$transaction_id = $row->transaction_id;
		$checkin = $row->checkin;
		$checkout = $row->checkout;
		$price =  $row->price;
		$currency =  $row->currency;
		
		//print_r($currency);exit;
		/*$query1     						 = $this->Users_model->get_user_by_id($row->userby);
		$traveler_name 				= $query1->row()->username;
		$traveler_email 			= $query1->row()->email;*/
		
		$query1 = $this->db->where('id',$row->userby)->get('users');
		$traveler_name 				= $query1->row()->username;
		$traveler_email 			= $query1->row()->email;
		
		//$query2     						 = $this->Users_model->get_user_by_id($row->userto);
		$query2 = $this->db->where('id',$row->userto)->get('users');
		$host_name 								= $query2->row()->username;
		$host_email 							= $query2->row()->email;
		
		$list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;
		
		$traveler          = $this->Common_model->getTableData('profiles', array('id' => $row->userby))->row();
		$host             	= $this->Common_model->getTableData('profiles', array('id' => $row->userto))->row();
	
		
		//Traveller Info
		if(!empty($traveler ))
		{
		$FnameT												=	$traveler->Fname;
		$LnameT												= $traveler->Lname;
		$liveT													= $traveler->live;
		$phnumT 											= $traveler->phnum;
		}
		else
		{
		$FnameT												=	'';
		$LnameT												= '';
		$liveT													= '';
		$phnumT 											= '';
		}
		
		//Host Info
		if(!empty($host ))
		{
		$FnameH												=	$host->Fname;
		$LnameH												= $host->Lname;
		$liveH													= $host->live;
		$phnumH 											= $host->phnum;
		}
		else
		{
		$FnameH												=	'';
		$LnameH												= '';
		$liveH													= '';
		$phnumH 											= '';
		}
	
	           $list_data = $this->db->where('id',$row->list_id)->get('list')->row();
			
			 //Send Message Notification To Traveler
			$insertData = array(
				'list_id'         => $row->list_id,
				'reservation_id'  => $reservation_id,
				'userby'          => $row->userby,
				'userto'          => $row->userto,
				'message'         => "$traveler_name gives review to you",
				'created'         => local_to_gmt(),
				'message_type'    => 3
				);
		
				$datarev['userby'] = $row->userby;
				$datarev['userto'] = $row->userto;
				$datarev['reservation_id'] = $reservation_id;
				$datarev['list_id'] = $row->list_id;
				$datarev['review'] = $review;
				$datarev['feedback'] = $feedback;
				$datarev['cleanliness'] = $clean_value;
				$datarev['communication'] = $comm_value;
				$datarev['house_rules'] = "0";
				$datarev['accuracy'] = $accur_value;
				$datarev['checkin'] = $checkin_value;
				$datarev['location'] = $location_value;
				$datarev['value'] = $value_value;
				$datarev['created'] = local_to_gmt();
				
				$this->db->insert('reviews', $datarev);
	
			$this->Message_model->sentMessage($insertData, 1);
			
			$updateData['is_respond'] = 1;
			$updateKey['reservation_id'] = $reservation_id;
			$updateKey['userto'] = $row->userby;
			
			$this->Message_model->updateMessage($updateKey,$updateData);

   
			$updateKey      		  = array('id' => $reservation_id);
			$updateData               = array();
			$updateData['status ']    = 10;
			$updateData['is_payed']   = 0;    
			$this->Trips_model->update_reservation($updateKey,$updateData);
		

			echo '[{"status":"Updated Successfully"}]';
					
	}
else
{
	echo '[{"status":"Invalid reservation id"}]';
}

		
	}
public function cancel()
	{
		
		$reservid=$this->input->get('id');
		$query = $this->db->where('id',$reservid)->get('reservation');

		//$query1 = $this->db->where('id',$roomid)->get('price');
	if($query->num_rows()!=0)
	{

					// $data['checkin'] = "0";
					// $data['checkout'] = "0";
                    $data['status']  = $this->input->get('status');
					$this->db->where('id',$reservid)->update('reservation',$data);
					
					echo '[{"status":"Updated Successfully"}]';
					
	}
else
{
	echo '[{"status":"Invalid reservation id"}]';
}
	}

public function updateimage()
{
        
        $roomid = $this->input->get('roomid');
        
        $data['id'] = $this->input->get('imageid1');
        $data['image'] = $this->input->get('imageurl1');
        $data['resize'] = $this->input->get('resizeurl1');
        $data['resize1'] = $this->input->get('resize1url1');
        
        
        $data1['id'] = $this->input->get('imageid2');
        $data1['image'] = $this->input->get('imageurl2');
        $data1['resize'] = $this->input->get('resizeurl2');
        $data1['resize1'] = $this->input->get('resize1url2');
        
        if(!empty($data['id']))
           {
               if(!empty($data['image']))
               {
                   if(!empty($data['resize']))
                   {
                       if(!empty($data['resize1']))
                       {
                           if(!empty($data1['id']))
                           {
                               if(!empty($data1['image']))
                               {
                                   if(!empty($data1['resize']))
                                   {
                                       if(!empty($data1['resize1']))
                                       {
                                           $this->db->where('list_id',$roomid)->where('id',$data['id'])->update('list_photo',$data);
                                           $this->db->where('list_id',$roomid)->where('id',$data1['id'])->update('list_photo',$data1);
            
                                           echo '[{"status":"images updated successfully"}]';
                                       }
                                       else
                                       {
                                           echo '[{"status":"some data are missing"}]';
                                       }
                                   }
                                   else
                                   {
                                       echo '[{"status":"some data are missing"}]';
                                   }
                               }
                               else
                               {
                                   echo '[{"status":"some data are missing"}]';
                               }
                           }
                           else
                           {
                               echo '[{"status":"some data are missing"}]';
                           }
                       }
                       else
                       {
                           echo '[{"status":"some data are missing"}]';
                       }
                   }
                   else
                   {
                       echo '[{"status":"some data are missing"}]';
                   }
               }
               else
               {
                   echo '[{"status":"some data are missing"}]';
               }
            }

        else
        {
            echo '[{"status":"some data are missing"}]';
        }
        
        
}
public function dis_amnities()
{
	$roomid = $this->input->get('roomid');
	// $roomid="1";
	$amnities = $this->db->query('select * from amnities');
	foreach ($amnities->result() as $row) {
		$data['id'] = $row->id;
		$data['name'] = $row->name;
		// $data['description'] = $row->description;
		$amenities[] = $data;
	}
	$roomamt=$this->db->where('id',$roomid)->get('list');
	foreach ($roomamt->result() as $row) {
		$roomamt=$row->amenities;
		$amenities['roomamt']=explode(",",$roomamt);
	}
	echo json_encode($amenities);
}	

}
    
?>
