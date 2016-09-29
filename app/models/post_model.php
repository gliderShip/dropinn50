<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post_model extends CI_Model {

	public function index()
	{
		
	} 
	public function Post_model()
	{
		parent::__Construct();
		$params = array('appId'  => $this->config->item('appId'),'secret' => $this->config->item('secret'));
		$this->load->library('facebook',$params);
		$this->load->library('twconnect');
		$this->load->helper('common_helper');
	}
	/*public function view($user_id,$post_id)
	{
	
	$this->db->from('post');
	$this->db->where("id",$post_id); 
	$this->db->join('users', 'post.user_id = users.id','inner');
	$this->db->select('post.resize1,users.image,users.fullname');
	$query = $this->db->get();
	$que_res = $query->result();
	return $que_res;			

	}*/
	public function view($user_id,$post_id)
	{
	
	$this->db->from('post');
	$this->db->where("id",$post_id); 
	$this->db->select('post.resize1');
	$query = $this->db->get();
	$que_res = $query->result();
	return $que_res;			

	}
	function get_long_url($shorty='') {
     	
    	$Value =  base64_decode(str_replace('-','=', $shorty));
    	
		if(is_numeric ($Value) ) {
		
    	
        $query=$this->db->get_where('urls', array('id'=> base64_decode(str_replace('-','=', $shorty))));
		
        if($query->num_rows()>0)
        {
        	
            foreach ($query->result() as $row)
            {
                return $row->long_url;
            }
        }
		} else {
			
			 redirect('');
		}
       
    }
	public function GetPostDetails($UserId)
	{
		    $this->db->select('*');
			$this->db->from('post');
			$this->db->where('user_id',$UserId);
			$this->db->order_by('id','desc');
			$result=$this->db->get()->row_array();
			return $result;
	}
	function TwitterShareByUser($post_id,$user_id,$title)
	{

		  	$long_url= $this->config->item('base_url')."post/twitterShare?user_id=".$user_id."&id=".$post_id."";//var_dump($long_url);exit;
//print_r($long_url);exit;
	      	$short_url = $this->config->item('base_url').store_long_url($long_url);
		  
			$post=$this->db->where('id',$post_id)->get('post')->row_array();  
			
			
	   	 	$token =$this->post_model->user_details($user_id);   
			
	    	$keys = $this->config->item('twitter');
		//print_r($keys);exit;
		 		$consumer_key = $keys['consumer_key'];
			
		 	$consumer_secret =  $keys['consumer_secret'];

		  	$oauth_token = $token['tw_key'];  
	      	$oauth_token_secret = $token['tw_secret'];
		 	$tweet = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
			//echo '<pre>';
		//print_r($tweet);exit;
			$message = "   ";
		
			$message .= $title;
			$message .= "  ";
			$message .= $short_url;
			//print_r($message);exit;
			/*$value ="";
			foreach ($message as $row) 
			{
				$value .=$row;
			}*/
		
		($tweet->post('statuses/update', array('status' => "$message")));
			//$tweetObj = $tweet->post('statuses/update', array('status' => '$message'));
			//print_r($tweetObj);exit;
			//echo $tweetObj;
			//echo '<pre>';
			//print_r($tweetObj);exit;
			//echo '</pre>';
	}
	function twitterShare($user_id,$post_id,$title)
	{
		                 
	$this->load->model('users_model');
	    $keys = $this->config->item('twitter');
		
		
		 $consumer_key = $keys['consumer_key'];
		 $consumer_secret =  $keys['consumer_secret'];
		$oauth_token= $this->users_model->getData('users',array('id'=>$user_id))->row()->tw_key; 
		$oauth_token_secret= $this->users_model->getData('users',array('id'=>$user_id))->row()->tw_secret;	
		$tweet = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
		// Your Message
		
		$message = $title;
        $user_array = $tweet->get("account/verify_credentials");
        $a=$user_array->errors[0]; //echo $a->message; exit;

		// Send tweet 
		$tweet->post('statuses/update', array('status' => "$message"));
		echo "[".json_encode(array('status' => $a->message))."]";
	} 
function twitterSharenew($post_id,$user_id) {

		  	$long_url= $this->config->item('base_url')."post/twitterShare?user_id=".$user_id."&id=".$post_id."";

	      	$short_url = $this->config->item('base_url').store_long_url($long_url);
		  
			$post=$this->db->where('id',$post_id)->get('post')->row_array();            
	   	 	$token =$this->post_model->user_details($user_id);
	    	$keys = $this->config->item('twitter');
		
		 	$consumer_key = $keys['consumer_key'];
		 	$consumer_secret =  $keys['consumer_secret'];

		  	$oauth_token = $token['twitter_access_key'];
	      	$oauth_token_secret = $token['twitter_access_secret'];
	
		 	$tweet = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
		
			$message .= "   ";
		
			$message .= $post['title'];
			$message .= "  ";
			$message .= $short_url;
		
		print_r($short_url);exit;
		
			// Send tweet 
			echo $tweet->post('statuses/update', array('status' => "$message"));
			
	}
public function detailview($user_id,$post_id)
	{
	
	$this->db->select("*");
	$this->db->from("post");
	$this->db->where("id",$post_id);
	$this->db->where("user_id",$user_id);
	
	$query = $this->db->get();
	$que_res = $query->result();
	return $que_res;
		
		
	}
	public function user_post_details($data)
	{
		$result = $this->db->where('post.user_id',$data)->order_by('post.id','desc')->join('like','like.post_id')->get('post');
		return $result->num_rows();
	}
	
	public function last_post_id()
	{
		$result = $this->db->order_by('id','desc')->limit(1)->get('post');
		
		if($result->num_rows() == 0)
		{
		return 1;
		}
		else
			{
		return $result->row()->id+1;
			}
	}
	
	public function unlike($data)
	{
		$this->db->where($data)->delete('like');
	}
	
	public function user_post_data($data)
	{
		$result = $this->db->where('post.id',$data)->order_by('post.id','desc')->join('like','like.post_id = post.id')->get('post');
		return $result->num_rows();
	}
		public function user_post_data_like($data)
	{
		$result = $this->db->where('post.id',$data)->order_by('post.id','desc')->join('like','like.post_id = post.id')->get('post');
		return $result;
	}
	
	
	public function post_comment_data($data,$limit = array(),$orderby = 'asc')
	{
		if(count($limit) > 0)
		{
			$this->db->limit($limit);
		}
		
		$result = $this->db->select('users.id,users.username,comment.comment')->where('comment.post_id',$data)->order_by('comment.id',$orderby)->join('users','users.id=comment.user_id')->get('comment');
		return $result;
	}
	
	public function follow_post_data($user_id)
	{
		
$result = $this->db->query("SELECT * FROM (`post`) WHERE `post`.`user_id` IN (SELECT `follow`.`user_id` from `follow` where `follow`.`following_id`='".$user_id."' ) OR `post`.`user_id` IN (SELECT `follow`.`following_id` from `follow` where `follow`.`user_id`='".$user_id."' ) OR `post`.`user_id` = '".$user_id."' ORDER BY `post`.`created` desc");

    return $result;
	}
		public function follow_post_data_limit($user_id,$limit)
	{
		/*
		$this->db->select('post.id,post.user_id,post.created,post.title,post.image');
		$this->db->group_by("post.id");		
		$this->db->where('follow.user_id',$user_id);
		$this->db->or_where('follow.following_id',$user_id);

		$result = $this->db->order_by('post.id','desc')->join('post','post.user_id = follow.following_id', 'inner')->get('follow');
	 */
	 		if($limit == "")
		{
$limit = 0 ;
		}
		
$result = $this->db->query("SELECT * FROM (`post`) WHERE `post`.`user_id` IN (SELECT `follow`.`user_id` from `follow` where `follow`.`following_id`='".$user_id."' ) OR `post`.`user_id` IN (SELECT `follow`.`following_id` from `follow` where `follow`.`user_id`='".$user_id."' ) OR `post`.`user_id` = '".$user_id."' ORDER BY `post`.`created` desc LIMIT $limit,20");
	 
	    
	    return $result;
	}
	
	//Add all post delete and fatch coding
	public function all_post_data($user_id)
	{
		$result = $this->db->where('follow.user_id !=',$user_id)->where('post.user_id !=',$user_id)->order_by('post.id','desc')->join('post','post.user_id = follow.following_id')->get('follow');
	    return $result;
	}
	public function all_post($user_id)
	{
		
		$result = $this->db->select('id, user_id, resize,resize1')->where('post.user_id !=',$user_id)->order_by('post.id', 'desc')->get('post');
	    return $result;
	}

	public function delete_post($post_id)
	{
		
		$result = $this->db->where('id',$post_id)->delete('post');
		$this->db->where('post_id',$post_id)->delete('like');
		$this->db->where('post_id',$post_id)->delete('comment');
	    return $result;
	}
	public function share_post($post_id)
	{
		
		$result = $this->db->select('id, user_id, resize1')->where('post.id =',$post_id)->get('post');
	    return $result;
	}
	function user_details($user_id)
	{
		$result = $this->db->where('id',$user_id)->from('users')->get();
		return $result->row_array();
	}
	 function user_detailsnew($user_id)
	{
		$result = $this->db->where('user_id',$user_id)->from('post')->get();
		return $result->row_array();
	
	} 
	function user_details_share($user_id)
	{
	      

				 $postval= $this->users_model->getvideo($user_id);
				 
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
				  
				
				 if($postDetails['token'] == "")
				 {
				 	$db_token_res = " ";
				 }
				 else
				 {
					 $db_token_res = $postDetails['token'];
				 }
		$postDetails = $this->db->where('id',$user_id)->from('users')->get()->row_array();
		 // print_r($videDetails);exit;
				 if($postDetails['token'] == "")
				 {
				 	$db_token_res = " ";
				 }
				 else
				 {
					 $db_token_res = $postDetails['token'];
				 }
				  
					$config = array(
					'appId'  => $this->config->item('appId'),
					'secret' => $this->config->item('secret')
					);
					 //print_r($config);
					$facebook = new Facebook($config);
					$attachment =  array(
					'access_token' => $db_token_res, 
					'link'=> $short_url,
					'title' =>$title,
					'picture'=> $post_poster
					//'link'=> $this->config->item('base_url')."post/fbsharenew?user_id=".$user_id."&id=".$post_id."",
					);
					//print_r($attachment);exit;
					try {
					    $this->facebook->api("/me/users", 'post', $attachment);
					//echo "<pre>"; print_r($attachment);echo "</pre>";
					} catch(FacebookApiException $e) {
					    # handling facebook api exception if something went wrong
					}
                    //echo '[{"status":"success."}]';
		//return $result->row_array();
	} 

 function fb_share($user_id)
	{
	      
		  
				//$user_detail = getSocialstatus($user_id);
				//$last_insert_id = $this->db->order_by('v_id','desc')->get('video')->row()->v_id;
              //$videval = $this->db->where('v_id',$last_insert_id)->get('video')->row_array(); 
				 //$videval= $this->mobile_model->getvideo($user_id);
				  //$videval= $this->db->where('v_id',$video_id)->get('video')->row_array();
				  $postval= $this->db->where('id',$post_id)->get('post')->row_array();
				 
                 if($postval['title'] == "")
                 {
                     $des_title = " ";
                 }
                 else
                  {
                 $des_title = $postval['title'];
                 }
				    
				 	      
                 if($postval['resize1'] == "")
                 {
                     $des_name = " ";
                 }
                 else
                  {
                 $des_name = $postval['resize1'];
                 }
				  $videDetails = $this->post_model->user_details($user_id);
				  
				  
				 if($videDetails['fb_token'] == "")
				 {
				 	$db_token_res = " ";
				 }
				 else
				 {
					 $db_token_res = $videDetails['fb_token'];
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
					 //print_r($config);
					$facebook = new Facebook($config);
					$attachment =  array(
					'access_token' => $db_token_res, 
					'link'=> $this->config->item('base_url')."post/fbupdates?user_id=".$user_id."&id=".$post_id."",
					'title' =>$des_title,
					'picture'=> $des_name
					);
					//print_r($attachment);exit;
					try {
					    $this->facebook->api("/me/feed", 'post', $attachment);
					//echo "<pre>"; print_r($attachment);echo "</pre>";
					} catch(FacebookApiException $e) {
					    # handling facebook api exception if something went wrong
					}
                    //echo '[{"status":"success."}]';
		//return $result->row_array();
	} 
}

/* End of file users.php */
/* Location: ./application/models/users_model.php */