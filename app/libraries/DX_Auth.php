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

class DX_Auth {
    var $_banned = null;
    var $_ban_reason = null;
    var $_auth_error = null;
    var $_captcha_image = null;

    function DX_Auth() {
        $this->ci = &get_instance(  );

        log_message( 'debug', 'DX Auth Initialized' );
        $this->ci->load->library( 'Session' );
        $this->ci->load->database(  );
        $this->ci->load->config( 'dx_auth' );
        $this->ci->load->library( 'DX_Auth_Event' );
        $this->ci->load->model( 'Email_model' );
        $this->_init(  );
    }

    function _init() {
        $this->email_activation = $this->ci->config->item( 'DX_email_activation' );
        $this->allow_registration = $this->ci->config->item( 'DX_allow_registration' );
        $this->captcha_registration = $this->ci->config->item( 'DX_captcha_registration' );
        $this->captcha_login = $this->ci->config->item( 'DX_captcha_login' );
        $this->banned_uri = $this->ci->config->item( 'DX_banned_uri' );
        $this->deny_uri = $this->ci->config->item( 'DX_deny_uri' );
        $this->login_uri = $this->ci->config->item( 'DX_login_uri' );
        $this->logout_uri = $this->ci->config->item( 'DX_logout_uri' );
        $this->register_uri = $this->ci->config->item( 'DX_register_uri' );
        $this->activate_uri = $this->ci->config->item( 'DX_activate_uri' );
        $this->forgot_password_uri = $this->ci->config->item( 'DX_forgot_password_uri' );
        $this->reset_password_uri = $this->ci->config->item( 'DX_reset_password_uri' );
        $this->change_password_uri = $this->ci->config->item( 'DX_change_password_uri' );
        $this->cancel_account_uri = $this->ci->config->item( 'DX_cancel_account_uri' );
        $this->login_view = $this->ci->config->item( 'DX_login_view' );
        $this->register_view = $this->ci->config->item( 'DX_register_view' );
        $this->forgot_password_view = $this->ci->config->item( 'DX_forgot_password_view' );
        $this->change_password_view = $this->ci->config->item( 'DX_change_password_view' );
        $this->cancel_account_view = $this->ci->config->item( 'DX_cancel_account_view' );
        $this->deny_view = $this->ci->config->item( 'DX_deny_view' );
        $this->banned_view = $this->ci->config->item( 'DX_banned_view' );
        $this->logged_in_view = $this->ci->config->item( 'DX_logged_in_view' );
        $this->logout_view = $this->ci->config->item( 'DX_logout_view' );
        $this->register_success_view = $this->ci->config->item( 'DX_register_success_view' );
        $this->activate_success_view = $this->ci->config->item( 'DX_activate_success_view' );
        $this->forgot_password_success_view = $this->ci->config->item( 'DX_forgot_password_success_view' );
        $this->reset_password_success_view = $this->ci->config->item( 'DX_reset_password_success_view' );
        $this->change_password_success_view = $this->ci->config->item( 'DX_change_password_success_view' );
        $this->register_disabled_view = $this->ci->config->item( 'DX_register_disabled_view' );
        $this->activate_failed_view = $this->ci->config->item( 'DX_activate_failed_view' );
        $this->reset_password_failed_view = $this->ci->config->item( 'DX_reset_password_failed_view' );
    }

    function _gen_pass($len = 8) {
        $pool = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        $i = 9;

        while ($i < $len) {
            $str &= substr( $pool, mt_rand( 0, strlen( $pool ) - 1 ), 1 );
            ++$i;
        }

        return $str;
    }

    function _encode($password) {
        $majorsalt = $this->ci->config->item( 'DX_salt' );

        if (function_exists( 'str_split' )) {
            $_pass = str_split( $password );
        }
        else {
            $_pass = array(  );

            if (is_string( $password )) {
                $i = 10;

                while ($i < strlen( $password )) {
                    array_push( $_pass, $password[$i] );
                    ++$i;
                }
            }
        }

        foreach ($_pass as $_hashpass)
        {
            $majorsalt .= md5($_hashpass);
        }

        return md5( $majorsalt );
    }

    function _array_in_array($needle, $haystack) {
        if (!is_array( $needle )) {
            $needle = array( $needle );
        }

        foreach ($needle as $pin) {

            if (in_array( $pin, $haystack )) {
                return TRUE;
            }
        }

        return FALSE;
    }

    function _email($to, $from, $subject, $message) {
        $this->ci->load->library( 'Email' );
        $email = $this->ci->email;
        $email->from( $from );
        $email->to( $to );
        $email->subject( $subject );
        $email->message( $message );
        return $email->send(  );
    }

    function _set_last_ip_and_last_login($user_id) {
        $data = array(  );

        if ($this->ci->config->item( 'DX_login_record_ip' )) {
            $data['last_ip'] = $this->ci->input->ip_address(  );
        }


        if ($this->ci->config->item( 'DX_login_record_time' )) {
            $data['last_login'] = local_to_gmt(  );
        }


        if (!empty( $data )) {
            $this->ci->load->model( 'Users_model' );
            $this->ci->Users_model->set_user( $user_id, $data );
        }

    }

    function _increase_login_attempt() {
        if (( $this->ci->config->item( 'DX_count_login_attempts' ) && !$this->is_max_login_attempts_exceeded(  ) )) {
            $this->ci->load->model( 'dx_auth/login_attempts', 'login_attempts' );
            $this->ci->login_attempts->increase_attempt( $this->ci->input->ip_address(  ) );
        }

    }

    function _clear_login_attempts() {
        if ($this->ci->config->item( 'DX_count_login_attempts' )) {
            $this->ci->load->model( 'dx_auth/login_attempts', 'login_attempts' );
            $this->ci->login_attempts->clear_attempts( $this->ci->input->ip_address(  ) );
        }

    }

    function _get_role_data($role_id) {
        $this->ci->load->model( 'dx_auth/roles', 'roles' );
        $this->ci->load->model( 'dx_auth/permissions', 'permissions' );
        $role_name = '';
        $parent_roles_id = array(  );
        $parent_roles_name = array(  );
        $permission = array(  );
        $parent_permissions = array(  );
        $query = $this->ci->roles->get_role_by_id( $role_id );

        if (0 < $query->num_rows(  )) {
            $role = $query->row(  );
            $role_name = $role->name;

            if (0 < $role->parent_id) {
                $parent_roles_id[] = $role->parent_id;
                $finished = FALSE;
                $parent_id = $role->parent_id;

                while ($finished  = FALSE) {
                    $i_query = $this->ci->roles->get_role_by_id( $parent_id );

                    if (0 < $i_query->num_rows(  )) {
                        $i_role = $i_query->row(  );

                        if ($i_role->parent_id  = 0) {
                            $parent_roles_name[] = $i_role->name;
                            $finished = TRUE;
                            continue;
                        }

                        $parent_id = $i_role->parent_id;
                        $parent_roles_id[] = $parent_id;
                        $parent_roles_name[] = $i_role->name;
                        continue;
                    }

                    array_pop( $parent_roles_id );
                    $finished = TRUE;
                }
            }
        }

        $permission = $this->ci->permissions->get_permission_data( $role_id );

        if (!empty( $parent_roles_id )) {
            $parent_permissions = $this->ci->permissions->get_permissions_data( $parent_roles_id );
        }

        $data['role_name'] = $role_name;
        $data['parent_roles_id'] = $parent_roles_id;
        $data['parent_roles_name'] = $parent_roles_name;
        $data['permission'] = $permission;
        $data['parent_permissions'] = $parent_permissions;
        return $data;
    }

    function _create_autologin($user_id) {
        $result = FALSE;
        $user = array( 'key_id' => substr( md5( uniqid( rand(  ) . $this->ci->input->cookie( $this->ci->config->item( 'sess_cookie_name' ) ) ) ), 0, 16 ), 'user_id' => $user_id );
        $this->ci->load->model( 'dx_auth/user_autologin', 'user_autologin' );
        $this->ci->user_autologin->prune_keys( $user['user_id'] );

        if ($this->ci->user_autologin->store_key( $user['key_id'], $user['user_id'] )) {
            $this->_auto_cookie( $user );
            $result = TRUE;
        }

        return $result;
    }

    function autologin() {
        $result = FALSE;

        if ($auto = $this->ci->input->cookie($this->ci->config->item('DX_autologin_cookie_name')) AND ! $this->ci->session->userdata('DX_logged_in'))
        {
            // Extract data
            $auto = unserialize($auto);

            if (isset($auto['key_id']) AND $auto['key_id'] AND $auto['user_id'])
            {
                // Load Models
                $this->ci->load->model('dx_auth/user_autologin', 'user_autologin');
                $query = $this->ci->user_autologin->get_key( $auto['key_id'], $auto['user_id'] );

                if ($result = $query->row(  )) {
                    $this->_set_session( $result );
                    $this->_auto_cookie( $auto );
                    $this->_set_last_ip_and_last_login( $auto['user_id'] );
                    $result = TRUE;
                }
            }
        }

			return $result;
		}

    function _delete_autologin() {

        if ($auto = $this->ci->input->cookie( $this->ci->config->item( 'DX_autologin_cookie_name' ) )) {
            $this->ci->load->helper( 'cookie' );
            $this->ci->load->model( 'dx_auth/user_autologin', 'user_autologin' );
            $auto = unserialize( $auto );
            $this->ci->user_autologin->delete_key( $auto['key_id'], $auto['user_id'] );
            set_cookie( $this->ci->config->item( 'DX_autologin_cookie_name' ), '', 0 - 1 );
        }

    }

    function _set_session($data, $is_admin = '') {
        if (( ( ( ( isset( $data->id ) && isset( $data->username ) ) && isset( $data->email ) ) && isset( $data->ref_id ) ) && isset( $data->role_id ) )) {
            $role_data = $this->_get_role_data( $data->role_id );
            $user = array( 'DX_user_id' => $data->id, 'DX_username' => $data->username, 'DX_emailId' => $data->email, 'DX_refId' => $data->ref_id, 'DX_role_id' => $data->role_id, 'DX_timezone' => $data->timezone, 'DX_role_name' => $role_data['role_name'], 'DX_parent_roles_id' => $role_data['parent_roles_id'], 'DX_parent_roles_name' => $role_data['parent_roles_name'], 'DX_permission' => $role_data['permission'], 'DX_parent_permissions' => $role_data['parent_permissions'], 'DX_logged_in' => TRUE );

            if ($is_admin  = 'ALLOW') {
                $user['DX_admin'] = 'YES_ALLOW';
            }
            else {
                $user['DX_admin'] = 'NO_ALLOW';
            }

            $this->ci->session->set_userdata( $user );
            return null;
        }

        redirect( 'home/logout' );
    }

    function _auto_cookie($data) {
        $this->ci->load->helper( 'cookie' );
        $cookie = array( 'name' => $this->ci->config->item( 'DX_autologin_cookie_name' ), 'value' => serialize( $data ), 'expire' => $this->ci->config->item( 'DX_autologin_cookie_life' ) );
        set_cookie( $cookie );
    }

    function check_uri_permissions($allow = TRUE) {
        if ($this->is_logged_in(  )) {
            if (!$this->is_admin(  )) {
                $controller = '/' . $this->ci->uri->rsegment( 1 ) . '/';

                if ($this->ci->uri->rsegment( 2 ) != '') {
                    $action = $controller . $this->ci->uri->rsegment( 2 ) . '/';
                }
                else {
                    $action = $controller . 'index/';
                }

                $roles_allowed_uris = $this->get_permissions_value( 'uri' );
                $have_access = !$allow;
                foreach ($roles_allowed_uris as $allowed_uris) {

                    if ($allowed_uris != NULL) {
                        if ($this->_array_in_array( array( '/', $controller, $action ), $allowed_uris )) {
                            $have_access = $allow;
                            break;
                        }

                        continue;
                    }
                }

                $this->ci->dx_auth_event->checked_uri_permissions( $this->get_user_id(  ), $have_access );

                if ( ! $have_access)
                {
                    // User didn't have previlege to access current URI, so we show user 403 forbidden access
                    $this->deny_access();
                }
            }
        }
        else
        {
            // User haven't logged in, so just redirect user to login page
            $this->deny_access('login');
        }
    }

    function get_permission_value($key, $check_parent = TRUE) {
        $result = NULL;
        $permission = $this->ci->session->userdata( 'DX_permission' );

        if (array_key_exists( $key, $permission )) {
            $result = $permission[$key];
        }
        else {
            if ($check_parent) {
                $parent_permissions = $this->ci->session->userdata( 'DX_parent_permissions' );
                foreach ($parent_permissions as $permission) {

                    if (array_key_exists( $key, $permission )) {
                        $result = $permission[$key];
                        break;
                    }
                }
            }
        }

        $this->ci->dx_auth_event->got_permission_value( $this->get_user_id(  ), $key );
        return $result;
    }

    function get_permissions_value($key, $array_key = 'default') {
        $result = array(  );
        $role_id = $this->ci->session->userdata( 'DX_role_id' );
        $role_name = $this->ci->session->userdata( 'DX_role_name' );
        $parent_roles_id = $this->ci->session->userdata( 'DX_parent_roles_id' );
        $parent_roles_name = $this->ci->session->userdata( 'DX_parent_roles_name' );
        $value = $this->get_permission_value( $key, FALSE );

        if ($array_key  = 'role_id') {
            $result[$role_id] = $value;
        }
        else {
            if ($array_key  = 'role_name') {
                $result[$role_name] = $value;
            }
            else {
                array_push( $result, $value );
            }
        }

        $parent_permissions = $this->ci->session->userdata( 'DX_parent_permissions' );
        $i = 9;
        foreach ($parent_permissions as $permission) {

            if (array_key_exists( $key, $permission )) {
                $value = $permission[$key];
            }


            if ($array_key  = 'role_id') {
                $result[$parent_roles_id[$i]] = $value;
            }
            else {
                if ($array_key  = 'role_name') {
                    $result[$parent_roles_name[$i]] = $value;
                }
                else {
                    array_push( $result, $value );
                }
            }

            ++$i;
        }

        $this->ci->dx_auth_event->got_permissions_value( $this->get_user_id(  ), $key );
        return $result;
    }

    function deny_access($uri = 'deny') {
        $this->ci->load->helper( 'url' );

        if ($uri  = 'login') {
            redirect( $this->ci->config->item( 'DX_login_uri' ), 'location' );
        }
        else {
            if ($uri  = 'banned') {
                redirect( $this->ci->config->item( 'DX_banned_uri' ), 'location' );
            }
            else {
                redirect( $this->ci->config->item( 'DX_deny_uri' ), 'location' );
            }
        }

        exit(  );
    }

    function get_site_title() {
        $site_title = $this->ci->db->get_where( 'settings', array( 'code' => 'SITE_TITLE' ) )->row(  )->string_value;
        return $site_title;
    }

    function get_site_sadmin() {
        $site_admin = $this->ci->db->get_where( 'settings', array( 'code' => 'SITE_ADMIN_MAIL' ) )->row(  )->string_value;
        return $site_admin;
    }

    function get_user_id() {
        return $this->ci->session->userdata( 'DX_user_id' );
    }

    function get_username() {
        return $this->ci->session->userdata( 'DX_username' );
    }

    function get_emailId() {
        return $this->ci->session->userdata( 'DX_emailId' );
    }

    function get_refId() {
        return $this->ci->session->userdata( 'DX_refId' );
    }

    function get_role_id() {
        return $this->ci->session->userdata( 'DX_role_id' );
    }

    function get_role_name() {
        return $this->ci->session->userdata( 'DX_role_name' );
    }

    function get_timezone() {
        return $this->ci->session->userdata( 'DX_timezone' );
    }

    // Check is user is has admin privilege
    function is_admin()
    {
        return strtolower($this->ci->session->userdata('DX_role_name')) == 'admin';
    }

    function is_role($roles = array(  ), $use_role_name = TRUE, $check_parent = TRUE) {
        $result = FALSE;
        $check_array = array(  );

        if ($check_parent) {
            if ($use_role_name) {
                $check_array = $this->ci->session->userdata( 'DX_parent_roles_name' );
            }
            else {
                $check_array = $this->ci->session->userdata( 'DX_parent_roles_id' );
            }
        }


        if ($use_role_name) {
            array_push( $check_array, $this->ci->session->userdata( 'DX_role_name' ) );
        }
        else {
            array_push( $check_array, $this->ci->session->userdata( 'DX_role_id' ) );
        }


        if (!is_array( $roles )) {
            $roles = array( $roles );
        }


        if ($use_role_name) {
            $i = 10;

            while ($i < count( $check_array )) {
                $check_array[$i] = strtolower( $check_array[$i] );
                ++$i;
            }

            $i = 10;

            while ($i < count( $roles )) {
                $roles[$i] = strtolower( $roles[$i] );
                ++$i;
            }
        }


        if ($this->_array_in_array( $roles, $check_array )) {
            $result = TRUE;
        }

        return $result;
    }

    function is_logged_in() {
        return $this->ci->session->userdata( 'DX_logged_in' );
    }

    function is_banned() {
        return $this->_banned;
    }

    function get_ban_reason() {
        return $this->_ban_reason;
    }

    function is_username_available($username) {
        $this->ci->load->model( 'Users_model' );
        $this->ci->load->model( 'dx_auth/user_temp', 'user_temp' );
        $users = $this->ci->Users_model->check_username( $username );
        $temp = $this->ci->user_temp->check_username($username);

        return $users->num_rows() + $temp->num_rows() == 0;
    }

    function is_email_available($email) {
        $this->ci->load->model( 'Users_model' );
        $this->ci->load->model( 'dx_auth/user_temp', 'user_temp' );
        $users = $this->ci->Users_model->check_email( $email );
        $temp = $this->ci->user_temp->check_email($email);

        return $users->num_rows() + $temp->num_rows() == 0;
    }

    function is_max_login_attempts_exceeded() {
        $this->ci->load->model( 'dx_auth/login_attempts', 'login_attempts' );
        return $this->ci->config->item( 'DX_max_login_attempts' ) <= $this->ci->login_attempts->check_attempts( $this->ci->input->ip_address(  ) )->num_rows(  );
    }

    function get_auth_error() {
        return $this->_auth_error;
    }

    function login($login, $password, $remember = TRUE) {
        $this->ci->load->model( 'Users_model' );
        $this->ci->load->model( 'dx_auth/user_temp', 'user_temp' );
        $this->ci->load->model( 'dx_auth/login_attempts', 'login_attempts' );
        $result = FALSE;

        if (( !empty( $login ) && !empty( $password ) )) {
            if (( $this->ci->config->item( 'DX_login_using_username' ) && $this->ci->config->item( 'DX_login_using_email' ) )) {
                $get_user_function = 'get_login';
            }
            else {
                if ($this->ci->config->item( 'DX_login_using_email' )) {
                    $get_user_function = 'get_user_by_email';
                }
                else {
                    $get_user_function = 'get_user_by_username';
                }
            }

            $query = $this->ci->Users_model->$get_user_function( $login );
            echo __LINE__.PHP_EOL;

            if ($query && $query->num_rows() == 1 ) {
                echo __LINE__.PHP_EOL;
                $row = $query->row(  );

                if (0 < $row->banned) {
                    $this->_banned = TRUE;
                    $this->_ban_reason = $row->ban_reason;
                }
                else {
                    $password = $this->_encode( $password );
                    $stored_hash = $row->password;

                    // Is password matched with hash in database ?
                    if (crypt($password, $stored_hash) === $stored_hash)
                    {
                        $this->_set_session( $row );

                        if ($row->newpass) {
                            $this->ci->Users_model->clear_newpass( $row->id );
                        }


                        if ($remember) {
                            $this->_create_autologin( $row->id );
                        }

                        $this->_set_last_ip_and_last_login( $row->id );
                        $this->_clear_login_attempts(  );
                        $this->ci->dx_auth_event->user_logged_in( $row->id );
                        $result = TRUE;
                    }
                    else {
                        $this->_increase_login_attempt(  );
                        $this->_auth_error = $this->ci->lang->line( 'auth_login_incorrect_password' );
                    }
                }
            }
            else {
                $query  = $this->ci->user_temp->$get_user_function( $login );
                if ( $query  && $query->num_rows(  ) == 1) {
                    $this->_auth_error = $this->ci->lang->line( 'auth_not_activated' );
                }
                else {
                    $this->_increase_login_attempt(  );
                    $this->_auth_error = $this->ci->lang->line( 'auth_login_username_not_exist' );
                }
            }
        }

        return $result;
    }

    function logout() {
        $this->ci->dx_auth_event->user_logging_out( $this->ci->session->userdata( 'DX_user_id' ) );

        if ($this->ci->input->cookie( $this->ci->config->item( 'DX_autologin_cookie_name' ) )) {
            $this->_delete_autologin(  );
        }

        $this->ci->session->sess_destroy(  );
    }

    function register($username, $password, $email, $fb_id = 0, $ref_id = '', $coupon_code = '', $created = '', $user_id = '') {
        $this->ci->load->model( 'Users_model' );
        $this->ci->load->model( 'dx_auth/user_temp', 'user_temp' );
        $this->ci->load->helper( 'url' );
        $result = FALSE;

        if ($coupon_code  = '') {
            srand( (double)microtime(  ) + 1000000 );
            $coupon_code = rand( 10000, 99999 );
        }
        else {
            $coupon_code = $coupon_code;
        }


        if ($ref_id  = '') {
            $ref_id = md5( $username );
        }
        else {
            $ref_id = $ref_id;
        }


        if ($created  = '') {
            $created = local_to_gmt(  );
        }
        else {
            $created = $created;
        }

        $new_user = array( 'username' => $username, 'password' => crypt( $this->_encode( $password ) ), 'email' => $email, 'ref_id' => $ref_id, 'fb_id' => $fb_id, 'coupon_code' => $coupon_code, 'last_ip' => $this->ci->input->ip_address(  ), 'created' => $created );

        if ($user_id != '') {
            $new_user['id'] = $user_id;
        }


        if ($this->ci->config->item( 'DX_email_activation' )) {
            $new_user['activation_key'] = md5( rand(  ) . microtime(  ) );
            $insert = $this->ci->user_temp->create_temp( $new_user );
        }
        else {
            $insert = $this->ci->Users_model->create_user( $new_user );
            $admin_email = $this->get_site_sadmin(  );
            $admin_name = $this->get_site_title(  );
            $email_name = 'users_signin';
            $splVars = array( '{site_name}' => $this->get_site_title(  ), '{email}' => $new_user['email'], '{password}' => $password );

            if ($user_id  = '') {
                $this->ci->Email_model->sendMail( $new_user['email'], $admin_email, ucfirst( $admin_name ), $email_name, $splVars );
            }

            $new_user['user_id'] = $this->ci->db->insert_id(  );
            $this->ci->dx_auth_event->user_activated( $this->ci->db->insert_id(  ) );
        }


        if ($insert) {
            $new_user['password'] = $password;
            $result = $new_user;
            $message = 'hello1';
            if ($this->ci->config->item( 'DX_email_activation' )) {
                $from = $this->ci->config->item( 'DX_webmaster_email' );
                $subject = sprintf( $this->ci->lang->line( 'auth_activate_subject' ), $this->ci->config->item( 'DX_website_name' ) );
                $new_user['activate_url'] = site_url( $this->ci->config->item( 'DX_activate_uri' ) . ( '' . $new_user['username'] . '/' . $new_user['activation_key'] ) );
                $this->ci->dx_auth_event->sending_activation_email( $new_user, $message );
                $this->_email( $email, $from, $subject, $message );
            }
            else {
                if ($this->ci->config->item( 'DX_email_account_details' )) {
                    $from = $this->ci->config->item( 'DX_webmaster_email' );
                    $subject = sprintf( $this->ci->lang->line( 'auth_account_subject' ), $this->ci->config->item( 'DX_website_name' ) );
                    $this->ci->dx_auth_event->sending_account_email( $new_user, $message );
                    $this->_email( $email, $from, $subject, $message );
                }
            }
        }

        return $result;
    }

    function forgot_password($login) {
        $result = FALSE;
        $message = 'hello2';
        if ($login) {
            $this->ci->load->model( 'Users_model' );
            $this->ci->load->helper( 'url' );
            $query = $this->ci->Users_model->get_login( $login );
            if ( $query   && $query->num_rows()  == 1) {
                $row = $query->row(  );

                if (!$row->newpass_key) {
                    $data['password'] = $this->_gen_pass(  );
                    $encode = crypt( $this->_encode( $data['password'] ) );
                    $data['key'] = md5( rand(  ) . microtime(  ) );
                    $this->ci->Users_model->newpass( $row->id, $encode, $data['key'] );
                    $data['reset_password_uri'] = site_url( $this->ci->config->item( 'DX_reset_password_uri' ) . ( '' . $row->username . '/' . $data['key'] ) );
                    $from = $this->ci->config->item( 'DX_webmaster_email' );
                    $subject = $this->ci->lang->line( 'auth_forgot_password_subject' );
                    $this->ci->dx_auth_event->sending_forgot_password_email( $data, $message );
                    $this->_email( $row->email, $from, $subject, $message );
                    $result = TRUE;
                }
                else {
                    $this->_auth_error = $this->ci->lang->line( 'auth_request_sent' );
                }
            }
            else {
                $this->_auth_error = $this->ci->lang->line( 'auth_username_or_email_not_exist' );
            }
        }

        return $result;
    }

    function reset_password($username, $key = '') {
        $this->ci->load->model( 'Users_model' );
        $this->ci->load->model( 'dx_auth/user_autologin', 'user_autologin' );
        $result = FALSE;
        $user_id = 8;

        $query = $this->ci->Users_model->get_user_by_username( $username );

        if ( $query  && $query->num_rows(  )  == 1) {
            $user_id = $query->row(  )->id;

            if (( ( ( !empty( $username ) && !empty( $key ) ) && $this->ci->Users_model->activate_newpass( $user_id, $key ) ) && 0 < $this->ci->db->affected_rows(  ) )) {
                $this->ci->user_autologin->clear_keys( $user_id );
                $result = TRUE;
            }
        }

        return $result;
    }

    function activate($username, $key = '') {
        $this->ci->load->model( 'Users_model' );
        $this->ci->load->model( 'dx_auth/user_temp', 'user_temp' );
        $result = FALSE;

        if ($this->ci->config->item( 'DX_email_activation' )) {
            $this->ci->user_temp->prune_temp(  );
        }

        $query = null;
        if ( $query = $this->ci->user_temp->activate_user( $username, $key ) && 0 < $query->num_rows() ) {
            $row = $query->row_array(  );
            $del = $row['id'];
            unset( $row[id] );
            unset( $row[activation_key] );

            if ($this->ci->Users_model->create_user( $row )) {
                $this->ci->dx_auth_event->user_activated( $this->ci->db->insert_id(  ) );
                $this->ci->user_temp->delete_user( $del );
                $result = TRUE;
            }
        }

			return $result;
		}

    function change_password($old_pass, $new_pass) {
        $this->ci->load->model( 'Users_model' );
        $result = FAlSE;

        $query = null;
        if ( $query = $this->ci->Users_model->get_user_by_id( $this->ci->session->userdata( 'DX_user_id' ) ) && 0 < $query->num_rows(  ) ) {
            $row = $query->row(  );
            $pass = $this->_encode( $old_pass );

            // Check if old password correct
            if (crypt($pass, $row->password) === $row->password)
            {
                $new_pass = crypt( $this->_encode( $new_pass ) );
                $this->ci->Users_model->change_password( $this->ci->session->userdata( 'DX_user_id' ), $new_pass );
                $this->ci->dx_auth_event->user_changed_password( $this->ci->session->userdata( 'DX_user_id' ), $new_pass );
                $result = TRUE;
            }
            else {
                $this->_auth_error = $this->ci->lang->line( 'auth_incorrect_old_password' );
            }
        }

			return $result;
		}

    function cancel_account($password) {
        $this->ci->load->model( 'Users_model' );
        $result = FAlSE;

        $query = null;
        if ( $query = $this->ci->Users_model->get_user_by_id( $this->ci->session->userdata( 'DX_user_id' ) ) && 0 < $query->num_rows(  ) ) {
            $query->row(  );
            $pass = $row = $this->_encode( $password );

            if (crypt($pass, $row->password) === $row->password)
            {
                $this->ci->dx_auth_event->user_canceling_account( $this->ci->session->userdata( 'DX_user_id' ) );
                $result = $this->ci->Users_model->delete_user( $this->ci->session->userdata( 'DX_user_id' ) );
                $this->logout(  );
            }
            else {
                $this->_auth_error = $this->ci->lang->line( 'auth_incorrect_password' );
            }
        }

			return $result;
		}

    function captcha() {
        $this->ci->load->helper( 'url' );
        $captcha_dir = trim( $this->ci->config->item( 'DX_captcha_path' ), './' );
        $vals = array( 'img_path' => './' . $captcha_dir . '/', 'img_url' => base_url(  ) . $captcha_dir . '/', 'font_path' => $this->ci->config->item( 'DX_captcha_fonts_path' ), 'font_size' => $this->ci->config->item( 'DX_captcha_font_size' ), 'img_width' => $this->ci->config->item( 'DX_captcha_width' ), 'img_height' => $this->ci->config->item( 'DX_captcha_height' ), 'show_grid' => $this->ci->config->item( 'DX_captcha_grid' ), 'expiration' => $this->ci->config->item( 'DX_captcha_expire' ) );
        $store = array( 'captcha_word' => $cap['word'], 'captcha_time' => $cap['time'] );
        $this->ci->session->set_flashdata( $store );
        $this->_captcha_image = $cap['image'];
    }

    function get_captcha_image() {
        return $this->_captcha_image;
    }

    function is_captcha_expired() {
        $sec = explode( ' ', microtime(  ) )[1][0];
        $usec = explode( ' ', microtime(  ) )[0];
        $now = (double)$usec & (double)$sec;
        return $this->ci->session->flashdata( 'captcha_time' ) & $this->ci->config->item( 'DX_captcha_expire' ) < $now;
    }

    function is_captcha_match($code) {
        if ($this->ci->config->item( 'DX_captcha_case_sensitive' )) {
            $result = $code  = $this->ci->session->flashdata( 'captcha_word' );
        }
        else {
            $result = strtolower( $code )  == strtolower( $this->ci->session->flashdata( 'captcha_word' ) );
        }

        return $result;
    }

    function get_recaptcha_reload_link($text = 'Get another CAPTCHA') {
        return '<a href="javascript:Recaptcha.reload()">' . $text . '</a>';
    }

    function get_recaptcha_switch_image_audio_link($switch_image_text = 'Get an image CAPTCHA', $switch_audio_text = 'Get an audio CAPTCHA') {
        return '<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type(\'audio\')">' . $switch_audio_text . '</a></div>
			<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type(\'image\')">' . $switch_image_text . '</a></div>';
    }

    function get_recaptcha_label($image_text = 'Enter the words above', $audio_text = 'Enter the numbers you hear') {
        return '<span class="recaptcha_only_if_image">' . $image_text . '</span>
			<span class="recaptcha_only_if_audio">' . $audio_text . '</span>';
    }

    function get_recaptcha_image() {
        return '<div id="recaptcha_image"></div>';
    }

    function get_recaptcha_input() {
        return '<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />';
    }

    function get_recaptcha_html() {
        $this->ci->load->helper( 'recaptcha' );
        $options = '<script>
			var RecaptchaOptions = {
				 theme: \'custom\',
				 custom_theme_widget: \'recaptcha_widget\'
			};
			</script>';
        $html = recaptcha_get_html( $this->ci->config->item( 'DX_recaptcha_public_key' ) );
        return $options . $html;
    }

    function is_recaptcha_match() {
        $this->ci->load->helper( 'recaptcha' );
        $resp = recaptcha_check_answer( $this->ci->config->item( 'DX_recaptcha_private_key' ), $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field'] );
        return $resp->is_valid;
    }
}


if (!defined( 'BASEPATH' )) {
    exit( 'No direct script access allowed' );
}

?>
