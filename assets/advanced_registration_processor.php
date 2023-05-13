<?php
/************************************************
Registration Form Handler for TNG Registration
************************************************/
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
global $whom, $wpdb;

if ((isset($_POST['submit']))) {
	extract($_POST);
	//Grab the posted variables
	$relative = stripslashes($_POST['relative']);
	$relation = stripslashes($_POST['relation']);
	$whom = $_POST['whom'];
	$personID = $_POST['newid'];
	
	//parents
	$father_firstname = stripslashes($_POST['father_firstname']);
	$father_lastname = stripslashes($_POST['father_lastname']);
	$father_birthdate = stripslashes($_POST['father_birthdate']);
	$mother_firstname = stripslashes($_POST['mother_firstname']);
	$mother_maidenname = stripslashes($_POST['mother_maidenname']);
	$mother_birthdate = stripslashes($_POST['mother_birthdate']);
	$parents_mar_date = stripslashes($_POST['parents_mar_date']);
	//grandparents
	$grandfather_firstname = stripslashes($_POST['grandfather_firstname']);
	$grandfather_lastname = stripslashes($_POST['grandfather_lastname']);
	$grandfather_birthdate = stripslashes($_POST['grandfather_birthdate']);
	$grandmother_firstname = stripslashes($_POST['grandmother_firstname']);
	$grandmother_maidenname = stripslashes( $_POST['grandmother_maidenname']);
	$grandmother_birthdate = stripslashes($_POST['grandmother_birthdate']);
	$grandparents_mar_date = stripslashes($_POST['grandparents_mar_date']);
	//great-grandparents
	$gr_grandfather_firstname = stripslashes($_POST['gr_grandfather_firstname']);
	$gr_grandfather_lastname = stripslashes($_POST['gr_grandfather_lastname']);
	$gr_grandfather_birthdate = stripslashes($_POST['gr_grandfather_birthdate']);
	$gr_grandmother_firstname = stripslashes($_POST['gr_grandmother_firstname']);
	$gr_grandmother_maidenname = stripslashes($_POST['gr_grandmother_maidenname']);
	$gr_grandmother_birthdate = stripslashes($_POST['gr_grandmother_birthdate']);
	$gr_grandparents_mar_date = stripslashes($_POST['gr_grandparents_mar_date']);
	
	//Spouse
	$spouse_firstname = stripslashes($_POST['spouse_firstname']);
	$spouse_lastname = stripslashes($_POST['spouse_lastname']);
	$spouse_birthdate = stripslashes($_POST['spouse_birthdate']);
	$spouse_birthplace = stripslashes($_POST['spouse_birthplace']);
	$spouse_mar_date = stripslashes($_POST['spouse_mar_date']);
	
	//self
	$first_name =  stripslashes($_POST['first_name']);
	$last_name = stripslashes($_POST['last_name']);
	$real_name = $first_name." ".$last_name;
	$birthdate = stripslashes($_POST['birthdate']);
	$birthplace = stripslashes($_POST['birthplace']);
	$telephone = stripslashes($_POST['telephone']);
	$address = stripslashes($_POST['address']);
	$city = stripslashes($_POST['city']);
	$state_prov = stripslashes($_POST['state_prov']);
	$postalcode = stripslashes($_POST['postalcode']);
	$country = stripslashes($_POST['country']);
	$user_url = stripslashes($_POST['user_url']);
	$user_login = stripslashes($_POST['user_login']);
	$user_email = stripslashes($_POST['user_email']);
	$email = stripslashes($_POST['confirm_email']);
	$user_pass = stripslashes($_POST['user_pass']);
	$pass = md5($user_pass);
	$mbtng_tree = stripslashes($_POST['tree']);
	$notes = stripslashes($_POST['notes']);
	
	//Structure Emails to Admin and New User
	$admin = get_bloginfo('admin_email');
	$asubject = 'A new user has registered on '.get_bloginfo('name');
	$usubject = 'Registration Information for '.get_bloginfo('name');
	$headers = array('Content-Type: text/html;', 'charset=UTF-8');
	$user = get_user_by( 'email', $admin );
	$admin_name = $user->first_name;
	$abody = '
	  <p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">Dear '.$admin_name.'</p>
	  <p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">'.$first_name.' '.$last_name.' has registered on '.get_bloginfo('name').'.
		They have provided the following information:</p>';
	$info = '	
	  <table style="width:100%; border-collapse: collapse; font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">
	  <tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Birthdate:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$birthdate.'</td>
	  </tr>
	  <tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Birthplace:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$birthplace.'</td>
	  </tr>
	  <tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Location:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$address.', '.$city.', '.$state_prov.', '.$country.'</td>
	  </tr>
		<tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Telephone:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$telephone.'</td>
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
	  <tr>
		<th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Relationship:</th>
		<td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$relative.' ('.$personID.') is '.$whom.' '.$relation.'</td>
	  </tr>
	  </table>
	 ';
	$abody .= $info;

	//If this is about a spouse
	$wspouse = '';
	if (($whom == "Spouse") || ($relation == "Spouse")) {
		$wspouse = '		
		<table style="width:100%; border-collapse: collapse; font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">
		<p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">My spouse\'s information is:
		<table style="width:100%; border-collapse: collapse; font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Spouse\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$spouse_firstname.' '.$spouse_lastname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Spouse\'s Birthdate/Birthplace:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$spouse_birthdate.' at '.$spouse_birthplace.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Spouse\'s Marriage Date:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$spouse_mar_date.'</td>
		</tr>
		</table>
		';
	} $abody .= $wspouse;

	// this part deals with grandfather/grandmother/sibling
	$message = '';
	if(($relation == "Grandmother") || ($relation == "Grandfather") || ($relation == "Sister") || ($relation == "Brother")){
		$message = '
		<table style="width:100%; border-collapse: collapse; font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">
		<p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">My parent\'s information is:
		<table style="width:100%; border-collapse: collapse; font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Father\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$father_firstname.' '.$father_lastname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Father\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$father_birthdate.'</td>
		</tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Mother\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$mother_firstname.' '.$mother_maidenname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Mother\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$mother_birthdate.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Parent\'s Marriage Date:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$parents_mar_date.'</td>
		</tr>
		</table>
		';
	} $abody .= $message;
	
	// this part deals with sister or brother of mother/father
	$message1 = '';
	if(($relation == "FatherSister") || ($relation == "MotherSister") || ($relation == "FatherBrother") || ($relation == "MotherBrother")){
		$message1 = '
		<table style="width:100%; border-collapse: collapse; font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">
		<p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">My parent\s information is:
		<table style="width:100%; border-collapse: collapse; font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Father\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$father_firstname.' '.$father_lastname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Father\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$father_birthdate.'</td>
		</tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Mother\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$mother_firstname.' '.$mother_maidenname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Mother\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$mother_birthdate.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Parent\'s Marriage Date:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$parents_mar_date.'</td>
		</tr>
		</table>
		';
	} $abody .= $message1;
	
	// this part deals with great grandfather/great grandmother
	$message2 = '';
	if (($relation == "GrGrandmother") || ($relation == "GrGrandfather")){
		$message2 = '
		<table style="width:100%; border-collapse: collapse; font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">
		<p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">My parent\'s information is:
		<table style="width:100%; border-collapse: collapse; font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Father\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$father_firstname.' '.$father_lastname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Father\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$father_birthdate.'</td>
		</tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Mother\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$mother_firstname.' '.$mother_maidenname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Mother\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$mother_birthdate.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Grandfather\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$grandfather_firstname.' '.$grandfather_lastname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Grandfather\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$grandather_birthdate.'</td>
		</tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Grandmother\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$grandmother_firstname.' '.$grandmother_maidenname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Grandmother\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$grandmother_birthdate.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Grandparent\'s Marriage Date:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$grandparents_mar_date.'</td>
		</tr>
		</table>
		';
	} $abody .= $message2;
	
	// this part deals with 2nd great grandfather/2nd great grandmother
	$message3 = '';
	if (($relation == "2ndGrGrandmother") || ($relation == "2ndGrGrandfather")){
		$message3 = '
		<table style="width:100%; border-collapse: collapse; font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">
		<p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">My parent\'s, grandparent\'s, and great grandparent\'s information is:
		<table style="width:100%; border-collapse: collapse; font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Father\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$father_firstname.' '.$father_lastname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Father\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$father_birthdate.'</td>
		</tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Mother\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$mother_firstname.' '.$mother_maidenname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Mother\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$mother_birthdate.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Grandfather\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$grandfather_firstname.' '.$grandfather_lastname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Grandfather\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$grandather_birthdate.'</td>
		</tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Grandmother\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$grandmother_firstname.' '.$grandmother_maidenname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Grandmother\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$grandmother_birthdate.'</td>
		</tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Great Grandfather\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$gr_grandfather_firstname.' '.$gr_grandfather_lastname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Great Grandfather\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$gr_grandather_birthdate.'</td>
		</tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Great Grandmother\'s Name:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$gr_grandmother_firstname.' '.$gr_grandmother_maidenname.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Great Grandmother\'s Birthdate:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$gr_grandmother_birthdate.'</td>
		</tr>
		<tr>
		  <th style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Great Grandparent\'s Marriage Date:</th>
		  <td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">'.$gr_grandparents_mar_date.'</td>
		</table>
		';
	} $abody .= $message3;
	
	$ubody = '
	  <p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">Dear '.$first_name.'</p>
	  <p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">Your request for a user account has been received. Before you can begin to add your information to our family tree, your
		account needs to be reviewed and activated. You will receive another email when that is done and then you will be able to 
		log in with your username, '.$user_login.', and the password you created when you registered.</p>
	  <p style="font-family: Helvetica, Tahoma, sans-serif; font-size: 1.05rem;">Thank you for registering!</p>
	  ';
	 function tngwp_wp_mail_html() {
		return 'text/html';
	}
	add_filter( 'wp_mail_content_type', 'tngwp_wp_mail_html' );
	wp_mail( $admin, $asubject, $abody, $headers );
	wp_mail( $user_email, $usubject, $ubody, $headers );
	remove_filter( 'wp_mail_content_type', 'tngwp_wp_mail_html' );
}

$wpdb->query( $wpdb->prepare(
    "INSERT INTO tng_users (description, username, password, password_type, role, allow_living, realname, email, dt_registered) VALUES ( %s, %s, %s, %s, %s, %d, %s, %s, %s )",
    array(
        $real_name,
        $user_login,
        $pass,
		'md5',
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
	'telephone' => $telephone,
	'address' => $address,
	'city' => $city,
	'state_prov' => $state_prov,
	'postalcode' => $postalcode,
	'country' => $country,
	'comment_shortcuts' => 1,
	'rich_editing' => 1,
	'show_admin_bar_front' => 0
);
$new_user_id = tng_meta_insert_user($userdata);

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

	foreach(get_user_address_profile_list() as $key => $value) {
	update_user_meta( $user_id, $key, $_POST[$key] );
	}

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

//send user to success page
	$host  = $_SERVER['HTTP_HOST'];
	$options = get_option('tngwp-frontend-user-functions-options');
	$page = $options['success_page'];
	$permalink = get_permalink( get_page_by_title( $page ) );
	wp_redirect( $permalink );
?>