# PHP Twitter Class

## Description

This class is a collection of helpful functions to help make retrieving your twitter data easy

## Installation

To Install the class, you simply need to add this code

```php
<?php

	require("TwitterClass.php");	// include class

?>
```

## Functions

* `getID()`
* `getName()`
* `getUsername()`
* `getLocation()`
* `getBio()`
* `getWebsite()`
* `getProfilePic()`
* `getFollowersCount()`
* `getFollowingCount()`
* `getBackground()`
* `getTweetCount()`
* `getLatestTweet()`
* `getLatestTweetsURL()`
* `getLatestTweetsRetweets()`
* `getTweets(n)`


## TODO

* Add other methods
* Allow publishing data
* <del>Add Multi-User Support </del>
* Finish OAuth integration
* Integrate Twitter Stream API
* Allow search methods 

## Examples
	
1. **Getting Followers Count** :
	The following method will get the users followers count

	```php
	<?php
	
		require("TwitterClass.php");
	
		$username = "lord_voldemort7";
		$twitter = new Twitter($username);
		$followers = $twitter->getFollowersCount();
		
		echo $followers;
		
	?>
	```

2. **Getting Latest Status** :
	The following method will get the users latest Twitter status

	```php
	<?php
	
		require("TwitterClass.php");
	
		$username = "lord_voldemort7";
		$twitter = new Twitter($username);
		$status = $twitter->getLatestTweet();
	
		echo $status;
	
	?>
	```
		
3. **Getting 5 Latest Statuses** :
	The following method will get the users first 5 statuses

	```php
	<?php
	
		require("TwitterClass.php");
	
		$username = "lord_voldemort7";
		$twitter = new Twitter($username);
		$statuses = $twitter->getTweets(5);	// gets first 5 statuses
		
		foreach($statuses as $status)
		{
			$id = $status["id"];	// status ID
			$text = $status["text"];	// actual status
			$retweets = $status["retweets"];	// number of retweets
			$source = $status["source"];	// where the tweet was published from (eg tweetdeck)
			$url = $status["url"];	// status URL
			
			echo "<p> $text </p>";
			echo "Retweets : <a href='$url'> $retweets </a> | ";
			echo "Tweeted Via: $source ";
			echo "<br />";
		}
		
	
	?>
	```
		


## ChangeLog

* Multiple User Support Added
	
