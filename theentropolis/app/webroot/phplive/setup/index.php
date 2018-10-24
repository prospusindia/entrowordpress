<?php
	/* (c) OSI Codes Inc. */
	/* http://www.osicodesinc.com */
	/****************************************/
	// STANDARD header for Setup
	if ( !is_file( "../web/config.php" ) ){ HEADER("location: install.php") ; exit ; }
	include_once( "../web/config.php" ) ;
	
	if ( !isset( $CONF['SQLTYPE'] ) ) { $CONF['SQLTYPE'] = "SQL.php" ; }
	else if ( $CONF['SQLTYPE'] == "mysql" ) { $CONF['SQLTYPE'] = "SQL.php" ; }

	include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Format.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Error.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/".Util_Format_Sanatize($CONF["SQLTYPE"], "ln") ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Security.php" ) ;
	if ( !$admininfo = Util_Security_AuthSetup( $dbh ) ){ ErrorHandler( 608, "Invalid setup session or session has expired.", $PHPLIVE_FULLURL, 0, Array() ) ; exit ; }
	// STANDARD header end
	/****************************************/
	/* AUTO PATCH */
	if ( !is_file( "$CONF[CONF_ROOT]/patches/$patch_v" ) )
	{
		$query = isset( $_SERVER["QUERY_STRING"] ) ? Util_Format_Sanatize( $_SERVER["QUERY_STRING"], "query" ) : "" ;
		database_mysql_close( $dbh ) ;
		HEADER( "location: ../patch.php?from=setup&".$query."&" ) ; exit ;
	}

	include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Vals.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Functions.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Depts/get.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Ops/get.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Ops/update_itr.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Chat/get.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Chat/get_ext.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Footprints/get_ext.php" ) ;

	$action = Util_Format_Sanatize( Util_Format_GetVar( "action" ), "ln" ) ;
	$error = "" ;
	$theme = "default" ;

	Ops_update_itr_IdleOps( $dbh ) ;

	$departments = Depts_get_AllDepts( $dbh ) ;
	$operators = Ops_get_AllOps( $dbh ) ;
	$t_transcripts = Chat_ext_get_TotalTranscript( $dbh ) ;
	$query = "SELECT SUM(rateit) AS rateit, SUM(ratings) AS ratings FROM p_rstats_depts" ;
	database_mysql_query( $dbh, $query ) ;
	$data = database_mysql_fetchrow( $dbh ) ;
	$t_rating = ( isset( $data["rateit"] ) && $data["rateit"] ) ? round( $data["ratings"]/$data["rateit"] ) : 0 ;
	$t_rating = Util_Functions_Stars( "..", $t_rating ) ;
	$t_requests = Chat_ext_get_AllRequests( $dbh, 0 ) ;
	$is_assigned = Ops_get_TotalOpsAssigned( $dbh ) ;

	$operators_hash = Array() ;
	for ( $c = 0; $c < count( $operators ); ++$c )
	{
		$operator = $operators[$c] ;
		$operators_hash[$operator["opID"]] = $operator["name"] ;
	}

	$ips = isset( $VALS['CHAT_SPAM_IPS'] ) ? explode( "-", $VALS['CHAT_SPAM_IPS'] ) : Array() ; $ips_spam = 0 ;
	for ( $c = 0; $c < count( $ips ); ++$c )
	{
		if ( $ips[$c] ) { ++$ips_spam ; }
	}

	$created = date( "M j, Y", $admininfo["created"] ) ;
	$diff = time() - $admininfo["created"] ; $days_running = round( $diff/(60*60*24) ) ;

	$now = time() ;
	$m = date( "m", $now ) ;
	$d = date( "j", $now ) ;
	$y = date( "Y", $now ) ;
	$stat_end = mktime( 0, 0, 1, $m, $d, $y ) ;
	$stat_end_day = date( "j", $stat_end ) ;

	$now_start = $now - (60*60*24*15) ;
	$m = date( "m", $now_start ) ;
	$d = date( "j", $now_start ) ;
	$y = date( "Y", $now_start ) ;
	$stat_start = mktime( 0, 0, 1, $m, $d, $y ) ;
	$stat_start_day = date( "j", $stat_start ) ;

	$requests_timespan = Chat_get_ext_RequestsRangeHash( $dbh, $stat_start, $stat_end, $operators, 0 ) ;

	$month_stats = Array() ;
	$month_total_requests = $month_total_taken = $month_total_declined = $month_total_message = $month_total_initiated = 0 ;
	$month_max_chat = 0 ;
	foreach ( $requests_timespan as $sdate => $deptop )
	{
		// todo: filter for invalid dates (should be fixed with timezone reset)
		if ( isset( $deptop["depts"] ) )
		{
			foreach ( $deptop["depts"] as $key => $value )
			{
				if ( !isset( $month_stats[$sdate] ) )
				{
					$month_stats[$sdate] = Array() ;
					$month_stats[$sdate]["requests"] = $month_stats[$sdate]["taken"] = $month_stats[$sdate]["declined"] = $month_stats[$sdate]["message"] = $month_stats[$sdate]["initiated"] = 0 ;
				}

				$month_stats[$sdate]["requests"] += $value["requests"] ;
				$month_stats[$sdate]["taken"] += $value["taken"] ;
				$month_stats[$sdate]["declined"] += $value["declined"] ;
				$month_stats[$sdate]["message"] += $value["message"] ;
				$month_stats[$sdate]["initiated"] += $value["initiated"] ;

				if ( $sdate )
				{
					$month_total_requests += $value["requests"] ;
					$month_total_taken += $value["taken"] ;
					$month_total_declined += $value["declined"] ;
					$month_total_initiated += $value["initiated"] ;
					$month_total_message += $value["message"] ;
				}

				$rating = ( $value["rateit"] ) ? round( $value["ratings"]/$value["rateit"] ) : 0 ;
			}
		}
		if ( isset( $deptop["ops"] ) )
		{
			foreach ( $deptop["ops"] as $key => $value )
			{
				$rating = ( $value["rateit"] ) ? round( $value["ratings"]/$value["rateit"] ) : 0 ;
			}
		}

		if ( isset( $month_stats[$sdate]["requests"] ) && ( $month_stats[$sdate]["requests"] > $month_max_chat ) && $sdate )
			$month_max_chat = $month_stats[$sdate]["requests"] ;
	}

	if ( !isset( $CONF['API_KEY'] ) ) { $CONF['API_KEY'] = Util_Format_RandomString( 10 ) ; Util_Vals_WriteToConfFile( "API_KEY", $CONF['API_KEY'] ) ; }
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

<script data-cfasync="false" type="text/javascript">
<!--
	var st_rd ;
	var global_c_chat ;

	$(document).ready(function()
	{
		$("body").css({'background': '#E4EBF3'}) ;
		init_menu() ;
		toggle_menu_setup( "home" ) ;

		<?php if ( $action === "success" ): ?>do_alert( 1, "Success" ) ;<?php endif ; ?>
	});

	function launch_tools_op_status()
	{
		var url = "tools_op_status.php" ;

		if ( <?php echo count( $operators ) ?> > 0 )
			External_lib_PopupCenter( url, "Status", 550, 550, "scrollbars=yes,menubar=no,resizable=1,location=no,width=550,height=550,status=0" ) ;
		else
		{
			if ( confirm( "Operator account does not exist.  Add an operator?" ) )
				location.href = "ops.php" ;
		}
	}

	function remote_disconnect( theopid, thelogin )
	{
		if ( typeof( st_rd ) != "undefined" ) { do_alert( 0, "Another disconnect in progress." ) ; return false ; }

		if ( confirm( "Remote disconnect operator console ("+thelogin+")?" ) )
		{
			var json_data = new Object ;

			$('#op_login').html( thelogin ) ;
			$('#remote_disconnect_notice').center().show() ;

			$.ajax({
				type: "POST",
				url: "../ajax/setup_actions.php",
				data: "action=remote_disconnect&opid="+theopid+"&"+unixtime(),
				success: function(data){
					eval( data ) ;

					if ( json_data.status )
						check_op_status( theopid ) ;
					else
					{
						$('#remote_disconnect_notice').hide() ;
						do_alert( 0, "Could not remote disconnect console.  Please try again." ) ;
					}
				}
			});
		}
	}

	function check_op_status( theopid )
	{
		var unique = unixtime() ;
		var json_data = new Object ;

		if ( typeof( st_rd ) != "undefined" ) { clearTimeout( st_rd ) ; }

		$.ajax({
		type: "POST",
		url: "../wapis/status_op.php",
		data: "opid="+theopid+"&jkey=<?php echo md5( $CONF['API_KEY'] ) ?>&"+unique,
		success: function(data){
			eval( data ) ;

			if ( !parseInt( json_data.status ) )
				location.href = 'index.php?action=success&'+unique ;
			else
				st_rd = setTimeout( function(){ check_op_status( theopid ) ; }, 2000 ) ;
		},
		error:function (xhr, ajaxOptions, thrownError){
			do_alert( 0, "Lost connection to server.  Please refresh the page and try again." ) ;
		} });
	}

	function show_div( thediv )
	{
		var divs = Array( "operator", "setup" ) ;

		for ( var c = 0; c < divs.length; ++c )
		{
			$('#login_'+divs[c]).hide() ;
			$('#menu_url_'+divs[c]).removeClass('op_submenu_focus').addClass('op_submenu') ;
		}

		$('#login_'+thediv).show() ;
		$('#menu_url_'+thediv).removeClass('op_submenu').addClass('op_submenu_focus') ;
	}

	function select_date_chat( theunix, thedayexpand, thetotal, thec, theincro )
	{
		$( '#tr_requests' ).find('*').each( function(){
			var div_name = this.id ;
			if ( div_name.indexOf("bar_v_requests_") != -1 )
				$(this).css({'border': '1px solid #4FD25B'}) ;
		} );

		if ( typeof( thetotal ) == "undefined" ) { var thetotal = 0 ; }

		if ( global_c_chat == thec )
		{
			global_c_chat = undeefined ;
			$('#stat_day_expand_chat').html( "" ) ;
		}
		else
		{
			global_c_chat = thec ;
			$('#stat_day_expand_chat').html( "<span class=\"info_box\" style=\"font-weight: bold;\">"+thedayexpand+"</span> &nbsp; Total Chat Requests: "+thetotal ) ;
			if ( typeof( thec ) != "undefined" ) { $('#bar_v_requests_'+thec).css({'border': '1px solid #235D28'}) ; }
		}
	}
//-->
</script>
</head>

<?php include_once( "./inc_header.php" ) ; ?>

		<table cellspacing=0 cellpadding=0 border=0 width="100%">
		<tr>
			<td valign="top">

				<div class="home_box" style="width: 210px; margin-left: 0px;">
					<div style="padding: 10px; background: url( ../pics/bg_glass.gif ) repeat-x #F6F7F8; border: 1px solid #DEDEDE; color: #6D6D71;" class="round_top">Stats Overview</div>
					<div style="margin-top: 8px; padding: 10px;"><a href="depts.php">Departments</a> (<?php echo count( $departments ) ?>)</div>
					<div style="margin-top: 8px; padding: 10px;"><a href="ops.php">Operators</a> (<?php echo count( $operators ) ; ?>)</div>
					<div style="margin-top: 8px; padding: 10px;"><a href="transcripts.php">Transcripts</a> (<?php echo ( $t_transcripts ) ? $t_transcripts : 0 ; ?>)</div>
					<div style="margin-top: 8px; padding: 10px;">
						<table cellspacing=0 cellpadding=0 border=0><tr><td><a href="reports_chat.php">Overall Rating</a>:</td><td style="padding-left: 5px;"> <?php echo $t_rating ?></td></tr></table>
					</div>
					<?php if ( $admininfo["status"] != -1 ): ?><div style="margin-top: 8px; padding: 10px;"><a href="../mapp/settings.php">Mobile App</a> (<?php echo ( isset( $CONF['MAPP_KEY'] ) && $CONF['MAPP_KEY'] ) ? "On" : "Off" ; ?>)</div><?php endif ; ?>
				</div>

			</td>
			<td width="280" valign="top">

				<div id="home_box_start" class="home_box" style="width: 280px;">
					<div class="edit_title round_top" style="margin-right: 0px; margin-bottom: 0px; padding: 15px; background: #57AFC3; border: 1px solid #509FB2; color: #FFFFFF; text-shadow: none;"><img src="../pics/icons/power_on.png" width="14" height="14" border="0" alt="" style="border: 1px solid #FFFFFF;" class="round"> Start Here</div>
					<div style="background: #F6F7F8; padding-top: 15px; padding-bottom: 25px; border: 1px solid #DEDEDE; border-top: 0px;" class="round_bottom">
						<div id="todo_1" class="home_box_li_blank" style="margin-top: 0px;"><span style="padding: 5px; background: #57AFC3; border: 1px solid #509FB2;" class="round"><img src="../pics/icons/menu_depts.png" width="12" height="12" border="0" alt=""></span> &nbsp; <span class=""><a href="depts.php" style="text-shadow: 1px 1px #FFFFFF;">Add/Edit Chat Department</a></span></div>
						<div class="home_box_li_blank" style="margin-top: 15px;"><span style="padding: 5px; background: #57AFC3; border: 1px solid #509FB2;" class="round"><img src="../pics/icons/menu_ops.png" width="12" height="12" border="0" alt=""></span> &nbsp; <span class=""><a href="ops.php" style="text-shadow: 1px 1px #FFFFFF;">Add/Edit Chat Operator</a></span></div>
						<div class="home_box_li_blank" style="margin-top: 15px;"><span style="padding: 5px; background: #57AFC3; border: 1px solid #509FB2; color: #6D6D71;" class="round"><img src="../pics/icons/menu_ops.png" width="12" height="12" border="0" alt=""></span> &nbsp; <span class=""><a href="ops.php?jump=assign" style="text-shadow: 1px 1px #FFFFFF;">Assign Operator to Department</a></span></div>
						<div class="home_box_li_blank" style="margin-top: 15px;"><span style="padding: 5px; background: #57AFC3; border: 1px solid #509FB2;" class="round"><img src="../pics/icons/menu_code.png" width="12" height="12" border="0" alt=""></span> &nbsp; <span class=""><a href="code.php" style="text-shadow: 1px 1px #FFFFFF;">Chat Icon HTML Code</a></span></div>
						<div class="home_box_li_blank" style="margin-top: 15px;"><span style="padding: 5px; background: #57AFC3; border: 1px solid #509FB2;" class="round"><img src="../pics/icons/menu_chats.png" width="12" height="12" border="0" alt=""></span> &nbsp; <span class=""><img src="../pics/icons/bulb.png" width="14" height="14" border="0" alt=""> <a href="ops.php?jump=online" style="text-shadow: 1px 1px #FFFFFF;">Go <span style="font-weight: bold;">ONLINE!</span></a></span></div>
					</div>
				</div>

			</td>
			<td valign="top">
				
				<div class="home_box" style="">
					<table cellspacing=0 cellpadding=0 border=0>
					<tr>
						<td valign="top">
							<div class="op_submenu" style="width: 100px;" onClick="location.href='interface_themes.php'">Interface</div>
							<div style="clear: both;"></div>
							<div style="padding: 10px; margin-top: 5px;"><a href="interface_themes.php">Chat Themes</a></div>
							<div style="padding: 10px;"><a href="interface.php">Company Logo</a></div>
							<div style="padding: 10px;"><a href="interface_op_pics.php">Profile Picture</a></div>
							<div style="padding: 10px;"><a href="interface.php?jump=time">Timezone</a></div>
							<div style="padding: 10px;"><a href="interface_lang.php">Language Text</a></div>
						</td>
						<td valign="top" style="padding-left: 25px;">
							<div class="op_submenu" style="width: 100px;" onClick="location.href='reports_chat.php'">Reports</div>
							<div style="clear: both;"></div>
							<div style="padding: 10px; margin-top: 5px;"><a href="reports_chat.php">Chat Reports</a></div>
							<div style="padding: 10px;"><a href="transcripts.php">Transcripts (<?php echo ( $t_transcripts ) ? $t_transcripts : 0 ; ?>)</a></div>
							<div style="padding: 10px;"><a href="reports_chat_active.php">Active Chats (<?php echo count( $t_requests ) ?>)</a></div>
							<div style="padding: 10px;"><a href="reports_chat_missed.php">Missed Chats</a></div>
							<div style="padding: 10px;"><a href="JavaScript:void(0)" onClick="launch_tools_op_status()">Status Monitor</a></div>
						</td>
					</tr>
					</table>
				</div>

			</td>
		</tr>
		<tr>
			<td valign="top" style="padding-top: 55px;">
				<div class="home_box" style="margin-left: 0px;">
					&nbsp;
				</div>
			</td>
			<td valign="top" colspan=2 style="padding-top: 55px;">

				<div class="home_box" style="">
					<div style="font-size: 14px;"><img src="../pics/icons/calendar.png" width="16" height="16" border="0" alt=""> 15 Day <a href="reports_chat.php">Chat Reports</a> Data &nbsp; <span id="stat_day_expand_chat"></span></div>
					<div style="margin-top: 15px;">
						<table cellspacing=0 cellpadding=0 border=0 style="height: 100px;" width="100%">
						<tr id="tr_requests">
							<?php
								$tooltips = Array() ; $stat_day_totals = Array() ; $incro = 1 ;
								while( $incro <= 15 )
								{
									$m = date( "m", $stat_start ) ;
									$d = date( "j", $stat_start ) ;
									$y = date( "Y", $stat_start ) ;
									$stat_day = mktime( 0, 0, 1, $m, $d+$incro, $y ) ;

									$stat_day_expand = date( "l, M j, Y", $stat_day ) ;
									$c = date( "j", $stat_day ) ;

									$h1 = "0px" ; $meter = "meter_v_green.gif" ;
									$tooltip = "$stat_day_expand" ;
									$tooltips[$stat_day] = "$tooltip (Total: 0)" ;
									if ( isset( $month_stats[$stat_day] ) )
									{
										$stat_day_totals[$c] = $month_stats[$stat_day]["requests"] ;
										if ( $month_max_chat )
											$h1 = round( ( $month_stats[$stat_day]["requests"]/$month_max_chat ) * 100 ) . "px" ;
										$tooltips[$stat_day] = "$tooltip (Total: ".$stat_day_totals[$c].")" ;
									}
									else
										$stat_day_totals[$c] = 0 ;

									print "
										<td valign=\"bottom\" width=\"2%\" style=\"height: 100px;\"><div id=\"bar_v_requests_$c\" title=\"".$tooltips[$stat_day]."\" alt=\"".$tooltips[$stat_day]."\" style=\"height: $h1; background: url( ../pics/meters/$meter ) repeat-y; border: 1px solid #4FD25B; border-top-left-radius: 5px 5px; -moz-border-radius-topleft: 5px 5px; border-top-right-radius: 5px 5px; -moz-border-radius-topright: 5px 5px; cursor: pointer;\" OnClick=\"select_date_chat( $stat_day, '$stat_day_expand', '".$stat_day_totals[$c]."', $c, $incro );\">&nbsp;</div></td>
										<td><img src=\"../pics/space.gif\" width=\"5\" height=1 border=0></td>
									" ;
									++$incro ;
								}
							?>
						</tr>
						<tr>
							<?php
								$incro = 1 ;
								while( $incro <= 15 )
								{
									$m = date( "m", $stat_start ) ;
									$d = date( "j", $stat_start ) ;
									$y = date( "Y", $stat_start ) ;
									$stat_day = mktime( 0, 0, 1, $m, $d+$incro, $y ) ;

									$stat_day_expand = date( "l, M j, Y", $stat_day ) ;
									$c = date( "j", $stat_day ) ;
									print "
										<td align=\"center\"><div id=\"requests_bg_day\" class=\"page_report\" style=\"min-width: 12px; margin: 0px; font-size: 10px; font-weight: bold;\" title=\"$tooltips[$stat_day]\" id=\"$tooltips[$stat_day]\" OnClick=\"select_date_chat( $stat_day, '$stat_day_expand', '".$stat_day_totals[$c]."', $c, $incro );\">$c</div></td>
										<td><img src=\"../pics/space.gif\" width=\"5\" height=1 border=0></td>
									" ;
									++$incro ;
								}
							?>
						</tr>
						<tr>
							<?php
								$incro = 1 ;
								while( $incro <= 15 )
								{
									$m = date( "m", $stat_start ) ;
									$d = date( "j", $stat_start ) ;
									$y = date( "Y", $stat_start ) ;
									$stat_day = mktime( 0, 0, 1, $m, $d+$incro, $y ) ;

									$stat_day_expand = date( "l, M j, Y", $stat_day ) ;
									$c = date( "j", $stat_day ) ;
									$total = $stat_day_totals[$c] ;
									if ( $total > 999 ) { $total = "+" ; }

									print "
										<td align=\"center\"><div id=\"requests_bg_total_$c\" class=\"info_clear\" style=\"margin: 0px; font-size: 10px; font-weight: bold;\">$total</div></td>
										<td><img src=\"../pics/space.gif\" width=\"5\" height=1 border=0></td>
									" ;
									++$incro ;
								}
							?>
						</tr>
						</table>
					</div>
				</div>

				<div class="home_box" style="margin-top: 25px;">
					<?php if ( $ips_spam > 0 ): ?>
					<div><table cellspacing=0 cellpadding=0 border=0><tr><td><img src="../pics/icons/bullet_red.png" width="20" height="20" border="0" alt=""></td><td style="padding-left: 5px;"> There are <a href="settings.php?jump=sips"><?php echo $ips_spam ?> IPs blocked</a> from chat access.  Blocked IPs will always see an offline chat status.</td></tr></table></div>
					<?php endif ; ?>
				</div>

			</td>
		</tr>
		</table>

		<div id="remote_disconnect_notice" style="display: none; position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background: url( ../pics/bg_trans_white.png ) repeat; overflow: hidden; z-index: 20;">
			<div style="padding-top: 300px; text-align: center;"><span class="info_error" style="">Disconnecting console [ <span id="op_login"></span> ].  Just a moment... <img src="../pics/loading_fb.gif" width="16" height="11" border="0" alt=""></span></div>
		</div>

<?php include_once( "./inc_footer.php" ) ; ?>
