<?php

require 'tmhOAuth.inc';

function getTweetAccount($lang)
{
    switch($lang)
    {
        case "french-utf8":
            return "ppsfr";
        case "italian-utf8":
            return "ppsit";
        case "german_informal-utf8":
            return "ppsde";
        default:
            return "ppsall";
    }
}

function twitterUserTimeline($user, $limit)
{
    $code = 200;
    $cachetime = 90;
    $result = array();
     $localfile = "/tmp/tweet_cache_".$user;

    if (!file_exists($localfile))
    {
        touch($localfile);
        chmod($localfile, 0666);
		
        $code = fetchfeed($localfile, $limit);
    }
    else if ((time()-filemtime($localfile))>$cachetime)
    {
        $code = fetchfeed($localfile, $limit);
    }

    $json_data = json_decode(file_get_contents($localfile));

    if (isset($json_data) && $code === 200)
    {
        foreach($json_data as $tweet)
        {
            array_push($result, array(
                'Ago' => twitterTime($tweet->created_at),
                'Avatar' => isset($tweet->retweeted_status) ? $tweet->retweeted_status->user->profile_image_url : $tweet->user->profile_image_url,
                'Content' => tweetContent($tweet),
                'Name' => isset($tweet->retweeted_status) ? $tweet->retweeted_status->user->name : $tweet->user->name,
                'RetweetCount' => $tweet->retweet_count,
                'RetweetCountClass' => retweetCountClass($tweet),
                'ScreenName' => isset($tweet->retweeted_status) ? $tweet->retweeted_status->user->screen_name : $tweet->user->screen_name,
                'Source' => $tweet->source,
                'UserDescription' => isset($tweet->retweeted_status) ? $tweet->retweeted_status->user->description : $tweet->user->description
            ));
        }
    }
    else
    {
        $error = "error unknown";
        if  (isset($json_data)) { $error = $json_data->errors[0]->message;}
        array_push($result, array(
            'Ago' => 'now',
            'Avatar' => '',
            'Content' => 'The Twitter-Feed has a problem: '.$error,
            'Name' => 'FailWhale',
            'RetweetCount' => '0',
            'RetweetCountClass' => 'awesometweet',
            'ScreenName' => 'Fail Whale',
            'Source' => 'Twitter.com',
            'UserDescription' => ''
        ));
    }
    return $result;
}

function fetchfeed($localfile, $limit)
{
     $tmhOAuth = new tmhOAuth(array(
        'consumer_key' => '',
        'consumer_secret' => '',
        'user_token' => '',
        'user_secret' => ''
    ));

    $code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/user_timeline'), array('screen_name' => , 'count' => $limit ));

    $fp=fopen($localfile, "w");
    fwrite($fp, $tmhOAuth->response['response']);
    fclose($fp);

   return $code;
}
	
function tweetContent($tweet)
{
    $text = $tweet->text;

    // Url Handling
    foreach($tweet->entities->urls as $url)
    {
        $htmlurl = '<a href="'.$url->expanded_url.'">'.$url->display_url.'</a>';
        $text = str_replace($url->url, $htmlurl, $text);
    }

    // Hashtag Handling
    foreach($tweet->entities->hashtags as $hashtag)
    {
        $htmlhashtag = '<a href="http://twitter.com/#!/search?q=%23'.$hashtag->text.'">#'.$hashtag->text.'</a>';
        $text = str_replace('#'.$hashtag->text, $htmlhashtag, $text);
    }

    // Mentions Handling
    foreach($tweet->entities->user_mentions as $mention)
    {
        $htmlmention = '<a title="'.$mention->name.'" href="http://twitter.com/'.$mention->screen_name.'">@'.$mention->screen_name.'</a>';
        $text = str_replace('@'.$mention->screen_name, $htmlmention, $text);
    }

    return $text;
}

function twitterTime($tweet)
{
    return timeAgo($tweet->created_at);
}

function timeAgo($a)
{
    //get current timestampt
    $b = strtotime("now"); 
    //get timestamp when tweet created
    $c = strtotime($a);
    //get difference
    $d = $b - $c;
    //calculate different time values
    $minute = 60;
    $hour = $minute * 60;
    $day = $hour * 24;
    $week = $day * 7;
        
    if(is_numeric($d) && $d > 0) {
        //if less then 3 seconds
        if($d < 3) return "right now";
        //if less then minute
        if($d < $minute) return floor($d) . " seconds ago";
        //if less then 2 minutes
        if($d < $minute * 2) return "about 1 minute ago";
        //if less then hour
        if($d < $hour) return floor($d / $minute) . " minutes ago";
        //if less then 2 hours
        if($d < $hour * 2) return "about 1 hour ago";
        //if less then day
        if($d < $day) return floor($d / $hour) . " hours ago";
        //if more then day, but less then 2 days
        if($d > $day && $d < $day * 2) return "yesterday";
        //if less then year
        if($d < $day * 365) return floor($d / $day) . " days ago";
        //else return more than a year
        return "over a year ago";
    }
}

function retweetCountClass($tweet)
{
    if ($tweet->retweet_count < 5)
      return "boredtweet";
    if ($tweet->retweet_count < 10)
      return "coldtweet";
    if ($tweet->retweet_count < 20)
      return "nicetweet";
    if ($tweet->retweet_count < 100)
      return "hottweet";
    return "awesometweet";
}


?>

