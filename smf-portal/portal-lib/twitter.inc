<?php

function twitterUserTimeline($user, $limit)
{
    $cachetime = 60;
    $localfile = "/tmp/tweet_cache";
    $url = "http://api.twitter.com/1/statuses/user_timeline.json?screen_name=".urlencode($user)."&include_rts=true&include_entities=true&count=".intval($limit);

    if (!file_exists($localfile))
    {
      touch($localfile);
      chmod($localfile, 0666); 

      fetchfeed($url, $localfile);
    }
    else if ((time()-filemtime($localfile))>$cachetime)
    {
      fetchfeed($url, $localfile);
    }

    $json_data = json_decode(file_get_contents($localfile));

    return $json_data;
}

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


function fetchfeed($url, $localfile)
{
  $contents=file_get_contents($url);
  $fp=fopen($localfile, "w");
  fwrite($fp, $contents);
  fclose($fp);
}

function getAvatar($tweet)
{
    if ($tweet->retweeted_status)
    {
        return $tweet->retweeted_status->user->profile_image_url;
    }
    else 
    {
        return $tweet->user->profile_image_url;
    }
}

function getName($tweet)
{
    if ($tweet->retweeted_status)
    {
        return $tweet->retweeted_status->user->name;
    }
    else 
    {
        return $tweet->user->name;
    }
}

function getScreenName($tweet)
{
    if ($tweet->retweeted_status)
    {
        return $tweet->retweeted_status->user->screen_name;
    }
    else 
    {
        return $tweet->user->screen_name;
    }
}

function getUserDescription($tweet)
{
    if ($tweet->retweeted_status)
    {
        return $tweet->retweeted_status->user->description;
    }
    else 
    {
        return $tweet->description;
    }
}

function getTweetContent($tweet)
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

function getTimeAgo($tweet)
{
    return twitter_time($tweet->created_at);
}

function getSource($tweet)
{
    return $tweet->source;
}

function twitter_time($a)
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

function getRetweetCount($tweet)
{
    return $tweet->retweet_count;
}

function getRetweetCountClass($tweet)
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

