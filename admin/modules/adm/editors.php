<?php
### НАСТРОЙКИ САЙТА
if($GLOBAL["sitekey"] == 1 && $GLOBAL["database"] == 1) {

    if(isset( $_POST["savebutton"] )) {
        adm_editors_save();
    }

    $action = isset( $_GET['action'] ) ? $_GET['action'] : '';

    if('new' == $action) {
        adm_editors_new();
    } elseif('edit' == $action) {
        adm_editors_edit();
    } else {
        adm_editors_list();
    }

}
$_SESSION["Msg"] = "";

function adm_editors_list() {
    global $pg, $alias, $AdminRight, $AdminText;
    $table  = "_editors";

    // ЭЛЕМЕНТЫ
    $AdminText .= '<h2>Редакция</h2>' . $_SESSION["Msg"];

    $onpage  = 50;
    $from    = ($pg - 1) * $onpage;

    $data = DB( "SELECT `$table`.* FROM `$table` ORDER BY `id` DESC LIMIT $from, $onpage" );

    $text        = "";
    $total_count = $data['total'];
    for($i = 0; $i < $data["total"]; $i++) {
        @mysql_data_seek( $data["result"], $i );
        $ar = @mysql_fetch_array( $data["result"] );
        if($ar["stat"] == 1) {
            $chk = "checked";
        } else {
            $chk = "";
        }
        $text  .= '<tr class="TRLine TRLine' . ($i % 2) . '" id="Line' . $ar["id"] . '">';
        $text  .= '<td class="CheckInput"><input type="checkbox" id="RS-' . $ar["id"] . '-' . $table . '" ' . $chk . '></td>';
        $text  .= "<td class='BigText'><span>" . $ar["name"] . "</span></td>";
        $text  .= '<td class="Act"><a href="?cat=adm_editors&action=edit&id=' . $ar["id"] . '" title="Править">' . AIco( '28' ) . '</a></td>';
        $text .= "</tr>";
    }

    $AdminText .= "<div class='RoundText' id='Tgg'><table>" . $text . "</table></div>";

    $AdminText .= Pager( $pg, $onpage, ceil( $total_count / $onpage ) );

    // ПРАВАЯ КОЛОНКА
    $AdminRight = '<div class="LinkG" style="margin-top:25px;"><a href="?cat=adm_editors&action=new">Добавить материал</a></div>';

    return $text;
}

function adm_editors_save() {
    global $ROOT, $id;
    $P     = $_POST;
    $F     = $_FILES;
    $error = 0;
    $is_new = ($_POST['savebutton'] === 'Сохранить новый');
    $P["charname"] = str_replace( array("\r\n", "\r", "\n", "'"), array("", "", "", "\'"), $P["charname"] );
    $P["charprof"] = str_replace( array("\r\n", "\r", "\n", "'"), array("", "", "", "\'"), $P["charprof"] );
    $P["text"] = str_replace( array("\r\n", "\r", "\n", "'"), array("", "", "", "\'"), $P["text"] );

    if(isset( $F['photo']['tmp_name'] ) && $F['photo']['tmp_name'] != '') {
        $max_image_size = 10;
        $valid_types    = array("jpg", "gif", "png", "jpeg");
        $userfile       = $_FILES['photo']['tmp_name'];
        $error          = 1;
        $ext            = strtolower( substr( $_FILES['photo']['name'], 1 + strrpos( $_FILES['photo']['name'], "." ) ) );

        $photo = '/userfiles/editors/' . date( "Y.m.d-H-i-s" ) . "-uid" . $P["uid"] . "." . $ext;

        if(filesize( $userfile ) > ($max_image_size * 1024 * 1024)) {
            $msg = "Файл больше $max_image_size мегабайт";
        } elseif( ! in_array( $ext, $valid_types )) {
            $msg = "Файл не является форматом " . implode( ", ", $valid_types );
        } else {
            if(@move_uploaded_file( $_FILES['photo']['tmp_name'], $ROOT . $photo )) {
                $msg   = "";
                $error = 0;
            } else {
                $msg = "Ошибка сервера. Свяжитесь с администратором!";
            }
        }
        if($msg != "") {
            $_SESSION["Msg"] = "<div class='ErrorDiv'>$msg</div>";
        }
    } else {
        $photo = "";
    }

    if($error == 0) {
        $fields = [
            'stat' => isset( $P['st'] ) ? '1' : '0',
            'photo' => $photo,
            'name' => $P['charname'],
            'prof' => $P['charprof'],
            'uid' => $P['uid'],
            'text' => $P['text'],
        ];
        if( $is_new ) {
            $keys = implode('`, `', array_keys($fields));
            $values = implode( '", "', $fields);
            $q = 'INSERT INTO `_editors` (`' . $keys . '`) VALUES ("' . $values . '");';
            DB( $q );
            $last = DBL();
            if(empty(mysql_error())) {
                $_SESSION["Msg"] = "<div class='SuccessDiv'>Новый материал успешно добавлен!</div>";
                @header( 'Location: /admin/?cat=adm_editors&action=edit&id=' . $last );
                exit();
            } else {
                $_SESSION['Msg'] = "<div class='ErrorDiv'>При сохранении произошла ошибка!0</div>";
            }
        } else {
            if(empty( $photo )) {
                unset( $fields['photo'] );
            }
            $fields_sql = array_map(function($value, $key){
                return '`' . $key . '` = "' . $value . '"';
            }, $fields, array_keys($fields));
            $q = 'UPDATE `_editors` SET ' . implode(', ', $fields_sql) . ' WHERE `id` = ' . $id;
            DB($q);
            if( empty( mysql_error() ) ) {
                $_SESSION['Msg'] = "<div class='SuccessDiv'>Изменения сохранены!</div>";
                @header( 'Location: ' . $_SERVER['REQUEST_URI'] );
                exit();
            } else {
                $_SESSION['Msg'] = "<div class='ErrorDiv'>При сохранении произошла ошибка!1</div>";
            }
        }
    }
}

function adm_editors_new() {
    global $AdminText, $AdminRight;
    $usr  = array();
    $data = DB( "SELECT `id`, `nick` FROM `_users` WHERE (`role`>0) ORDER BY `nick` ASC" );
    for($i = 0; $i < $data["total"]; $i++) {
        @mysql_data_seek( $data["result"], $i );
        $ar = @mysql_fetch_array( $data["result"] );
        $usr[ $ar["id"] ] = $ar["nick"];
    }
    $AdminText .= '<h2>Редакция: Новый</h2>' . $_SESSION["Msg"] . '
<form enctype="multipart/form-data" method="post" onsubmit="return JsVerify();" action="'.$_SERVER["REQUEST_URI"].'">
    <div class="RoundText">
        <table>
            <tr class="TRLine0">
                <td class="Vartext">Фотография<star>*</star></td>
                <td><input type="file" name="photo" style="border:2px solid #FFF; border-radius:5px;"></td>
            </tr>
            <tr class="TRLine1">
                <td class="VarText">Имя<star>*</star></td>
                <td class="LongInput"><input name="charname" id="charname" type="text"></td>
            </tr>
            <tr class="TRLine0">
                <td class="VarText">Должность<star>*</star></td>
                <td class="LongInput"><input name="charprof" id="charprof" type="text"></td>
            </tr>
            <tr class="TRLine1">
                <td class="VarText">На сайте</td>
                <td class="LongInput"><div class="sdiv"><select name="uid">'.GetSelected($usr,0).'</select></td>
            </tr>
            <tr class="TRLine0">
                <td class="Vartext">Доп. текст</td>
                <td class="LongInput"><textarea name="text" style="height:150px;"></textarea></td>
            </tr>
        </table>
    </div>
    <div class="CenterText">
        <input type="submit" name="savebutton" id="savebutton" class="SaveButton" value="Сохранить новый">
    </div>';
    $AdminRight = '
    <div class="RoundText">
        <table>
            <tr class="TRLine">
                <td class="CheckInput"><input type="checkbox" name="st" value="1"></td>
                <td><b>Включить показы</b></td>
            </tr>
        </table>
    </div>
</form>';
}

function adm_editors_edit() {
    global $id, $AdminRight, $AdminText;
    $data = DB( "SELECT * FROM `_editors` WHERE (`id`='" . $id . "') LIMIT 1" );
    if($data["total"] < 1) {
        $AdminText       = ATextReplace( 'Item-Module-Error', $id, "_pages" );
        $GLOBAL["error"] = 1;
    } else {
        @mysql_data_seek( $data["result"], 0 );
        $char = @mysql_fetch_array( $data["result"] );

        $usr  = array();
        $data = DB( "SELECT `id`, `nick` FROM `_users` WHERE (`role`>0) ORDER BY `nick` ASC" );
        for($i = 0; $i < $data["total"]; $i++) {
            @mysql_data_seek( $data["result"], $i );
            $ar = @mysql_fetch_array( $data["result"] );
            $usr[ $ar["id"] ] = $ar["nick"];
        }

        if( ! empty( $char['photo'] )) {
            $photo = '<img src="' . $char['photo'] . '" style="max-height:240px;">';
        } else {
            $photo = '';
        }

        $AdminText = '<h2>Редакция: Изменение</h2>' . $_SESSION["Msg"] . '
<form enctype="multipart/form-data" method="post" onsubmit="return JsVerify();" action="'.$_SERVER["REQUEST_URI"].'">
    <div class="RoundText">
        <table>
            <tr class="TRLine0">
                <td class="Vartext">Фотография<star>*</star></td>
                <td>
                    ' . $photo . '
                    <input type="file" name="photo" style="border:2px solid #FFF; border-radius:5px;">
                </td>
            </tr>
            <tr class="TRLine1">
                <td class="VarText">Имя<star>*</star></td>
                <td class="LongInput"><input name="charname" id="charname" type="text" value="' . $char['name'] . '"></td>
            </tr>
            <tr class="TRLine0">
                <td class="VarText">Должность<star>*</star></td>
                <td class="LongInput"><input name="charprof" id="charprof" type="text" value="' . $char['prof'] . '"></td>
            </tr>
            <tr class="TRLine1">
                <td class="VarText">На сайте</td>
                <td class="LongInput"><div class="sdiv"><select name="uid">'.GetSelected($usr,$char['uid']).'</select></td>
            </tr>
            <tr class="TRLine0">
                <td class="Vartext">Доп. текст</td>
                <td class="LongInput"><textarea name="text" style="height:150px;">' . $char['text'] . '</textarea></td>
            </tr>
        </table>
    </div>
    <div class="CenterText">
        <input type="submit" name="savebutton" id="savebutton" class="SaveButton" value="Сохранить">
    </div>';
        $AdminRight = '
    <div class="RoundText">
        <table>
            <tr class="TRLine">
                <td class="CheckInput"><input type="checkbox" name="st" value="1" ' .
                      ($char['stat'] == '1' ? 'checked="checked"' : '') . '></td>
                <td><b>Включить показы</b></td>
            </tr>
        </table>
    </div>
</form>';
    }
}