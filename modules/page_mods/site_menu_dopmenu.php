<?php

$file = "site_all_menus-menu" . $am["id"];
if ( RetCache( $file, "cachemenu" ) == "true" ) {
    list( $MENU[$am["link"]], $cap ) = GetCache( $file, 0 );
} else {
    $data  = DB( "SELECT `link`, `name` FROM `_menuitem` WHERE (`nid`='" . $am["id"] . "' && `stat`='1') ORDER BY `rate` DESC" );

    $li_wrap = '<li class="footer__link-wrapper"><a class="footer__link" href="%1$s">%2$s</a></li>';

    $rows = round($data['total'] / 3);
    $MENU[$am["link"]] = '<ul class="footer__menu">';
    for ( $i = 0; $i <= $data["total"]; $i++ ) {
        @mysql_data_seek( $data["result"], $i );
        $ar = @mysql_fetch_array( $data["result"] );
        if ( $ar['name'] != "" ) {
            $MENU[$am["link"]] .= sprintf($li_wrap, $ar["link"], $ar['name']);
        }
    }
    $MENU[$am["link"]] .= '</ul>';

    SetCache( $file, $MENU[$am["link"]], "", $type );
}
