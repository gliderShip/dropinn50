<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller {
function  post()
	{
		parent::__Construct();
		$this->load->database();
		$this->load->model("post_model");
		$this->load->model("users_model");
		$this->load->model("common_model");
		$this->load->model("email_model");
		
		//adding helper file
		$this->load->library('form_validation');
		$this->load->helper('url');
		//$this->load->library('Facebook_Lib');
		
		$params = array('appId'  => $this->config->item('appId'),'secret' => $this->config->item('secret'));
		$this->load->library('facebook',$params);		
		$this->load->library('twconnect');

	}
	public function index()
	{		
		$data['message_element']     = "view_home"; 
		$this->load->view('template',$data);
			
	}
	
    public function forgotpassword()
	{
		$data['message_element']     = "view_forgotpassword"; 
		$this->load->view('template',$data);		
		//$this->load->view('view_signin');
	}

	
	



	public function Email_Share()
{	    
	    if($this->input->get("user_id") && $this->input->get('id'))
       {
       	    
            extract($this->input->get());
			$this->load->model('post_model');
			$this->load->model("common_helper");

			 $post_id = $this->input->get('id');
						
				 $user_id = $this->input->get('user_id');
				
				 $title = $this->input->get('title');
				
           $user_detail = getSocialstatus($user_id);
		  //print_r($user_detail);exit;
	    if($user_detail['id'] != "")
           {
          
               $twitter_status = 'success';
					 
				   $this->post_model->twitterSharenew($post_id,$user_id);
				   

				  	$data['detail']= $this->post_model->detailview($user_id,$post_id);
			
				
	             $this->load->view('detail_view',$data);

	
				
				 
		   }
				 else
           {
               $twitter_status = 'failure';
               
           }
	   }
              
	    
}
	
	public function emailShare()
   {
   	 extract($this->input->get());
	 
	 $this->load->model('users_model');
	 
	 $this->load->model('email_model');
	 
	 $where['id'] = $user_id;
	 $URL['url'] = $url;
	 $email = $this->input->get('email');
	 $URL = $this->input->get('url');
	
	 $result = $this->users_model->get_data1('post',$where);
	 
	// $data['id']= $result->row()->id;
	// $data['resize1']= $result->row()->resize1;
	// $final = $data;
	 $userdata = $this->users_model->get_data2('users',$where);
	 
	 $from_email = $userdata->row()->email;
	 $from_name = $userdata->row()->username;
	 
	 
	 $result1 = $this->users_model->get_data('users',$where);
	 
	 /*if($result1->num_rows() != 0)
	 {
	    $to_email = $result1->row()->email;	
	 }
	 else {
		 echo '[{"status":"no data"}]';exit;
	 }*/
	 $to_email = $email;
	 if($from_email!= '')
		{
			$emailname = explode('@', $from_email);
			$users_data = ucfirst($emailname[0]);
		}		//print_r($users_data);exit;
	 $data['username']=$users_data;
	 
	 if($to_email!= '')
		{
			$emailname = explode('@', $to_email);
			$users_data1 = ucfirst($emailname[0]);
		}		
	 $data['fullname']=$users_data1;
	 
	 $subject1    = 'shared a new photo.';
	
	 $subject    =$users_data.' '.$subject1;
	// $data['username'] = $result1->row()->username;
	 
	 $data1['token'] = rand(1000000,100000000000);
	 
	 $where1['id'] = $user_id;
	 
	 $this->users_model->update_data('users',$data1,$where1);
	 
	$data['link'] = $URL;
	
	 $message = $this->load->view('shaer_postemail',$data,true);
	
	 $this->email_model->sendMail($to_email,$from_email,$from_name,$subject,$message);
	 
	 echo '[{"status":"Mail successfully sent."}]';
   }

function link_confirm()
   {
   	extract($this->input->get());
	
	$this->load->model('users_model');
	
	$data['token'] = $token;
	
	$result = $this->users_model->get_data('users',$data);
    
	if($result->num_rows() != 0)
	{
		$data1['token'] = '';
		
		$where1['token'] = $token;
		
		$this->users_model->get_data2('users',$data1,$where1);
		
		$data2['id'] = $result->row()->id;
		
		$this->load->view('email_share',$data2);
	}
	else {
		echo 'Sorry! this is not a valid link';
	}
	   
   }
	public function FacebookShareByUserId() {

	if($this->input->get("user_id")) {
	
	extract($this->input->get());

	$PostDetails = $this->post_model->GetPostDetails($user_id);

	  if($PostDetails['id'] == "") {
      $PostId = " ";
      } else {
      $PostId= $PostDetails['id'];
      }
				 
      if($PostDetails['title'] == "") {
      $Title = " ";
      } else {
      $Title = $PostDetails['title'];
      }
                
	  if($PostDetails['resize1'] == "") {
      $PostName = " ";
      } else {
      $PostName = $PostDetails['resize1'];
      }
	
	  $PDetails = $this->db->where('id',$user_id)->from('users')->get()->row_array();

		if($PDetails['fb_token'] == "") {
		$AccessToken = " ";
		} else {
		$AccessToken = $PDetails['fb_token'];
		}
		
		$LongUrl = $this->config->item('base_url')."post/fbsharenew?user_id=".$user_id."&id=".$PostId;    
		
        $ShortUrl = $this->config->item('base_url').store_long_url($LongUrl);
			
		$Config = array(
		'appId'  => $this->config->item('appId'),
		'secret' => $this->config->item('secret')
		);
					 //print_r($config);
		$Facebook = new Facebook($Config);
		
		$Attachment =  array(
		'access_token' => $AccessToken, 
		'title' => $Title,
		'link'=> $ShortUrl,
		'picture'=> $PostName
		 );
		 
		
		try {
		$this->facebook->api("/me/feed", 'post', $Attachment);
		echo '[{"status":"Success"}]';
		} catch(FacebookApiException $e) {
		echo '[{"status":"Failure}]';
		 echo '<pre>';
    print_r($e);
    echo '</pre>';
		}
	
		
	} else {
		
	echo '[{"status":"Required field"}]';
	
	}
	
}
	public function fbsharenew()
	{
		         $post_id = $this->input->get("id");
				 $user_id = $this->input->get('user_id');
				 $title = $this->input->get('title');
				 
		
				 	$user_detail = getSocialstatus($user_id);
				 if($user_detail['fb_id'] != "")
				 {
				
			  //  $this->mobile_model->user_details_share($user_id);
				//$user_id = $this->input->get('user_id');
			$details = $this->db->where('id',$user_id)->from('users')->get();
			if($details->num_rows()!=0)
			
				
			$data['detail']=$this->post_model->view($user_id,$post_id);
				$data['detail']=$this->post_model->detailview($user_id,$post_id);
				//$this->load->view('detail_view',$data);
					}
					
				
	}
	
	public function fbupdate()
	{
				if($this->input->get('user_id') && $this->input->get('id') && $this->input->get('title')) 
				{
				$this->load->helper('common_helper');		
				extract($this->input->get());
				$this->load->model('post_model');
				//$this->load->library('facebook',$params);
		$user_id=$this->input->get('user_id');
		$post_id=$this->input->get('id');
		$title=str_replace('<hash_with_space>', ' #', $title);
		$title=str_replace('<hash_without_space>', '#', $title);
	
	       $long_url= $this->config->item('base_url')."post/fbsharenew?user_id=".$user_id."&id=".$post_id;     
              // print_r($long_url);exit;      
      	   $short_url = $this->config->item('base_url').store_long_url($long_url);
		
		 //$this->db->query("INSERT into post (title) VALUES (".$title.") WHERE id=".$post_id);
		 	$insert = array(
						'title'  => $title,
						  );
			//   $this->db->where('post_id',$post_id);
			  // $result=$this->db->update('post', $insert);
			    $result = $this->db->where('id',$post_id)->update('post',$insert);
				//print_r($result);exit;
				 $user_detail = getSocialstatus($user_id);//print_r($user_detail);exit;
				 
				 if($user_detail['fb_id'] != "")
				 {
				
				 
				//$user_detail = getSocialstatus($user_id);
				//$last_insert_id = $this->db->order_by('id','desc')->get('post')->row()->id;
              // $postval= $this->db->where('id',$last_insert_id)->get('post')->row_array(); 
				//$postval=$this->db->update('title',$title); 
				//$postval="UPDATE post SET title='.$title.' WHERE  post_id=".$post_id;
				
				 $postval= $this->db->where('id',$post_id)->get('post')->row_array();
				// print_r($this->db->last_query());exit;
				 if($postval['title'] == "") {
			      $postTitle = " ";
			      } else {
			      $postTitle = $postval['title'];
			      }
							  
				   
                 if($postval['resize1'] == "")
                 {
                     $post_poster = " ";
                 }
                 else
                  {
                 $post_poster = $postval['resize1'];
                 }
				 
				  $postDetails = $this->post_model->user_details($user_id);
				  
				
				 if($postDetails['fb_token'] == "")
				 {
				 	$db_token_res = " ";
				 }
				 else
				 {
					 $db_token_res = $postDetails['fb_token'];//print_r($db_token_res);exit;
				 }
				  
				  
			 /*  $seg_three=$des_name;
			   $uri = $_SERVER['REQUEST_URI'];
			   $pieces = explode("?", $uri);
			   $hash=$pieces[1];		  
			   $hash=$seg_three.'?'.$hash;*/
				  
					$config = array(
					'appId'  => $this->config->item('appId'),
					'secret' => $this->config->item('secret')
					);
					
					$permissions = 'manage_pages, publish_stream';
					
					$facebook = new Facebook($config); 
					$attachment =  array(
					'access_token' => $db_token_res, 
					'link'=> $short_url,
					'title' =>$title,
					'picture'=> $post_poster
					);//print_r($attachment);exit;
						//echo "<pre>"; print_r($attachment);echo "</pre>";
					try {
						Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
   						Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 2;
					$permissions = $this->facebook->api("https://graph.facebook.com/me/feed", 'POST', $attachment);
				//	print_r($this->facebook->api($attachment));exit;
					//echo "<pre>"; print_r($this->facebook);echo "</pre>";
					echo '[{"status":"success."}]';
					} 
					catch(FacebookApiException $e) {
					   echo $e;
					    # handling facebook api exception if something went wrong
					}
					 
					}
					}
                else {
		                echo '[{"status":"Required All Fields"}]';
	                 }
				
	}
	
	public function twitterupdate()
	{
		        
				if($this->input->get('user_id') && $this->input->get('id') && $this->input->get('title')) 
				
				{ 
			$this->load->helper('common_helper');
			$this->load->model('post_model');
		extract($this->input->get());
		$user_id=$this->input->get('user_id');
		$post_id=$this->input->get('id');//print_r($post_id);exit;
		$title=str_replace('<hash_with_space>', ' #', $title);
		$title=str_replace('<hash_without_space>', '#', $title);
		
				 $user_detail = getSocialstatus($user_id);
		  
	    if($user_detail['tw_id'] != "")
           {
        
          
               $twitter_status = 'success';
               
				$this->post_model->TwitterShareByUser($post_id,$user_id,$title);
				   
				    echo '[{"twitter":"'.$twitter_status.'"}]'; 
				 
		   }
				 else
           {
               $twitter_status = 'failure';
               
           }
				 
				
					}
             
				
	}
	public function get_shorty() //this function is called by the routes file using the 404_override :)
    {
    
     echo  $shorty	 = $this->uri->segment(1);exit;//get the segment the user requested e.g. Nw from http://short.local/Nw
       $Long_url = $this->post_model->get_long_url($shorty);//direct the user to the long URL the short URL is connected to :)  MAGIC

			parse_str(parse_url($Long_url, PHP_URL_QUERY), $array);

          	$post_id = $array["id"];
			$user_id  = $array['user_id'];
			 
			$details = $this->db->where('id',$user_id)->from('users')->get();
			if($details->num_rows()!=0)
			$data['detail']=$this->post_model->view($user_id,$post_id);
			
			$this->load->view('detail_view',$data);
	
    }
   
   public function sample() {
    	$data['fsfs'] = "Murugan";
    	$this->load->view('detail_view',$data);
    }
	public function twitterShare()
{	    
	    if($this->input->get("user_id") && $this->input->get('id'))
       {
       	    
            extract($this->input->get());
			$this->load->model('post_model');
			$this->load->model("common_helper");

			 $post_id = $this->input->get('id');
						
				 $user_id = $this->input->get('user_id');
				
				 $title = $this->input->get('title');
				
           $user_detail = getSocialstatus($user_id);
		   
	    if($user_detail['tw_id'] != "")
           {
          
               $twitter_status = 'success';
               
			  
               
				//$user_detail = getSocialstatus($user_id);
				
				 // twitter share starts
				 
			   
			          //$profile_image = getTwitterImageByTwitterid($user_detail['twitter_id']);
					 
					  //$profile_img = $profile_image['profile_image'];
					 
				   $this->post_model->twitterSharenew($post_id,$user_id);
				   

				  	$data['detail']= $this->post_model->detailview($user_id,$post_id);
				//print_r($data);
				
	             $this->load->view('detail_view',$data);

	
				
				    //echo '[{"twitter":"'.$twitter_status.'"}]'; 
				 // echo '[{"status":"success."}]';
		   }
				 else
           {
               $twitter_status = 'failure';
               
           }
	   }
              
	    
}
	
	function user_data()
  {
  	$this->load->model('users_model');
	
	extract($this->input->get());
	
	$data['id']  =  $following_id;
	
	$result = $this->users_model->get_data('users',$data);
	
	$data1['user_id']  = $following_id;
	
	$result1 = $this->users_model->get_data('post',$data1);
  
    $post_count = $result1->num_rows();
	
	$data_follower['following_id'] = $following_id; 
	
	$result_follower = $this->users_model->get_data('follow',$data_follower);

	$follower_count = $result_follower->num_rows();
	
	$data_following['user_id'] = $following_id; 
	
	$result_following = $this->users_model->get_data('follow',$data_following);
	
	//$result_following = $this->db->where('following_id',$following_id)->get('follow');
	
	$following_count = $result_following->num_rows();
		//echo $following_count;exit;
	foreach($result->result() as $row)
	{
		$user_data['username'] 		= $row->username;
		$user_data['email']    		= $row->email;
		$user_data['fb_id']    		= $row->fb_id;
		$user_data['tw_id']    		= $row->tw_id;
		$user_data['image']    		= $row->image;
		$user_data['resize']    	= $row->resize;
		$user_data['fullname'] 		= $row->fullname;
		$user_data['website']       = $row->website;
		$user_data['bio'] 			= $row->bio;
		$user_data['gender'] 		= $row->gender;
		
		if($row->fullname == '' || $row->fullname == ' ')
		{
			$fullname = explode('@', $row->email);
			$user_data['fullname'] = strtoupper($fullname[0]);
		}
		
		$user_data['phonenumber']   = $row->phonenumber;
	}
	$user_data['post_count'] = $post_count;
	$user_data['follower_count'] = $follower_count;
	$user_data['following_count'] = $following_count;
	if($result_following->num_rows() != 0)
					{
						$user_data['follow_status'] = 'follow';
					}
					else {
						$user_data['follow_status'] = 'unfollow';
					} 
	if($result_follower->num_rows() != 0)
					{
						$user_data['follow_status'] = 'follow';
					}
					else {
						$user_data['follow_status'] = 'unfollow';
					} 
	
	echo '['.json_encode($user_data).']';

  }
	 function user_post_details()
  {
  	$this->load->model('users_model');
	
	$this->load->model('post_model');
	
  	extract($this->input->get());
	
	//$data['user_id'] = $user_id;
	
	
	$data['id']  =  $post_id;
	
	$order_by  = array('id'=>'desc');
	
	$result = $this->users_model->user_post_details('post',$data,$order_by);
		
		//print_r($result->result()); exit;
	if($result->num_rows() == 0)
	{
		echo '[{"status":"No Record Found"}]';exit;
	}
	$i = 0;
	
	foreach($result->result() as $row)
	{
		//$result_like = $this->post_model->user_post_details($row->id);
		$result_like=$this->db->where('user_id',$user_id)->where('post_id',$row->id)->get('like')->num_rows();
		//echo $result_like; exit;
		$result_comment = $this->post_model->post_comment_data($row->id);
		
		$result_comment_1 = $this->post_model->post_comment_data($row->id,1);
		
		if($result_comment->num_rows() < 5 && $result_comment->num_rows() != 0)
		{
		 $result_comment_4 = $this->post_model->post_comment_data($row->id,$result_comment->num_rows()-1,'desc');	
		}
		else {
			$result_comment_4 = $this->post_model->post_comment_data($row->id,4,'desc');
		}		
			
		$data1['id'] = $row->id;		
		$data1['user_id'] = $row->user_id;
		
		$postuserdetails=$this->db->where('id',$row->user_id)->get('users')->row();
		$data1['username'] = $postuserdetails->username;
		$data1['fullname'] = $postuserdetails->fullname;
		$data1['profile_image'] = $postuserdetails->image;
		$data1['profile_image1'] = $postuserdetails->resize;
		$data1['title'] = $row->title;
			$data1['image'] = $row->image;	
			$data1['resize'] = $row->resize;	
		$data1['resize1'] = $row->resize1;
		$data1['created'] = $this->facebook_style_date_time($row->created);
		//$data1['created'] = $this->facebook_style_date_time($row->created);
		if($result_like > 0)
		{
		$data1['like_status'] = 'liked';
		}
		else 
		{
		$data1['like_status'] = 'unliked';
		}
		
		if($result_comment->num_rows() != 0)
		{
		$data1['comment'] = array_merge($result_comment_1->result(),array_reverse($result_comment_4->result()));
		}
		
		else
        $data1['comment'] = '';
		$data1['resize'] = $row->resize;
		$inner_comment_count = $result_comment_1->num_rows()+$result_comment_4->num_rows();
		
		if($i == 0)
		$data1['inner_comment_count'] = $inner_comment_count;
		else
		$data1['inner_comment_count']-= $inner_comment_count;
	
		$data1['comment_count'] = $result_comment->num_rows();
		
		$i++;
			
		$result1[] = $data1;
	}
		
	echo json_encode($result1);
	
  }
	function share_post()
  {
  	$this->load->model('post_model');
	
	extract($this->input->get());
	
	$data['id']  =  $post_id;
	
	$result = $this->post_model->share_post($post_id);
	
	$data1['user_id']  = $post_id;
	
	$result = $this->post_model->share_post($post_id);
  
   $post_count = $result ->num_rows();
   
	$i = 0;
	if($post_count == 1)
	{
		foreach($result->result() as $row)
		{
			$share_post[$i]['id'] = $row->id;

			$share_post[$i]['user_id'] = $row->user_id;
						
			$share_post[$i]['resize1'] = $row->resize1; 
	
		/*if($row->resize1 == '' || $row->resize1 == ' ')
				{
				$resize1 = explode('@', $row->user_id);
				$share_post[$i]['resize'] = strtoupper($post_id[0]);
				
			
			$share_post[$i]['id'] = $row->id;
			$i++;}*/
		      echo json_encode($share_post);

			}	}
	else {
		 		echo '[{"status":"No Data Found"}]';
	}
  }
 
 
 
	public function update_post1()
	
	{
		$this->load->model('users_model');
		
		extract($this->input->get());
		
		$post_details=$this->db->where('id',$post_id)->get('post')->row();
		
		$data['image']=$post_details->image;
		
		$data['resize']=$post_details->resize;
		
	    $data['resize1']=$post_details->resize1;
		
		$data['title']   = $title;
		
		$data['user_id'] = $user_id;
		
		$data['created'] = time();
		
		$result_id = $this->users_model->insert_data('post',$data);
		
		$data_comment['user_id'] = $user_id;
		
		$data_comment['post_id'] = $result_id;
		
		$data_comment['comment'] = $title;
		
		$data_comment['created'] = time();
		if($title != '')
		{
		$this->users_model->insert_data('comment',$data_comment);
		}
		
		$data1['id'] = $result_id;
		
		//$result = $this->users_model->get_data('post',$data1); 
		//$this->db->where('user_id', $user_id);
		//$result=$this->db->get('post');
		echo '[{"status":"Post Added Successfully"}]';
		//echo json_encode($result->result());
		//print_r($result->result());exit;
	}
	//Add here coding follower and following post image and id
function post_data()
  {
  	$this->load->model('post_model');
	
	extract($this->input->get());
	
	$data['id']  =  $user_id;
	
	$result = $this->post_model->follow_post_data($user_id);
	
	$data1['user_id']  = $user_id;
	
	$result = $this->post_model->follow_post_data($user_id);
  
    $post_count = $result ->num_rows();
	
	$data_follower[]['following_id'] = $user_id; 
	
	$result_follower = $this->post_model->follow_post_data($user_id);
	
	$data_following[]['user_id'] = $user_id; 
	
	$result_following = $this->post_model->follow_post_data($user_id);	
	
	$i = 0;
		foreach($result->result() as $row)
		{
			$post_data[$i]['id'] = $row->id;
			$post_data[$i]['user_id'] = $row->user_id;
			$post_data[$i]['image'] = $row->image; 
			if($row->image == '' || $row->image == ' ')
				{
				$image = explode('@', $row->user_id);
				$post_data[$i]['image'] = strtoupper($user_id[0]);
				}
			
			$post_data[$i]['id'] = $row->id;
			$i++;
		}
	echo json_encode($post_data);

  }
	function all_post_data()
  {
  	$this->load->model('post_model');
	
	extract($this->input->get());
	
	$data['id']  =  $user_id;
	
	$result = $this->post_model->all_post_data($user_id);
	
	$data1['user_id']  = $user_id;
	
	$result = $this->post_model->all_post_data($user_id);
  
    $post_count = $result ->num_rows();
	
	$data_follower[]['following_id'] = $user_id; 
	
	$result_follower = $this->post_model->all_post_data($user_id);
	
	$data_following[]['user_id'] = $user_id; 
	
	$result_following = $this->post_model->all_post_data($user_id);	
	
	$i = 0;
		foreach($result->result() as $row)
		{
			$all_post_data[$i]['id'] = $row->id;
			$all_post_data[$i]['user_id'] = $row->user_id;
			$all_post_data[$i]['resize'] = $row->resize; 
			if($row->resize == '' || $row->resize == ' ')
				{
				$image = explode('@', $row->user_id);
				$all_post_data[$i]['resize'] = strtoupper($user_id[0]);
				}
			
			$all_post_data[$i]['id'] = $row->id;
			$i++;
		}
	echo json_encode($all_post_data);

  }
  function all_post()
  {
  	$this->load->model('post_model');
	
	extract($this->input->get());
	
	$data['id']  =  $user_id;
	
	$result = $this->post_model->all_post($user_id);
	
	$data1['user_id']  = $user_id;
	
	$result = $this->post_model->all_post($user_id);
  
    $post_count = $result ->num_rows();
	$i = 0;
		foreach($result->result() as $row)
		{
			$all_post[$i]['id'] = $row->id;
			$all_post[$i]['user_id'] = $row->user_id;
			$all_post[$i]['resize'] = $row->resize; 
			$all_post[$i]['resize1'] = $row->resize1; 
			if($row->resize == '' || $row->resize == ' ')
				{
				$image = explode('@', $row->user_id);
				$all_post[$i]['resize'] = strtoupper($user_id[0]);
				}
			
			$all_post[$i]['id'] = $row->id;
			$i++;
		}
	echo json_encode($all_post);

  }
  
	public function post_upload()
	{
		    $status = "";
			$msg = "";
			$file_element_name = 'uploadedfile';
			
			$this->load->model('post_model');
			
			if ($status != "error")	
			{
			
			$post_id = $this->post_model->last_post_id();
				
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
				
				$resize = base_url().'images/'.$data['raw_name'].'_100_100'.$data['file_ext'];
				
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
				
				$resize1 = base_url().'images/'.$data['raw_name'].'_320_320'.$data['file_ext'];
		        
				echo '[{"image":"'.$image.'","resize":"'.$resize.'","resize1":"'.$resize1.'"}]';exit;				
			}
					
			@unlink($_FILES[$file_element_name]);
	        }
    }
	
	public function add()
	{
		$this->load->model('users_model');
		
		extract($this->input->get());
		
		$data['image']   = $image;
		
		$data['resize']  = $resize;
		
		$data['resize1']  = $resize1;
		
		$data['title']   = $title;
		
		$data['user_id'] = $user_id;
		
		$data['created'] = time();
		
		$result_id = $this->users_model->insert_data('post',$data);
		
		$data_comment['user_id'] = $user_id;
		
		$data_comment['post_id'] = $result_id;
		
		$data_comment['comment'] = $title;
		
		$data_comment['created'] = time();
		
		if($title != '')
		{
		$this->users_model->insert_data('comment',$data_comment);
		}
		
		$data1['id'] = $result_id;
		
		$result = $this->users_model->get_data('post',$data1); 
		
		echo json_encode($result->result());
	}

    public function like()
	{
		$this->load->model('users_model');
		
		extract($this->input->get());
		
		$data['user_id'] = $user_id;
		
		$data['post_id'] = $post_id;
		
		$data['created'] = time();
		
		$this->users_model->insert_data('like',$data);
		
		echo '[{"status":"liked"}]';
	}
	
	public function unlike()
	{
		$this->load->model('post_model');
		
		extract($this->input->get());
		
		$data['user_id'] = $user_id;
		
		$data['post_id'] = $post_id;
		
		$this->post_model->unlike($data);
		
		echo '[{"status":"unliked"}]';
	}
    
	public function send_comment()
	{
		$this->load->model('users_model');
		
		extract($this->input->get());
		
		$data['user_id'] = $user_id;
		
		$data['post_id'] = $post_id;
		
		$data['comment'] = $comment;
		
		$data['created'] = time();
		
		$comment_id = $this->users_model->insert_data('comment',$data);
		
		$data1['user_id'] = $user_id;
		$data1['post_id'] = $post_id;
		
		$result = $this->users_model->get_data('comment',$data1);
		
		foreach($result->result() as $row)
		{
			$data2['id'] = $row->id;
			$data2['user_id'] = $row->user_id;
			$data2['post_id'] = $row->post_id;
			$data2['comment'] = $row->comment;
			$data2['created'] = $this->facebook_style_date_time($row->created);
			
			$data3['id'] = $row->user_id;
			
			$result_user = $this->users_model->get_data('users',$data3)->row();
			
			$data2['username'] = $result_user->username;
			$data2['resize'] = $result_user->resize;
 			
			$result1[] = $data2;
		}
		
		echo json_encode($result1);
	}	
	
	function comment()
	{
		$this->load->model('users_model');
		
		extract($this->input->get());
		
		$data1['post_id'] = $post_id;
		
		$result = $this->users_model->get_data('comment',$data1);
		
		if($result->num_rows() != 0)
		{
		foreach($result->result() as $row)
		{
			$data2['id'] = $row->id;
			$data2['user_id'] = $row->user_id;
			$data2['post_id'] = $row->post_id;
			$data2['comment'] = $row->comment;
			$data2['created'] = $this->facebook_style_date_time($row->created);
			
			$data3['id'] = $row->user_id;
			
			$result_user = $this->users_model->get_data('users',$data3)->row();
			
			$data2['username'] = $result_user->username;
			$data2['resize'] = $result_user->resize;
 			
			$result1[] = $data2;
		}
			echo json_encode($result1);
		}
		else
			{
		      echo '["status":"no data"]';
			}
	}

function facebook_style_date_time($timestamp){
	$difference = time() - $timestamp;
	$periods = array("s", "m", "h", "d", "w", "M", "y", "d");
	$lengths = array("60","60","24","7","4.35","12","10");
	 
	if ($difference > 0) { // this was in the past time
		} else { // this was in the future time
	$difference = -$difference;
	}
	for($j = 0; $difference >= $lengths[$j]; $j++) $difference /= $lengths[$j];
	$difference = round($difference);
	if($difference != 1) $periods[$j].= "";
	$text = "$difference$periods[$j]";
	if($text == '0s' || $text == '1s')
	{
		$text = 'now';
	}
	return $text;
	}

   public function delete_comment()
   {
   	 $this->load->model('users_model');
	 
	 extract($this->input->get());
	 
	 $data['id'] = $comment_id;
	 
	 $this->users_model->delete_data('comment',$data);
	 
	 $data1['post_id'] = $post_id;
		
		$result = $this->users_model->get_data('comment',$data1);
		
		if($result->num_rows() != 0)
		{
		foreach($result->result() as $row)
		{
			$data2['id'] = $row->id;
			$data2['user_id'] = $row->user_id;
			$data2['post_id'] = $row->post_id;
			$data2['comment'] = $row->comment;
			$data2['created'] = $this->facebook_style_date_time($row->created);
			
			$data3['id'] = $row->user_id;
			
			$result_user = $this->users_model->get_data('users',$data3)->row();
			
			$data2['username'] = $result_user->username;
			$data2['resize'] = $result_user->resize;
 			
			$result1[] = $data2;
		}
			echo json_encode($result1);
		}
		else
			{
		      echo '["status":"no data"]';
			}
   }
   function view_photo()
{
		
	if($this->dx_auth->is_logged_in()) 
		{ 
	$ph_id = $this->uri->segment(3);
	$data['id'] = $ph_id ;
	$id_login=$this->dx_auth->get_user_id();
   $this->db->from('post');
   $this->db->where('id', $ph_id);  
   $query = $this->db->get();	
   $data['result_photo'] = $query->row();
   $this->db->order_by('created','desc');
   $like_list = $this->db->get_where('like',array('post_id'=>$ph_id));
   $this->db->order_by('created','asc');
   $comment_list = $this->db->get_where('comment',array('post_id'=>$ph_id));  //// comment detail
   $data['result_comment'] = $comment_list;	
   $data['result_like'] = $like_list;
   
   		$follow_detail = $this->common_model->getTableData('follow',array('user_id'=>$id_login,'following_id'=>$query->row()->user_id));
		if($follow_detail->num_rows() > 0)
		{
			$data['status']="following";
			$data['class_']="following";
		}else{
			$data['status']="follow";
			$data['class_']="login_btn";	
		}
	$data['message_element'] ='view_photo';
	$this->load->view('template',$data);
			}else{
		   		redirect('accounts');	
		}
	
}

function embed_view($id)
{
	
	
	   $this->db->from('post');
	   $this->db->where('id', $id);  
	   $query = $this->db->get();	
	   $data['result_photo'] = $query->row();
	   $data["user_id"] =$query->row()->user_id;
	   $this->db->order_by('created','desc');
	   $like_list = $this->db->get_where('like',array('post_id'=>$id));
	   $data['result_like'] = $like_list->num_rows();
		
	   $this->db->order_by('created','asc');
	   $comment_list = $this->db->get_where('comment',array('post_id'=>$id)); 
	   $data['result_comment'] = $comment_list->num_rows();	
	   
	   if($this->dx_auth->is_logged_in())
	   {
	   $id_login=$this->dx_auth->get_user_id();	   
	   
	   $follow_detail = $this->common_model->getTableData('follow',array('user_id'=>$id_login,'following_id'=>$data["user_id"]));
		if($follow_detail->num_rows() > 0)
		{
			$data['status']="following";
			$data['class_']="following";
		}else{
			$data['status']="follow";
			$data['class_']="login_btn";	
		}
	  }
	   else
	   {
		  $data['status']="follow";
		  $data['class_']="login_btn"; 
	   }
	   $data['id']=$id;	   
	   $this->load->view(THEME_FOLDER.'/embed_view',$data); 
	   
}

function login_popup()
{
	$data["post_user"]=$this->input->post('userid');
	$data["post_id"]=$this->input->post('postid');
	$this->session->set_userdata('embed_post',$data["post_id"]);
	$this->session->set_userdata('embed_postuser',$data["post_user"]);
	echo json_encode($data);
	exit;	
	//$this->load->view('view_signin',$data); 
}

function like_post()
{	
		
		$ph_id = $this->uri->segment(3);
		$s_userid=$this->dx_auth->get_user_id();
		$data['user_id'] = $s_userid;		
		$data['post_id'] = $ph_id;	
		$name = get_user_detail($s_userid)->username;
		$query = $this->db->get_where('like',array('user_id'=>$s_userid,'post_id'=>$ph_id));

		if($query->num_rows() == 0)
		{
		$data['created'] = time();	
		$this->users_model->insert_data('like',$data);	
		// echo '{"status":"liked","userid":"'.base_url().'accounts/viewprofile/'.$s_userid.'","username":"'.$name.'"}';	
		}else{
		$this->post_model->unlike($data);
	// echo '{"status":"unliked"}';
		}
		$this->db->order_by('created','desc');
		$query_result = $this->db->get_where('like',array('post_id'=>$ph_id));
		$data1['result_like'] = $query_result ;
		if($this->uri->segment('4') == "home")
		{
		$data1['page'] = 1;	
		}else{
		$data1['page'] = 0;	
		}
		$this->load->view(THEME_FOLDER.'/like_post',$data1); 
 	
				
}
function update_comment()
{
	$post_id = $this->uri->segment('3');
	$comment = $this->input->post('data1');
	$userid=$this->dx_auth->get_user_id();
	$data['user_id'] = $userid ;
	$data['post_id'] = $post_id ;
	$data['comment'] = $comment ;
	$data['created'] = time();
	$query = $this->db->get_where('comment',array('user_id'=>$userid,'post_id'=>$userid));
$user_detail = get_user_detail($userid);
$profileimage =  $user_detail->image;
if($profileimage == "")
{
	$profileimage = base_url().'img/no_avatar-xlarge.png';
}
$url = base_url()."accounts/viewprofile/".$userid;
$data_['url_image'] = $url ;
$data_['fullname'] = $user_detail->username ;
/// add @tag ///
$final_comment = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i'), array('<a href="$1" target="_blank">$1</a>', '$1<a href="">@$2</a>', '$1<a link_add="$2" class="tagcomment" style="cursor: pointer;" >@$2</a>'), $comment);
 /// add @tag /// 
$data_['comment'] = $final_comment ;
$data_['profileimage'] = $profileimage ;
$result1[] = $data_;
//	echo 	$profileimage ;	
$this->db->insert('comment',$data);
$id_cmt = $this->db->insert_id();
$data_['id'] = $id_cmt ;
	echo json_encode($data_);
		exit;
}
function load_more_post()
{
	$limit = $this->input->post('lastmsg');
	$status_query = $this->input->post('status');
	
    $uid = $this->dx_auth->get_user_id();
   	$query = $this->post_model->follow_post_data_limit($uid,$limit);
	 		
//$query=$this->db->query("SELECT `post`.`id`,`post`.`user_id`,`post`.`created`,`post`.`image`,`post`.`title` FROM (`follow`) INNER JOIN `post` ON `post`.`user_id` = `follow`.`following_id` WHERE (`follow`.`user_id` = '".$uid."' OR `follow`.`following_id` = '".$uid."') GROUP BY `post`.`id` ORDER BY `post`.`id` desc LIMIT $msgid, 20"); 
 //echo $this->db->last_query();
 //exit;
  /* $this->db->from('post');
   $this->db->limit('20');
   $this->db->order_by('created','desc');
   $this->db->where('id < ', $msgid); 
   $this->db->where('user_id', $this->dx_auth->get_user_id());  
   $query = $this->db->get();
   * */
  //exit;
  
  if($status_query == "follow_post")
  { 	
	   if($query->num_rows() == 0)
	{
		 echo $query->num_rows() ;
	}
	else{
	   	$data['query'] = $query;
   $this->load->view(THEME_FOLDER.'/load_posts',$data);  	
	    }
	   
  }else if($status_query == "user_post")
  {

		   $this->db->order_by('created','desc');
		   $this->db->where('user_id', $this->dx_auth->get_user_id());  
		   $query_ = $this->db->get('post',$limit,20);
		   $data['query'] = $query_;
		     
	       if($query_->num_rows() == 0)	 
	       {
	       	 echo $query_->num_rows() ;
	       }else{
	       	 $data['query'] = $query_;
   $this->load->view(THEME_FOLDER.'/load_posts',$data); 
	       } 	   
		   
  }
 
   
}

function load_popup()
{
	 $count = $this->input->post("count");
	
	
		   $order = array('created','DESC');
		   $user_id=$this->dx_auth->get_user_id();		   	   
		   $query_ = $this->common_model->getTableData('post',array('user_id'=>$user_id),NULL,NULL,array('0'=>'20','1'=>$count),NULL,NULL,$order);
		   $data['query'] = $query_;
		     
	       if($query_->num_rows() == 0)	 
	       {
	       	 echo $query_->num_rows() ;
	       }else{
	       	 $data['query'] = $query_;
   $this->load->view(THEME_FOLDER.'/load_popup',$data); 
	       } 	
}

function load_post_image()
{
	 $count = $this->input->post("count");
	 $month = $this->input->post("month");
	
		   $order = array('created','DESC');
		   $user_id=$this->dx_auth->get_user_id();
		    $query_=$this->db->select("* , FROM_UNIXTIME( created, '%m' ) as month ",FALSE)->where("user_id",$user_id)->limit(20, $count)->order_by("created", "desc")->get('post');		   
		  // $query_ = $this->common_model->getTableData('post',array('user_id'=>$user_id),NULL,NULL,array('0'=>'20','1'=>$count),NULL,NULL,$order);
		   $data['query'] = $query_;
		   $data['month'] = $month;  
	       if($query_->num_rows() == 0)	 
	       {
	       	 echo $query_->num_rows() ;
	       }else{
	       	 $data['query'] = $query_;
   $this->load->view(THEME_FOLDER.'/load_post_image',$data); 
	       } 	
}
function update_follow()
{
	if($this->dx_auth->is_logged_in())
	{
	$followid = $this->input->post('userid');
	$loginid = $this->dx_auth->get_user_id();
	$follow_detail = $this->common_model->getTableData('follow',array('user_id'=>$loginid,'following_id'=>$followid));
		if($follow_detail->num_rows() > 0)
		{
			$this->db->where(array('user_id'=>$loginid,'following_id'=>$followid))->delete('follow');
			$data['status_']="follow";
			$data['class_']="login_btn";
			$data['login']="true";
			$data_[] = $data ;
				echo json_encode($data);
		exit;
		
		}else{
			$data1 = array('user_id'=>$loginid,'following_id'=>$followid,'created'=>time());
			$this->db->insert('follow',$data1);
			$data['status_']="following";
			$data['class_']="following";
			$data['login']="true";	
			$data_[] = $data ;
				echo json_encode($data);
		exit;
		
		}
	}
	else
	{
			$data['status_']="following";
			$data['class_']="following";
			$data['login']="false";	
			$data_[] = $data ;
			echo json_encode($data);
			exit;
				
	}
	
}

function update_follow_embed()
{
	$followid = $this->input->post('userid');
	$loginid = $this->dx_auth->get_user_id();
	$follow_detail = $this->common_model->getTableData('follow',array('user_id'=>$loginid,'following_id'=>$followid));
	if($follow_detail->num_rows() == 0)
	{
		$data1 = array('user_id'=>$loginid,'following_id'=>$followid,'created'=>time());
		$this->db->insert('follow',$data1);
	}
	$data['status']="updated";
	echo json_encode($data);
	exit;
}

function delete_comment_post()
{
	$commentid = $this->input->post('commentid');
	$data['id'] = $commentid;
	$this->users_model->delete_data('comment',$data);
	$data['status_']="deleted";
	echo json_encode($data);
}

function scroll_image()
{
				
    $count = $this->input->post("count");
	$reslut=array();
	$user_id=$this->dx_auth->get_user_id();
	$order = array('created','DESC');
	$db_val = $this->common_model->getTableData('post',array('user_id'=>$user_id),NULL,NULL,array('0'=>'20','1'=>$count),$order);
	foreach($db_val->result() as $row)
	{
	$like=$this->db->get_where('like',array('post_id'=>$row->id))->num_rows();
	$comment=$this->db->get_where('comment',array('post_id'=>$row->id))->num_rows();	
	$reslut["multi"][]= $row; 	
	}
	//echo $this->db->last_query();
	echo  json_encode($reslut);
	
	
}
function addtag_comment()
{
	    $comment_tag = $this->input->post("msg");
		$this->db->select('id');
		$this->db->where('username',$comment_tag);
		$userresult = $this->db->get('users');
		if($userresult->num_rows() > 0)
		{
		$reslut['id'] =  $userresult->row()->id;	
		}else{
		$reslut['id'] =  0;	
		}
		echo  json_encode($reslut);
		
}
function report_image()
{
	    $report = $this->input->post("msg");
		$id = $this->input->post("id");
		$Url=base_url()."post/view_photo/".$id;		
		$condition['id']=$this->dx_auth->get_user_id();
		$db_value =  $this->users_model->get_data('users',$condition);
			
			foreach($db_value->result() as $row)
			{
		 	$username= $row->username;
			$from	= $row->email;	 	
			}		
			
		$to=$this->db->where('code','SITE_ADMIN')->get('settings')->row()->string_value;
		$admin		=$this->db->where('code','SITE_ADMIN')->get('settings')->row()->name;
		$subject 	="Report Inapporopriate";							
		$message	="Hi Admin, The Gottospot user $username, Reporth the post is Inapporopriate( user Report: ".$report.").  The post url :	$Url. ";	 	
		//$this->email_model->sendMail($to,$from,$admin,$subject,$message);	
		
}



}

/* End of file welcome.php */
/* Location: ./application/controllers/post.php */