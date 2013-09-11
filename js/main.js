var currentPage = 1;
var borderHeight = $('.page .footer').height();
var scrollBuffer = 0;
var detecting = true;
var player;

function onPlayerStateChange(state){
  if(state === 0){
    scrollToPage(3 , true);    
  }
}

function onYouTubePlayerReady() {
  player = document.getElementById("videobox");

  //console.log(player.getAvailableQualityLevels());
  player.addEventListener('onStateChange', 'onPlayerStateChange');

  if($(player).width() > 800){
    player.setPlaybackQuality('hd720');
  } 

  if(window.location.hash == "#video" || window.location.hash == ""){
    player.playVideo();
  }
};

function isTouchDevice() {
  return !!('ontouchstart' in window) // works on most browsers 
      || !!('onmsgesturechange' in window); // works on ie10
}

function changePageHeight(){
  var height = $(window).height();
  var width = $(window).width();
  var minimumHeight = 700;

  if(height < minimumHeight){
    height = minimumHeight;
  }

  //console.log('window height: '+height);
  //$('.bg').css({ backgroundSize: '2053px '+(height*(pagesCount-1))+'px' });
  $('.page').css({ height: height });
  $('.center').css({ height: height-(borderHeight*2) });


  pageHeight = height;
  scrollToPage(currentPage);

  var minimumVideoWidth = 600;
  var videoRate = 854/480;
  var videoWidth = $(window).width() > minimumVideoWidth ? $(window).width()-300 : minimumVideoWidth;
  $('#videobox').css({ width: videoWidth, height: videoWidth/videoRate });

  resolveScroll();
}

function scrollToPageOffset(offset, callback){
  var targetPage = (currentPage+offset);
  scrollToPage(targetPage, true, callback);
}

function resolveScroll(){
    var scrollTop = $(window).scrollTop();
    // konfigurace bg
    var scrollTopOffset = 0; // 500 pri vice str
    var scrollBgRatio = 0.4; // 0.5 pri vice str
    $('#mainframe > div.bg').css({ backgroundPosition: "center "+((scrollTop*scrollBgRatio)+scrollTopOffset)+"px" });
}

function scrollToPage(page, animate, callback){
  var target = "#"+pagesMap[page];
  detecting = false;

  if(player){
    player.stopVideo();
  }

  if($(target).size() > 0){
    var offset = $(target).offset().top;
    //console.log("Scrolling to page: "+page);
    if(animate){
      //$('#mainframe > .bg').trigger('freeze');
      detecting = false;
      //$('.footer.active, .header.active').addClass('shaded');
      $('html,body').animate({
        scrollTop: offset
      }, 1000, function(){
        //$('.footer.active, .header.active').removeClass('shaded');
        setTimeout(function(){ $('#mainframe > .bg').trigger('unfreeze').trigger('mousemove'); }, 1500);
        if(callback){ callback(); }
        setCurrentPage(page);
        detecting = true;

        if(pagesMap[page] == "video" && player){
          player.playVideo();
        }
      });
    } else {
      $('html,body').scrollTop(offset);
      detecting = true;
      if(callback){ callback(); }
    }
    //currentPage = page;
  }
};

function setCurrentPage(page){
  currentPage = page;
  window.location.hash = pagesMap[page];
}

function detectPage(set, move){
  //console.log('detecting page.. current page:'+currentPage+' ['+pagesMap[currentPage]+']');
  var position = $(window).scrollTop();
  //console.log('position: '+position);
  var realUpdate = position/pageHeight;
  var updatePage = Math.round(realUpdate)+1;
  if(currentPage != updatePage){
    //console.log('Setting page to: '+updatePage);
    currentPage = updatePage;
  }
}

function fade_in_pages(){
    //$('.page').removeClass('nonbg').css({ opacity: 0 }).animate({ opacity: 1 });
    $('.page').removeClass('nonbg');
    $('#mainframe .bg').fadeIn();
    $('div.page > div').fadeIn(function(){

      detectPage(true, true);
      if(isTouchDevice()){
        $('.header, .footer').css({ opacity: 1 });
      }

      $('#help').fadeIn(function(){
        setTimeout(function(){
          $('#help').fadeOut();
        }, 5000);
      });
    });
}
$(document).ready(function(){

  changePageHeight();
  //$('.footer.active, .header.active').css({ opacity: 0 });

  setTimeout(function(){

    var dp = "1"; // DEFAULT PAGE !!!!!
    for(pi in pagesMap){
      if(pagesMap[pi] == defaultPage){
        dp = pi;
      }
    }
    //console.log(dp);
    if(window.location.hash == ''){
      scrollToPage(dp, false, function(){
       // $('.footer.active, .header.active').animate({ opacity: 100 }, 2000);
        fade_in_pages();
      });
    } else {
      //$('.footer.active, .header.active').animate({ opacity: 100 }, 2000);
      fade_in_pages();
    }
  }, 100);


  $(window).resize(function(){
    //console.log('windows resized!');
    changePageHeight();
  });

  $(document).scroll(function(){ 

    resolveScroll();
    var access = false;

    if(scrollBuffer % 10 == 0){
      if(detecting) detectPage(true, false);
    }
    scrollBuffer++;
  });
  $(document).keydown(function(e){
    if(e.keyCode == 38){ scrollToPageOffset(-1); return false; }
    if(e.keyCode == 40){ scrollToPageOffset(+1); return false; }
  });
  $('.header.active').bind('click',function(){ 
    //console.log('click!');
    var page = parseInt($(this).parent().parent().attr('data-page'));
    scrollToPage(page-1, true);
  });
  $('.footer.active .next').bind('click',function(){
    //console.log('click!');
    var page = parseInt($(this).parent().parent().parent().attr('data-page'));
    scrollToPage(page+1, true);
  });

  $('.page').bind('mouseenter', function(){
        $('.footer.active, .header.active', this).animate({ opacity: 1 });
  }).bind('mouseleave', function(){
        $('.footer.active, .header.active', this).animate({ opacity: 0 });
  });

  $('div.nvlogo').click(function(){
    document.location = 'http://www.facebook.com/neo.violence';
    return false;
  });

});
