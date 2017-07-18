$(document).ready(function(){
  rotateBg();
  rotateTweets();

  function rotateBg(){
    var numSlides = $(".slideshow").length;
    var curSlideNum = 0;
    var $curSlide = $(".slide-"+curSlideNum);
    var $lastSlide = $(".slide-"+(numSlides-1));

    TweenMax.to($curSlide,2,{opacity: 1, delay: 5, onComplete:nextSlide});

    function killSlide(){
      var prevTweetNum;
      var $prevTweet;

      if(curSlideNum == 0){
        prevTweetNum = numSlides-1;
      }
      else{
        prevTweetNum = curSlideNum-1;
      }
      $prevTweet = $(".slide-"+prevTweetNum);
      $prevTweet.addClass("hidden");
      TweenMax.set($prevTweet,{opacity:1});

      TweenMax.to($curSlide,2,{opacity: 1, delay: 5, onComplete:nextSlide});
    }

    function nextSlide(){
      if(curSlideNum < numSlides-1){
        curSlideNum += 1;
      }
      else{
        curSlideNum = 0;
      }
      $curSlide = $(".slide-"+curSlideNum);
      $curSlide.removeClass("hidden");

      if(curSlideNum > 0){
        TweenMax.from($curSlide,2,{opacity: 0, onComplete:killSlide});
      }
      else{
        TweenMax.to($lastSlide,2,{opacity: 0, onComplete:killSlide});
      }
    }
  }

  function rotateTweets(){
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
  }
});
