var $j = jQuery.noConflict(); 

if ($j(location).attr('pathname') == "/pages") {
  window.location = '/pages/fabric';
}

$j(window).load(function() {
    $j('.blueberry').blueberry();
});

$j(document).ready(function() {

    $j(window).resize(function() {
      //console.log('resizing');
      var mywindow = $j(window);
      //console.log(mywindow.width());
    });  

  // make KORA logo clickable
  $j('h1#logo').bind('click', function() {
    window.location = $j(this).find('a').attr('href');
  });

  // COOKIES
  $j.cookieCuttr({
    cookieAnalytics: false,
    cookiePolicyLink: '/pages/contact/customer-service/cookies',
    cookieMessage: 'We use cookies on this website, you can <a href="{{cookiePolicyLink}}" title="read about our cookies">read about them here</a>. To use the website as intended, please '
  });

//  $j('a#login').bind('click', function() {
//    $j('div#login-box').toggle();
//    return false;
//  })

  // lightbox on wordpress pages for gallery images
  $j("dl.gallery-item").find("a").addClass("pirobox_gall").attr("rel","gallery");
  // add "last" class to thumbnails at the end of the row
  var thumbCount=0;
  $j('dl.gallery-item').each(function(index) {
    thumbCount++;
    if (thumbCount % 4 == 0) {
      $j(this).addClass("last");
    }
  });  
  
  $j().piroBox_ext({
        piro_speed : 900,
        bg_alpha : 0.1,
        piro_scroll : true //pirobox always positioned at the center of the page
    });

  //$j(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});

  // tabs for product details
  $j('#tabs').tabs();

  // adjust layout if there are no right-hand sub nav links (not for blog)
  if ($j(location).attr('pathname').indexOf('blog')==0) {
    if ($j("div.related_content").eq(1).find("li").length == 0) {
      $j("div.related_content").eq(1).hide();
      $j("div.main_content").addClass("span_5");
    }
  }

  $j('.scroll-pane').jScrollPane({showArrows:false});
  $j('.ui-tabs-panel').jScrollPane({showArrows:false});
  $j('.ui-tabs-nav a[href*=fabric]').bind('click', function() {
    // Reinitialise the #pane2 scrollpane
    var paneApi = $j('#fabric').data('jsp');
    paneApi.reinitialise();
  })

  $j('.ui-tabs-nav a[href*=reviews]').bind('click', function() {
    // Reinitialise the #pane2 scrollpane
    var paneApi = $j('#reviews').data('jsp');
    paneApi.reinitialise();
  })

  $j('.ui-tabs-nav a[href*=sizing]').bind('click', function() {
    // Reinitialise the #pane2 scrollpane
    var paneApi = $j('#sizing').data('jsp');
    paneApi.reinitialise();
  })

  // add "active" class to first page-links item if 
  // it's the base page for the section
  $title = $j("div.page-title h1");
  $active_page = $j(".related_content li.active a");
  if ($title.html() == $active_page.html()) {
    $j("ul#page-links li").first().addClass("active");
  }
  else {
    $j("ul#page-links li").each(function() {
      if ($j(this).find('a').html() == $title.html()) {
        $j(this).addClass("active");
      }
    });
  }

  /* Place Supersized Elements
  ----------------------------*/
  $j('div.page').append('<div id="supersized-loader"></div><ul id="supersized"></ul>');

  if ($j(location).attr('pathname') == "/") {
    var slides = '<div class="blueberry"><ul class="slides">';
    slides += '<li><a href="/shop"><img src="/skin/frontend/default/kora/images/slides/homepage-slide-1.jpg" /></a></li>';
    slides += '<li><a href="/pages/fabric/yak-wool-performance"><img src="/skin/frontend/default/kora/images/slides/homepage-slide-2.jpg" /></a></li>';
    slides += '<li><a href="/blog/category/ambassadors/"><img src="/skin/frontend/default/kora/images/slides/homepage-slide-3.jpg" /></a></li>';
    slides += '<li><a href="/pages/big-picture"><img src="/skin/frontend/default/kora/images/slides/homepage-slide-4.jpg" /></a></li>';
    slides += '</ul></div><span id="banner">100% YAK WOOL PERFORMANCE BASE LAYERS - <a href="/shop">VIEW IN THE SHOP</a></span>';
    $j('.cms-home .main_content.span_6').append(slides);
  }

  // Slideshow Images
  var slides = [];
  if ($j(location).attr('pathname') == "/") {
    slides = [
            {image : '/skin/frontend/default/kora/images/background-home.jpg', title : ''}
            //Himalaya born, extreme performance, enduring comfort<br><a href="/shop">Visit our shop</a>
//            {image : '/skin/frontend/default/kora/images/background-3.jpg', title : 'kora<sup>TM</sup> uses nature\'s science to support your athletic performance in extreme environments<br><a href="/pages/fabric/yak-wool-performance">Yak wool performance</a>'},
//            {image : '/skin/frontend/default/kora/images/home-bg-go-further.jpg', title : 'Every day is an adventure...and anything is possible<br><a href="/pages/kora-story">the kora<sup>TM</sup> story</a>'},
//            {image : '/skin/frontend/default/kora/images/home-bg-adventure.jpg', title : 'kora<sup>TM</sup> people go further to challenge their limits... and connect with the natural world<br><a href="/pages/community">kora<sup>TM</sup> community</a>'},
//            {image : '/skin/frontend/default/kora/images/home-bg-its-what-you-do.jpg', title : 'kora<sup>TM</sup> will bring positive change to the communities that provide our wool. <br><a href="/pages/big-picture">The big picture</a>'}
            ]
  }
  else if (~$j(location).attr('pathname').indexOf('blog')) {
    slides = [{image : '/skin/frontend/default/kora/images/blog-bg.jpg', title : ''}]
  }
  else if (~$j(location).attr('pathname').indexOf('shop')) {
    slides = [{image : '/skin/frontend/default/kora/images/background-3.jpg', title : ''}]
  }
  else if (~$j(location).attr('pathname').indexOf('/pages/fabric')) {
    slides = [{image : '/skin/frontend/default/kora/images/background-fabric.jpg', title : ''}]
  }
  else if (~$j(location).attr('pathname').indexOf("/pages/community")) {
    slides = [{image : '/skin/frontend/default/kora/images/home-bg-go-further.jpg', title : ''} ]
  }
  else if (~$j(location).attr('pathname').indexOf("/pages/big-picture")) {
    slides = [{image : '/skin/frontend/default/kora/images/background-home.jpg', title : ''} ]
  }
  else if (~$j(location).attr('pathname').indexOf("/pages/kora-story")) {
    slides = [{image : '/skin/frontend/default/kora/images/background-kora-story.jpg', title : ''}]
  }
  else if (~$j(location).attr('pathname').indexOf("/blog")) {
    slides = [{image : '/skin/frontend/default/kora/images/background-2.jpg', title : ''}]
  }
  else if (~$j(location).attr('pathname').indexOf("/pages/contact")) {
    slides = [{image : '/skin/frontend/default/kora/images/background-contact.jpg', title : ''} ]
  }
  else {
    slides = [     
            {image : '/skin/frontend/default/kora/images/background-home.jpg', title : ''}
            ]
  }

  $j.supersized({

    // Functionality
    slideshow               :   1,      // Slideshow on/off
    autoplay        : 1,      // Slideshow starts playing automatically
    start_slide             :   1,      // Start slide (0 is random)
    stop_loop       : 0,      // Pauses slideshow on last slide
    random          :   0,      // Randomize slide order (Ignores start slide)
    slide_interval          :   7000,   // Length between transitions
    transition              :   1,      // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
    transition_speed    : 1000,   // Speed of transition
    new_window        : 0,      // Image links open in new window/tab
    pause_hover             :   0,      // Pause slideshow on hover
    keyboard_nav            :   0,      // Keyboard navigation on/off
    performance       : 3,      // 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
    image_protect     : 1,      // Disables image dragging and right click with Javascript
                         
    // Size & Position               
    min_width           :   0,      // Min width allowed (in pixels)
    min_height            :   0,      // Min height allowed (in pixels)
    vertical_center         :   0,      // Vertically center background
    horizontal_center       :   1,      // Horizontally center background
    fit_always        : 0,      // Image will never exceed browser width or height (Ignores min. dimensions)
    fit_portrait          :   1,      // Portrait images will not exceed browser height
    fit_landscape     :   1,      // Landscape images will not exceed browser width
                         
    // Components             
    slide_links       : false,  // Individual links for each slide (Options: false, 'number', 'name', 'blank')
    thumb_links       : 0,      // Individual thumb links for each slide
    thumbnail_navigation    :   0,      // Thumbnail navigation
    slides          :   slides,
     
    // Theme Options         
    progress_bar      : 0,      // Timer for each slide             
    mouse_scrub       : 0
  
  });


});

function preload(arrayOfImages) {
    $j(arrayOfImages).each(function(){
        $j('<img/>')[0].src = this;
        // Alternatively you could use:
        // (new Image()).src = this;
    });
}

(function($){
  $j.fn.extend({
    blueberry: function(options) {

      //default values for plugin options
      var defaults = {
        interval: 5000,
        duration: 2000,
        lineheight: 1,
        height: 'auto', //reserved
        hoverpause: false,
        pager: false,
        nav: false, //reserved
        keynav: false
      }
      var options =  $j.extend(defaults, options);
 
      return this.each(function() {
        var o = options;
        var obj = $j(this);

        //store the slide and pager li
        var slides = $j('.slides li', obj);
        var pager = $j('.pager li', obj);

        //set initial current and next slide index values
        var current = 0;
        var next = current+1;

        //get height and width of initial slide image and calculate size ratio
        var imgHeight = slides.eq(current).find('img').height();
        var imgWidth = slides.eq(current).find('img').width();
        var imgRatio = imgWidth/imgHeight;

        //define vars for setsize function
        var sliderWidth = 0;
        var cropHeight = 0;

        //hide all slides, fade in the first, add active class to first slide
        slides.hide().eq(current).fadeIn(o.duration).addClass('active');
        

        //build pager if it doesn't already exist and if enabled
        if(pager.length) {
          pager.eq(current).addClass('active');
        } else if(o.pager){
          obj.append('<ul class="pager"></ul>');
          slides.each(function(index) {
            $j('.pager', obj).append('<li><a href="#"><span>'+index+'</span></a></li>')
          });
          pager = $j('.pager li', obj);
          pager.eq(current).addClass('active');
        }

        //rotate to selected slide on pager click
        if(pager){
          $j('a', pager).click(function() {
            //stop the timer
            clearTimeout(obj.play);
            //set the slide index based on pager index
            next = $j(this).parent().index();
            //rotate the slides
            rotate();
            return false;
          });
        }

        //primary function to change slides
        var rotate = function(){
          //fade out current slide and remove active class,
          //fade in next slide and add active class
          slides.eq(next).fadeIn(o.duration).addClass('active').queue(function(){
                  //add rotateTimer function to end of animation queue
                  //this prevents animation buildup caused by requestAnimationFrame
                  //rotateTimer starts a timer for the next rotate
                  rotateTimer();
                  $j(this).dequeue()
              });
          slides.eq(current).fadeOut(o.duration).removeClass('active');

          //update pager to reflect slide change
          if(pager){
            pager.eq(current).removeClass('active')
              .end().eq(next).addClass('active');
          }

          //update current and next vars to reflect slide change
          //set next as first slide if current is the last
          current = next;
          next = current >= slides.length-1 ? 0 : current+1;
        };
        //create a timer to control slide rotation interval
        var rotateTimer = function(){
          obj.play = setTimeout(function(){
            //trigger slide rotate function at end of timer
            rotate();
          }, o.interval);
        };
        //start the timer for the first time
        rotateTimer();

        //pause the slider on hover
        //disabled by default due to bug
        if(o.hoverpause){
          slides.hover(function(){
            //stop the timer in mousein
            clearTimeout(obj.play);
          }, function(){
            //start the timer on mouseout
            rotateTimer();
          });
        }

        //calculate and set height based on image width/height ratio and specified line height
        var setsize = function(){
          sliderWidth = $j('.slides', obj).width();
          cropHeight = Math.floor(((sliderWidth/imgRatio)/o.lineheight))*o.lineheight;

          $j('.slides', obj).css({height: cropHeight});
        };
        setsize();

        //bind setsize function to window resize event
        $j(window).resize(function(){
          setsize();
        });
        
        

        //Add keyboard navigation

        if(o.keynav){
          $j(document).keyup(function(e){

            switch (e.which) {

              case 39: case 32: //right arrow & space

                clearTimeout(obj.play);

                rotate();

                break;


              case 37: // left arrow
                clearTimeout(obj.play);
                next = current - 1;
                rotate();

                break;
            }

          });
        }


      });
    }
  });
})(jQuery);



preload([
'/skin/frontend/default/kora/images/background-home.jpg'
//'/skin/frontend/default/kora/images/background-3.jpg',
//'/skin/frontend/default/kora/images/home-bg-go-further.jpg',
//'/skin/frontend/default/kora/images/home-bg-adventure.jpg',
//'/skin/frontend/default/kora/images/home-bg-its-what-you-do.jpg'
]);
