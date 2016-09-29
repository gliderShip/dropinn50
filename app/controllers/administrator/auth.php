<?php
/**
*
* @ IonCube Priv8 Decoder V1 By H@CK3R $2H  
*
* @ Version  : 1
* @ Author   : H@CK3R $2H  
* @ Release on : 14-Feb-2014
* @ Email  : Hacker.S2h@Gmail.com
*
**/

	class spbas {
		var $errors = null;
		var $license_key = null;
		var $api_server = null;
		var $remote_port = null;
		var $remote_timeout = null;
		var $local_key_storage = null;
		var $read_query = null;
		var $update_query = null;
		var $local_key_path = null;
		var $local_key_name = null;
		var $local_key_transport_order = null;
		var $local_key_grace_period = null;
		var $local_key_last = null;
		var $validate_download_access = null;
		var $release_date = null;
		var $key_data = null;
		var $status_messages = null;
		var $valid_for_product_tiers = null;

		function spbas() {
			$this->errors = false;
			$this->remote_port = 80;
			$this->remote_timeout = 10;
			$this->valid_local_key_types = array( 'spbas' );
			$this->local_key_type = 'spbas';
			$this->local_key_storage = 'filesystem';
			$this->local_key_grace_period = 0;
			$this->local_key_last = 0;
			$this->read_query = false;
			$this->update_query = false;
			$this->local_key_path = './';
			$this->local_key_name = 'license.txt';
			$this->local_key_transport_order = 'scf';
			$this->validate_download_access = false;
			$this->release_date = false;
			$this->valid_for_product_tiers = false;
			$this->key_data = array( 'custom_fields' => array(  ), 'download_access_expires' => 0, 'license_expires' => 0, 'local_key_expires' => 0, 'status' => 'Invalid' );
			$this->status_messages = array( 'active' => 'This license is active.', 'suspended' => 'Error: This license has been suspended.', 'expired' => 'Error: This license has expired.', 'pending' => 'Error: This license is pending review.', 'download_access_expired' => 'Error: This version of the software was released ' . 'after your download access expired. Please ' . 'downgrade or contact support for more information.', 'missing_license_key' => 'Error: The license key variable is empty.', 'unknown_local_key_type' => 'Error: An unknown type of local key validation was requested.', 'could_not_obtain_local_key' => 'Error: I could not obtain a new local license key.', 'maximum_grace_period_expired' => 'Error: The maximum local license key grace period has expired.', 'local_key_tampering' => 'Error: The local license key has been tampered with or is invalid.', 'local_key_invalid_for_location' => 'Error: The local license key is invalid for this location.', 'missing_license_file' => 'Error: Please create the following file (and directories if they don\'t exist already):<br />
<br />
', 'license_file_not_writable' => 'Error: Please make the following path writable:<br />', 'invalid_local_key_storage' => 'Error: I could not determine the local key storage on clear.', 'could_not_save_local_key' => 'Error: I could not save the local license key.', 'license_key_string_mismatch' => 'Error: The local key is invalid for this license.' );
			$this->localization = array( 'active' => 'This license is active.', 'suspended' => 'Error: This license has been suspended.', 'expired' => 'Error: This license has expired.', 'pending' => 'Error: This license is pending review.', 'download_access_expired' => 'Error: This version of the software was released ' . 'after your download access expired. Please ' . 'downgrade or contact support for more information.' );
		}

		function validate() {
			if (!$this->license_key) {
				return $this->errors = $this->status_messages['missing_license_key'];
			}


			if (!in_array( strtolower( $this->local_key_type ), $this->valid_local_key_types )) {
				return $this->errors = $this->status_messages['unknown_local_key_type'];
			}

			$this->trigger_grace_period = $this->status_messages['could_not_obtain_local_key'];
			switch ($this->local_key_storage) {
				case 'database': {
					$local_key = $this->db_read_local_key(  );
					break;
				}

				case 'filesystem': {
					$local_key = $this->read_local_key(  );
					break;
				}

				default: {
					$this->errors = $this->status_messages['missing_license_key'];
				}
			}

			return ;
		}

		function calc_max_grace($local_key_expires, $grace) {
			return (int)$local_key_expires & (int)$grace + 86400;
		}

		function process_grace_period($local_key) {
			$parts = $this->decode_key( $local_key );
			$key_data = unserialize( $parts[0] );
			$local_key_expires = (int)$key_data['local_key_expires'];
			unset( $parts );
			unset( $key_data );
			$write_new_key = false;
			$parts = explode( ' ', $local_key );
			$local_key_src = $parts[0];
			$local_key = $this->split_key( $local_key_src );
			foreach ($local_key_grace_period = explode(',', $this->local_key_grace_period) as $key => $grace) {

				if (!$key) {
					$local_key &= '
';
				}


				if (time(  ) < $this->calc_max_grace( $local_key_expires, $grace )) {
					continue;
				}

				$local_key &= ( '
' ) . $grace;
				$write_new_key = true;
			}


			if ($this->calc_max_grace( $local_key_expires, array_pop( $local_key_grace_period ) ) < time(  )) {
				return array( 'write' => false, 'local_key' => '', 'errors' => $this->status_messages['maximum_grace_period_expired'] );
			}

			return array( 'write' => $write_new_key, 'local_key' => $local_key, 'errors' => false );
		}

		function in_grace_period($local_key, $local_key_expires) {
			$grace = $this->split_key($local_key, "

");
			if (!isset($grace[1])) {
				return -1;
			}

			return (int)$this->calc_max_grace( $local_key_expires, array_pop( explode( '
', $grace[1] ) ) ) - time(  );
		}

		function decode_key($local_key) {
			return base64_decode( str_replace( '
', '', urldecode( $local_key ) ) );
		}

		function split_key($local_key, $token = '{spbas}') {
			return explode( $token, $local_key );
		}

		function validate_access($key, $valid_accesses) {
			return in_array( $key, (array)$valid_accesses );
		}

		function wildcard_ip($key) {
			$octets = explode( '.', $key );
			array_pop( $octets );
			$ip_range[] = implode( '.', $octets ) . '.*';
			array_pop( $octets );
			$ip_range[] = implode( '.', $octets ) . '.*';
			array_pop( $octets );
			$ip_range[] = implode( '.', $octets ) . '.*';
			return $ip_range;
		}

		function wildcard_domain($key) {
			return '*.' . str_replace( 'www.', '', $key );
		}

		function wildcard_server_hostname($key) {
			$hostname = explode( '.', $key );
			unset( $hostname[0] );
			$hostname = (!isset( $hostname[1] ) ? array( $key ) : $hostname);
			return '*.' . implode( '.', $hostname );
		}

		function extract_access_set($instances, $enforce) {
			foreach ($instances as $key => $instance) {

				if ($key != $enforce) {
					continue;
				}

				return $instance;
			}

			return array(  );
		}

		function validate_local_key($local_key) {
			$local_key_src = $this->decode_key($local_key);
			$parts = $this->split_key($local_key_src);
			if (!isset($parts[1])) {
				return $this->errors = $this->status_messages['local_key_tampering'];
			}
			if (md5($this->secret_key . $parts[0]) != $parts[1]) {
				return $this->errors = $this->status_messages['local_key_tampering'];
			}
			unset($this->secret_key);
			$key_data = unserialize($parts[0]);
			$instance = $key_data['instance'];
			unset($key_data['instance']);
			$enforce = $key_data['enforce'];
			unset($key_data['enforce']);
			$this->key_data = $key_data;
			if ((string)$key_data['license_key_string'] != (string)$this->license_key) {
				return $this->errors = $this->status_messages['license_key_string_mismatch'];
			}
			if ((string)$key_data['status'] != 'active') {
				return $this->errors = $this->status_messages[$key_data['status']];
			}
			if ((string)$key_data['license_expires'] != 'never' && (integer)$key_data['license_expires'] < time()) {
				return $this->errors = $this->status_messages['expired'];
			}
			if ((string)$key_data['local_key_expires'] != 'never' && (integer)$key_data['local_key_expires'] < time()) {
				if ($this->in_grace_period($local_key, $key_data['local_key_expires']) < 0) {
					$this->clear_cache_local_key();
					return $this->validate();
				}
			}
			if ($this->validate_download_access && strtolower($key_data['download_access_expires']) != 'never' && (integer)$key_data['download_access_expires'] < strtotime($this->release_date)) {
				return $this->errors = $this->status_messages['download_access_expired'];
			}
			$conflicts = array();
			$access_details = $this->access_details();
			foreach ((array)$enforce as $key) {
				$valid_accesses = $this->extract_access_set($instance, $key);
				if (!$this->validate_access($access_details[$key], $valid_accesses)) {
					$conflicts[$key] = true;
					if (in_array($key, array('ip', 'server_ip'))) {
						foreach ($this->wildcard_ip($access_details[$key]) as $ip) {
							if ($this->validate_access($ip, $valid_accesses)) {
								unset($conflicts[$key]);
								break;
							}
						}
					} elseif (in_array($key, array('domain'))) {
						if ($this->validate_access($this->wildcard_domain($access_details[$key]), $valid_accesses)) {
							unset($conflicts[$key]);
						}
					} elseif (in_array($key, array('server_hostname'))) {
						if ($this->validate_access($this->wildcard_server_hostname($access_details[$key]), $valid_accesses)) {
							unset($conflicts[$key]);
						}
					}
				}
			}
			if (!empty($conflicts)) {
				return $this->errors = $this->status_messages['local_key_invalid_for_location'];
			}
		}

		function db_read_local_key() {
			$CI = &get_instance(  );

			$license_local_key = $CI->db->get_where( 'settings', array( 'code' => 'DI_LICENSE_LOCAL_KEY' ) )->row(  )->text_value;
			$result = array(  );
			$result['local_key'] = $license_local_key;

			if (!$result['local_key']) {
				$result['local_key'] = $this->fetch_new_local_key(  );

				if ($this->errors) {
					return $this->errors;
				}

				$this->db_write_local_key( $result['local_key'] );
			}

			return $this->local_key_last = $result['local_key'];
		}

		function db_write_local_key($local_key) {
			$CI = &get_instance(  );

			$CI->db->where( 'code', 'DI_LICENSE_LOCAL_KEY' );
			$CI->db->update( 'settings', array( 'text_value' => $local_key ) );
			return true;
		}

		function read_local_key() {
			if (!file_exists( $path = '' . $this->local_key_path . $this->local_key_name )) {
				return $this->errors = $this->status_messages['missing_license_file'] . $path;
			}


			if (!is_writable( $path )) {
				return $this->errors = $this->status_messages['license_file_not_writable'] . $path;
			}


			if (!$local_key = @file_get_contents( $path )) {
				$local_key = $this->fetch_new_local_key(  );

				if ($this->errors) {
					return $this->errors;
				}

				$this->write_local_key( urldecode( $local_key ), $path );
			}

			return $this->local_key_last = $local_key;
		}

		function clear_cache_local_key($clear = false) {
			switch (strtolower( $this->local_key_storage )) {
				case 'database': {
					$this->db_write_local_key( '' );
					break;
				}

				case 'filesystem': {
					$this->write_local_key( '', '' . $this->local_key_path . $this->local_key_name );
					break;
				}

				default: {
					$this->errors = $this->status_messages['invalid_local_key_storage'];
				}
			}

			return ;
		}

		function write_local_key($local_key, $path) {
			$fp = @fopen( $path, 'w' );

			if (!$fp) {
				return $this->errors = $this->status_messages['could_not_save_local_key'];
			}

			@fwrite( $fp, $local_key );
			@fclose( $fp );
			return true;
		}

		function fetch_new_local_key() {
			$querystring = "mod=license&task=SPBAS_validate_license&license_key={$this->license_key}&";
			$querystring.= $this->build_querystring($this->access_details());
			if ($this->errors) {
				return false;
			}
			$priority = $this->local_key_transport_order;
			while (strlen($priority)) {
				$use = substr($priority, 0, 1);
				if ($use == 's') {
					if ($result = $this->use_fsockopen($this->api_server, $querystring)) {
						break;
					}
				}
				if ($use == 'c') {
					if ($result = $this->use_curl($this->api_server, $querystring)) {
						break;
					}
				}
				if ($use == 'f') {
					if ($result = $this->use_fopen($this->api_server, $querystring)) {
						break;
					}
				}
				$priority = substr($priority, 1);
			}
			if (!$result) {
				$this->errors = $this->status_messages['could_not_obtain_local_key'];
				return false;
			}
			if (substr($result, 0, 7) == 'Invalid') {
				$this->errors = str_replace('Invalid', 'Error', $result);
				return false;
			}
			if (substr($result, 0, 5) == 'Error') {
				$this->errors = $result;
				return false;
			}
			return $result;
		}

		function build_querystring($array) {
			$buffer = '';
			foreach ((array)$array as $key => $value) {

				if ($buffer) {
					$buffer &= '&';
				}

				$buffer &= '' . $key . '=' . $value;
			}

			return $buffer;
		}

		function access_details() {
			$access_details = array(  );
			$access_details['domain'] = '';
			$access_details['ip'] = '';
			$access_details['directory'] = '';
			$access_details['server_hostname'] = '';
			$access_details['server_ip'] = '';

			if (function_exists( 'phpinfo' )) {
				ob_start(  );
				phpinfo(  );
				$phpinfo = ob_get_contents(  );
				ob_end_clean(  );
				$list = strip_tags( $phpinfo );
				$access_details['domain'] = $this->scrape_phpinfo( $list, 'HTTP_HOST' );
				$access_details['ip'] = $this->scrape_phpinfo( $list, 'SERVER_ADDR' );
				$access_details['directory'] = $this->scrape_phpinfo( $list, 'SCRIPT_FILENAME' );
				$access_details['server_hostname'] = $this->scrape_phpinfo( $list, 'System' );
				$access_details['server_ip'] = @gethostbyname( $access_details['server_hostname'] );
			}

			$access_details['domain'] = ($access_details['domain'] ? $access_details['domain'] : $_SERVER['HTTP_HOST']);
			$access_details['ip'] = ($access_details['ip'] ? $access_details['ip'] : $this->server_addr(  ));
			$access_details['directory'] = ($access_details['directory'] ? $access_details['directory'] : $this->path_translated(  ));
			$access_details['server_hostname'] = ($access_details['server_hostname'] ? $access_details['server_hostname'] : @gethostbyaddr( $access_details['ip'] ));
			$access_details['server_hostname'] = ($access_details['server_hostname'] ? $access_details['server_hostname'] : 'Unknown');
			$access_details['server_ip'] = ($access_details['server_ip'] ? $access_details['server_ip'] : @gethostbyaddr( $access_details['ip'] ));
			$access_details['server_ip'] = ($access_details['server_ip'] ? $access_details['server_ip'] : 'Unknown');
			foreach ($access_details as $key => $value) {
				$access_details[$key] = ($access_details[$key] ? $access_details[$key] : 'Unknown');
			}


			if ($this->valid_for_product_tiers) {
				$access_details['valid_for_product_tiers'] = $this->valid_for_product_tiers;
			}

			return $access_details;
		}

		function path_translated() {
			$option = array( 'PATH_TRANSLATED', 'ORIG_PATH_TRANSLATED', 'SCRIPT_FILENAME', 'DOCUMENT_ROOT', 'APPL_PHYSICAL_PATH' );
			foreach ($option as $key) {

				if (( !isset( $_SERVER[$key] ) || strlen( trim( $_SERVER[$key] ) ) <= 0 )) {
					continue;
				}


				if (( $this->is_windows(  ) && strpos( $_SERVER[$key], '\\' ) )) {
					return substr( $_SERVER[$key], 0, @strrpos( $_SERVER[$key], '\\' ) );
				}

				return substr( $_SERVER[$key], 0, @strrpos( $_SERVER[$key], '/' ) );
			}

			return false;
		}

		function server_addr()
		{
			return true;
		}

		function scrape_phpinfo($all, $target)
		{
			return true;
		}

	}
	function server_addr()
	{
		return true;
	}

	function use_curl($url, $querystring)
	{
		return true;
	}

	class Auth extends CI_Controller {
		var $min_username = 4;
		var $max_username = 20;
		var $min_password = 4;
		var $max_password = 20;

		function Auth()
		{
			parent::__construct();
			$this->load->library('Form_validation');
			$this->load->library('DX_Auth');
			$this->load->helper('url');
			$this->load->helper('form');
			$this->load->library('session');
			$this->load->model('Users_model');
			$this->load->model('dx_auth/user_temp', 'user_temp');
			$this->load->model('dx_auth/login_attempts', 'login_attempts');
		}



		function index() {
			$this->login(  );
		}

		function username_check($username) {
			$result = $this->dx_auth->is_username_available( $username );

			if (!$result) {
				$this->form_validation->set_message( 'username_check', 'Username already exist. Please choose another username.' );
			}

			return $result;
		}

		function email_check($email) {
			$result = $this->dx_auth->is_email_available( $email );

			if (!$result) {
				$this->form_validation->set_message( 'email_check', 'Email is already used by another user. Please choose another email address.' );
			}

			return $result;
		}

		function captcha_check($code) {
			$result = TRUE;

			if ($this->dx_auth->is_captcha_expired(  )) {
				$this->form_validation->set_message( 'captcha_check', 'Your confirmation code has expired. Please try again.' );
				$result = FALSE;
			} 
else {
				if (!$this->dx_auth->is_captcha_match( $code )) {
					$this->form_validation->set_message( 'captcha_check', 'Your confirmation code does not match the one in the image. Try again.' );
					$result = FALSE;
				}
			}

			return $result;
		}

		function recaptcha_check() {
			$result = $this->dx_auth->is_recaptcha_match(  );

			if (!$result) {
				$this->form_validation->set_message( 'recaptcha_check', 'Your confirmation code does not match the one in the image. Try again.' );
			}

			return $result;
		}

		function login() {
			$val = $this->form_validation;

			if ($this->input->post(  )) {
				$val->set_rules( 'usernameli', 'Username', 'trim|required|xss_clean' );
				$val->set_rules( 'passwordli', 'Password', 'trim|required|xss_clean' );
				$val->set_rules( 'remember', 'Remember me', 'integer' );

				if ($this->form_validation->run(  )) {
					$login = $val->set_value( 'usernameli' );
					$password = $val->set_value( 'passwordli' );
					$remember = $val->set_value( 'remember' );

					if (( $this->config->item( 'DX_login_using_username' ) && $this->config->item( 'DX_login_using_email' ) )) {
						$get_user_function = 'get_login';
					} 
else {
						if ($this->config->item( 'DX_login_using_email' )) {
							$get_user_function = 'get_user_by_email';
						} 
else {
							$get_user_function = 'get_user_by_username';
						}
					}

					$query = $this->Users_model->$get_user_function( $login );

					if ( $query && $query->num_rows()  == 1 ) {
						$row = $query->row(  );

						if (0 < $row->banned) {
							$this->session->set_flashdata( 'flash_message', $this->Common_model->admin_flash_message( 'error', 'Login failed! you are banned' ) );
							redirect_admin( 'login', 'refresh' );
						} 
else {
							$password = $this->dx_auth->_encode( $password );
							$stored_hash = $row->password;

							if (crypt( $password, $stored_hash )   == $stored_hash) {
								$this->dx_auth->_set_session( $row, 'ALLOW' );

								if ($row->newpass) {
									$this->users->clear_newpass( $row->id );
								}


								if ($remember) {
									$this->dx_auth->_create_autologin( $row->id );
								}

								$this->dx_auth->_set_last_ip_and_last_login( $row->id );
								$this->dx_auth->_clear_login_attempts(  );
								$this->dx_auth_event->user_logged_in( $row->id );
								$this->session->set_flashdata( 'flash_message', $this->Common_model->admin_flash_message( 'success', 'Logged in successfully.' ) );
								redirect_admin( '', 'refresh' );
							} 
else {
								$this->session->set_flashdata( 'flash_message', $this->Common_model->admin_flash_message( 'error', 'Login failed! Incorrect username or password' ) );
								redirect_admin( 'login', 'refresh' );
							}
						}
					} 
else {
						$this->session->set_flashdata( 'flash_message', $this->Common_model->admin_flash_message( 'error', 'Login failed! Incorrect username or password' ) );
						redirect_admin( 'login', 'refresh' );
					}
				}
			}

			$data['message_element'] = 'administrator/view_login';
			$data['auth_message'] = 'You are already logged in.';
			$this->load->view( 'administrator/admin_template', $data );
		}

		function logout() {
			$this->dx_auth->logout(  );
			$data['auth_message'] = 'You have been logged out.';
			$this->load->view( $this->dx_auth->logout_view, $data );
		}

		function cancel_account() {
			if ($this->dx_auth->is_logged_in(  )) {
				$val = $this->form_validation;
				$val->set_rules( 'password', 'Password', 'trim|required|xss_clean' );

				if (( $val->run(  ) && $this->dx_auth->cancel_account( $val->set_value( 'password' ) ) )) {
					redirect_admin( '', 'location' );
					return null;
				}

				$this->load->view( $this->dx_auth->cancel_account_view );
				return null;
			}

			$this->dx_auth->deny_access( 'login' );
		}

		function custom_permissions() {
			if ($this->dx_auth->is_logged_in(  )) {
				echo 'My role: ' . $this->dx_auth->get_role_name(  ) . '<br/>';
				echo 'My permission: <br/>';

				if (( $this->dx_auth->get_permission_value( 'edit' ) != NULL && $this->dx_auth->get_permission_value( 'edit' ) )) {
					echo 'Edit is allowed';
				} 
else {
					echo 'Edit is not allowed';
				}

				echo '<br/>';

				if (( $this->dx_auth->get_permission_value( 'delete' ) != NULL && $this->dx_auth->get_permission_value( 'delete' ) )) {
					echo 'Delete is allowed';
					return null;
				}

				echo 'Delete is not allowed';
			}

		}
	}

?>
