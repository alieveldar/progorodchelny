<?php
$table  = $link . "_nodes";
$table2 = $link . "_cats";

if(isset( $_SESSION['Data']["sendbutton"] )) {
    $P = $_SESSION['Data'];
    if((isset( $P['captcha'] ) && strtolower( $P['captcha'] ) != strtolower( $_SESSION["CaptchaCode"] )) || trim( $P['name'] ) == '' || trim( $P['author'] ) == '' || trim( $P['contacts'] ) == '') {
        $msg = '<div class="ErrorDiv">Ошибка! Поля не заполнены или заполнены неверно</div>';
    } else {
        if(mb_strpos( $P['text'], "[url=" ) === false) {
            $pics     = "";
            $name     = strip_tags( $P['name'] );
            $text     = strip_tags( $P['text'] );
            $author   = strip_tags( $P['author'] );
            $contacts = strip_tags( $P['contacts'] );
            $time     = $P['url'];
            $md5      = $P['site'];
            $cid      = $P['cat'] ? $P['cat'] : $start;
            if($P["attachment"]) {
                foreach($P["attachment"] as $pic) {
                    $pics .= $pics ? "|" . $pic : $pic;
                }
            }

            $msg = '<div class="SuccessDiv">Спасибо! Ваша заявка отправлена редактору сайта</div>';
            If($contacts != "123456" && $name != $author && $md5 === md5( $time . 'test' )) {
                $q = "INSERT INTO `$table` (`cat`, `name`, `text`, `author`, `contacts`, `pics`, `data`) VALUES ($cid, '$name', '$text', '$author', '$contacts', '$pics', '" . time() . "')";
                DB( $q );
                $P = array();

                $data = DB( "SELECT `email`, `name` FROM `" . $table2 . "` WHERE `id`=$cid" );
                @mysql_data_seek( $data["result"], 0 );
                $cat     = @mysql_fetch_array( $data["result"] );
                $subject = 'Поступила заявка в ' . $cat['name'];
                $body    = $name . '"<hr>';
                $body    .= $text . '"<hr>';
                $body    .= date( 'd.m.Y H:i', time() ) . ' Автор: ' . $author;
                MailSend( $cat['email'], $subject, $body, $VARS["sitemail"] );
            }
        }
    }
    SD();
}

$where = $start ? "WHERE `id`=$start" : '';
$data  = DB( "SELECT `id`,`name` FROM `" . $table2 . "` $where" );
@mysql_data_seek( $data["result"], 0 );
$cat = @mysql_fetch_array( $data["result"] );
if($data["total"] > 1) {
    $cats = array();
    for($i = 0; $i < $data["total"]; $i++) {
        @mysql_data_seek( $data["result"], $i );
        $ar                = @mysql_fetch_array( $data["result"] );
        $cats[ $ar['id'] ] = $ar['name'];
    }
}
$a=<<<HTML
<!-- соглашения и правила / ссылки в тексте -->
<h2 class="text-header text-header_blue">соглашения и правила</h2>
<p class="article__text">Пожалуйста, внимательно прочитайте настоящее соглашение и правила, прежде чем начать пользоваться сайтом. Вы обязаны соблюдать условия соглашения и правила, заходя на этот сайт.</p>
<br>
<a class="text-link" href="">Пользовательское соглашение</a>
<a class="text-link" href="">Политика защиты и обработки персональных данных</a>
HTML;

$text = '<link media="all" href="/modules/standart/multiupload/client/uploader2.css" type="text/css" rel="stylesheet"><script type="text/javascript" src="/modules/standart/multiupload/client/uploader.js"></script>';
$text .= $msg;
$text .= '<div class="add-comment">';
$text .= '<p class="add-comment__text">Если вы стали свидетелем происшествия или увидели в городе что-то интересное - пишите нам! Важно: новость должна быть авторской и не должна быть перепечатана с других сайтов.</p>
    <p class="add-comment__text">Если ваша новость будет опубликована на сайте, то вы получаете гонорар 350-500 рублей. Он выдается по вторникам с 15.00 до 17.00 в нашем офисе на бульваре Бердаха, 4. При себе нужно иметь паспорт, ИНН и страховое свидетельство.</p><hr>';
$text .= '<form action="/modules/SubmitForm.php?bp=' . $RealPage . '" enctype="multipart/form-data" method="post" onsubmit="return JsVerify();">';
$text .= '<input class="add-comment__input JsVerify2" type="text" name="name" value="' . $P['name'] . '" placeholder="Напишите название новости">
        <textarea class="add-comment__textarea" name="text" id="" cols="30" rows="10" placeholder="Введите текст новости">' . $P['text'] . '</textarea>
        <input class="add-comment__input JsVerify2" type="text" name="author" value="' . $GLOBAL["USER"]['nick'] . '" placeholder="Введите ваше имя">
        <span class="add-comment__link">или авторизуйтесь</span>' . GetUserAuthForm() . '
        <input class="add-comment__input JsVerify2" type="text" name="contacts" value="' . $P['contacts'] . '" placeholder="Введите ваши контакты">
        <p class="add-comment__text">Укажите ваши контактные данные: телефон или e-mail. Контакты не публикуются и нужны только для уточнения информации редактором сайта, мы не требуем отправлять СМС или других операций.</p>
        <div class="add-comment__input" id="uploader"></div>
        <span class="add-comment__link">Прикрепите фотографии (jpg, gif и png)</span>';
if( ! $start) {
    $text .= '<div><select name="cat">' . GetSelected( $cats, $cat['id'] ) . '</select></div>';
}
if($UserSetsSite[5] == '1') {
    $text .= '<div>Код с картинки<star>*</star></div><div><img src="/modules/standart/captcha/Captcha.php?' . time() . '" class="captchaImg" /><input name="captcha" type="text" class="JsVerify2"></div>';
}
$text .= '<button class="add-comment__submit SaveButton" type="submit" name="sendbutton" id="sendbutton">Отправить</button>';
$time = time();
$md5  = md5( $time . 'test' );
$text .= '<div style="display:none;"><input type="text" name="url" value="' . $time . '"><input type="text" name="site" value="' . $md5 . '"></div>';
$text .= '</form></div>';

$Page["Content"] = $text;
if($start) {
    $Page["Caption"] = $cat['name'];
    $Page['Caption'] = "<h2 class='add-comment__header'>" . $cat['name'] . '</h2>';
}

function GetSelected($ar, $id) {
    $text = "";
    foreach($ar as $key => $val) {
        if($key == $id) {
            $text .= "<option value='$key' selected style='color:#FFF; background:#036;'>$val</option>";
        } else {
            $text .= "<option value='$key'>$val</option>";
        }
    }

    return $text;
}
