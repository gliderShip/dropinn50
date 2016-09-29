<?php
/**
 * DROPinn Users Controller Class
 *
 * It helps shows the home page with slider.
 *
 * @package		Users
 * @subpackage	Controllers
 * @category	Referrals
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 */
	
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Database_cron extends CI_Controller {

		//Constructor
		public function Database_cron()
		{
			parent::__construct();
		}

      public function index() {
			session_start();
			//ini_set("display_errors",1); 
			
	if($this->config->item('hostname') != '' && $this->config->item('db_username') != '' &&  $this->config->item('db_password') != '')
			{
			
			$link = $this->osc_db_connect(trim($this->config->item('hostname')), trim($this->config->item('db_username')), trim($this->config->item('db_password')));
			$mysqlDB	=$this->config->item('db');
			if($link) {
				$install = dirname($_SERVER['SCRIPT_FILENAME']);
				$sql_file = $install.'/app/controllers/install.sql'; 
				$install = $this->osc_db_install($mysqlDB, $sql_file);
			if($install) {
			redirect("");
			}
			
			}
			
			}
			
  
  }
  
  
  function osc_db_connect($server, $username, $password, $link = 'db_link') {
    global $$link, $db_error;

    $db_error = false;

    if (!$server) {
      $db_error = 'No Server selected.';
      return false;
    }

    $$link = @mysql_connect($server, $username, $password) or $db_error = mysql_error();

    return $$link;
  }
  
  
   function osc_db_select_db($database) {
    return mysql_select_db($database);
  }

  function osc_db_query($query, $link = 'db_link') {
    global $$link;

    return mysql_query($query, $$link);
  }

  function osc_db_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }

  function osc_db_install($database, $sql_file) {
    global $db_error;

    $db_error = false;
	ini_set("display_errors",1); 
	 
  //$database = "dropin1";
//  
//   $this->install = realpath(APPPATH . '../app/controllers');
//			$db_error = false;
//			 $sql_file = $this->install . '\install.sql'; 
			 
    if (!$this->osc_db_select_db($database)) {
      if ($this->osc_db_query('create database ' . $database)) {
        $this->osc_db_select_db($database);
      } else {
        $db_error = mysql_error();
      }
    }

    if (!$db_error) {
      if (file_exists($sql_file)) {
        $fd = fopen($sql_file, 'rb');
        $restore_query = fread($fd, filesize($sql_file));
        fclose($fd);
      } else {
        $db_error = 'SQL file does not exist: ' . $sql_file;
        return false;
      }

      $sql_array = array();
      $sql_length = strlen($restore_query);
      $pos = strpos($restore_query, ';');
      for ($i=$pos; $i<$sql_length; $i++) {
        if ($restore_query[0] == '#') {
          $restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
          $sql_length = strlen($restore_query);
          $i = strpos($restore_query, ';')-1;
          continue;
        }
        if ($restore_query[($i+1)] == "\n") {
          for ($j=($i+2); $j<$sql_length; $j++) {
            if (trim($restore_query[$j]) != '') {
              $next = substr($restore_query, $j, 6);
              if ($next[0] == '#') {
// find out where the break position is so we can remove this line (#comment line)
                for ($k=$j; $k<$sql_length; $k++) {
                  if ($restore_query[$k] == "\n") break;
                }
                $query = substr($restore_query, 0, $i+1);
                $restore_query = substr($restore_query, $k);
// join the query before the comment appeared, with the rest of the dump
                $restore_query = $query . $restore_query;
                $sql_length = strlen($restore_query);
                $i = strpos($restore_query, ';')-1;
                continue 2;
              }
              break;
            }
          }
		  
          if ($next == '') { // get the last insert query
            $next = 'insert';
          }
         if ( (stristr($next,'create')) || (stristr($next,'insert')) || (stristr($next,'drop t')) ) {
            $next = '';
            $sql_array[] = substr($restore_query, 0, $i);
            $restore_query = ltrim(substr($restore_query, $i+1));
            $sql_length = strlen($restore_query);
            $i = strpos($restore_query, ';')-1;
          }
        }
      }
     
	$this->osc_db_query("drop table if exists `settings`, `amnities`, `messages`, `paymode`, `calendar`, `reservation_status`, `ci_sessions`, `list`, `list_photo`, `lys_status`, `profiles`, `login_attempts`, `paywhom`,`page`, `permissions`, `price`,  `reservation`, `roles`,  `user_autologin`,  `statistics`, `users`, `neigh_city`, `neigh_city_place`, `neigh_post`, `neigh_category`,`theme_select`, `neigh_place_category`");

      for ($i=0; $i<sizeof($sql_array); $i++) {
        $this->osc_db_query($sql_array[$i]);
      }
    } else {
      return false;
    }
	return TRUE;
  }
  function osc_set_time_limit($limit) {
    if (!get_cfg_var('safe_mode')) {
      set_time_limit($limit);
    }
  }

     
}
/* End of file home.php */
/* Location: ./app/controllers/home.php */
?>
