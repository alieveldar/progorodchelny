<?php

$file = "site_all_menus-menu" . $am["id"];
if ( RetCache( $file, "cachemenu" ) == "true" ) {
    list( $MENU[$am["link"]], $cap ) = GetCache( $file, 0 );
} else {
    $data  = DB( "SELECT `link`, `name` FROM `_menuitem` WHERE (`nid`='" . $am["id"] . "' && `stat`='1') ORDER BY `rate` DESC" );

    $li_wrap = '<li class="header__link-wrapper"><a class="header__link" href="%1$s">%2$s</a></li>';

    $rows = round($data['total'] / 3);
    $MENU[$am["link"]] = '<ul class="header__menu">';
    for ( $i = 0; $i <= $data["total"]; $i++ ) {
        @mysql_data_seek( $data["result"], $i );
        $ar = @mysql_fetch_array( $data["result"] );
        if ( $ar['name'] != "" ) {
            $MENU[$am["link"]] .= sprintf($li_wrap, $ar["link"], $ar['name']);
        }
    }
    $MENU[$am["link"]] .= '<li class="header__link-wrapper hidden-desktop">
        <a class="header__link" href="/modules/standart/ChangeVersion.php?to=1">Полная версия</a></li>';
    $MENU[$am["link"]] .= '<li class="header__link-wrapper"><a class="header__link" href="javascript:void(0);"><!--USER--></a></li>';
    $MENU[$am["link"]] .= '<!--mobilesite-->';
    $MENU[$am["link"]] .= '<li class="header__link-wrapper hidden-desktop"><a class="header__link" href="/add/1">Предложить Новость</a></li>';
    $MENU[$am["link"]] .= '</ul>';

    SetCache( $file, $MENU[$am["link"]], "", $type );
}

if( ! empty($_SESSION['userid']) ) {
    $MENU[$am["link"]] = str_replace('href="javascript:void(0);"><!--USER-->',
        'href="/users/view/' . $_SESSION['userid'] . '">Профиль', $MENU[$am["link"]]);
}
if( isset($_SESSION['full']) && $_SESSION['full'] === 1 ) {
    $MENU[$am["link"]] = str_replace('<!--mobilesite-->', '<li class="header__link-wrapper hidden-mobile"><a class="header__link" href="/modules/standart/ChangeVersion.php?to=0" style="font-size:0.9em;">Мобильный</a></li>', $MENU[$am["link"]]);
}
