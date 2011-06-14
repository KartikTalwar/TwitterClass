<?php

ini_set("max_execution_time", 60);	// set execution time to a minute
require("./lib/ScraperClass.php");	// include the scraper tools


/**
 * The Twitter Class with helpful simple functions to embed the user details on your site
 *
 * @author     	Kartik Talwar
 * @version    	1.0
 * @example	./examples.php
 * @link	http://github.com/kartiktalwar/Twitter-Class
 */
class Twitter extends Scraper
{
	var $username;	// username
	var $password;	// password
	var $info;	// user info
	var $statusinfo;	// statuses info
	
	public $limit = 5;	// status retrieve limit
	
	
	/**
	 * Constructor
	 *
	 * The following function assigns the data to variables
	 *
	 * @param	(string) $username The Twitter username and password (optional)
	 * @return	(bool) Returns True
	 */
	public function __construct($username, $password=NULL)
	{
		$this->username = $username;	// assign username to temp
		$this->password = $password;	// assign password to temp

		$data = $this->getInfo();	// get basic info
		$this->info = $data;	// assign to temp
		
		$statusdata = $this->getStatusInfo();	// get status info
		$this->statusinfo = $statusdata;	// assign to temp
		
		return True;
	}
	
	
	/**
	 * Gets the users twitter ID
	 */
	public function getID()
	{
		$info = $this->info;
		return $info["id"];
	}
	
	
	/**
	 * Gets the users real name
	 */
	public function getName()
	{
		$info = $this->info;
		return $info["name"];
	}

	
	/**
	 * Gets the users case-sensitive username
	 */
	public function getUsername()
	{
		$info = $this->info;
		return $info["username"];
	}

	
	/**
	 * Gets the users location
	 */
	public function getLocation()
	{
		$info = $this->info;
		return $info["location"];
	}	
	
	
	/**
	 * Gets the users bio/description
	 */
	public function getBio()
	{
		$info = $this->info;
		return $info["description"];
	}	


	/**
	 * Gets the users website url
	 */
	public function getWebsite()
	{
		$info = $this->info;
		return $info["website"];
	}	


	/**
	 * Gets the users profile avatar
	 */
	public function getProfilePic()
	{
		$info = $this->info;
		return $info["avatar"];
	}	


	/**
	 * Gets the users followers count
	 */
	public function getFollowersCount()
	{
		$info = $this->info;
		return $info["followers"];
	}		
	
	
	/**
	 * Gets the users following count
	 */
	public function getFollowingCount()
	{
		$info = $this->info;
		return $info["following"];
	}		


	/**
	 * Gets the users twitter background picture
	 */
	public function getBackground()
	{
		$info = $this->info;
		return $info["background"];
	}		


	/**
	 * Gets the users statuses count
	 */
	public function getStatusesCount()
	{
		$info = $this->info;
		return $info["statusescount"];
	}		


	/**
	 * Gets the users latest status post
	 */
	public function getLatestStatus()
	{
		$info = $this->statusinfo;
		return $info[0]["text"];
	}	


	/**
	 * Gets the users latest status URL
	 */
	public function getLatestStatusURL()
	{
		$info = $this->statusinfo;
		return $info[0]["url"];
	}


	/**
	 * Gets the users latest status's retweet count
	 */
	public function getLatestStatusRetweets()
	{
		$info = $this->statusinfo;
		return $info[0]["retweets"];
	}	
	
	
	/**
	 * Gets the n number of statuses by the user
	 */
	public function getStatuses($n)
	{
		$data = $this->statusinfo;	// get data
		
		// minor error handling
		if($n == 0 || empty($n) ) { $n = 1; }
		if( $n > $this->limit ) { $n = $this->limit; }
		
		$result = array();	// init results
		
		// start iterring
		for($i=0; $i<$n; $i++)
		{
			$result[] = $data[$i];	// append results
		}
		
		return $result;	// output it
	}
	
	
	/**
	 * Get Basic Info Function
	 *
	 * The following function gets the basic user details 
	 *
	 * @param	(none) NONE
	 * @return	(array) $info Key-Value pairs of basic user information
	 */
	public function getInfo()
	{
		$username = $this->username;	// get the username
		
		// make sure its not empty
		if( !empty($username) )
		{
			// make sure its valid
			$url = "http://twitter.com/users/show/".$username;
			$get = curl_init($url);
			curl_setopt($get, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($get, CURLOPT_URL, $url);
			$result = curl_exec($get);
			$http_status = curl_getinfo($get, CURLINFO_HTTP_CODE);
			curl_close($get);
			
			// if it is
			if( $http_status == 200 )
			{
				$parse = $this->parseXML($result);	// get data
				
				// take what you need
				$info = array( "id" => $parse->id,
									 "name" => $parse->name,
									 "username" => $parse->screen_name,
									 "location" => $parse->location,
									 "description" => $parse->description,
									 "website" => $parse->url,
									 "avatar" => $parse->profile_image_url,
									 "following" => $parse->friends_count,
									 "followers" => $parse->followers_count,
									 "background" => $parse->profile_background_image_url,
									 "statusescount" => $parse->statuses_count
									);
									
				return $info;	// and go
			}
			else
			{
				return $this->error(404);	// otherwise shout
			}
		}
		
		return $this->error(404);	// no username provided
	}
	
	
	/**
	 * Get Statuses Function
	 *
	 * The following function gets latest user statuses
	 *
	 * @param	(none) NONE
	 * @return	(array) $info Key-Value pairs of status information
	 */
	public function getStatusInfo()
	{
		$url = "http://twitter.com/statuses/user_timeline/".$this->getID().".xml";	// make the status url
		$get = $this->load($url);	// get contents
		$parse = $this->parseXML($get);	// parse results
		$xml = $parse->status;
		
		$results = array();	// init results
		$username = $xml[0]->user->screen_name;
		
		// start iterring
		for($i=0; $i<$this->limit; $i++)
		{
			$status = $xml[$i];
			// if status exists
			if( !empty($status) )
			{
				$results[$i]["statusid"] = $this->strip($status->id);	// get id
				$results[$i]["text"] = $this->linkify($this->unescape($status->text));	// get the actual status
				$results[$i]["retweets"] = $this->strip($status->retweet_count);	// get retweet count
				$results[$i]["source"] = $this->strip($status->source);	// get the status publish source
				$results[$i]["url"] = "http://twitter.com/".$username."/statuses/".$status->id;	// make status URL
			}
		}
		
		return $results;	// output status
	}

	
	/**
	 * Linkify Function
	 *
	 * The following function converts the text into relevant twitter links
	 *
	 * @param	(string) $text The text to linkify
	 * @return	(string) $text The HTML text with replies, hashtags and URL's
	 */
	public function linkify($text)
	{
		$text = $this->strip($text);	// make it plain text
		
		// links
		$text = preg_replace("#(^|[\n ])([\w]+?://[^ \"\n\r\t<]*)#is", "$1<a href='$2' target='_blank' class='t-link'>$2</a>", $text);
		$text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#is", "$1<a href='http://$2' target='_blank' class='t-link'>$2</a>", $text);
		
		// emails
		$text = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "$1<a href='mailto:$2@$3' class='t-email'>$2@$3</a>", $text);
		
		// replies
		$text = preg_replace('/([\.|\,|\:|\?|\?|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href='http://twitter.com/$2' class='t-user'>@$2</a>$3 ", $text);
		
		// hashtags
		$text = preg_replace('/([\.|\,|\:|\?|\?|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href='http://twitter.com/search?q=%23$2' class='t-hash'>#$2</a>$3 ", $text);
	
		return $text;
	}
	
	
	/**
	 * Error Response Function
	 *
	 * The following function returns the error description
	 *
	 * @param	(int) $code The error code to output
	 * @return	(string) $messages The error description
	 */
	public function error($code)
	{
		// error messages
		$messages = array( 404 => "Invalid Username",
									   500 => "Invalid Password",
									   200 => "OK",
									);
		
		return $messages[$code];	// shout error
	}

	
}


?>