<?php

ini_set("max_execution_time", 60);	// set execution time to a minute



/**
 * The Twitter Class with helpful simple functions to embed the user details on your site
 *
 * @author     	Kartik Talwar
 * @version    	1.3
 * @example	./examples.php
 * @link	http://github.com/KartikTalwar/Twitter-Class
 */
class Twitter
{
	var $username;		// username
	var $password;		// password
	var $info;		// user info
	var $tweetinfo;		// statuses info
	
	public $limit = 25;	// max status retrieve limit
	
	
	/**
	 * Constructor
	 *
	 * The following function assigns the data to variables
	 *
	 * @param	(string) $username The Twitter username (optional)
	 * @param	(string) $password The Twitter password (optional)
	 * @return	(bool) Returns True
	 */
	public function __construct($username = NULL, $password = NULL)
	{
		$this->username = (isset($username)) ? $username : NULL;	// assign username to temp
		$this->password = (isset($password)) ? $password : NULL;	// assign password to temp

		if( !empty($username) )
		{
			$data = $this->getInfo($this->username);	// get basic info
			$this->info = $data;		// assign to temp
		
			$tweetdata = $this->getTweetInfo($this->username);	// get status info
			$this->tweetinfo = $tweetdata;	// assign to temp
		}
		else
		{
			$this->error(404);
		}
		
		return True;
	}
	
	
	/**
	 * Gets the users Twitter ID
	 * 
	 * @param	 NONE 
	 * @return	(int) Returns a user's Twitter ID
	 */
	public function getID()
	{
		$info = $this->info;
		return $info["id"];
	}
	
	
	/**
	 * Gets the users real name
	 *
	 * @param	 NONE 
	 * @return	(string) Returns the users real name
	 */
	public function getName()
	{
		$info = $this->info;
		return $info["name"];
	}

	
	/**
	 * Gets the users case-sensitive username
	 *
	 * @param	 NONE 
	 * @return	(string) Returns the users case-sensitive registered username
	 */
	public function getUsername()
	{
		$info = $this->info;
		return $info["username"];
	}

	
	/**
	 * Gets the users location
	 *
	 * @param	 NONE 
	 * @return	(string) Returns the users location
	 */
	public function getLocation()
	{
		$info = $this->info;
		return $info["location"];
	}	
	
	
	/**
	 * Gets the users bio/description
	 *
	 * @param	 NONE 
	 * @return	(string) Returns the users biography
	 */
	public function getBio()
	{
		$info = $this->info;
		return $info["description"];
	}	


	/**
	 * Gets the users website url
	 *
	 * @param	 NONE 
	 * @return	(string) Returns the users website
	 */
	public function getWebsite()
	{
		$info = $this->info;
		return $info["website"];
	}	


	/**
	 * Gets the users profile avatar
	 *
	 * @param	 NONE 
	 * @return	(string) Returns the users profile picture URL
	 */
	public function getProfilePic()
	{
		$info = $this->info;
		return $info["avatar"];
	}	


	/**
	 * Gets the users followers count
	 *
	 * @param	 NONE 
	 * @return	(int) Returns the number of people that follow the user
	 */
	public function getFollowersCount()
	{
		$info = $this->info;
		return $info["followers"];
	}		
	
	
	/**
	 * Gets the users following count
	 *
	 * @param	 NONE 
	 * @return	(int) Returns the number of people the user is following
	 */
	public function getFollowingCount()
	{
		$info = $this->info;
		return $info["following"];
	}		


	/**
	 * Gets the users Twitter background picture
	 *
	 * @param	 NONE 
	 * @return	(string) Returns the URL of the users background image
	 */
	public function getBackground()
	{
		$info = $this->info;
		return $info["background"];
	}		


	/**
	 * Gets the users Tweet count
	 *
	 * @param	 NONE 
	 * @return	(int) Returns the total number of Tweets the user has posted
	 */
	public function getTweetsCount()
	{
		$info = $this->info;
		return $info["tweetscount"];
	}		


	/**
	 * Gets the users latest Tweet
	 *
	 * @param	 NONE 
	 * @return	(string) Returns the users latest Tweet
	 */
	public function getLatestTweet()
	{
		$info = $this->tweetinfo;
		return $info[0]["text"];
	}	


	/**
	 * Gets the users latest Tweet URL
	 *
	 * @param	 NONE 
	 * @return	(string) Returns the URL of the users latest Tweet
	 */
	public function getLatestTweetsURL()
	{
		$info = $this->tweetinfo;
		return $info[0]["url"];
	}


	/**
	 * Gets the users latest Tweet's retweets
	 *
	 * @param	 NONE 
	 * @return	(int) Returns the number of time the latest Tweet has been retweeted 
	 */
	public function getLatestTweetsRetweets()
	{
		$info = $this->tweetinfo;
		return $info[0]["retweets"];
	}	
	
	
	/**
	 * Gets the n number of Tweets by the user
	 *
	 * @param	(int) $n The number of Tweets to get
	 * @param	(string) $username The username to get the Tweets from (optional)
	 * @return	(array) Returns the key-value pairs of users Tweet info
	 */
	public function getTweets($n, $username = NULL)
	{
		$username = (isset($username)) ? $username : $this->username;	// get the username
		
		if( !empty($username) )
		{
			$data = $this->getTweetInfo($username);	// get data
			
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
		else
		{
			return $this->error(404);
		}
	}
	
	
	/**
	 * Get Basic Info Function
	 *
	 * The following function gets the basic user details 
	 *
	 * @param	(string) $username The username to get the info of (optional)
	 * @return	(array) $info Key-Value pairs of basic user information
	 */
	public function getInfo($username = NULL)
	{
		$username = (isset($this->username)) ? $this->username : $username;	// get the username
		
		// make sure its not empty
		if( !empty($username) )
		{
			// make sure its valid
			$url = "http://twitter.com/users/show/".$username;
			$http_status = $this->getHTTPStatus($url);
			
			// if it is
			if( $http_status == 200 )
			{
				$result = $this->load($url);	// load url
				$parse = $this->parseXML($result);	// get data
				
				// take what you need
				$info = array(  "id" => $parse->id,
						"name" => $parse->name,
						"username" => $parse->screen_name,
						"location" => $parse->location,
						"description" => $parse->description,
						"website" => $parse->url,
						"avatar" => $parse->profile_image_url,
						"following" => $parse->friends_count,
						"followers" => $parse->followers_count,
						"background" => $parse->profile_background_image_url,
						"tweetscount" => $parse->statuses_count
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
	 * @param	(string) $username The Twitter username to get Tweets of (optional)
	 * @return	(array) $info Key-Value pairs of status information
	 */
	public function getTweetInfo($username = NULL)
	{
		if(isset($username))
		{
			$id = $this->getInfo($username);
			$id = $id["id"];
		}
		else
		{
			$id = $this->getID();
		}

		$url = "http://twitter.com/statuses/user_timeline/".$id.".xml";	// make the status url
		$get = $this->load($url);	// get contents
		$parse = $this->parseXML($get);	// parse results
		$xml = $parse->status;
		
		$results = array();	// init results
		$username = $xml[0]->user->screen_name;	// set username
		
		// start iterring
		for($i=0; $i<($this->limit); $i++)
		{
			$status = $xml[$i];
			// if status exists
			if( !empty($status) )
			{
				$results[$i]["tweetid"] = strip_tags($status->id);	// get id
				$results[$i]["text"] = $this->linkify(html_entity_decode($status->text));	// get the actual tweet
				$results[$i]["retweets"] = strip_tags($status->retweet_count);	// get retweet count
				$results[$i]["source"] = strip_tags($status->source);		// get the status publish source
				$results[$i]["url"] = "http://twitter.com/".$username."/statuses/".$status->id;	// make status URL
			}
		}
		
		return $results;	// output status
	}
	
	
	/**
	 * Search Function
	 *
	 * The following function gets latest user statuses
	 *
	 * @param	(string) $query The search query
 	 * @param	(int) $results The number of results to display
	 * @return	(array) $info Key-Value pairs of status information
	 */
	public function search($query, $results = 10)
	{
		if(!empty($query))
		{
			$url = "http://search.twitter.com/search.json?rpp=".$results."&include_entities=true&result_type=mixed&q=".urlencode($query);	// make the status url
			$get = $this->load($url);	// get contents
			$parse = json_decode($get);
			$response = array();
		
			$i = 0;
			foreach($parse->results as $result)
			{
				$response[$i]['created'] = $this->relativeTime(strtotime($result->created_at));
				$response[$i]['from'] = $result->from_user;
				$response[$i]['tweet'] = $this->linkify(html_entity_decode($result->text));	// get the actual tweet
				$i++;
			}
		
			return $response;
		}
		
		return $this->error(503);
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
		$text = strip_tags($text);	// make it plain text
		
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
	 * Relative Time Function
	 *
	 * The following function converts unix timestamp to relative time
	 *
	 * @param	(int) time The UNIX timestamp
	 * @return	(string) Relative time
	 */	
	public function relativeTime($time)
	{

		$second = 1;
		$minute = 60;
		$hour = 3600;
		$day = 86400;
		$month = 2592000;

		$delta = time() - $time;

		if ($delta < 1*$second)
		{
			if( $delta == 1 ) 
			{
				return "one second ago"; 
			}
			else
			{
				return $delta." seconds ago";
			}
		}

		if ($delta < 2 * $second)
		{
			return "a minute ago";
		}

		if ($delta < 45 * $minute)
		{
			return floor($delta / $minute) . " minutes ago";
		}

		if ($delta < 90 * $minute)
		{
			return "an hour ago";
		}

		if ($delta < 24 * $hour)
		{
			return floor($delta / $hour) . " hours ago";
		}

		if ($delta < 48 * $hour)
		{
			return "yesterday";
		}

		if ($delta < 30 * $day)
		{
			return floor($delta / $day) . " days ago";
		}

		if ($delta < 12 * $month)
		{
			$months = floor($delta / $day / 30);
			if( $months <= 1 ) 
			{
				return "one month ago"; 
			}
			else
			{
				return $months . " months ago";
			}
		}
		else
		{
			$years = floor($delta / $day / 365);
			if( $years <= 1 )
			{
				return "one year ago"; 
			}
			else
			{
				$years . " years ago";
			}
		}

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
				   503 => "Invalid Query"
				 );
		
		return $messages[$code];	// shout error
	}


	/**
	 * Get HTTP Status Code Function
	 *
	 * The following function gets the HTTP Status Code for the given URL
	 *
	 * @param	(string) $url The URL of the webpage
	 * @return	(int) $status The status code of the page
	 */
	public function getHTTPStatus($url)
	{
		$get = curl_init($url);	// init curl
	
		curl_setopt($get, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($get, CURLOPT_URL, $url);
		
		$data = curl_exec($get);	// execute request
		$status = curl_getinfo($get, CURLINFO_HTTP_CODE);	// get status
		
		curl_close($get);	// close curl
		
		return $status;	// output it
	}


	/**
	 * Load Function
	 *
	 * The following function gets the contents of the webpage
	 *
	 * @param	(string) $url The URL of the page to load
	 * @return	(string) $data The contents of the URL
	 */
	public function load($url)
	{
		$url = str_replace( array(' '), array('+'), $url );	// remove spaces
		
		// if file_get_contents exists use that
		if( function_exists("file_get_contents"))
		{
			ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/11.0.696 Safari/525.13");	// set user agent
			return file_get_contents($url);	// return the contents
		}
		// otherwise use curl
		else
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);	// get the url contents
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/11.0.696 Safari/525.13");	// set user agent
			curl_setopt($ch, CURLOPT_HEADER	, TRUE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
			
			$data = curl_exec($ch);	// execute curl request
			curl_close($ch);
			
			return $data;	// return contents
		}
	}


	/**
	 * XML Parser Function
	 *
	 * The following function parses the given XML into an object
	 *
	 * @param	(string) $xml The URL of the XML content
	 * @return	(array) The parsed XML array
	 */
	public function parseXML($url)
	{		
		if(function_exists('simplexml_load_string'))
		{
			$xml = simplexml_load_string($url);	// Parse it
			
			return $xml;	// output it
		}
	
	}


}	// end class


?>	