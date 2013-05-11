var currentPage = 1;
var borderHeight = $('.page .footer').height();
var scrollBuffer = 0;
var detecting = true;

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

  console.log('window height: '+height);
  //$('.bg').css({ backgroundSize: '2053px '+(height*(pagesCount-1))+'px' });
  $('.page').css({ height: height });
  $('.center').css({ height: height-(borderHeight*2) });


  pageHeight = height;
  scrollToPage(currentPage);

  var minimumVideoWidth = 600;
  var videoRate = 854/480;
  var videoWidth = $(window).width() > minimumVideoWidth ? $(window).width()-300 : minimumVideoWidth;
  $('#video').css({ width: videoWidth, height: videoWidth/videoRate });

  resolveScroll();
}

function scrollToPageOffset(offset, callback){
  var targetPage = (currentPage+offset);
  scrollToPage(targetPage, true, callback);
}

function resolveScroll(){
    var scrollTop = $(window).scrollTop();
    $('#mainframe > div.bg').css({ backgroundPosition: "center "+((scrollTop*0.5)+500)+"px" });
}

function scrollToPage(page, animate, callback){
  var target = "#"+pagesMap[page];
  detecting = false;

  if($(target).size() > 0){
    var offset = $(target).offset().top;
    console.log("Scrolling to page: "+page);
    if(animate){
      //$('#mainframe > .bg').trigger('freeze');
      detecting = false;
      //$('.footer.active, .header.active').addClass('shaded');
      $('html,body').animate({
        scrollTop: offset
      }, 500, function(){
        //$('.footer.active, .header.active').removeClass('shaded');
        setTimeout(function(){ $('#mainframe > .bg').trigger('unfreeze').trigger('mousemove'); }, 1500);
        if(callback){ callback(); }
        setCurrentPage(page);
        detecting = true;
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
    console.log('Setting page to: '+updatePage);
    currentPage = updatePage;
  }
  if(set){
    /*if(realUpdate % 1 < 0.1){
      console.log('### Forcing page to: '+updatePage);
      window.location.hash = pagesMap[currentPage];
    }*/
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
    });
}
$(document).ready(function(){

  changePageHeight();
  //$('.footer.active, .header.active').css({ opacity: 0 });

  setTimeout(function(){

    if(window.location.hash == ''){
      scrollToPage(2, false, function(){
       // $('.footer.active, .header.active').animate({ opacity: 100 }, 2000);
        fade_in_pages();
      });
    } else {
      //$('.footer.active, .header.active').animate({ opacity: 100 }, 2000);
      fade_in_pages();
    }
  }, 100);


  $(window).resize(function(){
    console.log('windows resized!');
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
    console.log('click!');
    var page = parseInt($(this).parent().parent().attr('data-page'));
    scrollToPage(page-1, true);
  });
  $('.footer.active .next').bind('click',function(){
    console.log('click!');
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

  var mainFrame = $('#mainframe');
  var bg = mainFrame.children('.bg');

  /*bg.parallax(
    { mouseport: mainFrame, frameDuration: 50 },
    { xparallax: 0.3, yparallax: 0.8 }
  );*/

});
