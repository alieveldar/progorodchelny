<?
//echo "aaa";exit;
$pg = $dir[1] ? $dir[1] : 1;
$file = "_index-kazannews" . (int) $pg;
$VARS["cachepages"] = 10;
$Page["Caption"] = "Новости Казани";
$CSSmodules["авто включение ленты"] = "/modules/lenta/lenta.css";
if ( RetCache( $file ) == "true" ) {
	list( $text, $cap ) = GetCache( $file, 0 );
} else {
	list( $text, $cap ) = KazanNews();
	SetCache( $file, $text, "" );
}
$Page["Content"] = $text;
//echo "aaa";exit;

function KazanNews() {
	$C = "";
	global $VARS, $GLOBAL, $C10, $C15, $C20, $C25, $C, $used, $VAR, $dir;
	$onpage = 50;
	$pg = $dir[1] ? $dir[1] : 1;
	$from = ( $pg - 1 ) * $onpage;
	$onblock = 3;
	$text = "";
	$news = array();
	$ads = array();
	// Главная новость ==============================

	$q = "SELECT `[table]`.`id`, `[table]`.`uid`, `[table]`.`name`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, `[table]`.`onind`, `_users`.`nick`, '[link]' AS `link` " .
	"FROM `[table]` LEFT JOIN `_users` ON `_users`.`id`=`[table]`.`uid` WHERE (`[table]`.`stat`='1' && `[table]`.`onind`=1)";

	$endq = "ORDER BY `data` DESC LIMIT 1";
	$data = getNewsFromLentas( $q, $endq );
	if ( $data["total"] == 1 ) {
		@mysql_data_seek( $data["result"], 0 );
		$ar = @mysql_fetch_array( $data["result"] );
		$used[ $ar["link"] ][] = $ar["id"];
		$news[] = $ar;
	}

	// Вип новости ==================================

	$q = "SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`data`, `[table]`.`pic`, '[link]' AS `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && (`[table]`.`promo`=1 || `[table]`.`spromo`=1) && `[table]`.`data`>'".(time()-3*24*60*60)."' [used])";
	$endq = "ORDER BY `data` DESC";
	$data = getNewsFromLentas( $q, $endq );
	for ( $i = 0; $i < $data["total"]; $i++ ) {
		@mysql_data_seek( $data["result"], $i );
		$ar = @mysql_fetch_array( $data["result"] );
		$used[ $ar["link"] ][] = $ar["id"];
		$ar["style"] = "ReOneOrder";
		$ar["data"] = '';
		if ( $ar["pic"] != "" ) {
			if ( strpos( $ar["pic"], "old" ) != 0 ) { /*Старый вид картинок*/
				$ar['pic'] = "<img src='" . $ar["pic"] . "' title='" . $ar["name"] . "' />";
			} else { /*Новый вид картинок*/
				$ar['pic'] = "<img src='/userfiles/pictavto/" . $ar["pic"] . "' title='" . $ar["name"] . "' />";
			}
		}
		if ( $UserSetsSite[3] == 1 && $ar["comments"] != 2 ) {
			$ar['coms'] = "<div class='CommentBox'><a href='/" . $ar["link"] . "/view/" . $ar["id"] . "#comments'>" . $ar["comcount"] . "</a></div>";
		} else {
			$ar['coms'] = "";
		}
		if ( $ar["link"] != "ls" ) {
			$ads[] = $ar;
		}
	}

	// Находим все таблицы с lenta ==================

	$q = "SELECT `[table]`.`id`, `[table]`.`uid`, `[table]`.`name`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, `[table]`.`onind`, `_users`.`nick`, '[link]' AS `link` " .
	"FROM `[table]` LEFT JOIN `_users` ON `_users`.`id`=`[table]`.`uid` WHERE (`[table]`.`stat`='1' [used])";
	$endq = "ORDER BY `data` DESC LIMIT " . $from . ", " . $onpage;
	$data = getNewsFromLentas( $q, $endq );
	$j = 0;
	$b = 1;

	// выводим новости ==============================

	for ( $i = 0; $i < $data["total"]; $i++ ) {
		@mysql_data_seek( $data["result"], $i );
		$ar = @mysql_fetch_array( $data["result"] );
		$used[ $ar["link"] ][] = $ar["id"];
		$news[] = $ar;
	}
	$i = -1;
	foreach ( $news as $ar ) {
		$i++;
		$d = ToRusData( $ar["data"] );
		$pic = "";
		if ( $ar["pic"] != "" ) {
			if ( strpos( $ar["pic"], "old" ) != 0 ) { /*Старый вид картинок*/
				$pic = "<img src='" . $ar["pic"] . "' title='" . $ar["name"] . "' />";
			} else { /*Новый вид картинок*/
				$pic = "<img src='/userfiles/pictavto/" . $ar["pic"] . "' title='" . $ar["name"] . "' />";
			}
		}
		if ( $ar["uid"] != 0 && $ar["nick"] != "" ) {
			$auth = "<a href='/users/view/" . $ar["uid"] . "/'>" . $ar["nick"] . "</a>";
		} else {
			$auth = "<a href='/add/2/'>Народный корреспондент</a>";
		}
		if ( $UserSetsSite[3] == 1 && $ar["comments"] != 2 ) {
			$coms = "<div class='CommentBox'><a href='/" . $ar["link"] . "/view/" . $ar["id"] . "#comments'>" . $ar["comcount"] . "</a></div>";
		} else {
			$coms = "";
		}
		$text .= "<div class='NewsLentaList' id='NewsLentaList-" . $ar["id"] . "'><a href='/" . $ar["link"] . "/view/" . $ar["id"] . "'>" . $pic . "</a><h2><a href='/" . $ar["link"] . "/view/" . $ar["id"] . "'>" . $ar["name"] . "</a></h2>" . $C . "
		<div class='Info'><div class='Other'>" . Replace_Data_Days( $d[4] ) . ",  Автор: " . $auth . "</div>" . $coms . "</div></div>";

		if ( ( $i + 1 ) % $onblock == 0 ) {
			$ad = $ads[(int) (($i + 1) / $onblock - 1)];
			$text .= $C25."<div class='NewsLentaList' id='NewsLentaList-" . $ad["id"] . "'><a href='/" . $ad["link"] . "/view/" . $ad["id"] . "'>" . $ad['pic'] . "</a><h2><a href='/" . $ad["link"] . "/view/" . $ad["id"] . "'>" . $ad["name"] . "</a></h2>" . $C . "</div>";
			if ( $j == 0 ) {
				$text .= "<div class='Banner' id='Banner-27-1'></div>" . $C15;
			} elseif ( $j == 1 ) {
				$text .= "<div class='Banner' id='Banner-27-2'></div>" . $C15;
			} else {
				$text .= "<div class='Banner' id='Banner-28-" . $b . "'></div>" . $C15;
				$b ++;
			}
			$j ++;
		} else {
			$text .= $C25;
		}
		if ( $i === 3 ) {
			$text .= '<script type="text/javascript">
(function loadExtData(w) {
  w.adv = new Adv();
})(window);
</script>
  <div id="adfox_149699505329239857"></div>
  <script type="text/javascript">
adv.banner(function(webmd) {
    console.log("Showing banner 1", webmd);
           var wmclusters = webmd["clusters"].toString();
           var audiences =  webmd["audiences"].toString();
           wmclusters = wmclusters.replace(/,/g,":");
           audiences = audiences.replace(/,/g,":");
    window.Ya.adfoxCode.create({
        ownerId: 251657,
        containerId: "adfox_149699505329239857",
        params: {
            pp: "g",
            ps: "cjof",
            p2: "frcw",
            puid1: webmd["socio_demographics"]["age"],
            puid2: webmd["socio_demographics"]["gender"],
            puid3: webmd["socio_demographics"]["social_class"],
            puid4: wmclusters,
            puid5: audiences
        },
           onRender: function() { console.log("otag_rendered"); },
           onError: function(error) { console.log("otag_error"); },
           onStub: function() { console.log("otag_stub"); }
});
});
</script>';
		}
	}
	// строим пагер =================================
	$q = "SELECT `[table]`.`id` FROM `[table]` WHERE (`[table]`.`stat`='1')";
	$endq = "";
	$data = getNewsFromLentas( $q, $endq );
	$text .= Pager2( $pg, $onpage, ceil( $data["total"] / $onpage ), "kazan-news/" . "[page]" );

	// ==============================================
	return ( array( $text, $C ) );
}

?>