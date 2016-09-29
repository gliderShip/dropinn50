<?php
/**
 * DROPinn Admin Social Controller Class
 *
 * helps to achieve common tasks related to the site like flash message formats,pagination variables.
 *
 * @package		DROPinn
 * @subpackage	Controllers
 * @category	Admin Social
 * @author		Cogzidel Product Team
 * @version		Version 1.6
 * @link		http://www.cogzidel.com
 
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends CI_Controller
{

	public function Backup()
	{
			parent::__construct();
		
		// Protect entire controller so only admin, 
		// and users that have granted role in permissions table can access it.
		$this->dx_auth->check_uri_permissions();
	}
	
	public function index()
	{
		 	//Load View	
	 $data['message_element'] = "administrator/view_backup";
	 $this->load->view('administrator/admin_template', $data);
	}
	
	public function mysql_backup()
	{
				// Load the DB utility class
$this->load->dbutil();

$date = new DateTime();
$time = $date->format('Y-m-d_H-i-s');

// Backup your entire database and assign it to a variable
$prefs = array(
                'tables'      => array(),  // Array of tables to backup.
                'ignore'      => array(),           // List of tables to omit from the backup
                'format'      => 'txt',             // gzip, zip, txt
                'filename'    => 'sql_backup_'.$time.'.sql',    // File name - NEEDED ONLY WITH ZIP FILES
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                'newline'     => "\n"               // Newline character used in backup file
              );

$backup = $this->dbutil->backup($prefs); 

// Load the file helper and write the file to your server
$this->load->helper('file');
write_file('./backup/sql_backup_'.$time.'.sql', $backup);

// Load the download helper and send the file to your desktop
$this->load->helper('download');
force_download('sql_backup_'.$time.'.sql', $backup); 

	}
}

?>
