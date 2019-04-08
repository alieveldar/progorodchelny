<?php
// Тема проспмотрена если человек за ней следил
function UserTracker($link, $pid) { return;
	#global $VARS, $GLOBAL, $UserSetsSite; if ($GLOBAL["USER"]["id"]!=0){ $uid=$GLOBAL["USER"]["id"]; DB("UPDATE `_tracker` SET `stat`='0' WHERE (`uid`='".(int)$uid."' && `link`='".$link."' && `pid`='".(int)$pid."')"); }
}

function UsersComments($link, $pid, $sets, $header = 1) {
    global $GLOBAL, $RealHost, $RealPage, $UserSetsSite;
    $file = "user_comments-" . $link . "." . $pid;

    if( ! isset( $UserSetsSite )) {
        return false;
    } // Если не загружены настройки
    if($UserSetsSite[3] == 0) {
        return false;
    }  // Если запрещены комментарии в настройках
    if($sets == 2 && $GLOBAL["USER"]["role"] < 1) {
        return false;
    } // Если запрещены комментарии в данной статье

    list( $text, $cap ) = GetUsersComments( $link, $pid );

    $text = '<div class="comments" id="UserCommentsList"><h2 class="comments__header">комментарии</h2>' . $text . '</div>';

    //lentainform
    $text .= '<div id="M409095ScriptRootC675683"><div id="M409095PreloadC675683">‡агрузка...</div><script>' . "(function(){var D=new Date(),d=document,b='body',ce='createElement',ac='appendChild',st='style',ds='display',n='none',gi='getElementById',lp=d.location.protocol,wp=lp.indexOf('http')==0?lp:'https:';var i=d[ce]('iframe');i[st][ds]=n;d[gi]('M409095ScriptRootC675683')[ac](i);try{var iw=i.contentWindow.document;iw.open();iw.writeln('<ht'+'ml><bo'+'dy></bo'+'dy></ht'+'ml>');iw.close();var c=iw[b];} catch(e){var iw=d;var c=d[gi]('M409095ScriptRootC675683');}var dv=iw[ce]('div');dv.id=\"MG_ID\";dv[st][ds]=n;dv.innerHTML=675683;c[ac](dv);var s=iw[ce]('script');s.async='async';s.defer='defer';s.charset='utf-8';s.src=wp+'//jsc.lentainform.com/p/r/progorodchelny.ru.675683.js?t='+D.getYear()+D.getMonth()+D.getUTCDate()+D.getUTCHours();c[ac](s);})();" . '</script></div>';

    if($sets == 0 || $GLOBAL["USER"]["role"] >= 1) {
        if(($UserSetsSite[4] == 1 && $GLOBAL["USER"]["id"] == 0) || $GLOBAL["USER"]["id"] != 0) {
            if($header == 1) {
                $text .= "<a name='addcomment' id='addcomment'></a><div class='UserCommentsForm'>" . GetFormComments( $link, $pid ) . "</div>";
            }
            if($header == 0) {
                $text .= "<a name='addcomment' id='addcomment'></a><div class='UserCommentsForm'>" . GetFormComments( $link, $pid ) . "</div>";
            }
        }
        if(($UserSetsSite[4] == 1 && $GLOBAL["USER"]["id"] == 0) || $GLOBAL["USER"]["id"] != 0) {
            $text .= "<script>jQuery.each($('.CommentAnswer'), function(i, val) { var fid=$(this).attr('id'); id=fid.split('-'); $(this).html(\"<a href='javascript:void(0);' onClick='CommentAnswer(\"+id[1]+\");'>ответить</a>\"); });</script>";
        } else {
            $redirect_uri = rawurlencode( "http://" . $RealHost . "/modules/standart/LoginSocial.php?back=http://" . $RealHost . "/" . $RealPage );
            $text .= '<script>jQuery.each($(".CommentAnswer"), function(i, val) { var fid=$(this).attr("id"); id=fid.split("-"); $(this).html("<a href=\"javascript:void(0);\" onclick=\"UserAuthEnter(\'Авторизация\', \'' . $redirect_uri . '\');\">ответить</a>"); });</script>';
        }
    }

    /* редактировать и удалить*/
    if($GLOBAL["USER"]["role"] > 1) {
        $text .= "<script>jQuery.each($('.CommentAdmin'), function(i, val) { var fid=$(this).attr('id'); id=fid.split('-'); $(this).html(\"<span class='CommentDelete'><img src='/template/standart/loader2.gif' style='width:57px; height:14px; padding:0; margin-left:15px;' /><a href='javascript:void(0);' onClick='CommentDelete(\"+id[1]+\");' class='CommentDelAdn'>Удалить</a></span><span class='CommentEdit'><img src='/template/standart/loader2.gif' style='width:57px; height:14px; padding:0; float:right; margin-left:15px;' /><a href='javascript:void(0);' onClick='GetCommentForm(\"+id[1]+\");' class='CommentEditAdn'>Редактировать</a></span>\"); });</script>";
    } else {
        if($GLOBAL["USER"]["id"]) {
            $text .= "<script>jQuery.each($('.CommentAdmin'), function(i, val) { var fid=$(this).attr('id'); id=fid.split('-'); if(id[2] == " . (int) $GLOBAL["USER"]["id"] . " || id[2] == " . str_replace( ".", "", $GLOBAL["ip"] ) . ") { $(this).html(\"<span class='CommentEdit'><img src='/template/standart/loader2.gif' style='width:57px; height:14px; padding:0; float:right; margin-left:15px;' /><a href='javascript:void(0);' onClick='GetCommentForm(\"+id[1]+\");' class='CommentEditAdn'>Редактировать</a></span>\");} });</script>";
        }
    }

    if($sets == 0 && $GLOBAL["USER"]["id"] == 0 && $UserSetsSite[4] == 0) {
        $text .= "<a name='addcomment' id='addcomment'></a><h2>Авторизуйтесь для добавления комментария</h2><div class='UserCommentsForm'>" . GetUserAuthForm() . "</div>";
    }
    $text .= "<script>jQuery.each($('.quote'), function(i, val) { if($(this).height()>$(this).parents('.quote-overview.short').height()) $('.ToggleShow', $(this).parents('.quote-container')).show() });</script>";

    return ($text);
}


// Текст комментариев
function GetUsersComments($link, $pid) {
    global $UserSetsSite, $VARS;
    $text  = "";
    $lastc = 0;
    $ip    = $_SERVER['REMOTE_ADDR'];

    $smiles = array(
        ":-)"  => "<span class='Smile Smile1'>:-)</span>", ";-)" => "<span class='Smile Smile2'>;-)</span>",
        ":-("  => "<span class='Smile Smile3'>:-(</span>", ":-D" => "<span class='Smile Smile4'>:-D</span>",
        ":-P"  => "<span class='Smile Smile5'>:-P</span>", "=-D" => "<span class='Smile Smile6'>=-D</span>",
        "8'-(" => "<span class='Smile Smile7'>8'-(</span>", ">-(" => "<span class='Smile Smile8'>>-(</span>",
        ";-|"  => "<span class='Smile Smile9'>;-|</span>", "8-*" => "<span class='Smile Smile10'>8-*</span>",
        "(!)"  => "<span class='Smile Smile11'>(!)</span>", "(?)" => "<span class='Smile Smile12'>(?)</span>",
        "8-|"  => "<span class='Smile Smile13'>8-|</span>", "%-8" => "<span class='Smile Smile14'>%-8</span>",
        "B-|"  => "<span class='Smile Smile15'>B-|</span>", ">:>" => "<span class='Smile Smile16'>>:></span>",
    );

    $data = DB( "SELECT `comments1`.*, `comments2`.`uid` AS `touid`, `comments2`.`text` AS `totext`, `users1`.`nick`, `users1`.`spectitle`, `users1`.`avatar`, `users1`.`signature`, `users1`.`role`, `users1`.`karma`, `users1`.`created`, `users2`.`nick` AS `tonick` FROM `_comments` AS `comments1`
       LEFT JOIN `_users` AS `users1` ON `users1`.`id`=`comments1`.`uid` LEFT JOIN `_comments` AS `comments2` ON `comments2`.`id`=`comments1`.`toid` LEFT JOIN `_users` AS `users2` ON `users2`.`id`=`comments2`.`uid` WHERE (`comments1`.`link`='" . $link . "' && `comments1`.`pid`='" . (int) $pid . "') GROUP BY 1 ORDER BY `comments1`.`data` ASC" );

    if($data["total"] == 0) {
        return (array("<div class='Info' id='NoComments'>Нет комментариев к данной публикации</div>", ""));
    }

    $ids        = array();
    $inc        = array();
    $maxlikes   = 0;
    $maxlikesid = 0;
    ### ID комментариев
    for($i = 0; $i < $data["total"]; $i++) {
        @mysql_data_seek( $data["result"], $i );
        $com   = @mysql_fetch_array( $data["result"] );
        $ids[] = $com["id"];
        $alt   = $com["likes"] - $com["dislikes"];
        if($alt > $maxlikes && $alt >= 10) {
            $maxlikesid = $com['id'];
            $maxlikes   = $alt;
        }
    }
    ### Вложения для всех комментариев
    if($UserSetsSite[7] == 1) {
        $f = DB( "SELECT `pic`, `pid` FROM `_commentf` WHERE (`pid` IN (" . implode( ",", $ids ) . ")) ORDER BY `id` ASC" );
        for($j = 0; $j < $f["total"]; $j++) {
            @mysql_data_seek( $f["result"], $j );
            $fl            = @mysql_fetch_array( $f["result"] );
            $cid           = $fl["pid"];
            $inc[ $cid ][] = $fl["pic"];
        }
    }

    ### Likes для комментариев по IP
    $waslike = array();
    $f       = DB( "SELECT `pid` FROM `_likes` WHERE (`link`='_comments' && `data`>'" . (time() - 24 * 60 * 60) . "' && `ip`='" . $ip . "')" );
    for($j = 0; $j < $f["total"]; $j++) {
        @mysql_data_seek( $f["result"], $j );
        $fl        = @mysql_fetch_array( $f["result"] );
        $waslike[] = $fl["pid"];
    }

    if( ! empty($VARS['stopwords']) ) {
        $stopwords = array_map(function($word) {
            return trim($word, " \t\r\n");
        }, explode(',', $VARS['stopwords'] ));
    } else {
        $stopwords = [];
    }

    ### Вывод комментариев
    for($i = 0; $i < $data["total"]; $i++) {
        @mysql_data_seek( $data["result"], $i );
        $com       = @mysql_fetch_array( $data["result"] );
        foreach($stopwords as $stopword) {
            if( false !== strpos( $com['text'], $stopword ) ) {
                continue 2;
            }
        }
        $datar     = ToRusData( $com["data"] );
        $toComment = "";
        $cid       = $com["id"];
        if($com["uid"] == 0) {
            $com["nick"] = "<span id='UserIdComment-" . $com["id"] . "' class='UserComName'>" . ($com["uname"] ? $com["uname"] : "Горожанин") . "</span>";
            $avatar_id   = 0;
            array_map(function($num) use(&$avatar_id){
                $avatar_id = ( $avatar_id + $num ) % 48;
            }, explode('.', $com['ip']));
            $avatar_id = ( $pid + $avatar_id ) % 48 + 1;
            $avatar_link = '/userfiles/avatar/commentator/' . $avatar_id . '.png';
            $avatar      = '<img class="comment__ava" src="' . $avatar_link . '">';
        } else {
            $com["nick"] = "<a target='_blank' href='/users/view/" . $com["uid"] . "/'><span id='UserIdComment-" . $com["id"] . "' class='UserComName'>" . $com["nick"] . "</span></a>";

            /* Якорь на последние комментарии */
            if($lastc == 0 && $i > ($data["total"] - 4)) {
                $com["nick"] .= '<a id="endcomments" name="endcomments"></a>';
                $lastc       = 1;
            }

            if($com["avatar"] != "" && $com["avatar"] != "/" &&
             is_file( $_SERVER['DOCUMENT_ROOT'] . "/" . $com["avatar"] ) &&
             filesize( $_SERVER['DOCUMENT_ROOT'] . "/" . $com["avatar"] ) > 100) {
                $avatar = '<a target="_blank" href="/users/view/' . $com["uid"] . '"><img class="comment__ava" src="/' . $com["avatar"] . '"></a>';
        } else {
            $avatar_id   = $com['uid'] % 48;
            $avatar_id   = ($pid + $avatar_id) % 48 + 1;
            $avatar_link = '/userfiles/avatar/commentator/' . $avatar_id . '.png';
            $avatar      = '<a target="_blank" href="/users/view/' . $com["uid"] . '"><img class="comment__ava" src="' . $avatar_link . '"></a>';
        }
    }

    $answer = "<span class='CommentAdmin' id='CommentAdmin-" . $com["id"] . "-" . ($com["uid"] ? $com["uid"] : str_replace( ".", "", $com["ip"] )) . "'></span>";

        ### Комментарий
    if($com["toid"]) {
        $toComment = "<div class='quote-container'><b>В ответ на <a href='#comment" . $com["toid"] . "'><u>комментарий</u></a> пользователя <a href='/users/view/" . $com["touid"] . "'><u>" . $com["tonick"] . "</u></a></b><div class='C5'></div><div><div class='quote-overview short'><div class='quote'>" . nl2br( AntiMatFunc2( $com["totext"] ) );
        if(is_array($inc[ $com["toid"] ]) && count( $inc[ $com["toid"] ] ) > 0) {
            $toComment .= "<div class='CommentInc'>";
            foreach($inc[ $com["toid"] ] as $k => $pic) {
                $toComment .= "<a href='/userfiles/comoriginal/" . $pic . "' rel='prettyPhoto[gallery]'><img src='/userfiles/compreview/" . $pic . "' /></a>";
            }
            $toComment .= "<div class='C'></div></div>";
        }
        $toComment .= "</div></div><div class='ToggleShow'><a href='javascript:void(0)' rel='nofollow' onclick='$(\".quote-overview\", $(this).parents(\".quote-container\")).removeClass(\"short\"); $(this).parents(\".ToggleShow\").hide()'>Развернуть</a></div></div></div>";
    }

    $comment = nl2br( $com["text"] );

        // Если не проверяем количество, страница падает из-за сложного запроса
    preg_match_all( '#youtube.com|youtu.be#', strip_tags( $comment ), $m );

    $youtubePattern = '/(http[s]?:\/\/)?((www.youtube.com)|(youtu.be))\/(\S)+/i';
    if(1 === count( $m[0] ) && preg_match_all( $youtubePattern, strip_tags( $comment ), $output )) {
        foreach($output[0] as $url) {
            if(preg_match( '/youtu.be/', $url )) {
                $tmp_url  = explode( '/', $url );
                $video_id = $tmp_url[ count( $tmp_url ) - 1 ];
            } else {
                preg_match( '/v=[^&]+/', $url, $matches );
                $video_id = str_replace( 'v=', '', $matches[0] );
            }
            $embed   = '<br /><object width="360" height="205"><param name="movie" value="http://www.youtube.com/v/' . $video_id . '&hl=en&fs=1&" /><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><embed src="http://www.youtube.com/v/' . $video_id . '&hl=en&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="390" /></object>';
            $comment = str_replace( $url, $embed, $comment );
        }
    }

    $toComment = strtr( $toComment, array("&#039;" => "'") );
    $toComment = strtr( $toComment, $smiles );
    $comment   = strtr( $comment, array("&#039;" => "'") );
    $comment   = strtr( $comment, $smiles );

        // ----------- ВЫВОД ------------ ВЫВОД ------------ ВЫВОД -----------

    $text     .= "<a name='comment" . $com["id"] . "' id='comment" . $com["id"] . "'></a>";
    $dopclass = "";
    $dophref1 = "";
    $dophref2 = "";
    if($com["id"] == $maxlikesid) {
        $dopclass = " comment_best";
    }
    if(($com["dislikes"] - $com["likes"]) >= 10) {
        $dopclass = " BedCom";
        $dophref1 = "<div class='comment__text BadCommentA BadCommentA-" . $com["id"] . "'>Пользователи сочли этот комментарий бесполезным и мы его скрыли. <a href='javascript:void(0);' onclick='ShowBadComment(" . $com["id"] . ");'>Показать</a></div><div class='ComHiddenPart-" . $com["id"] . "' style='display:none;'>";
        $dophref2 = "</div>";
    }
    $text .= '<div class="comment' . $dopclass . '" id="CommentItem-' . $com["id"] . '">';
    $text .= '<div class="comment__ava-wrapper">' . $avatar . "</div>";
    $text .= '<div class="comment__main"><div class="comment__info">';
    $text .= '<div class="comment__username">' . $com["nick"] . '</div>';
    $text .= '<div class="comment__info-wrapper">';
    $text .= '<div id="CommentLike-' . $com["id"] . '"><div class="comment" style="margin:0;padding:0;">';
    if(in_array( $com["id"], $waslike )) {
        $onclick = ['like' => '', 'dislike' => '', 'style' => ''];
    } else {
        $onclick = [
            'like'    => 'onclick="likeSaveComment(1,' . $com['id'] . ')"',
            'dislike' => 'onclick="likeSaveComment(0,' . $com['id'] . ')"',
            'style' => 'style="cursor: pointer;"',
        ];
    }
    $text .= '<div class="comment__like" ' . $onclick['like'] . $onclick['style'] . '>' . $com["likes"] . '</div>';
    $text .= '<div class="comment__dislike" ' . $onclick['dislike'] . $onclick['style'] . '>' . $com["dislikes"] . '</div>';
    $text .= '</div></div>';
    list($date, $time) = explode(', ', $datar[1]);
    $text .= '<div class="comment__date">' . $date . '</div>';
    $text .= '<div class="comment__time">' . $time . '</div>';
    $text .= '</div></div>' . $dophref1;
    $text .= '<div class="comment__text view1">' . AntiMatFunc2( $comment ) . '</div>';
    $text .= '<div class="comment__text view2"></div>';

        #Вложения
    if(is_array( $inc[ $cid ] ) && count( $inc[ $cid ] ) > 0) {
        $text .= "<div class='CommentInc'>";
        foreach($inc[ $cid ] as $k => $pic) {
            $text .= "<a href='/userfiles/comoriginal/" . $pic . "' rel='prettyPhoto[gallery]'><img src='/userfiles/compreview/" . $pic . "' /></a>";
        }
        $text .= "</div>";
    }

    $text .= $toComment;

    $text .= $answer . '<div class="CommentAnswer" id="CommentAnswer-' . $com["id"] . '" style="text-transform:lowercase;"></div>';
        //$text .= '<div>' . $toComment . '</div>';

    $text .= '</div></div>'.$dophref2;
}

return (array($text, ""));
}


// Форма комментариев
function GetFormComments($link, $pid) {
    global $VARS, $GLOBAL, $UserSetsSite;
    $smiles = "<div class='Smiles'><a href='javascript:void(0)' class='Smile Smile1 toggle'></a>";
    $smiles .= "<div class='SmilesGroup'>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile1' onclick='addSmile($(this))' rel='nofollow'>:-)</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile2' onclick='addSmile($(this))' rel='nofollow'>;-)</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile3' onclick='addSmile($(this))' rel='nofollow'>:-(</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile4' onclick='addSmile($(this))' rel='nofollow'>:-D</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile5' onclick='addSmile($(this))' rel='nofollow'>:-P</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile6' onclick='addSmile($(this))' rel='nofollow'>=-D</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile7' onclick='addSmile($(this))' rel='nofollow'>8'-(</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile8' onclick='addSmile($(this))' rel='nofollow'>>-(</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile9' onclick='addSmile($(this))' rel='nofollow'>;-|</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile10' onclick='addSmile($(this))' rel='nofollow'>8-*</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile11' onclick='addSmile($(this))' rel='nofollow'>(!)</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile12' onclick='addSmile($(this))' rel='nofollow'>(?)</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile13' onclick='addSmile($(this))' rel='nofollow'>8-|</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile14' onclick='addSmile($(this))' rel='nofollow'>%-8</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile15' onclick='addSmile($(this))' rel='nofollow'>B-|</a>";
    $smiles .= "<a href='javascript:void(0)' class='Smile Smile16' onclick='addSmile($(this))' rel='nofollow'>>:></a>";
    $smiles .= "</div></div>";

    $text = '<div class="add-comment">';
    $text .= '<h2 class="add-comment__header">добавить комментарий:</h2>';
    $text .= '<p class="add-comment__text"><span class="add-comment__text_blue">Внимание!</span> Правилами сайта запрещается использовать мат и высказываться оскорбительно по отношению к другим людям</p>';

    if($GLOBAL["USER"]["id"] == 0) {
        $text .= "<input class='add-comment__input UserComName' type='text' placeholder='Введите свое имя' value='" . $_SESSION["username"] . "' /><p class='or add-comment__link'>или авторизуйтесь</p>" . GetUserAuthForm();
    }
    $text .= "<div class='UserComAnswer'></div><div class='UserComAnswerC'></div>";
    $text .= "<textarea class='add-comment__textarea UserComText' cols='30' rows='10' placeholder='Введите текст комментария'></textarea><div class='CommentMsg'></div>";
    $text .= $smiles;
    $text .= '<div class="Info" style="line-height:26px;">  Допускаются теги &lt;b&gt;, &lt;i&gt;, &lt;u&gt;, &lt;p&gt; и ссылки http://youtube.com/watch?v=VIDEO</div>';
    if($UserSetsSite[7] == 1) {
        $text .= '<link href="/modules/standart/multiupload/client/uploader2.css" type="text/css" rel="stylesheet">';
        $text .= '<script type="text/javascript" src="/modules/standart/multiupload/client/uploader.js"></script>';

        $text .= '<div id="uploadercom" class="add-comment__input"></div>';
        $text .= '<a class="add-comment__link" href="javascript:void(0);">Прикрепите фотографии (jpg, gif и png)</a>';
        $text .= '<div id="uploadercompics" style="display:none;"></div>';
    }
    if($GLOBAL["USER"]["id"] == 0 && $UserSetsSite[5] == 1) {
        $text .= '<div class="MiniInput"><img src="/modules/standart/captcha/Captcha.php?' . time() . '" class="captchaImg" /><input name="captcha" class="UserComCaptcha" type="text"></div>';
    }
    $text .= '<button class="add-comment__submit CommentSend" name="sendbutton" type="submit" onclick="SendUserComment($(this).parents(\'.UserCommentsForm\'));">Добавить комментарий</button>';
    if($VARS["commenttext"] != "") {
        $text .= '<div class="add-comment__text">' . $VARS["commenttext"] . "</div>";
    }
    $text .= '</div>';

    return $text;
}
