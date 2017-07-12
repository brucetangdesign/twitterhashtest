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
<html>
  <head>
  </head>
  <body>
    <?php
      if (count($data->statuses)) {
        // Open the list
        echo "<ul>";
        // Cycle through the array
        foreach ($data->statuses as $idx => $statuses) {
            // Output an li
            $text = $statuses->text;
            //$url = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
            echo "<li>";
            if(preg_match($reg_exUrl, $text, $url)) {
                   // make the urls hyper links
                  echo preg_replace($reg_exUrl, '<a href="'.$url[0].'" rel="nofollow">'.$url[0].'</a>', $text);

            } else {
                   // if no urls in the text just return the text
                   echo $text;

            }
            echo "<br>";
            echo "$statuses->created_at<br>";
            echo "<a href='http://twitter.com/".$statuses->user->screen_name."'>".$statuses->user->screen_name."</a>";

            echo "</li>";
        }
        // Close the list
        echo "</ul>";
        echo "<br><br>";
        print_r($data);
    }

    ?>
  </body>
</html>
