<?php

/**
 * Twitter Busyness Average PHP function
 * by Chris Dzombak <http://chris.dzombak.name>
 * 
 * http://chris.dzombak.name/blog/2011/04/twitter-busyness-average
 *
 * This is alpha-quality code.
 *
 * TODO:
 *   - document this code + readme
 *   - licensing
 */
 
 /* This function calculates the TBA for a given user.
  * 
  * The function will block while (potentially) more than one HTTP
  * requests are performed.
  * 
  * This requires PHP with curl and json support compiled in.
  *
  * Parameters:
  * $username: [string] Twitter username
  * $k: [float] constant used when calculating the inverse of Tweet frequency
  * $days: [int] the number of days to evaluate
  *
  * Returns a float.
  */
function tba($username='cdzombak', $k=0.75, $days=2) {
	$now = time();
	$current_page = 1;
	
	$ch = curl_init();
		curl_setopt ($ch, CURLOPT_FAILONERROR, TRUE);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 6);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 4);
	
	$hit_boundary = false;
	$tweet_count = 0;
	
	while(!$hit_boundary) {
		curl_setopt($ch, CURLOPT_URL, tba_build_url($username, $current_page));
		$json = curl_exec($ch);
		if(curl_errno($ch) || $json === FALSE) {
			$err = curl_error($ch);
			curl_close($ch);
			// could change this to return -1, die, etc...
			throw new Exception('cURL failed. Error: ' . $err);
		}
		$result = json_decode($json);
	
		for ($i=0; $i<count($result); $i++) {
			$tweet_date = strtotime($result[$i]->created_at);
			
			// X days * 24 hours/day * 3600 seconds/hr
			if ( ($now - $tweet_date) < ($days * 24 * 3600) ) {
				$tweet_count++;
			} else {
				$hit_boundary = true;
				break;
			}
		}
		$current_page++;
	}

	curl_close($ch);
	
	if ($tweet_count == 0) $tweet_count = 1;
	return $k / ( $tweet_count / ($days*24) );
}

function tba_build_url($screen_name, $page) {
	return 'http://api.twitter.com/1/statuses/user_timeline.json?page=' . $page . '&screen_name=' . $screen_name;
}
