<?php
/**
 * DropInn Admin Key Class
 *
 * Help to handle tables related to static Admin_keys of the system.
 *
 * @package		DropInn
 * @subpackage	Models
 * @category	Admin Key 
 * @author		Cogzidel Product Team
 * @version		Version 3.1.6
 * @link		http://www.cogzidel.com 
 */
 
 class Admin_key_model extends CI_Model {
	 
	/**
	 * Constructor 
	 *
	 */
	  function Admin_key() 
	  {
		  	parent::__construct();
				
      }
	 
		
	/**
	 * Delete Admin_key
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function deleteAdmin_key($id=0,$conditions=array())
	 {
	 	if(is_array($conditions) and count($conditions)>0)		
	 		$this->db->where($conditions);
		else	
		    $this->db->where('id', $id);
		 $this->db->delete('admin_key');
		 
	 }
	 

	/**
	 * Get Static Admin_keys
	 *
	 * @access	private
	 * @param	array	conditions to fetch data
	 * @return	object	object with result set
	 */
		// Puhal changes Start. For the popup Admin_keys Privacy Policy and the Company & Conditions (Sep 17 Issue 2)	 
	 function getAdmin_keys($conditions=array(),$like=array(),$like_or=array())
	 {
	 	//Check For like statement
	 	if(is_array($like) and count($like)>0)		
	 		$this->db->like($like);
			
		//Check For like statement
	 	if(is_array($like_or) and count($like_or)>0)		
	 		$this->db->or_like($like_or);
// Puhal changes End. For the popup Admin_keys Privacy Policy and the Company & Conditions (Sep 17 Issue 2)			
	 	if(count($conditions)>0)		
	 		$this->db->where($conditions);
		
		$this->db->from('admin_key');
	 	$this->db->select();
		$result = $this->db->get();
		return $result;
		
	 }

	 
		
	/**
	 * Add  Static Admin_key
	 *
	 * @access	private
	 * @param	array	an associative array of insert values
	 * @return	void
	 */
	 function addAdmin_key($insertData=array())
	 {
	 	$this->db->insert('admin_key', $insertData);
		 
	 }//End of add Admin Key Function

	 
	// --------------------------------------------------------------------
		
	/**
	 * Update Static Admin_key
	 *
	 * @access	private
	 * @param	array	an associative array - for update key values
	 * @param	array	an associative array of update data
	 * @return	void
	 */
	 function updateAdmin_key($updateKey=array(),$updateData=array())
	 {
	 	 $this->db->update('admin_key',$updateData,$updateKey);
		 
	 }//End of update Admin Key Function 
	 

}
// End Admin_key Class
   
/* End of file Admin_key.php */ 
/* Location: ./app/models/admin_key_model.php */