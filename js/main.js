var currentPage = 1;
var borderHeight = $('#page-1 .footer').height();

function isTouchDevice() {
  return !!('ontouchstart' in window) // works on most browsers 
      || !!('onmsgesturechange' in window); // works on ie10
}

function changePageHeight(){
  var height = $(window).height();
  var minimumHeight = 700;

  if(height < minimumHeight){
    height = minimumHeight;
  }

  console.log('window height: '+height);
  $('.page').css({ height: height });
  $('.center').css({ height: height-(borderHeight*2) });

  pageHeight = height;
  scrollToPage(currentPage);
}

function scrollToPageOffset(offset, callback){
  var targetPage = (currentPage+offset);
  scrollToPage(targetPage, true, callback);
};
function scrollToPage(page, animate, callback){
  var target = '#page-'+page;

  if($(target).size() > 0){
    var offset = $(target).offset().top;
    console.log("Scrolling to page: "+page);
    if(animate){
      //$('.footer.active, .header.active').addClass('shaded');
      $('html,body').animate({
        scrollTop: offset
      }, 500, function(){
        //$('.footer.active, .header.active').removeClass('shaded');
        if(callback){ callback(); }
        setCurrentPage(page);
      });
    } else {
      $('html,body').scrollTop(offset);
      if(callback){ callback(); }
    }
    //currentPage = page;
  }
};

function setCurrentPage(page){
  currentPage = page;
  window.location.hash = 'page-'+currentPage;
}

function detectPage(){
  console.log('detecting page.. current page:'+currentPage);
  var position = $(window).scrollTop();
  console.log('position: '+position);
  var updatePage = Math.round(position/pageHeight)+1;
  if(currentPage != updatePage){
    console.log('Setting page to: '+updatePage);
    currentPage = updatePage;
    //window.location.hash = 'page-'+currentPage;
  }
}

function fade_in_pages(){
    console.log(currentPage);
    $('div.page > div').fadeIn(function(){
      if(isTouchDevice()){
        $('.header, .footer').css({ opacity: 1 });
      }
    });
}
$(document).ready(function(){

  changePageHeight();
  //$('.footer.active, .header.active').css({ opacity: 0 });

  setTimeout(function(){

    console.log(window.location.hash);
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

  $(document).scroll(function(){ detectPage(); });
  $(document).keydown(function(e){
    if(e.keyCode == 38){ scrollToPageOffset(-1); return false; }
    if(e.keyCode == 40){ scrollToPageOffset(+1); return false; }
  });
  $('.header.active').bind('click',function(){ 
    console.log('click!');
    var page = parseInt($(this).parent().parent().attr('id').match(/page-(\d+)/)[1]);
    scrollToPage(page-1, true);
  });
  $('.footer.active').bind('click',function(){
    console.log('click!');
    var page = parseInt($(this).parent().parent().attr('id').match(/page-(\d+)/)[1]);
    console.log(page);
    scrollToPage(page+1, true);
  });
});
