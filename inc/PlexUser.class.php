<?php
	class PlexUser
	{
		//Plex user class.
		private $username; //Plex username
		private $plexID; //PlexID
		private $email; //Plex email
		private $thumb; //Plex picture location
		private $token; //Plex token
		private $pin; //Plex pin. Don't know what to do with this yet. Maybe useful later.
		private $auth = false; //If the user has been authenticated or not.
		private $groups; //Array of uri's that the user can access.
		
		public function __construct($token){
			$this->Load($token);
			$this->token = $token;
			$this->AuthUser($this->username);
			$_SESSION['ytbuser'] = serialize($this);
		}
		
		private function Load($token) {
			//Loads a plex users details.
			//URL to get users details from.
			$host = "https://plex.tv/users/account?X-Plex-Token=";
			//Loads user info.
			$user_info = simplexml_load_file($host . "$token");

			//While we're grabbing the users details. We should also load in some other useful info to save trips to plex in the coming requests.
			$this->username = strtolower($user_info->attributes()['username']);
			$this->plexID = (string)$user_info->attributes()['id'];
			$this->pin = (string)$user_info->attributes()['pin'];
			$this->email = (string)$user_info->attributes()['email'];
			$this->thumb = (string)$user_info->attributes()['thumb'];
			$this->loadGroups();
		}
		
		private function loadGroups(){
			if ($this->username == $GLOBALS['ini_array']['plexowner']){
				//This is an override for the Plex server owner.
				$this->groups = array("admin");
			} else {
				$userid = $this->PlexID; //Get the users plexID
				$pytoken = $GLOBALS['ini_array']['plexpytoken']; //Load PlexPy token from config file.
				$json = file_get_contents("http://127.0.0.1:8181/plexpy/api/v2?apikey=".$pytoken."&cmd=get_users"); //Load JSON from PlexPy
				$groups = json_decode($json); //Decode json.
				$groups = $groups->response->data; //specify specific section of JSON that we need.
				foreach ($groups as $user) {
					//Loop through each user until we find this user.
					if ($user->user_id == $this->plexID) {
						$string = urldecode($user->filter_photos); //We are going to use the photo's filer. This could also be done with any Plex restriction option. URLdecode this too.
						$string = substr($string, 6); //Remove the label= from beginning of string.
						$permissions = explode(',',$string); //Explode the string into an array.
						$this->groups = $permissions; //Set permissions.
						break;
					}
				}
			}
		}
		
		public function printGroups(){
			print_r($this->groups);
		}
		
		public function authURI($uri){
			//Check if we have access to this uri.
			$uri = substr($uri, 1); //Remove leading / (slash) from uri.
			$uri = explode('/',$uri)[0];
			if (in_array("admin", $this->groups)){
				return true;
			} elseif (in_array($uri, $this->groups)){
				return true;
			}
			return false;
		}
		
		public function CheckToken($token){
			//Checks if input token matches stored token.
			if ($token == $this->token){
				return true;
			} else {
				return false;
			}
		}
		
		public function setToken($token){
			$this->token = $token;
		}
		
		public function getToken(){
			return $this->token;
		}
		
		public function getID(){
			return $this->plexID;
		}
		
		public function setAuth($status){
			//Sets a users auth status.
			$this->auth = $status;
			return $this->auth;
		}
		
		public function getAuth(){
			//Gets a users auth status.
			return $this->auth;
		}
		
		public function setThumb($thumb){
			//Sets the users thumb location.
			$this->thumb = $thumb;
		}
		
		public function getThumb(){
			//returns users thumb location. This will be a URL.
			return $this->thumb;
		}
		
		public function getUsername(){
			//returns Username
			return $this->username;
		}
		
		private function AuthUser($username) {
			//Load plex friends into an array.
			$sxml = simplexml_load_file("https://plex.tv/pms/friends/all?X-Plex-Token=" . $GLOBALS['ini_array']['token']);
			$auth = false; //Auth is false unless changed.
			foreach (($sxml->User) as $user){
				//Loop through all uers and convert them to lowercase and add them to array.
				//if (strcmp($username, strtolower($user['username']))){
				if (strcmp(strtolower($username), strtolower($user['username'])) == 0) {
					$auth = true; //set auth to true.
					break; //break the loop.
				}
				
				if ($username == $GLOBALS['ini_array']['plexowner']){
					//This is an override for the Plex server owner. Because the Plex server isn't technically shared with the owner.
					$auth = true;
				}
				
			}
			$this->auth = $auth; //Set auth status to value of auth
		}
		
		public function getEmail(){
			//returns email.
			return $this->email;
		}
	}
?>