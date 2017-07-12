$(document).ready(function(){
  var $tweetList = $("#tweet-list");
  var tweetsArray = [];
  var curTweetNum = 0;
  var $curTweet = $($tweetList.children()[curTweetNum]);

  nextSlide();

  function killSlide(){
    TweenMax.to($curTweet,0.7,{top: $curTweet.offset().top - 50, opacity: 0, delay: 2, ease: Power3.easeIn, onComplete:nextSlide});
  }

  function nextSlide(){
    $curTweet.addClass("hidden");
    if(curTweetNum < $tweetList.children().length){
      curTweetNum += 1;
    }
    else{
      curTweetNum = 0;
    }
    $curTweet = $($tweetList.children()[curTweetNum]);
    $curTweet.removeClass("hidden");
    TweenMax.from($curTweet,1.3,{top: $curTweet.offset().top+$curTweet.height() + 50, opacity: 0,  ease: Power3.easeOut});//, onComplete:killSlide});
  }
});
