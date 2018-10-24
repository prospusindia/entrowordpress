<?php
	/* (c) OSI Codes Inc. */
	/* http://www.osicodesinc.com */
	/****************************************/
	// STANDARD header for Setup
	if ( !is_file( "../web/config.php" ) ){ HEADER("location: install.php") ; exit ; }
	include_once( "../web/config.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Format.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Error.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/".Util_Format_Sanatize($CONF["SQLTYPE"], "ln") ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Util_IP.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Security.php" ) ;
	if ( !$admininfo = Util_Security_AuthSetup( $dbh ) ){ ErrorHandler( 608, "Invalid setup session or session has expired.", $PHPLIVE_FULLURL, 0, Array() ) ; exit ; }
	// STANDARD header end
	/****************************************/

	if ( is_file( "$CONF[DOCUMENT_ROOT]/API/Util_Extra_Pre.php" ) ) { include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Upload_.php" ) ; }
	else { include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Upload.php" ) ; }
	include_once( "$CONF[DOCUMENT_ROOT]/API/Ops/get.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$opid = Util_Format_Sanatize( Util_Format_GetVar( "opid" ), "n" ) ;

	$error = "" ;

	if ( $action === "update" )
	{
		include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Upload_File.php" ) ;
		$profile_pic_onoff = Util_Format_Sanatize( Util_Format_GetVar( "profile_pic_onoff" ), "n" ) ;

		LIST( $error, $filename ) = Util_Upload_File( "profile", $opid ) ;
		if ( !$error )
		{
			if ( $opid )
			{
				include_once( "$CONF[DOCUMENT_ROOT]/API/Ops/update.php" ) ;
				Ops_update_OpValue( $dbh, $opid, "pic", $profile_pic_onoff ) ;
			}
		}
	}
	else if ( ( $action === "clear" ) && $opid )
	{
		$dir_files = glob( $CONF["CONF_ROOT"]."/profile_$opid.*", GLOB_NOSORT ) ;
		$total_dir_files = count( $dir_files ) ;
		if ( $total_dir_files )
		{
			for ( $c = 0; $c < $total_dir_files; ++$c )
			{
				if ( $dir_files[$c] && is_file( $dir_files[$c] ) ) { unlink( $dir_files[$c] ) ; }
			}
		}
	}

	$operators = Ops_get_AllOps( $dbh ) ;
	$opinfo = Ops_get_OpInfoByID( $dbh, $opid ) ;
	$opvars = Ops_get_OpVars( $dbh, $opid ) ;

	$profile_pic_onoff = ( $opid && ( isset( $opinfo["pic"] ) && ( $opinfo["pic"] == 1 ) ) ) ? 1 : 0 ;
?>
<?php include_once( "../inc_doctype.php" ) ?>
<head>
<title> PHP Live! Support </title>

<meta name="description" content="PHP Live! Support <?php echo $VERSION ?>">
<meta name="keywords" content="powered by: PHP Live!  www.phplivesupport.com">
<meta name="robots" content="all,index,follow">
<meta http-equiv="content-type" content="text/html; CHARSET=utf-8">
<?php include_once( "../inc_meta_dev.php" ) ; ?>

<link rel="Stylesheet" href="../css/setup.css?<?php echo $VERSION ?>">
<script data-cfasync="false" type="text/javascript" src="../js/global.js?<?php echo $VERSION ?>"></script>
<script data-cfasync="false" type="text/javascript" src="../js/setup.js?<?php echo $VERSION ?>"></script>
<script data-cfasync="false" type="text/javascript" src="../js/framework.js?<?php echo $VERSION ?>"></script>
<script data-cfasync="false" type="text/javascript" src="../js/framework_cnt.js?<?php echo $VERSION ?>"></script>
<script data-cfasync="false" type="text/javascript" src="../js/jquery_md5.js?<?php echo $VERSION ?>"></script>

<script data-cfasync="false" type="text/javascript">
<!--
	var global_profile_pic_onoff = parseInt( <?php echo $profile_pic_onoff ?> ) ;
	var global_pic_edit = parseInt( <?php echo isset( $opvars["pic_edit"] ) ? $opvars["pic_edit"] : 0 ?> ) ;

	$(document).ready(function()
	{
		$("body").css({'background': '#E4EBF3'}) ;
		init_menu() ;
		toggle_menu_setup( "ops" ) ;

		<?php if ( $action && $error ): ?>
		do_alert_div( "..", 0, "<?php echo $error ?>" ) ;
		<?php elseif ( $action ): ?>
		do_alert(1, "Success" ) ;
		<?php endif ; ?>

	});

	function switch_op()
	{
		var opid = $('#select_ops').val() ;
		location.href = "interface_op_pics.php?opid="+opid ;
	}

	function confirm_clear()
	{
		if ( confirm( "Really clear this operator profile picture and use Global Default?" ) )
		{
			location.href = "interface_op_pics.php?action=clear&opid=<?php echo $opid ?>" ;
		}
	}

	function update_profile_pic_onoff( thevalue )
	{
		if ( global_profile_pic_onoff != thevalue )
		{
			var json_data = new Object ;

			$.ajax({
				type: "POST",
				url: "../ajax/setup_actions_.php",
				data: "action=update_profile_pic_onoff&opid=<?php echo $opid ?>&value="+thevalue+"&"+unixtime(),
				success: function(data){
					location.href = "interface_op_pics.php?opid=<?php echo $opid ?>&action=success" ;
				}
			});
		}
	}

	function confirm_pic_edit( thevalue )
	{
		if ( global_pic_edit != thevalue )
		{
			var json_data = new Object ;

			$.ajax({
				type: "POST",
				url: "../ajax/setup_actions_.php",
				data: "action=update_pic_edit&opid=<?php echo $opid ?>&value="+thevalue+"&flag=<?php echo ( isset( $opvars["pic_edit"] ) ) ? 1 : 0 ; ?>&"+unixtime(),
				success: function(data){
					global_pic_edit = thevalue ;
					do_alert( 1, "Success" ) ;
				}
			});
		}
	}
//-->
</script>
</head>
<?php include_once( "./inc_header.php" ) ?>

		<div class="op_submenu_wrapper">
			<div class="op_submenu" style="margin-left: 0px;" onClick="location.href='ops.php?jump=main'" id="menu_ops_main">Chat Operators</div>
			<div class="op_submenu" onClick="location.href='ops.php?jump=assign'" id="menu_ops_assign">Assign Operator to Department</div>
			<div class="op_submenu_focus">Profile Picture</div>
			<div class="op_submenu" onClick="location.href='ops_reports.php'" id="menu_ops_report">Online Activity</div>
			<div class="op_submenu" onClick="location.href='ops.php?jump=monitor'" id="menu_ops_monitor">Status Monitor</div>
			<div class="op_submenu" onClick="location.href='ops.php?jump=online'" id="menu_ops_online"><img src="../pics/icons/bulb.png" width="12" height="12" border="0" alt=""> Go ONLINE!</div>
			<div style="clear: both"></div>
		</div>

		<form method="POST" action="interface_op_pics.php" enctype="multipart/form-data">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="opid" value="<?php echo $opid ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="50000">

		<div style="margin-top: 25px;" id="div_op_pics">
			Browse available <a href="http://www.phplivesupport.com/r.php?r=avatars" target="_blank">profile picture avatars</a>.

			<?php if ( count( $operators ) > 0 ): ?>
			<div style="margin-top: 25px;">
				<div style="">
					<select id="select_ops" style="font-size: 16px; background: #D4FFD4; color: #009000;" OnChange="switch_op()"><option value="0">Global Default</option>
					<?php
						for ( $c = 0; $c < count( $operators ); ++$c )
						{
							$operator = $operators[$c] ;
							$selected = "" ;
							if ( $opid == $operator["opID"] )
							{
								$selected = "selected" ;
								$op_name = $operator["name"] ;
							}
							$p_onoff = ( $operator["pic"] == 1 ) ? "on" : "off" ;
							print "<option value=\"$operator[opID]\" $selected>$operator[name] ($p_onoff)</option>" ;
						}
					?>
					</select>
				</div>
				<?php if ( $opid ): ?>
				<div class="info_info" style="margin-top: 15px;">
					<table cellspacing=0 cellpadding=0 border=0>
					<tr>
						<td style="padding-right: 15px;">
							<div style="text-shadow: none; font-size: 14px;">
								<div class="info_good" style="float: left; width: 80px; padding: 3px; cursor: pointer;" onclick="$('#profile_pic_on').prop('checked', true);update_profile_pic_onoff(1);"><input type="radio" name="profile_pic_onoff" id="profile_pic_on" value="1" <?php echo ( $profile_pic_onoff ) ? "checked" : "" ?> > Display</div>
								<div class="info_error" style="float: left; margin-left: 10px; width: 80px; padding: 3px; cursor: pointer;" onclick="$('#profile_pic_pic_off').prop('checked', true);update_profile_pic_onoff(0);"><input type="radio" name="profile_pic_onoff" id="profile_pic_off" value="0" <?php echo ( !$profile_pic_onoff ) ? "checked" : "" ?> > Off</div>
								<div style="clear: both;"></div>
							</div>
						</td>
						<td><div id="div_info_title">Profile picture display setting for chat operator <span id="span_dept_name" style="font-size: 18px; font-weight: bold;"><?php echo ( isset( $opinfo["name"] ) ) ? $opinfo["name"] : "Global Default" ; ?></span></div></td>
					</tr>
					</table>
					<?php if ( $opid && !$opinfo["pic"] ): ?>
					<div style="font-size: 12px; margin-top: 15px; text-shadow: none;" class="info_error"><img src="../pics/icons/warning.png" width="12" height="12" border="0" alt=""> Operator's profile picture <b>will not be displayed</b> to the visitor during a chat session.</div>
					<?php elseif ( $opid ): ?>
					<div style="font-size: 12px; margin-top: 15px;"><img src="../pics/icons/info.png" width="12" height="12" border="0" alt=""> <b>"On"</b> will display the operator's profile picture on the visitor chat window.</div>
					<div style="display: inline-block; font-size: 12px; margin-top: 15px;" class="info_box">
						<table cellspacing=0 cellpadding=0 border=0>
						<tr>
							<td>Allow the operator to update their profile picture? &nbsp;</td>
							<td>
								<div style="">
									<div class="li_op round" style="cursor: pointer; text-shadow: none;" onclick="$('#pic_edit_on').prop('checked', true);confirm_pic_edit(1);"><input type="radio" name="pic_edit" id="pic_edit_on" value="1" <?php echo ( isset( $opvars["pic_edit"] ) && $opvars["pic_edit"] ) ? "checked" : "" ; ?>> Yes</div>
									<div class="li_op round" style="cursor: pointer; text-shadow: none;" onclick="$('#pic_edit_off').prop('checked', true);confirm_pic_edit(0);"><input type="radio" name="pic_edit" id="pic_edit_off" value="0" <?php echo ( isset( $opvars["pic_edit"] ) && $opvars["pic_edit"] ) ? "" : "checked" ; ?>> No</div>
									<div style="clear: both;"></div>
								</div>
							</td>
						</tr>
						</table>
					</div>
					<?php endif ; ?>
				</div>
				<?php endif ; ?>

				<div id="div_profile" style="margin-top: 25px;">
					<img src="<?php print Util_Upload_GetLogo( "profile", $opid ) ?>" width="55" height="55" border=0 style="border: 1px solid #DFDFDF;" class="round"> &nbsp; &nbsp;

					<?php if ( $opid && ( Util_Upload_GetLogo( "profile", 0 ) != Util_Upload_GetLogo( "profile", $opid ) ) ): ?>
					<img src="../pics/icons/reset.png" width="16" height="16" border="0" alt=""> <a href="JavaScript:void(0)" onClick="confirm_clear()">clear operator profile picture and use Global Default image</a>
					<?php endif ; ?>
				</div>

				<div id="div_alert" style="display: none; margin-top: 15px; margin-bottom: 25px;"></div>
				<div style="margin-top: 15px;">* profile picture should be <b>55 pixels width</b> and <b>55 pixels height</b>.  The image will be automatcially scaled to fit the dimensions.</div>
				<div style="margin-top: 15px;">
					<div><input type="file" name="profile" size="30"></div>
					<div style="margin-top: 5px;"><input type="submit" value="Upload Picture" style="margin-top: 10px;" class="btn"></div>
				</div>
			</div>
			<?php else: ?>
			<div style="margin-top: 25px;"><span class="info_error"><img src="../pics/icons/warning.png" width="12" height="12" border="0" alt=""> Add an <a href="ops.php" style="color: #FFFFFF;">Operator</a> to continue.</span></div>
			<?php endif ; ?>
		</div>
		</form>

<?php include_once( "./inc_footer.php" ) ?>

