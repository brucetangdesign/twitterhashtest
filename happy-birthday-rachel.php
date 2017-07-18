<?php
ini_set('display_errors', 1);
require_once('TwitterAPIExchange.php');

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "26592872-sUPW3G3DlO0xVCDRTOX8Yzq4VUbgIxNuQPnYotKpE",
    'oauth_access_token_secret' => "Y5vIafOneLlMtdc75Cpgyz30lovRBgPJ9DygZiBplW1R4",
    'consumer_key' => "rxuG71DjMcfw9XEql7WBXntvn",
    'consumer_secret' => "O08Hup1Ta4uiNjwQsK56jmYKcNa8KUPCKUxq2lPRe9DzHR648i"
);

/** URL for REST request, see: https://dev.twitter.com/docs/api/1.1/ **/
//$url = 'https://api.twitter.com/1.1/blocks/create.json';
//$requestMethod = 'POST';

/** POST fields required by the URL above. See relevant docs as above **/
/*$postfields = array(
    'screen_name' => 'usernameToBlock',
    'skip_status' => '1'
);*/

/** Perform a POST request and echo the response **/
/*$twitter = new TwitterAPIExchange($settings);
echo $twitter->buildOauth($url, $requestMethod)
             ->setPostfields($postfields)
             ->performRequest();
             */

/** Perform a GET request and echo the response **/
/** Note: Set the GET field BEFORE calling buildOauth(); **/
$url = 'https://api.twitter.com/1.1/search/tweets.json';
$getfield = '?q=#JRFoundation&result_type=recent&tweet_mode=extended&exclude=retweets';
$requestMethod = 'GET';

$twitter = new TwitterAPIExchange($settings);
$response = $twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest();

$data = json_decode( $response);

//parse tweet for links
function linkify_tweet($tweet) {
  //Convert urls to <a> links
  $tweet = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $tweet);
  //Convert hashtags to twitter searches in <a> links
  $tweet = preg_replace("/#([A-Za-z0-9\/\.\_]*)/", "<a target=\"_blank\" href=\"http://twitter.com/search?q=$1\">#$1</a>", $tweet);
  //Convert attags to twitter profiles in <a> links
  $tweet = preg_replace("/@([A-Za-z0-9\/\.\_]*)/", "<a target=\"_blank\" href=\"http://www.twitter.com/$1\">@$1</a>", $tweet);
  return $tweet;
}

//set default timezone
date_default_timezone_set('America/New_York');
?>

<html lang="en-us">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <link rel="stylesheet" href="css/style.css">
    <script src="scripts/jquery-3.2.1.min.js"></script>
    <script src="scripts/TweenMax.min.js"></script>
  </head>
  <body>
    <section id="main">
      <?php
      $directory = "images/slideshow/";
        $images = glob($directory . "*.jpg");
        $index = 0;
        $hidden_class = "";
        foreach($images as $image)
        {
          if($index > 0){
            $hidden_class = " hidden";
          }
          echo '<div class="slideshow slide-'.$index.$hidden_class.'" style="background-image: url('.$image.')"></div>';
          $index ++;
        }
      ?>
      <div class="overlay"></div>
      <div class="header">
        <a class="logo" href="http://www.jackierobinson.org"><img src="images/logo-inverse.png" alt="logo"></a>
        <a class="send-tweet-bt" href="https://twitter.com/intent/tweet?hashtags=HappyBirthdayRachelRobinson"><button id="send-tweet">Send Tweet</button></a>
      </div>
      <ul id="tweet-list">
        <?php
          if (count($data->statuses)) {
            // Cycle through the array
            foreach ($data->statuses as $idx => $statuses) {
                // Output an li
                //tweet text
                $text = $statuses->full_text;
                $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
                echo '<li class="hidden">';
                  //bg serves as a link to the tweet
                  $tweet_link = "http://www.twitter.com/".$statuses->user->screen_name."/"."status/".$statuses->id_str;
                  echo '<a class="tweet-bg" href="'.$tweet_link.'" target="_blank"><div></div></a>';

                  //profile info
                  echo '<div class="profile-info">';
                    //pic
                    $profile_pic = $statuses->user->profile_image_url;
                    echo '<div class="profile-pic"><img src="'.$profile_pic.'"></div>';
                    //name
                    echo '<div class="user-name">';
                      echo '<a class="full-name" href="http://twitter.com/'.$statuses->user->screen_name.'" target="_blank">'.$statuses->user->name.'</a><br>';
                      echo '<a class="screen-name" href="http://twitter.com/'.$statuses->user->screen_name.'" target="_blank">@'.$statuses->user->screen_name.'</a>';
                    echo '</div>';//end user-name
                  echo '</div>';//end profile info
                  //tweet info
                  echo '<div class="tweet-info">';
                    //tweet text
                    echo '<div class="tweet-text">'.linkify_tweet($text).'</div>';

                    //media
                    if (isset($statuses->entities->media)) {
                      $media_url = $statuses->entities->media[0]->media_url;
                      echo '<div class="media-container">';
                        echo '<img src="'.$media_url.'">';
                      echo '</div>';
                    }

                    //time stamp
                    echo '<div class="timestamp">'.date("F jS, Y  g:ia",strtotime($statuses->created_at)).'</div>';
                  echo '</div>'; //end tweet-info
                echo '</li>';
              }
            }
        ?>
      </ul>

      <?php
      //raw data - turn off when live
      echo "<div style='display:none'>";
      print_r($data);
      echo "</div>";
      ?>
    </section>
  </body>
  <script src="scripts/main.js"></script>
  <script src="scripts/twitter-window.js"></script>
</html>
