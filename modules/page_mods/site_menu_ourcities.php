<?php

$file = "site_all_menus-menu" . $am["id"];
if ( RetCache( $file, "cachemenu" ) == "true" ) {
    list( $MENU[$am["link"]], $cap ) = GetCache( $file, 0 );
} else {
    $data  = DB( "SELECT `link`, `name` FROM `_menuitem` WHERE (`nid`='" . $am["id"] . "' && `stat`='1') ORDER BY `rate` DESC" );

    $li_wrap = '<li class="footer__site-wrapper"><a class="footer__site" href="%1$s"%2$s>%3$s</a></li>';
    $start_ul = '<ul class="footer__sites-block">';
    $end_ul = '</ul>';

    $rows = ceil($data['total'] / 3);
    $MENU[$am["link"]] = $start_ul;
    $menu_offset = 3 * $rows - $data['total'];
    for ( $i = 0; $i <= $data["total"]; $i++ ) {
        @mysql_data_seek( $data["result"], $i );
        $ar = @mysql_fetch_array( $data["result"] );
        if ( $ar['name'] != "" ) {
            if( 1 == $menu_offset && ( $i == (2 * $rows - 1) || $i == $rows ) ||
                2 == $menu_offset && ( $i == ($rows - 1) || $i == 2 * $rows - 1 ) ||
                0 == $menu_offset && ( $i > 0 && $i % $rows === 0) ) {
                $MENU[ $am["link"] ] .= $end_ul . $start_ul;
            }
            if( false === strpos($ar['link'], 'progorodchelny.ru') ) {
                $rel = ' rel="nofollow"';
            } else {
                $rel = '';
            }
            $MENU[$am["link"]] .= sprintf($li_wrap, $ar["link"], $rel, $ar['name']);
        }
    }
    $MENU[$am["link"]] .= $end_ul;

    SetCache( $file, $MENU[$am["link"]], "", $type );
}
