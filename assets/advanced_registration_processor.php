<?php
/************************************************
Registration Form Handler for TNG Registration
************************************************/
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

//function to process form data
function tngwp_process_simple_registration() {
	global $wpdb;
	//Extract form data
	extract($_POST); 
	//Grab the posted variables
	$first_name = stripslashes($_POST['first_name']);
	$last_name = stripslashes($_POST['last_name']);
	$city = stripslashes($_POST['city']);
	$state_prov = stripslashes($_POST['state_prov']);
	$postalcode = stripslashes($_POST['postalcode']);
	$country = stripslashes($_POST['country']);
	$user_url = stripslashes($_POST['user_url']);
	$user_login = stripslashes($_POST['user_login']);
	$user_email = stripslashes($_POST['user_email']);
	$email = stripslashes($_POST['confirm_email']);
	$user_pass = stripslashes($_POST['password']);
	$pass = md5($user_pass);
	$mbtng_tree = stripslashes($_POST['tree']);
	$interest = stripslashes($_POST['interest']);
	$relationship = stripslashes($_POST['relationship']);
	$comments = stripslashes($_POST['comments']);
	//Structure Emails to Admin and New User
	$admin = get_bloginfo('admin_email');
	$asubject = 'A new user has registered on '.get_bloginfo('name');
	$user = get_user_by( 'email', $admin );
	$admin_name = $user->first_name;
	$abody = '
	  <p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">Dear '.$admin_name.'</p>
	  <p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">'.$first_name.' '.$last_name.' has registered on '.get_bloginfo('name').'.
		They have provided the following information:
	  <table style="width:100%; border-collapse: collapse; font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">
	  <tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Email:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$email.'</td>
	  </tr>
	  <tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Location:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$city.', '.$state_prov.', '.$country.'</td>
	  </tr>
		<tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Website:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$user_url.'</td>
	  </tr>
	  <tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">User Name:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$user_login.'</td>
	  </tr>
	  <tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Email:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$user_email.'</td>
	  </tr>
	  <tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Tree:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$mbtng_tree.'</td>
	  </tr>
	  <tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Interest:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$interest.'</td>
	  </tr>
	  <tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Relationship:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$relationship.'</td>
	  </tr>
	  </table>
	  <p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem; font-weight: bold;">Comments:</p>
	  <p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">'.$comments.'</p>
	';
	
	$usubject = 'Registration Information for '.get_bloginfo('name');
	$ubody = '
	  <p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">Dear '.$first_name.'</p>
	  <p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">Your request for a user account has been received. Before you can begin to add your information to our family tree, your
		account needs to be reviewed and activated. You will receive another email when that is done and then you will be able to 
		log in with your username, '.$user_login.', and the password you created when you registered.</p>
	  <p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">Thank you for registering!</p>
	  ';
	
	$urlparts = wp_parse_url(home_url());
	$domain = $urlparts['host'];
	$afrom = 'wordpress@'.$domain;
	$aheaders[] = 'Content-Type: text/html; charset=UTF-8';
	$aheaders[] = 'From: '.$admin;
	$uheaders[] = 'Content-Type: text/html; charset=UTF-8';
	$uheaders[] = 'From: '.$admin;
	mail($admin, $asubject, $abody, implode("\r\n", $aheaders));
	mail($user_email, $usubject, $ubody, implode("\r\n", $uheaders));
	
	//Process user
	// Add the new user to TNG
	$date = date("m-d-y, h:m");
	$real_name = $first_name." ".$last_name;
	$wpdb->query( $wpdb->prepare(
		"INSERT INTO tng_users (description, username, password, password_type, role, allow_living, realname, email, dt_registered) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s )",
		array(
			'member',
			$user_login,
			$pass,
			'sha256',
			'guest',
			-1,
			$real_name,
			$user_email,
			$date
		)
	));
	$wpdb->flush();

	// Add the new user to WordPress
	$userdata = array (
		'user_login' => $user_login,
		'user_pass' => $user_pass,
		'user_nicename' => $first_name.' '.$last_name,
		'user_email' => $user_email,
		'user_url' => $user_url,
		'nickname' => $first_name.' '.$last_name,
		'first_name' => $first_name,
		'last_name' => $last_name,
		'city' => $city,
		'state_prov' => $state_prov,
		'postalcode' => $postalcode,
		'country' => $country,
		'comment_shortcuts' => 1,
		'rich_editing' => 1,
		'show_admin_bar_front' => 0
	);

	//my function to create new WordPress user
	function tng_meta_insert_user($userdata) {
		global $wpdb;

		extract($userdata, EXTR_SKIP);

		// Are we updating or creating?
		if ( !empty($ID) ) {
			$ID = (int) $ID;
			$update = true;
			$old_user_data = WP_User::get_data_by( 'id', $ID );
		} else {
			$update = false;
			// Hash the password
			$user_pass = wp_hash_password($user_pass);
		}

		$user_login = sanitize_user($user_login, true);
		$user_login = apply_filters('pre_user_login', $user_login);

		//Remove any non-printable chars from the login string to see if we have ended up with an empty username
		$user_login = trim($user_login);

		if ( empty($user_login) )
			return new WP_Error('empty_user_login', __('Cannot create a user with an empty login name.') );

		if ( !$update && username_exists( $user_login ) )
			return new WP_Error('existing_user_login', __('This username is already registered.') );

		if ( empty($user_nicename) )
			$user_nicename = sanitize_title( $user_login );
		$user_nicename = apply_filters('pre_user_nicename', $user_nicename);

		if ( empty($user_url) )
			$user_url = '';
		$user_url = apply_filters('pre_user_url', $user_url);

		if ( empty($user_email) )
			$user_email = '';
		$user_email = apply_filters('pre_user_email', $user_email);

		if ( !$update && ! defined( 'WP_IMPORTING' ) && email_exists($user_email) )
			return new WP_Error('existing_user_email', __('This email address is already registered.') );

		if ( empty($display_name) )
			$display_name = $user_login;
		$display_name = apply_filters('pre_user_display_name', $display_name);

		if ( empty($nickname) )
			$nickname = $user_login;
		$nickname = apply_filters('pre_user_nickname', $nickname);

		if ( empty($first_name) )
			$first_name = '';
		$first_name = apply_filters('pre_user_first_name', $first_name);

		if ( empty($last_name) )
			$last_name = '';
		$last_name = apply_filters('pre_user_last_name', $last_name);

		if ( empty($description) )
			$description = '';
		$description = apply_filters('pre_user_description', $description);

		if ( empty($rich_editing) )
			$rich_editing = 'true';

		if ( empty($comment_shortcuts) )
			$comment_shortcuts = 'false';

		if ( empty($admin_color) )
			$admin_color = 'fresh';
		$admin_color = preg_replace('|[^a-z0-9 _.\-@]|i', '', $admin_color);

		if ( empty($use_ssl) )
			$use_ssl = 0;

		if ( empty($user_registered) )
			$user_registered = gmdate('Y-m-d H:i:s');

		if ( empty($show_admin_bar_front) )
			$show_admin_bar_front = 'false';

		$user_nicename_check = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->users WHERE user_nicename = %s AND user_login != %s LIMIT 1" , $user_nicename, $user_login));

		if ( $user_nicename_check ) {
			$suffix = 2;
			while ($user_nicename_check) {
				$alt_user_nicename = $user_nicename . "-$suffix";
				$user_nicename_check = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->users WHERE user_nicename = %s AND user_login != %s LIMIT 1" , $alt_user_nicename, $user_login));
				$suffix++;
			}
			$user_nicename = $alt_user_nicename;
		}

		$data = compact( 'user_pass', 'user_email', 'user_url', 'user_nicename', 'display_name', 'user_registered' );
		$data = stripslashes_deep( $data );

		if ( $update ) {
			$wpdb->update( $wpdb->users, $data, compact( 'ID' ) );
			$user_id = (int) $ID;
		} else {
			$wpdb->insert( $wpdb->users, $data + compact( 'user_login' ) );
			$user_id = (int) $wpdb->insert_id;
		}

		$user = new WP_User( $user_id );

		foreach ( _get_additional_user_keys( $user ) as $key ) {
			if ( isset( $$key ) )
				update_user_meta( $user_id, $key, $$key );
		}

/*		foreach(get_user_address_profile_list() as $key => $value) {
		update_user_meta( $user_id, $key, $_POST[$key] );
		}
*/
		if ( isset($role) )
			$user->set_role($role);
		elseif ( !$update )
			$user->set_role(get_option('default_role'));

		wp_cache_delete($user_id, 'users');
		wp_cache_delete($user_login, 'userlogins');

		if ( $update )
			do_action('profile_update', $user_id, $old_user_data);
		else
			do_action('user_register', $user_id);

		return $user_id;
		$wpdb->flush();
	}

	$new_user_id = tng_meta_insert_user( wp_slash( $userdata ) );

	//send user to success page
		$host  = $_SERVER['HTTP_HOST'];
		$options = get_option('tngwp-frontend-user-functions-options');
		$page = $options['success_page'];
		$permalink = get_permalink( get_page_by_title( $page ) );
		wp_redirect( $permalink );
}
?>
