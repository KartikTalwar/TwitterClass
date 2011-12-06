<?php


require("TwitterClass.php");


## Set up the class
$username = "lord_voldemort7";	// username
$twitter = new Twitter($username);


// example
$lateststatus = $twitter->getLatestTweet();
echo $lateststatus;


// example
$website = $twitter->getWebsite();
echo $website;


// example
$backgroundimage = $twitter->getBackground();
echo $backgroundimage;


// example
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
