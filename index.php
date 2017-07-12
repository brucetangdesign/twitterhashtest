<?php
ini_set('display_errors', 1);
require_once('TwitterAPIExchange.php');

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "2230139474-k8PKU4dHTZTuEtwX3u5RArHMgxboRy9jXq4XKio",
    'oauth_access_token_secret' => "CyXosX3rOWjLA1vBSHIyfFF6sthDtqMBxiEgDfq01VnTx",
    'consumer_key' => "CNe29mZeGmdsfcgrtpMwfntt1",
    'consumer_secret' => "PPEtN9LLMN52rAeGQ640JVNEUMT5yOxRLJsydBP7w15XxZwm1s"
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
$getfield = '?q=#mountains&result_type=recent';
$requestMethod = 'GET';


$twitter = new TwitterAPIExchange($settings);
$response = $twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest();

$data = json_decode( $response);

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
        if (count($data->statuses)) {
          // Open the list
          echo '<ul id="tweet-list">';
          // Cycle through the array
          foreach ($data->statuses as $idx => $statuses) {
              // Output an li
              $text = $statuses->text;
              //$url = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
              $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
              echo '<li class="hidden">';
              $profile_pic = str_replace("_normal","_bigger",$statuses->user->profile_image_url);
              echo '<div class="profile-pic"><img src="'.$profile_pic.'"></div>';
              echo '<div class="profile-info">';
              echo '<div class="timestamp">'.date("F jS, Y  g:ia",strtotime($statuses->created_at)).'</div>';
              echo '<div class="tweet-text">';
              if(preg_match($reg_exUrl, $text, $url)) {
                     // make the urls hyper links
                    echo preg_replace($reg_exUrl, '<a href="'.$url[0].'" rel="nofollow">'.$url[0].'</a>', $text);

              } else {
                     // if no urls in the text just return the text
                     echo $text;

              }
              echo '</div>';
              echo '<a class="username" href="http://twitter.com/'.$statuses->user->screen_name.'">@'.$statuses->user->screen_name.'</a>';

              echo '</li>';
              echo '</div>';
          }
          // Close the list
          echo '</ul>';
          //echo "<br><br>";
          //print_r($data);
      }

      ?>
    </section>
  </body>
  <script src="scripts/main.js"></script>
</html>
