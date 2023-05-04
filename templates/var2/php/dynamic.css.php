<?php
  $basecolor = "006699";
  header('Content-Type: text/css; charset=iso-8859-1');

  /*
  * Caching der Seite verhindern
  */
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
  header('Cache-Control: no-store');
  header('Pragma: no-cache');

  list($usec, $sec) = explode(' ',microtime());
  print('/* '.((float)$usec + (float)$sec)." */\n");

  if (!empty($_COOKIE['fontsize'])) {
    $fontsize = $_COOKIE['fontsize'];
  } elseif (!empty($_COOKIE['fontsizeR'])) {
    $fontsize = $_COOKIE['fontsizeR'];
  } else {
    $fontsize = 12;
  }
  
   if (!empty($_COOKIE['my_color'])) {
    $my_color = $_COOKIE['my_color'];
  } elseif (!empty($_COOKIE['my_colorS'])) {
    $my_color = $_COOKIE['my_colorS'];
  } else {
    $my_color = $basecolor;
  }

?>
body {
  font-size: <?php print($fontsize."px"); ?>;
}

.readon {
border-bottom:1px dotted #<?php print($my_color); ?>;
border-left:2px solid #<?php print($my_color); ?>;
}
#newsflash, .div_newsflash{
background-color:#<?php print($my_color); ?>;
}

a:link, a:visited, h3, .contentheading,.blog_more div strong {
color:#<?php print($my_color); ?>;
}
