<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />

<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" href="/skin/frontend/default/kora/css/gridpak.css" media="all" />
<link rel="stylesheet" type="text/css" href="/skin/frontend/default/kora/css/supersized.css" media="all" />
<link rel="stylesheet" type="text/css" href="/skin/frontend/default/kora/css/colorbox.css" media="all" />
<link rel="stylesheet" type="text/css" href="/skin/frontend/default/kora/css/jquery-ui-1.8.19.custom.css" media="all" />
<link rel="stylesheet" type="text/css" href="/skin/frontend/default/kora/css/jquery.jqzoom.css" media="all" />
<link rel="stylesheet" type="text/css" href="/skin/frontend/default/kora/css/cookiecuttr.css" media="all" />
<link rel="stylesheet" type="text/css" href="/skin/frontend/default/kora/css/print.css" media="print" />
<link rel="stylesheet" type="text/css" href="/skin/frontend/default/kora/css/jquery.jscrollpane.css" media="print" />

<style type="text/css">
form {
  display: inline;
}
input#author, input#email, input#url {
  width: 150px;
}
</style>

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="icon" href="/skin/frontend/default/kora/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/skin/frontend/default/kora/favicon.ico" type="image/x-icon" />

<!-- add jquery -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> 
<script>window.jQuery || document.write('<script src="/js/libs/jquery-1.7.2.min.js"><\/script>')</script>

<script type="text/javascript" src="/js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="/js/jquery.easing.min.js"></script>
<script type="text/javascript" src="/js/jquery.jqzoom-core.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.19.custom.min.js"></script>
<script type="text/javascript" src="/js/supersized.3.2.7.js"></script>
<script type="text/javascript" src="/js/supersized.shutter.js"></script>
<script type="text/javascript" src="/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/js/jquery.cookiecuttr.js"></script>
<script type="text/javascript" src="/js/pirobox_extended.js"></script>
<script type="text/javascript" src="/js/jscrollpane/mwheelIntent.js"></script>
<script type="text/javascript" src="/js/jscrollpane/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/js/jscrollpane/jquery.jscrollpane.js"></script>

<script type="text/javascript" src="/js/application.js"></script>

<script type="text/javascript">
  var logged_in = $j.cookie('kora_logged_in');
  var html = "";
  if (!logged_in) {
    html += '<a id="login" href="/customer/account/login">Log in</a>';
  } else {
    html += '<a href="/customer/account">My Account</a> | ';
    html += '<a href="/checkout/cart">Basket</a> | ';
    html += '<a href="/customer/account/logout">Log out</a>';
  }
  html += ' | <a href="/pages/contact/customer-service">Service</a>';

</script>

<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body class=" wordpress-page-view-index is-blog">

      <div class="page">
        <div class="container clearfix">

<script type="text/javascript">
  function isValidEmailAddress(emailAddress) {
      var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
      return pattern.test(emailAddress);
  };
  $j(document).ready(function() {
    $j("div#newslettersignup form").bind('submit', function() {
      var emailAddress = $j(this).find('input#email').val();
      if (!isValidEmailAddress(emailAddress)) {
        return false;
      }
    });
  });
</script>

<div class="row login">
  <div id="newslettersignup" class="col span_2">
    Newsletter:
    <form action="https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8" method="POST">
      <input type="hidden" name="debug" value="0">
      <input type="hidden" name="debugEmail" value="ruth@whatsthestory.net">
      <input type="hidden" name="oid" value="00Db0000000H8TT"/>
      <input type="hidden" name="retURL" value="http://www.kora.net/blog/?nl=1"/>
      <input id="email" maxlength="80" name="email" size="25" type="text" placeholder="Enter your email address" />
      <input type="hidden" id="lead_source" name="lead_source" value="web-mailing list">
      <input type="hidden" id="Opt_out_keep_in_touch__c" name="Opt_out_keep_in_touch__c" value="Yes">
      <input type="submit" value="Go!" name="submit">
    </form>
  </div>

  <?php 
    if ($_GET["nl"] == "1") {?>
    <span id="signupsuccess">Thanks, you've signed up!</span>
  <?}?>

  <div id="login" class="col span_4">
    <script type="text/javascript">
      document.write(html);
    </script>
    <br/>
    <?php if (!$loggedIn) { ?>
      <div id="login-box">

      </div>
    <? } ?>
    <div id="icons">
      <a href="http://www.facebook.com/koraoutdoor" target="_blank"><img src="/skin/frontend/default/kora/images/social/facebook.png"/></a>
      <a href="http://www.twitter.com/koraoutdoor" target="_blank"><img src="/skin/frontend/default/kora/images/social/twitter.png"/></a>
      <a href="http://www.instagram.com/koraoutdoor" target="_blank"><img src="/skin/frontend/default/kora/images/social/instagram.png"/></a>
      <a href="http://www.pinterest.com/koraoutdoor" target="_blank"><img src="/skin/frontend/default/kora/images/social/pinterest.png"/></a>
      <a href="http://www.youtube.com/koraoutdoor" target="_blank"><img src="/skin/frontend/default/kora/images/social/youtube.png"/></a>
    </div>    
  </div>
</div>

<div class="row">
  <div class="header col">
    <h1 id="logo">
      <a href="/">
        Kora Yak Wool Base Layers
      </a>
    </h1>
  </div>
</div>
<div class="row">
  <div class="navigation col">
    <div class="wrapper">
      <div style="background-color: #5B7F95;width: 70px;text-align: center;" class="shop menuitem <?php echo stristr($_SERVER['REQUEST_URI'], 'shop') ? 'active' : '';?>"><a href="/shop">Shop</a></div>
      <div class="menuitem <?php echo stristr($_SERVER['REQUEST_URI'], 'fabric') ? 'active' : '';?>"><a href="/pages/fabric">Fabric</a></div>
      <div class="menuitem <?php echo stristr($_SERVER['REQUEST_URI'], 'community') ? 'active' : '';?>"><a href="/pages/community">Community</a></div>
      <div class="menuitem <?php echo stristr($_SERVER['REQUEST_URI'], 'big-picture') ? 'active' : '';?>"><a href="/pages/big-picture">Big Picture</a></div>
      <div class="menuitem <?php echo stristr($_SERVER['REQUEST_URI'], 'kora-story') ? 'active' : '';?>"><a href="/pages/kora-story">Kora Story</a></div>
      <div class="menuitem <?php echo stristr($_SERVER['REQUEST_URI'], 'blog') ? 'active' : '';?>"><a href="/blog/">Blog</a></div>
      <div class="menuitem <?php echo stristr($_SERVER['REQUEST_URI'], 'contact') ? 'active' : '';?>"><a href="/pages/contact">Contact</a></div>
      <div class="stretcher"></div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col span_4">
    <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
  </div>
</div>