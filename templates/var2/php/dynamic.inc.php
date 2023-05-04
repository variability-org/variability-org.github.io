<?php
$basecolor = "006699";
  /*
  * nach einem gesetzten Cookie suchen
  */
  if (!empty($_COOKIE['fontsize'])) {
    $fontsize = $_COOKIE['fontsize'];
  } elseif (!empty($_COOKIE['fontsizeR'])) {
    $fontsize = $_COOKIE['fontsizeR'];
  } else {
    $fontsize = 12;
  }

  /*
  * Schriftgröße soll geändert werden
  */
  if (isset($_GET['font'])) {

    if ($_GET['font']=='base') {
      $fontsize = 12;
    } elseif (($_GET['font']=='dec') && ($fontsize>10)) {
      $fontsize -= 1;
    } elseif (($_GET['font']=='inc') && ($fontsize<16)) {
      $fontsize += 1;
    }

    /*
    * Session-Cookie setzen, da die meist akzeptiert werden
    */
    setcookie('fontsize', $fontsize, NULL, '/');
    /*
    * zusätzlich versuchen, dauerhaften Cookie zu setzen
    */
    setcookie('fontsizeR', $fontsize, time()+60*60*24*365, '/');

    /*
    * Caching der Seite verhindern
    */
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
    header('Cache-Control: no-store');
    header('Pragma: no-cache');

  }

  /*
  * Hilfsfunktion, zum erzeugen der Links
  */
  function get_fontsize_link($action, $name) {
    
    $document_uri = current(preg_split('/[&|\?]{1}font=/', $_SERVER['REQUEST_URI']));

    $document_ref = str_replace('&', '&amp;', $document_uri);

    if (strpos($document_ref, '?') === FALSE) {
      $document_ref .= '?font='.$action;
    } else {
      $document_ref .= '&amp;font='.$action;
    }

    return '<a href="'.$document_ref.'">'.$name.'</a>';
  }
/*
FARB-LINKS
*/

  /*
  * nach einem gesetzten Cookie suchen
  */
  if (!empty($_COOKIE['my_color'])) {
    $my_color = $_COOKIE['my_color'];
  } elseif (!empty($_COOKIE['my_colorS'])) {
    $my_color = $_COOKIE['my_colorS'];
  } else {
    $my_color = $basecolor;
  }

  /*
  * Farbe soll geändert werden
  */
  if (isset($_GET['chg_color'])) {

    $my_color = $_GET['chg_color'];
	
    }

    /*
    * Session-Cookie setzen, da die meist akzeptiert werden
    */
    setcookie('my_color', $my_color, NULL, '/');
    /*
    * zusätzlich versuchen, dauerhaften Cookie zu setzen
    */
    setcookie('my_colorS', $my_color, time()+60*60*24*365, '/');

   /*
  * Hilfsfunktion, zum erzeugen der Links
  */
  function get_color_link($action) {
    
    $document_uri = current(preg_split('/[&|\?]{1}chg_color=/', $_SERVER['REQUEST_URI']));

    $document_ref = str_replace('&', '&amp;', $document_uri);

    if (strpos($document_ref, '?') === FALSE) {
      $document_ref .= '?chg_color='.$action;
    } else {
      $document_ref .= '&amp;chg_color='.$action;
    }

    return $document_ref;
  }
?> 