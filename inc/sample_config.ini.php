;<?php
	;echo "ERROR!"
	;die(); //Kill direct connection to file.
	;/*
	
	[PLEX_SERVER]
	token = "YOUR PLEX TOKEN"; //Follow this guide to get your token. https://support.plex.tv/hc/en-us/articles/204059436-Finding-your-account-token-X-Plex-Token
	plexowner = "PLEX OWNERS USERNAME"; //Your Plex username. Not your email.
	
	[SERVER]
	domain = "Your domain."; //This is used for the cookie.
	protocol = "https"; //What protocol does your domain use? [https|http]
	session_path = "/var/lib/php5/sessions/sess_"; //This is your PHP session path folder with session prefix.
	session_name = "PHPSESSID";
	
	[REMEMBER_ME]
	secure = true; //If the EememberMe cookie should be only allowed via SSL. Disable this if you are using HTTP for your domain.
	remember_cookie = "PlexAuthRM"; //Name of the cookie that will be used to remember user.
	expire_time = "604800"; //Time for the cookie to expire (Seconds). 604800 = 1 week.
	
	[PLEX_AUTH]
	debug = false; //Debug on or off.
	
	;*/
;?>