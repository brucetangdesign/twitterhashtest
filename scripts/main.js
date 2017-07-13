$(document).ready(function(){
  var $tweetList = $("#tweet-list");
  var tweetsArray = [];
  var curTweetNum = 0;
  var $curTweet = $($tweetList.children()[curTweetNum]);
  var slideAmount = 100;

  nextSlide();

  function killSlide(){
    TweenMax.to($curTweet,0.7,{x: slideAmount, opacity: 0, delay: 2, ease: Power3.easeIn, onComplete:nextSlide});
  }

  function nextSlide(){
    $curTweet.addClass("hidden");
    TweenMax.set($curTweet,{clearProps:"all"})
    if(curTweetNum < $tweetList.children().length-1){
      curTweetNum += 1;
    }
    else{
      curTweetNum = 0;
    }
    $curTweet = $($tweetList.children()[curTweetNum]);
    $curTweet.removeClass("hidden");
    TweenMax.from($curTweet,1.3,{x: -slideAmount, opacity: 0,  ease: Power3.easeOut, onComplete:killSlide});
  }
});
