<?php
	/* (c) OSI Codes Inc. */
	/* http://www.osicodesinc.com */
	include_once( "../web/config.php" ) ;
	include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Format.php" ) ;
	$datauri = ( isset( $VARS_CHATICON_DATAURI ) && $VARS_CHATICON_DATAURI ) ? 1 : 0 ;
	if ( is_file( "$CONF[DOCUMENT_ROOT]/API/Util_Extra_Pre.php" ) || $datauri ) { include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Upload_.php" ) ; $datauri = 1 ; }
	else { include_once( "$CONF[DOCUMENT_ROOT]/API/Util_Upload.php" ) ; }

	$query = Util_Format_Sanatize( Util_Format_GetVar( "q" ), "" ) ;
	if ( !$query ) { $query = Util_Format_Sanatize( Util_Format_GetVar( "v" ), "" ) ; }
	$lang = Util_Format_Sanatize( Util_Format_GetVar( "lang" ), "ln" ) ;
	$agent = isset( $_SERVER["HTTP_USER_AGENT"] ) ? $_SERVER["HTTP_USER_AGENT"] : "&nbsp;" ;
	LIST( $os, $browser ) = Util_Format_GetOS( $agent ) ;
	$mobile = ( $os == 5 ) ? 1 : 0 ;

	$params = Array( ) ; $params = explode( "|", $query ) ;
	$deptid = ( isset( $params[0] ) && $params[0] ) ? Util_Format_Sanatize( $params[0], "n" ) : 0 ;
	$btn = ( isset( $params[1] ) && $params[1] ) ? Util_Format_Sanatize( $params[1], "n" ) : 0 ;
	$placeholder = ( isset( $params[2] ) && $params[2] ) ? Util_Format_Sanatize( rawurldecode( $params[2] ), "n" ) : 0 ;
	$text = ( isset( $params[3] ) && $params[3] ) ? Util_Format_Sanatize( rawurldecode( $params[3] ), "ln" ) : "" ;
	$theme = ( isset( $params[4] ) && $params[4] ) ? Util_Format_Sanatize( rawurldecode( $params[4] ), "ln" ) : "" ;
	$base_url = $CONF["BASE_URL"] ;

	if ( !isset( $CONF['foot_log'] ) ) { $CONF['foot_log'] = "on" ; }
	if ( !isset( $CONF['icon_check'] ) ) { $CONF['icon_check'] = "on" ; }
	if ( !isset( $VARS_ADA_TXT ) ) { $VARS_ADA_TXT = "" ; }

	$initiate = ( isset( $VALS["auto_initiate"] ) && $VALS["auto_initiate"] ) ? unserialize( html_entity_decode( $VALS["auto_initiate"] ) ) : Array( ) ;
	$automatic_invite_pos = ( isset( $initiate["pos"] ) ) ? $initiate["pos"] : 1 ;
	if ( !is_numeric( $automatic_invite_pos ) || ( $automatic_invite_pos == 1 ) || ( $automatic_invite_pos > 4 ) ) { $automatic_invite_show = "left,3em" ; $automatic_invite_start = "top: 40%; left: -800px;" ; }
	else if ( $automatic_invite_pos == 2 ) { $automatic_invite_show = "right,3em" ; $automatic_invite_start = "top: 40%; right: -800px;" ; }
	else if ( $automatic_invite_pos == 3 ) { $automatic_invite_show = "bottom,3em" ; $automatic_invite_start = "bottom: -800px; left: 3em;" ; }
	else { $automatic_invite_show = "bottom,3em" ; $automatic_invite_start = "bottom: -800px; right: 3em;" ; }

	$online = ( isset( $VALS['ONLINE'] ) && $VALS['ONLINE'] ) ? unserialize( $VALS['ONLINE'] ) : Array( ) ;
	if ( !isset( $online[0] ) ) { $online[0] = "embed" ; }
	if ( !isset( $online[$deptid] ) ) { $online[$deptid] = $online[0] ; }
	$offline = ( isset( $VALS['OFFLINE'] ) && $VALS['OFFLINE'] ) ? unserialize( $VALS['OFFLINE'] ) : Array( ) ;
	if ( !isset( $offline[0] ) ) { $offline[0] = "embed" ; }
	if ( !isset( $offline[$deptid] ) ) { $offline[$deptid] = $offline[0] ; }

	$redirect_url = ( isset( $offline[$deptid] ) && !preg_match( "/^(icon|hide|embed|tab)$/", $offline[$deptid] ) ) ? $offline[$deptid] : "" ;
	$icon_hide = ( isset( $offline[$deptid] ) && preg_match( "/^(hide)$/", $offline[$deptid] ) ) ? 1 : 0 ;
	$embed_online = ( isset( $online[$deptid] ) && preg_match( "/^(embed)$/", $online[$deptid] ) ) ? 1 : 0 ;
	$embed_offline = ( isset( $offline[$deptid] ) && preg_match( "/^(embed)$/", $offline[$deptid] ) ) ? 1 : 0 ;
	$tabbed_online = ( isset( $online[$deptid] ) && preg_match( "/^(tab)$/", $online[$deptid] ) ) ? 1 : 0 ;
	$tabbed_offline = ( isset( $online[$deptid] ) && preg_match( "/^(tab)$/", $offline[$deptid] ) ) ? 1 : 0 ;
	$mobile_newwin = ( isset( $VALS["MOBILE_NEWWIN"] ) && is_numeric( $VALS["MOBILE_NEWWIN"] ) ) ? intval( $VALS["MOBILE_NEWWIN"] ) : 0 ;
	$embed_pos = ( !isset( $VALS["EMBED_POS"] ) || ( isset( $VALS["EMBED_POS"] ) && ( $VALS["EMBED_POS"] != "left" ) ) ) ? "right" : "left" ;

	if ( !isset( $VALS["EXCLUDE"] ) ) { $VALS["EXCLUDE"] = "" ; }
	$exclude_array = explode( ",", $VALS["EXCLUDE"] ) ; $exclude_process = 0 ; $exclude_string = "" ;
	for ( $c = 0; $c < count( $exclude_array ); ++$c ) { if ( $exclude_array[$c] ) { $exclude_string .= "($exclude_array[$c])|" ; } }
	if ( $exclude_string ) { $exclude_process = 1 ; $exclude_string = substr_replace( $exclude_string, "", -1 ) ; }
	else { $exclude_string = "place-holder_text" ; }

	$initiate_array = ( isset( $VALS["auto_initiate"] ) && $VALS["auto_initiate"] ) ? unserialize( html_entity_decode( $VALS["auto_initiate"] ) ) : Array( ) ;
	$initiate_duration = ( isset( $initiate_array["duration"] ) && $initiate_array["duration"] ) ? $initiate_array["duration"] : 0 ;
	$exclude_string_invite = "" ;
	if ( isset( $initiate_array["exclude"] ) && $initiate_array["exclude"] )
	{
		$exclude_array = explode( ",", $initiate_array["exclude"] ) ;
		for ( $c = 0; $c < count( $exclude_array ); ++$c ) { $exclude_string_invite .= "($exclude_array[$c])|" ; }
		if ( $exclude_string_invite ) { $exclude_string_invite = substr_replace( $exclude_string_invite, "", -1 ) ; }
	}
	$initiate_doit = ( !$initiate_duration || !isset( $initiate_array["andor"] ) || !is_numeric( $initiate_array["andor"] ) || ( isset( $initiate_array["andor"] ) > 2 ) ) ? 0 : 1 ;
	if ( $datauri )
	{
		$widget_close_image = Util_Upload_Output(0, 0, "$CONF[DOCUMENT_ROOT]/themes/initiate/close_box.png", "image/png" ) ;
		$blank_space_image = Util_Upload_Output(0, 0, "$CONF[DOCUMENT_ROOT]/pics/space.gif", "image/gif" ) ;
		$embed_loading_image = Util_Upload_Output(0, 0, "$CONF[DOCUMENT_ROOT]/themes/initiate/loading_embed.gif", "image/png" ) ;
	}
	else
	{
		$widget_close_image = "$base_url/themes/initiate/close_box.png" ;
		$blank_space_image = "$base_url/pics/space.png" ;
		$embed_loading_image = "$base_url/themes/initiate/loading_embed.gif" ;
	} $initiate_chat_image = Util_Upload_GetInitiate( 0 ) ;
	if ( $text )
	{
		$icon_online_image = $icon_offline_image = $text ;
	}
	else
	{
		$icon_online_image = Util_Upload_GetChatIcon( "icon_online", $deptid ) ;
		$icon_offline_image = Util_Upload_GetChatIcon( "icon_offline", $deptid ) ;
	} Header( "Content-type: application/javascript" ) ;
?>
if ( typeof( phplive_base_url ) == "undefined" )
{
	if ( typeof( phplive_utf8_encode ) == "undefined" ){ function phplive_utf8_encode(r){if(null===r||"undefined"==typeof r)return"";var e,n,t=r+"",a="",o=0;e=n=0,o=t.length;for(var f=0;o>f;f++){var i=t.charCodeAt(f),l=null;if(128>i)n++;else if(i>127&&2048>i)l=String.fromCharCode(i>>6|192,63&i|128);else if(55296!=(63488&i))l=String.fromCharCode(i>>12|224,i>>6&63|128,63&i|128);else{if(55296!=(64512&i))throw new RangeError("Unmatched trail surrogate at "+f);var d=t.charCodeAt(++f);if(56320!=(64512&d))throw new RangeError("Unmatched lead surrogate at "+(f-1));i=((1023&i)<<10)+(1023&d)+65536,l=String.fromCharCode(i>>18|240,i>>12&63|128,i>>6&63|128,63&i|128)}null!==l&&(n>e&&(a+=t.slice(e,n)),a+=l,e=n=f+1)}return n>e&&(a+=t.slice(e,o)),a} function phplive_md5(n){var r,t,u,e,o,f,c,i,a,h,v=function(n,r){return n<<r|n>>>32-r},g=function(n,r){var t,u,e,o,f;return e=2147483648&n,o=2147483648&r,t=1073741824&n,u=1073741824&r,f=(1073741823&n)+(1073741823&r),t&u?2147483648^f^e^o:t|u?1073741824&f?3221225472^f^e^o:1073741824^f^e^o:f^e^o},s=function(n,r,t){return n&r|~n&t},d=function(n,r,t){return n&t|r&~t},l=function(n,r,t){return n^r^t},w=function(n,r,t){return r^(n|~t)},A=function(n,r,t,u,e,o,f){return n=g(n,g(g(s(r,t,u),e),f)),g(v(n,o),r)},C=function(n,r,t,u,e,o,f){return n=g(n,g(g(d(r,t,u),e),f)),g(v(n,o),r)},b=function(n,r,t,u,e,o,f){return n=g(n,g(g(l(r,t,u),e),f)),g(v(n,o),r)},m=function(n,r,t,u,e,o,f){return n=g(n,g(g(w(r,t,u),e),f)),g(v(n,o),r)},y=function(n){for(var r,t=n.length,u=t+8,e=(u-u%64)/64,o=16*(e+1),f=new Array(o-1),c=0,i=0;t>i;)r=(i-i%4)/4,c=i%4*8,f[r]=f[r]|n.charCodeAt(i)<<c,i++;return r=(i-i%4)/4,c=i%4*8,f[r]=f[r]|128<<c,f[o-2]=t<<3,f[o-1]=t>>>29,f},L=function(n){var r,t,u="",e="";for(t=0;3>=t;t++)r=n>>>8*t&255,e="0"+r.toString(16),u+=e.substr(e.length-2,2);return u},S=[],_=7,j=12,k=17,p=22,q=5,x=9,z=14,B=20,D=4,E=11,F=16,G=23,H=6,I=10,J=15,K=21;for(n=this.phplive_utf8_encode(n),S=y(n),c=1732584193,i=4023233417,a=2562383102,h=271733878,r=S.length,t=0;r>t;t+=16)u=c,e=i,o=a,f=h,c=A(c,i,a,h,S[t+0],_,3614090360),h=A(h,c,i,a,S[t+1],j,3905402710),a=A(a,h,c,i,S[t+2],k,606105819),i=A(i,a,h,c,S[t+3],p,3250441966),c=A(c,i,a,h,S[t+4],_,4118548399),h=A(h,c,i,a,S[t+5],j,1200080426),a=A(a,h,c,i,S[t+6],k,2821735955),i=A(i,a,h,c,S[t+7],p,4249261313),c=A(c,i,a,h,S[t+8],_,1770035416),h=A(h,c,i,a,S[t+9],j,2336552879),a=A(a,h,c,i,S[t+10],k,4294925233),i=A(i,a,h,c,S[t+11],p,2304563134),c=A(c,i,a,h,S[t+12],_,1804603682),h=A(h,c,i,a,S[t+13],j,4254626195),a=A(a,h,c,i,S[t+14],k,2792965006),i=A(i,a,h,c,S[t+15],p,1236535329),c=C(c,i,a,h,S[t+1],q,4129170786),h=C(h,c,i,a,S[t+6],x,3225465664),a=C(a,h,c,i,S[t+11],z,643717713),i=C(i,a,h,c,S[t+0],B,3921069994),c=C(c,i,a,h,S[t+5],q,3593408605),h=C(h,c,i,a,S[t+10],x,38016083),a=C(a,h,c,i,S[t+15],z,3634488961),i=C(i,a,h,c,S[t+4],B,3889429448),c=C(c,i,a,h,S[t+9],q,568446438),h=C(h,c,i,a,S[t+14],x,3275163606),a=C(a,h,c,i,S[t+3],z,4107603335),i=C(i,a,h,c,S[t+8],B,1163531501),c=C(c,i,a,h,S[t+13],q,2850285829),h=C(h,c,i,a,S[t+2],x,4243563512),a=C(a,h,c,i,S[t+7],z,1735328473),i=C(i,a,h,c,S[t+12],B,2368359562),c=b(c,i,a,h,S[t+5],D,4294588738),h=b(h,c,i,a,S[t+8],E,2272392833),a=b(a,h,c,i,S[t+11],F,1839030562),i=b(i,a,h,c,S[t+14],G,4259657740),c=b(c,i,a,h,S[t+1],D,2763975236),h=b(h,c,i,a,S[t+4],E,1272893353),a=b(a,h,c,i,S[t+7],F,4139469664),i=b(i,a,h,c,S[t+10],G,3200236656),c=b(c,i,a,h,S[t+13],D,681279174),h=b(h,c,i,a,S[t+0],E,3936430074),a=b(a,h,c,i,S[t+3],F,3572445317),i=b(i,a,h,c,S[t+6],G,76029189),c=b(c,i,a,h,S[t+9],D,3654602809),h=b(h,c,i,a,S[t+12],E,3873151461),a=b(a,h,c,i,S[t+15],F,530742520),i=b(i,a,h,c,S[t+2],G,3299628645),c=m(c,i,a,h,S[t+0],H,4096336452),h=m(h,c,i,a,S[t+7],I,1126891415),a=m(a,h,c,i,S[t+14],J,2878612391),i=m(i,a,h,c,S[t+5],K,4237533241),c=m(c,i,a,h,S[t+12],H,1700485571),h=m(h,c,i,a,S[t+3],I,2399980690),a=m(a,h,c,i,S[t+10],J,4293915773),i=m(i,a,h,c,S[t+1],K,2240044497),c=m(c,i,a,h,S[t+8],H,1873313359),h=m(h,c,i,a,S[t+15],I,4264355552),a=m(a,h,c,i,S[t+6],J,2734768916),i=m(i,a,h,c,S[t+13],K,1309151649),c=m(c,i,a,h,S[t+4],H,4149444226),h=m(h,c,i,a,S[t+11],I,3174756917),a=m(a,h,c,i,S[t+2],J,718787259),i=m(i,a,h,c,S[t+9],K,3951481745),c=g(c,u),i=g(i,e),a=g(a,o),h=g(h,f);var M=L(c)+L(i)+L(a)+L(h);return M.toLowerCase( )} }
	var phplive_base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(a){var d="",c=0;for(a=phplive_base64._utf8_encode(a);c<a.length;){var b=a.charCodeAt(c++);var e=a.charCodeAt(c++);var f=a.charCodeAt(c++);var g=b>>2;b=(b&3)<<4|e>>4;var h=(e&15)<<2|f>>6;var k=f&63;isNaN(e)?h=k=64:isNaN(f)&&(k=64);d=d+this._keyStr.charAt(g)+this._keyStr.charAt(b)+this._keyStr.charAt(h)+this._keyStr.charAt(k)}return d},decode:function(a){var d="",c=0;for(a=a.replace(/[^A-Za-z0-9\+\/\=]/g,"");c<a.length;){var b=this._keyStr.indexOf(a.charAt(c++));var e=this._keyStr.indexOf(a.charAt(c++));var f=this._keyStr.indexOf(a.charAt(c++));var g=this._keyStr.indexOf(a.charAt(c++));b=b<<2|e>>4;e=(e&15)<<4|f>>2;var h=(f&3)<<6|g;d+=String.fromCharCode(b);64!=f&&(d+=String.fromCharCode(e));64!=g&&(d+=String.fromCharCode(h))}return d=phplive_base64._utf8_decode(d)},_utf8_encode:function(a){a=a.replace(/\r\n/g,"\n");for(var d="",c=0;c<a.length;c++){var b=a.charCodeAt(c);128>b?d+=String.fromCharCode(b):(127<b&&2048>b?d+=String.fromCharCode(b>>6|192):(d+=String.fromCharCode(b>>12|224),d+=String.fromCharCode(b>>6&63|128)),d+=String.fromCharCode(b&63|128))}return d},_utf8_decode:function(a){var d="",c=0;for(c1=c2=0;c<a.length;){var b=a.charCodeAt(c);128>b?(d+=String.fromCharCode(b),c++):191<b&&224>b?(c2=a.charCodeAt(c+1),d+=String.fromCharCode((b&31)<<6|c2&63),c+=2):(c2=a.charCodeAt(c+1),c3=a.charCodeAt(c+2),d+=String.fromCharCode((b&15)<<12|(c2&63)<<6|c3&63),c+=3)}return d}};
	var phplive_base_url_orig = "<?php echo $base_url ?>" ;
	var phplive_base_url = phplive_base_url_orig ;
	var phplive_proto = ( location.href.indexOf("https") == 0 ) ? 1 : 0 ; // to avoid JS proto error, use page proto for areas needing to access the JS objects
	if ( !phplive_proto && ( phplive_base_url.match( /http/i ) == null ) ) { phplive_base_url = "http:"+phplive_base_url_orig ; }
	else if ( phplive_proto && ( phplive_base_url.match( /https/i ) == null ) ) { phplive_base_url = "https:"+phplive_base_url_orig ; }
	var phplive_regex_replace = new RegExp( phplive_base_url_orig, "g" ) ; var undeefined ;
	var phplive_browser = navigator.appVersion ; var phplive_mime_types = "" ;
	var phplive_display_width = screen.availWidth ; var phplive_display_height = screen.availHeight ; var phplive_display_color = screen.colorDepth ; var phplive_timezone = new Date( ).getTimezoneOffset( ) ;
	if ( navigator.mimeTypes.length > 0 ) { for (var x=0; x < navigator.mimeTypes.length; x++) { phplive_mime_types += navigator.mimeTypes[x].description ; } }
	var phplive_browser_token = phplive_md5( phplive_display_width+phplive_display_height+phplive_display_color+phplive_timezone+phplive_browser+phplive_mime_types ) ;
	var phplive_stat_refer = phplive_base64.encode( document.referrer.replace("http", "hphp") ) ;
	var phplive_stat_onpage_raw = location.toString( ) ; var phplive_stat_onpage = phplive_base64.encode( phplive_stat_onpage_raw.replace("http", "hphp") ) ;
	var phplive_stat_title = phplive_base64.encode( document.title ) ;
	var phplive_stat_title_temp = phplive_stat_title.replace( / /g,'' ) ; if ( !phplive_stat_title_temp )  { phplive_stat_title = phplive_base64.encode( "- no title. -" ) ; }
	var phplive_resolution = encodeURI( screen.width + " x " + screen.height ) ;
	var phplive_query_extra = "&r="+phplive_stat_refer+"&tl="+phplive_stat_title+"&resolution="+phplive_resolution ;
	var phplive_fetch_status_url = phplive_base_url+"/ajax/status.php?action=js&token="+phplive_browser_token+"&deptid={deptid}&pst=1" ;
	var phplive_request_url_query = "token="+phplive_browser_token+"&pg="+phplive_stat_onpage+"&tl="+phplive_stat_title ;
	var phplive_request_url = phplive_base_url+"/phplive.php?d={deptid}&"+phplive_request_url_query ;
	var phplive_si_phplive_fetch_status = parseInt( <?php echo $VARS_JS_CHATICON_CHECK ?> ) ;
	var phplive_si_phplive_fetch_footprints = parseInt( <?php echo $VARS_JS_FOOTPRINT_CHECK ?> ) ;
	var phplive_si_fetch_status = new Object ; var phplive_st_fetch_footprints ;
	var phplive_depts = new Object ; var phplive_btns = new Object ; var phplive_globals = new Object ;
	phplive_globals["icon_initiate"] = "<?php echo $initiate_chat_image ?>".replace( phplive_regex_replace, phplive_base_url ) ;
	phplive_globals["icon_initiate_close"] = "<?php echo $widget_close_image ?>".replace( phplive_regex_replace, phplive_base_url ) ;
	phplive_globals["icon_embed_loading"] = "<?php echo $embed_loading_image ?>".replace( phplive_regex_replace, phplive_base_url ) ;
	phplive_globals["icon_space"] = "<?php echo $blank_space_image ?>".replace( phplive_regex_replace, phplive_base_url ) ;
	phplive_globals["popout"] = <?php echo ( isset( $VALS["POPOUT"] ) && ( $VALS["POPOUT"] == "on" ) ) ? 1 : 0 ?> ;
	phplive_globals["embedinvite"] = <?php echo ( isset( $VALS["EMBED_OPINVITE_AUTO"] ) && ( $VALS["EMBED_OPINVITE_AUTO"] == "on" ) ) ? 1 : 0 ?> ;
	phplive_globals["exclude_process"] = <?php echo $exclude_process ?> ;
	phplive_globals["exclude_string"] = "<?php echo $exclude_string ?>" ;
	phplive_globals["exclude_string_invite"] = "<?php echo $exclude_string_invite ?>" ;
	phplive_globals["initiate_duration"] = <?php echo $initiate_duration ?> ;
	phplive_globals["newwin_width"] = <?php echo $VARS_CHAT_WIDTH ?> ;
	phplive_globals["newwin_height"] = <?php echo $VARS_CHAT_HEIGHT ?> ;
	phplive_globals["embed_width"] = "<?php echo $VARS_CHAT_WIDTH_WIDGET ?>" ;
	phplive_globals["embed_height"] = "<?php echo $VARS_CHAT_HEIGHT_WIDGET ?>" ;
	phplive_globals["mobile_newwin"] = <?php echo $mobile_newwin ?> ;
	phplive_globals["embed_pos"] = "<?php echo $embed_pos ?>" ;
	phplive_globals["embed_padding"] = <?php echo $VARS_CHAT_PADDING_WIDGET ?> ;
	phplive_globals["invite_pos"] = <?php echo $automatic_invite_pos ?> ;
	phplive_globals["invite_start"] = "<?php echo $automatic_invite_start ?>" ;
	phplive_globals["invite_show"] = "<?php echo $automatic_invite_show ?>" ;
	phplive_globals["invite_dur"] = <?php echo $initiate_duration ?> ;
	phplive_globals["invite_exin"] = "<?php echo ( isset( $initiate_array["exin"] ) ) ? $initiate_array["exin"] : "" ; ?>" ;
	phplive_globals["invite_andor"] = <?php echo ( isset( $initiate_array["andor"] ) && is_numeric( $initiate_array["andor"] ) ) ? $initiate_array["andor"] : 0 ; ?> ;
	phplive_globals["invite_exin_pages"] = "<?php echo $exclude_string_invite ; ?>" ;
	phplive_globals["invite_doit"] = <?php echo $initiate_doit ?> ;
	phplive_globals["foot_log"] = "<?php echo $CONF["foot_log"] ?>" ;
	phplive_globals["icon_check"] = "<?php echo $CONF['icon_check'] ?>" ;
	phplive_globals["processes"] = 0 ; phplive_globals["deptid"] ;
}
if ( typeof( phplive_depts[<?php echo $deptid ?>] ) == "undefined" )
{
	phplive_depts[<?php echo $deptid ?>] = new Object ;
	phplive_depts[<?php echo $deptid ?>]["icon_online"] = "<?php echo $icon_online_image ?>".replace( phplive_regex_replace, phplive_base_url ) ;
	phplive_depts[<?php echo $deptid ?>]["icon_offline"] = "<?php echo $icon_offline_image ?>".replace( phplive_regex_replace, phplive_base_url ) ;
	phplive_depts[<?php echo $deptid ?>]["redirect_url"] = "<?php echo $redirect_url ?>" ;
	phplive_depts[<?php echo $deptid ?>]["icon_hide"] = <?php echo $icon_hide ?> ;
	phplive_depts[<?php echo $deptid ?>]["embed_online"] = <?php echo $embed_online ?> ;
	phplive_depts[<?php echo $deptid ?>]["embed_offline"] = <?php echo $embed_offline ?> ;
	phplive_depts[<?php echo $deptid ?>]["tabbed_offline"] = <?php echo $tabbed_offline ?> ;
	phplive_depts[<?php echo $deptid ?>]["tabbed_online"] = <?php echo $tabbed_online ?> ;
	phplive_depts[<?php echo $deptid ?>]["isonline"] = -1 ;
	phplive_depts[<?php echo $deptid ?>]["redirect"] = "<?php echo $redirect_url ?>" ;
	phplive_depts[<?php echo $deptid ?>]["loaded"] = 0 ;
	var phplive_si_check_jquery_<?php echo $deptid ?> = setInterval(function( ){
		if ( typeof( phplive_jquery ) != "undefined" )
		{
			clearInterval( phplive_si_check_jquery_<?php echo $deptid ?> ) ;
			var fetch_url = phplive_fetch_status_url.replace( /{deptid}/, <?php echo $deptid ?> ) ;
			phplive_fetch_status( <?php echo $deptid ?>, fetch_url ) ;
			phplive_si_fetch_status[<?php echo $deptid ?>] = setInterval(function( ){
				phplive_fetch_status( <?php echo $deptid ?>, fetch_url ) ;
			}, phplive_si_phplive_fetch_status * 1000 ) ;
		}
	}, 100 ) ; window.phplive_launch_chat_<?php echo $deptid ?> = function( ) { phplive_launch_chat( <?php echo $deptid ?> ) ; } ;
} if ( typeof( phplive_btns[<?php echo $btn ?>] ) == "undefined" ) {
	phplive_btns[<?php echo $btn ?>] = new Object ;
	phplive_btns[<?php echo $btn ?>]["deptid"] = <?php echo $deptid ?> ;
	phplive_btns[<?php echo $btn ?>]["isonline"] = -1 ;
}

/********************** begin minify **********************/

if ( typeof( phplive_init_jquery ) == "undefined" )
{
	var phplive_jquery ;
	var phplive_session_support = ( typeof( Storage ) !== "undefined" ) ? 1 : 0 ;
	var phplive_js_center = function(a){var b=phplive_jquery(window),c=b.scrollTop( );return this.each(function( ){var f=phplive_jquery(this),e=phplive_jquery.extend({against:"window",top:false,topPercentage:0.5},a),d=function( ){var h,g,i;if(e.against==="window"){h=b;}else{if(e.against==="parent"){h=f.parent( );c=0;}else{h=f.parents(against);c=0;}}g=((h.width( ))-(f.outerWidth( )))*0.5;i=((h.height( ))-(f.outerHeight( )))*e.topPercentage+c;if(e.top){i=e.top+c;}f.css({left:g,top:i});};d( );b.resize(d);});} ;

	var phplive_automatic_chat_invite_footpassed = 0 ;
	var phplive_automatic_chat_invite_processed = 0 ;
	var phplive_thec = 0 ;
	var phplive_fetch_footprint_image ;

	var phplive_si_automatic_chat_invite_timer ;
	var phplive_automatic_chat_invite_regex = new RegExp( phplive_globals["invite_exin_pages"], "g" ) ;
	if ( phplive_globals["invite_exin_pages"] )
	{
		if ( ( phplive_globals["invite_exin"] == "include" ) && !phplive_stat_onpage_raw.match( phplive_automatic_chat_invite_regex ) )
		{
			phplive_globals["invite_doit"] = 0 ;
		}
		else if ( ( phplive_globals["invite_exin"] == "exclude" ) && phplive_stat_onpage_raw.match( phplive_automatic_chat_invite_regex ) )
		{
			phplive_globals["invite_doit"] = 0 ;
		}
	}
	var phplive_chat_icon_exclude_regex = new RegExp( phplive_globals["exclude_string"], "g" ) ;
	if ( phplive_stat_onpage_raw.match( phplive_chat_icon_exclude_regex ) && ( typeof( phplive_exclude ) == "undefined" ) )
		var phplive_exclude = 1 ;

	var phplive_orientation_isportrait = -1 ; var phplive_orientation_isportrait_global ;

	var phplive_mobile = 0 ; var phplive_mobile_v_height ; var phplive_mobile_v_height_px ;
	var phplive_userAgent = navigator.userAgent || navigator.vendor || window.opera ;
	var phplive_ipad = 0 ;
	if ( phplive_userAgent.match( /iPad/i ) || phplive_userAgent.match( /iPhone/i ) || phplive_userAgent.match( /iPod/i ) )
	{
		if ( phplive_userAgent.match( /iPad/i ) ) { phplive_mobile = 0 ; phplive_ipad = 1 ; }
		else { phplive_mobile = 1 ; }
	}
	else if ( phplive_userAgent.match( /Android/i ) ) { phplive_mobile = 2 ; }

	var phplive_embed_win_width = ( !phplive_globals["embed_width"].match( /%/ ) ) ? phplive_globals["embed_width"] + "px" : phplive_globals["embed_width"] ;
	var phplive_embed_win_height = ( !phplive_globals["embed_height"].match( /%/ ) ) ? phplive_globals["embed_height"] + "px" : phplive_globals["embed_height"] ;
	var phplive_embed_win_padding = phplive_globals["embed_padding"] + "px" ;
	if ( phplive_mobile )
	{
		var phplive_embed_win_width = "100%" ;
		var phplive_embed_win_height = "100%" ;
		var phplive_embed_win_padding = "0px" ;
	}
	window.phplive_unique = function( ) { var date = new Date( ) ; return date.getTime( ) ; } ;
	window.phplive_init_jquery = function( )
	{
		if ( typeof( phplive_jquery ) == "undefined" )
		{
			var phplive_temp_jquery_version_string = ( typeof( window.jQuery ) != "undefined" ) ? window.jQuery.fn.jquery : "0.0.0" ;
			var phplive_temp_jquery_version_array = phplive_temp_jquery_version_string.split('.') ; var phplive_temp_jquery_version = 0 ;
			for ( var c = 0; c < phplive_temp_jquery_version_array.length; ++c )
			{
				if ( c == 0 ) { phplive_temp_jquery_version += parseInt( phplive_temp_jquery_version_array[c] ) * 1000 ; }
				else if ( c == 1 ) { phplive_temp_jquery_version += parseInt( phplive_temp_jquery_version_array[c] ) * 100 ; }
				else if ( c == 2 ) { phplive_temp_jquery_version += parseInt( phplive_temp_jquery_version_array[c] ) * 10 ; }
			}
			if ( phplive_temp_jquery_version < 1700 )
			{
				var script_jquery = document.createElement('script') ;
				script_jquery.type = "text/javascript" ; script_jquery.async = true ;
				script_jquery.onload = script_jquery.onreadystatechange = function( ) {
					if ( ( typeof( this.readyState ) == "undefined" ) || ( this.readyState == "loaded" || this.readyState == "complete" ) )
					{
						phplive_jquery = window.jQuery.noConflict( true ) ;
						phplive_jquery.fn.center = phplive_js_center ;
						phplive_init( ) ;
					}
				} ;
				script_jquery.src = phplive_base_url+"/js/framework.js" ;
				var script_jquery_s = document.getElementsByTagName('script')[0] ;
				script_jquery_s.parentNode.insertBefore(script_jquery, script_jquery_s) ;
			}
			else
			{
				phplive_jquery = window.jQuery ;
				phplive_jquery.fn.center = phplive_js_center ;
				phplive_init( ) ;
			}
		}
	}
	window.phplive_objsize = function( theobj )
	{
		var size = 0 ; for ( var key in theobj ) { size++; } return size ;
	} ;
	window.phplive_unique = function( ) { var date = new Date( ) ; return date.getTime( ) ; } ;
	window.phplive_init = function( )
	{
		phplive_init_orientation( ) ;
		if ( phplive_globals["invite_doit"] ) { phplive_automatic_chat_invite_timer( ) ; }
	} ;
	window.phplive_init_orientation = function( )
	{
		phplive_init_orientation_set( ) ;
		if ( phplive_orientation_isportrait_global != phplive_orientation_isportrait ) { phplive_orientation_isportrait_global = phplive_orientation_isportrait ; }
		phplive_jquery(window).resize(function( ) {
			if ( phplive_mobile )
			{
				phplive_init_orientation_set( ) ;
				if ( phplive_orientation_isportrait_global != phplive_orientation_isportrait )
				{
					phplive_orientation_isportrait_global = phplive_orientation_isportrait ;
				}
			}
		});
	} ;
	window.phplive_init_orientation_set = function( )
	{
		var width = phplive_jquery(window).width( ) ;
		var height = phplive_jquery(window).height( ) ;
		if ( phplive_mobile === 2 )
		{
			// Android keyboard resize must use screen instead of window
			width = screen.width ;
			height = screen.height ;
		}
		if ( height > width ) { phplive_orientation_isportrait = 1 ; }
		else { phplive_orientation_isportrait = 0 ; }
	} ;
	window.phplive_External_lib_PopupCenter = function( url, title, w, h, winopt )
	{
		var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left ;
		var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top ;

		var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width ;
		var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height ;

		var left = ((width / 2) - (w / 2)) + dualScreenLeft ;
		var top = ((height / 2) - (h / 2)) + dualScreenTop ;
		var newwin = window.open(url, title, winopt+ ', top=' + top + ', left=' + left) ;
		if (window.focus) { newwin.focus( ) ; }
	} ;
	window.phplive_automatic_chat_invite_window_build = function( )
	{
		var invite_show = phplive_globals["invite_show"].split( ',' ) ;
		var invite_pos = {} ; invite_pos[invite_show[0]] = invite_show[1] ;

		if ( !phplive_automatic_chat_invite_processed )
		{
			var phplive_automatic_chat_invite_div = "<div id='phplive_automatic_chat_invite_wrapper' style='display: none; position: fixed !important; "+phplive_globals["invite_start"]+" -moz-border-radius: 10px; border-radius: 10px; z-Index: 200000005 !important;'>"+
				"<div style='text-align: right !important;'><a href='JavaScript:void(0)' onClick='phplive_automatic_chat_invite_window_close( 0 )'><img src='"+phplive_globals["icon_initiate_close"]+"' border='0' style='width: 18px !important; height: 18px !important; -moz-border-radius: 2px; border-radius: 2px;'></a></div>"+
				"<div><a href='JavaScript:void(0)' onClick='phplive_automatic_chat_invite_accept( )'><img src='"+phplive_globals["icon_initiate"]+"' border=0 style='max-width: 100% !important; max-height: 100% !important;'></a></div>"+
			"</div>" ;
			phplive_jquery( "body" ).append( phplive_automatic_chat_invite_div ).promise( ).done(function( ) {
				phplive_automatic_chat_invite_processed = 1 ;
				if ( phplive_globals["invite_pos"] > 4 )
				{
					// needs delay for center to process correctly
					setTimeout( function( ){ phplive_jquery( '#phplive_automatic_chat_invite_wrapper' ).center( ).fadeIn( 'fast' ) ; }, 400 ) ;
				}
				else
				{
					phplive_jquery( '#phplive_automatic_chat_invite_wrapper' ).show( ).animate(invite_pos, 1500, function( ) {
						//
					}) ;
				}
			}) ;
		}
		else if ( !phplive_jquery('#phplive_automatic_chat_invite_wrapper').is(':visible') )
		{
			if ( phplive_globals["invite_pos"] > 4 )
			{
				// needs delay for center to process correctly
				setTimeout( function( ){ phplive_jquery( '#phplive_automatic_chat_invite_wrapper' ).center( ).fadeIn( 'fast' ) ; }, 400 ) ;
			}
			else
			{
				phplive_jquery( '#phplive_automatic_chat_invite_wrapper' ).fadeIn( "fast" ) ;
			}
		}
	} ;
	window.phplive_automatic_chat_invite_window_close = function( theflag )
	{
		phplive_jquery('#phplive_automatic_chat_invite_wrapper').fadeOut("fast") ;
		var phplive_automatic_chat_invite_fetch_image = new Image ;
		phplive_automatic_chat_invite_fetch_image.onload = function( ) {
			var thisflag = phplive_automatic_chat_invite_fetch_image.width ;
		};
		phplive_automatic_chat_invite_fetch_image.src = phplive_base_url+"/ajax/auto_invite_actions.php?action=submit&&flag="+theflag+"&token="+phplive_browser_token+"&"+phplive_unique( ) ;
	} ;
	window.phplive_automatic_chat_invite_accept = function( )
	{
		var processed = 0 ; var all_deptid = 0 ; var temp_deptid = 0 ;
		if ( phplive_session_support ) { try { sessionStorage.setItem( "minmax", 1 ) ; } catch (error) {} }

		if ( phplive_jquery('#phplive_iframe_chat_embed_wrapper').is(':visible') )
		{
			if ( phplive_jquery('#phplive_iframe_chat_embed_maximize').is(':visible') )
				phplive_embed_window_maximize( ) ;
			else
				phplive_jquery('#phplive_iframe_chat_embed_wrapper').fadeOut("fast").fadeIn("fast") ;
		}
		else
		{
			for ( var thisdeptid in phplive_depts )
			{
				if ( thisdeptid == 0 ) { all_deptid = 1 ; } temp_deptid = thisdeptid ;
				if ( phplive_depts[thisdeptid]["isonline"] )
				{
					processed = 1 ;
					phplive_launch_chat( thisdeptid ) ; break ;
				}
			} if ( !processed ) {
				var request_deptid = ( all_deptid ) ? 0 : temp_deptid ;
				phplive_launch_chat( request_deptid ) ;
			}
		}
		phplive_automatic_chat_invite_window_close( 1 ) ;
	} ;
	window.phplive_fetch_status = function( thedeptid, theurl )
	{
		if ( ( phplive_globals["icon_check"] == "off" ) && ( typeof( phplive_si_fetch_status[thedeptid] ) != "undefined" ) )
		{
			clearInterval( phplive_si_fetch_status[thedeptid] ) ;
			phplive_si_fetch_status[thedeptid] = undeefined ;
			return true ;
		}

		var fetch_url = theurl ;

		var phplive_fetch_status_image = new Image ;
		phplive_fetch_status_image.onload = function( ) {
			var thisflag = phplive_fetch_status_image.width ;
			var thisstatus ;
			if ( ( thisflag == 1 ) || ( thisflag == 4 ) || ( thisflag == 6 ) || ( thisflag == 8 ) || ( thisflag == 10 )  )
			{
				thisstatus = 1 ;
				if ( thisflag == 4 ) {
					phplive_embed_window_build( thedeptid ) ;
				}
				else if ( thisflag == 8 ) {
					// in queue or connected
					phplive_embed_window_build( thedeptid ) ;
				}
				else if ( thisflag == 10 ) {
					// connecting status
					phplive_embed_window_build( thedeptid ) ;
				}
				else if ( thisflag == 6 ) {
					// operator chat invite
					if ( phplive_globals["embedinvite"] )
					{
						if ( phplive_session_support ) { try { sessionStorage.setItem( "minmax", 1 ) ; } catch (error) {} }
						setTimeout(function( ){ phplive_embed_window_build( thedeptid ) ; }, 500) ;
					}
					else
						phplive_automatic_chat_invite_window_build( ) ;
				}
			}
			else
			{
				thisstatus = 0 ;
				if ( thisflag == 5 ) {
					phplive_embed_window_build( thedeptid ) ;
				}
				else if ( thisflag == 12 ) {
					// in queue or connecting
					phplive_embed_window_build( thedeptid ) ;
				}
				else if ( thisflag == 11 ) {
					// connecting status
					phplive_embed_window_build( thedeptid ) ;
				}
				else if ( thisflag == 9 ) {
					// activate chat
					phplive_embed_window_build( thedeptid ) ;
				}
				else if ( thisflag == 7 ){
					if ( phplive_globals["embedinvite"] )
					{
						if ( phplive_session_support ) { try { sessionStorage.setItem( "minmax", 1 ) ; } catch (error) {} }
						setTimeout(function( ){ phplive_embed_window_build( thedeptid ) ; }, 500) ;
					}
					else
						phplive_automatic_chat_invite_window_build( ) ;
				}
			}

			if ( ( typeof( phplive_exclude ) == "undefined" ) || !parseInt( phplive_exclude ) )
			{
				for ( var thisbtnid in phplive_btns )
				{
					if ( ( phplive_btns[thisbtnid]["deptid"] == thedeptid ) && ( thisstatus != phplive_btns[thisbtnid]["isonline"] ) )
					{
						phplive_btns[thisbtnid]["isonline"] = thisstatus ;
						phplive_depts[thedeptid]["isonline"] = thisstatus ;
						phplive_write_to_span( thisbtnid, thedeptid ) ;
					}
				}
			}

		} ; phplive_fetch_status_image.src = fetch_url+"&u="+phplive_unique( ) ;
	} ;
	window.phplive_write_to_span = function( thebtnid, thedeptid )
	{
		var thischaticon = ( phplive_depts[thedeptid]["isonline"] ) ? phplive_depts[thedeptid]["icon_online"] : phplive_depts[thedeptid]["icon_offline"] ;
		if ( thischaticon.indexOf("http") == 0 ) { thischaticon = "<img src='"+thischaticon+"' border=0 alt='' title=''>" ; }
		if ( !phplive_depts[thedeptid]["isonline"] && phplive_depts[thedeptid]["icon_hide"] ) { thischaticon = "" ; }

		phplive_jquery( "#phplive_btn_"+thebtnid ).html( thischaticon ) ;
		if ( phplive_depts[thedeptid]["isonline"] && ( typeof( phplive_callback_online ) === "function" ) ) { phplive_callback_online( thedeptid ) ; }
		else if ( !phplive_depts[thedeptid]["isonline"] && ( typeof( phplive_callback_offline ) === "function" ) ) { phplive_callback_offline( thedeptid ) ; }

		if ( !phplive_depts[thedeptid]["loaded"] && ( typeof( phplive_callback_loaded ) === "function" ) )
		{
			phplive_depts[thedeptid]["loaded"] = 1 ;
			phplive_callback_loaded( thedeptid, phplive_depts[thedeptid]["isonline"] ) ;
		}
	} ;
	window.phplive_launch_chat = function( thedeptid )
	{
		var name = "", email = "" ; var custom_vars = "&custom=" ; var viewport = 1 ;
		if ( typeof( phplive_v ) != "undefined" )
		{
			for ( var key in phplive_v )
			{
				if ( key == "name" ) { name = phplive_base64.encode( phplive_v["name"] ) ; }
				else if ( key == "email" ) { email = phplive_base64.encode( phplive_v["email"] ) ; }
				else { custom_vars += key+"-_-"+phplive_v[key]+"-cus-" ; }
			}
		}
		var request_url = phplive_request_url.replace( /{deptid}/, thedeptid ) ;
		request_url += "&js_name="+name+"&js_email="+email+custom_vars+"&u="+phplive_unique( ) ;
		if ( phplive_session_support ) { try { sessionStorage.setItem( "minmax", 1 ) ; } catch (error) {} }

		if ( phplive_jquery('meta[name="viewport"]').length ) { viewport = 1 ; }
		else if ( phplive_mobile ) { viewport = 0 ; }

		if ( viewport && phplive_jquery('#phplive_iframe_chat_embed_wrapper').is(':visible') )
		{
			if ( phplive_jquery('#phplive_iframe_chat_embed_maximize').is(':visible') )
				phplive_embed_window_maximize( ) ;
			else
				phplive_jquery('#phplive_iframe_chat_embed_wrapper').fadeOut("fast").fadeIn("fast") ;
		}
		else
		{
			// override mobile
			if ( phplive_mobile && phplive_globals["mobile_newwin"] )
				window.open( request_url, '_blank' ) ;
			else if ( phplive_ipad && ( phplive_globals["mobile_newwin"] == 2 ) )
				window.open( request_url, '_blank' ) ;
			else
			{
				if ( phplive_depts[thedeptid]["isonline"] )
				{
					if ( viewport && phplive_depts[thedeptid]["embed_online"] )
						phplive_embed_window_build( thedeptid ) ;
					else if ( viewport && phplive_depts[thedeptid]["tabbed_online"] )
						window.open( request_url, '_blank' ) ;
					else
						phplive_External_lib_PopupCenter( request_url, 'win_'+thedeptid, phplive_globals["newwin_width"], phplive_globals["newwin_height"], 'scrollbars=no,resizable=yes,menubar=no,location=no,screenX=50,screenY=100,width='+phplive_globals["newwin_width"]+',height='+phplive_globals["newwin_height"] ) ;
				}
				else
				{
					if ( viewport && phplive_depts[thedeptid]["embed_offline"] )
						phplive_embed_window_build( thedeptid ) ;
					else if ( viewport && phplive_depts[thedeptid]["redirect"] )
						location.href = phplive_depts[thedeptid]["redirect"] ;
					else if ( viewport && phplive_depts[thedeptid]["tabbed_offline"] )
						window.open( request_url, '_blank' ) ;
					else
						phplive_External_lib_PopupCenter( request_url, 'win_'+thedeptid, phplive_globals["newwin_width"], phplive_globals["newwin_height"], 'scrollbars=no,resizable=yes,menubar=no,location=no,screenX=50,screenY=100,width='+phplive_globals["newwin_width"]+',height='+phplive_globals["newwin_height"] ) ;
				}
			}
		}
		if ( typeof( phplive_callback_launchchat ) === "function" )
		{
			phplive_callback_launchchat( phplive_depts[thedeptid]["isonline"], thedeptid ) ;
		}
	} ;
	window.phplive_embed_window_build = function( thedeptid )
	{
		if ( phplive_jquery('#phplive_iframe_chat_embed_wrapper').is(':visible') )
		{
			// already open... no action.
		}
		else
		{
			if ( !document.getElementById("phplive_iframe_chat_embed_wrapper") )
			{
				if ( phplive_globals["deptid"] != thedeptid ) { phplive_globals["deptid"] = thedeptid ; }

				var popout_display = ( !phplive_mobile && !phplive_ipad && phplive_globals["popout"] ) ? "" : "display: none; " ;
				var phplive_request_url_iframe = phplive_request_url.replace( /{deptid}/, thedeptid )+"&embed=1" ;
				var name = "", email = "" ; var custom_vars = "&custom=" ;
				if ( typeof( phplive_v ) != "undefined" )
				{
					for ( var key in phplive_v )
					{
						if ( key == "name" ) { name = phplive_base64.encode( phplive_v["name"] ) ; }
						else if ( key == "email" ) { email = phplive_base64.encode( phplive_v["email"] ) ; }
						else { custom_vars += key+"-_-"+phplive_v[key]+"-cus-" ; }
					}
				} phplive_request_url_iframe += "&js_name="+name+"&js_email="+email+custom_vars+"&u="+phplive_unique( ) ;
				var phplive_chat_embed_div = "<div id='phplive_iframe_chat_embed_wrapper' style='position: fixed !important; bottom: -1000px; width: "+phplive_embed_win_width+"; height: "+phplive_embed_win_height+"; "+phplive_globals["embed_pos"]+": "+phplive_embed_win_padding+" !important; box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.2); border-top-left-radius: 5px 5px; -moz-border-radius-topleft: 5px 5px; border-top-right-radius: 5px 5px; -moz-border-radius-topright: 5px 5px; z-Index: 200000006 !important;'>"+
					"<div id='phplive_iframe_chat_embed_wrapper_menu' style='position: absolute !important; top: 0px !important; left: 0px !important; height: 32px !important; width: 100% !important;'> "+
						"<div style='float: left !important; background: url( "+phplive_globals["icon_space"]+" ) repeat !important; width: 50px !important; height: 32px !important; cursor: pointer !important;'><a href='JavaScript:void(0)' onClick='phplive_embed_window_minimize( )'><img src='"+phplive_globals["icon_space"]+"' style='width: 50px !important; height: 32px !important;' width='50' height='32' border=0></a></div> "+
						"<div style='display: none; float: left !important; background: url( "+phplive_globals["icon_space"]+" ) repeat !important; width: 50px !important; height: 32px !important; cursor: pointer !important;'><a href='JavaScript:void(0)' onClick='phplive_embed_window_maximize( )'><img src='"+phplive_globals["icon_space"]+"' style='width: 50px !important; height: 32px !important;' width='50' height='32' border=0></a></div> "+
						"<div style='"+popout_display+"float: left !important; background: url( "+phplive_globals["icon_space"]+" ) repeat !important; width: 50px !important; height: 32px !important; cursor: pointer !important;'><a href='JavaScript:void(0)' onClick='phplive_embed_window_popout( )'><img src='"+phplive_globals["icon_space"]+"' style='width: 50px !important; height: 32px !important;' width='50' height='32' border=0></a></div> "+
						"<div style='float: left !important; background: url( "+phplive_globals["icon_space"]+" ) repeat !important; width: 50px !important; height: 32px !important; cursor: pointer !important;'><a href='JavaScript:void(0)' onClick='phplive_embed_window_close( )'><img src='"+phplive_globals["icon_space"]+"' style='width: 50px !important; height: 32px !important;' width='50' height='32' border=0></a></div> "+
						"<div style='clear: both !important;'></div> "+
					"</div> "+
					"<iframe id='phplive_iframe_chat_embed' src='"+phplive_request_url_iframe+"' scrolling='no' border=0 frameborder=0 style='width: 100% !important; height: 100% !important; border-top-left-radius: 5px 5px; -moz-border-radius-topleft: 5px 5px; border-top-right-radius: 5px 5px; -moz-border-radius-topright: 5px 5px;'></iframe> "+
					"</div><span id='phplive_embed_loading' style='position: fixed; bottom: 0px; "+phplive_globals["embed_pos"]+": 0px; padding: 3px; background: #FFFFFF; border-top-left-radius: 5px 5px; -moz-border-radius-topleft: 5px 5px; border-top-right-radius: 5px 5px; -moz-border-radius-topright: 5px 5px; z-Index: 200000007 !important;'><img src='"+phplive_globals["icon_embed_loading"]+"' width='16' height='16' border=0></span><div id='phplive_iframe_chat_embed_maximize' style='display: none; position: fixed !important; width: "+phplive_embed_win_width+"; height: "+phplive_embed_win_height+"; background: url( "+phplive_globals["icon_space"]+" ) repeat !important; box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.2); z-Index: 200000006 !important;'><a href='JavaScript:void(0)' onClick='phplive_embed_window_maximize( )'><img src='"+phplive_globals["icon_space"]+"' style='width: 100%; height: 100%;' width='100%' height='100%' border=0></a></div>" ; // a href method instead of div onClick because of Android quirk on invisible div click
				phplive_jquery( "body" ).append( phplive_chat_embed_div ).promise( ).done(function( ) {
					if ( !phplive_session_support || sessionStorage.getItem("minmax") == 1 ) {
						phplive_embed_window_maximize( ) ;
					}
					else { phplive_embed_window_minimize( ) ; }

					phplive_jquery('#phplive_iframe_chat_embed').on("load", function( ){
						phplive_jquery('#phplive_embed_loading').fadeOut( "fast" ) ;
					}) ;
				}) ;
			}
		}
	} ;
	window.phplive_embed_window_maximize = function( )
	{
		if ( phplive_jquery('#phplive_iframe_chat_embed_wrapper').is(':visible') )
		{
			phplive_jquery('#phplive_iframe_chat_embed_wrapper').hide( ) ;
			phplive_jquery('#phplive_iframe_chat_embed_wrapper_menu').show( ) ;
			phplive_jquery('#phplive_iframe_chat_embed_maximize').hide( ) ;

			phplive_jquery('#phplive_iframe_chat_embed_wrapper').css({'bottom': '-1000px', 'width': phplive_embed_win_width, 'height': phplive_embed_win_height}).show( ).animate({'bottom': 0}, {
				duration: 700,
				complete: function( ) {
					if ( phplive_session_support ) { try { sessionStorage.setItem( "minmax", 1 ) ; } catch (error) {} }
					if ( phplive_mobile ) { phplive_jquery( 'body' ).css({'overflow': 'hidden', 'position': 'fixed'}) ; }
				}
			});

			if ( typeof( phplive_callback_maximize ) === "function" )
			{
				phplive_callback_maximize( ) ;
			}
		}
	} ;
	window.phplive_embed_window_minimize = function( )
	{
		var animate_to = phplive_jquery('#phplive_iframe_chat_embed_wrapper').height( ) - 10000 ;
		var embed_pos_value = phplive_globals["embed_pos"] ;
		var embed_pos = {} ; embed_pos[embed_pos_value] = phplive_embed_win_padding ;
		phplive_jquery('#phplive_iframe_chat_embed_wrapper_menu').hide( ) ; // IE hide first
		phplive_jquery('#phplive_iframe_chat_embed_wrapper').hide( ).css({'width': '250px', 'height': '45px', 'bottom': '0px'}).promise( ).done(function( ) {
			phplive_jquery('#phplive_iframe_chat_embed_wrapper').fadeIn("slow").promise( ).done(function( ) {
				phplive_jquery('#phplive_iframe_chat_embed_maximize').css(embed_pos).css({'width': '250px', 'height': '45px', 'bottom': '0px'}).show( ) ;
			}) ;

			if ( phplive_session_support ) { try { sessionStorage.setItem( "minmax", 0 ) ; } catch (error) {} }
			if ( phplive_mobile ) { phplive_jquery( 'body' ).css({'overflow': 'auto', 'position': 'static'}) ; }
		}) ;

		if ( typeof( phplive_callback_minimize ) === "function" )
		{
			phplive_callback_minimize( ) ;
		}
	} ;
	window.phplive_embed_window_close = function( )
	{
		var animate_to = phplive_jquery('#phplive_iframe_chat_embed_wrapper').height( ) - 10000 ;
		phplive_jquery('#phplive_iframe_chat_embed_wrapper').fadeOut("fast").promise( ).done(function( ) {
			if ( phplive_mobile ) { phplive_jquery( 'body' ).css({'overflow': 'auto', 'position': 'static'}) ; }
			phplive_jquery('#phplive_iframe_chat_embed_wrapper').remove( ) ;
			phplive_jquery('#phplive_iframe_chat_embed_maximize').remove() ;
			phplive_jquery('#phplive_embed_loading').remove() ;
		}) ;
		if ( typeof( phplive_callback_close ) === "function" ) { phplive_callback_close( ) ; }
	} ;
	window.phplive_embed_window_popout = function( )
	{
		var request_url = phplive_request_url.replace( /{deptid}/, phplive_globals["deptid"] )+"&popout=1&u="+phplive_unique( ) ;
		phplive_External_lib_PopupCenter( request_url, 'win_'+phplive_globals["deptid"], phplive_globals["newwin_width"], phplive_globals["newwin_height"], 'scrollbars=no,resizable=yes,menubar=no,location=no,screenX=50,screenY=100,width='+phplive_globals["newwin_width"]+',height='+phplive_globals["newwin_height"] ) ;
		phplive_embed_window_close( ) ;
	} ;
	window.phplive_get_thec = function( ) { return phplive_thec ; } ;
	window.phplive_footprint_track = function( )
	{
		var c = phplive_get_thec( ) ; ++phplive_thec ;
		var fetch_url = phplive_base_url+"/ajax/footprints.php?token="+phplive_browser_token+"&pg="+phplive_stat_onpage+"&c="+c ;

		if ( !c ) { fetch_url += phplive_query_extra ; }
		phplive_fetch_footprint_image = new Image ;
		phplive_fetch_footprint_image.onload = phplive_fetch_footprint_actions ;
		phplive_fetch_footprint_image.src = fetch_url+"&"+phplive_unique( ) ;
	} ;
	window.phplive_fetch_footprint_actions = function( )
	{
		var thisflag = phplive_fetch_footprint_image.width ;

		if ( ( thisflag == 1 ) || ( thisflag == 2 ) || ( thisflag == 3 ) || ( thisflag == 5 ) )
		{
			if ( typeof( phplive_st_fetch_footprints ) != "undefined" )
				clearTimeout( phplive_st_fetch_footprints ) ;

			if ( phplive_globals["foot_log"] == "on" )
				phplive_st_fetch_footprints = setTimeout(function( ){ phplive_footprint_track( ) }, phplive_si_phplive_fetch_footprints * 1000) ;

			if ( thisflag == 2 ) { phplive_automatic_chat_invite_footpassed = 1 ; }
			else if ( thisflag == 3 ) { phplive_automatic_chat_invite_footpassed = 3 ; } // needed to skip the duration check for OR condition
			else if ( thisflag == 5 ) { phplive_automatic_chat_invite_footpassed = 0 ; phplive_globals["invite_andor"] = 2 ; }
		}
		else if ( thisflag == 4 )
		{
			// if browser idle too long, clear all interval processes to save on resources
			phplive_clear_timeouts( ) ;
		}
	} ;
	window.phplive_automatic_chat_invite_timer = function( )
	{
		if ( typeof( phplive_si_automatic_chat_invite_timer ) == "undefined" )
		{
			var duration_counter = 0 ;
			phplive_si_automatic_chat_invite_timer = setInterval(function( ){
				++duration_counter ;

				// make sure ops are online
				var isonline = 0 ;
				for ( var deptid in phplive_depts )
				{
					if ( phplive_depts[deptid]["isonline"] ) { isonline = 1 ; break ; }
				}
				if ( isonline )
				{
					if ( ( phplive_globals["invite_andor"] == 1 ) && ( phplive_automatic_chat_invite_footpassed || ( duration_counter > phplive_globals["invite_dur"] ) ) )
					{
						// flag 3 indicates action taken and timer still needs reset
						if ( phplive_automatic_chat_invite_footpassed != 3 )
						{
							clearInterval( phplive_si_automatic_chat_invite_timer ) ;
							phplive_si_automatic_chat_invite_timer = undeefined ;

							phplive_automatic_chat_invite_window_build( ) ;
						}
					}
					else if ( ( phplive_globals["invite_andor"] == 2 ) && ( ( phplive_automatic_chat_invite_footpassed == 1 ) && ( duration_counter > phplive_globals["invite_dur"] ) ) )
					{
						clearInterval( phplive_si_automatic_chat_invite_timer ) ;
						phplive_si_automatic_chat_invite_timer = undeefined ;

						phplive_automatic_chat_invite_window_build( ) ;
					}
				}

			}, 1000) ;
		}
	} ;
	window.phplive_clear_timeouts = function( )
	{
		if ( typeof( phplive_st_fetch_footprints ) != "undefined" )
		{
			clearTimeout( phplive_st_fetch_footprints ) ;
			phplive_st_fetch_footprints = undeefined ;
		}
		if ( typeof( phplive_si_automatic_chat_invite_timer ) != "undefined" )
		{
			clearInterval( phplive_si_automatic_chat_invite_timer ) ;
			phplive_si_automatic_chat_invite_timer = undeefined ;
		}
		for ( var thisdeptid in phplive_depts )
		{
			if ( typeof( phplive_si_fetch_status[thisdeptid] ) != "undefined" )
			{
				clearInterval( phplive_si_fetch_status[thisdeptid] ) ;
				phplive_si_fetch_status[thisdeptid] = undeefined ;
			}
		}
	} ; phplive_init_jquery( ) ; phplive_footprint_track( ) ;
} ;