<?php
### НАСТРОЙКИ САЙТА
if($GLOBAL["sitekey"] == 1 && $GLOBAL["database"] == 1) {

    // РАЗДЕЛ
    $data = DB( "SELECT `id`,`shortname`,`link`, `sets` FROM `_pages` WHERE (`link`='" . $alias . "') LIMIT 1" );
    if($data["total"] != 1) {
        $AdminText       = ATextReplace( 'Item-Module-Error', $id, "_pages" );
        $GLOBAL["error"] = 1;
    } else {
        @mysql_data_seek( $data["result"], 0 );
        $raz = @mysql_fetch_array( $data["result"] );

        // СОХРАНЕНИЕ ПОЛЕЙ И ФОРМ
        $P = $_POST;
        if(isset( $P["savebutton"] )) {
            $dtags = ",";
            foreach($P["tags"] as $k => $v) {
                $dtags .= $k . ",";
            }
            $ar     = explode( ".", $P["ddata1"] );
            $sdata1 = mktime( $P["ddata2"], $P["ddata3"], $P["ddata4"], $ar[1], $ar[0], $ar[2] );
            $ar     = explode( ".", $P["ddata11"] );
            $sdata2 = mktime( $P["ddata21"], $P["ddata31"], $P["ddata41"], $ar[1], $ar[0], $ar[2] );
            $ar     = explode( ".", $P["ddata12"] );
            $live_start = mktime( $P["ddata22"], $P["ddata32"], $P["ddata42"], $ar[1], $ar[0], $ar[2] );
            $ar     = explode( ".", $P["ddata13"] );
            $live_end = mktime( $P["ddata23"], $P["ddata33"], $P["ddata43"], $ar[1], $ar[0], $ar[2] );

            if( empty($P['stream_link']) && ! empty($P['stream_iframe']) ) {
                preg_match('#<iframe.*?src="([^"]*)"#', $P['stream_iframe'], $m);
                if( ! empty($m[1]) ) {
                    $P['stream_link'] = $m[1];
                }
            }

            $q = "INSERT INTO `" . $alias . "_lenta` (`uid`, `bid`, `cat`, `name`, `kw`, `ds`, `cens`, `realinfo`, `comments`, `data`, `astat`, `adata`, `promo`, `spromo`, `onind`, `spec`, `yarss`, `mailrss`, `tavto`, `tags`, `redak`,`gis`,`mailtizer`,`showauthor`, `start`, `end`, `stream_link`, `stream_iframe`)
		VALUES ('" . (int) $P['authid'] . "', '" . (int) $P['bid'] . "', '" . (int) $P["site"] . "', '" . str_replace( "'", '&#039;', $P["dname"] ) . "', '" . str_replace( "'", '&#039;', $P["dkw"] ) . "', '" . str_replace( "'", '&#039;', $P["dds"] ) . "', '" . $P["cens"] . "', '" . str_replace( "'", '&#039;', $P["realinfo"] ) . "', '" . $P["comms"] . "', '" . $sdata1 . "',
		'" . $P["autoon"] . "', '" . $sdata2 . "', '" . $P["comrs"] . "', '" . $P["scomrs"] . "','" . $P["ontv"] . "', '" . $P["spec"] . "', '" . $P["yarss"] . "', '" . $P["mailrss"] . "', '" . $P["tavto"] . "', '" . $dtags . "', '" . $P["redak"] . "', '" . $P["gis"] . "', '" . $P["mailtizer"] . "', '" . $P['showauthor'] . "', '" . $live_start . "', '" . $live_end . "', '{$P['stream_link']}', '{$P['stream_iframe']}')";

            $_SESSION["Msg"] = "<div class='SuccessDiv'>Новая публикация успешно создана!</div>";
            $data            = DB( $q );
            $last            = DBL();
            DB( "UPDATE `" . $alias . "_lenta` SET `rate`='" . $last . "' WHERE  (id='" . $last . "')" );
            DB( "INSERT INTO `_lentalog` (`link`, `id`, `uid`, `data`, `ip`, `text`) VALUES ('" . $alias . "', '" . $last . "', '" . $_SESSION['userid'] . "', '" . time() . "', '" . $_SERVER['REMOTE_ADDR'] . "', 'Создание #" . $last . ": " . str_replace( "'", '&#039;', $P["dname"] ) . "')" );
            @header( "location: ?cat=" . $raz["link"] . "_edit&id=" . $last );
            exit();
        }
        // ВЫВОД ПОЛЕЙ И ФОРМ

        $site = array();
        $data = DB( "SELECT `id`, `name` FROM `" . $alias . "_cats` ORDER BY `rate` DESC" );
        for($i = 0; $i < $data["total"]; $i++): @mysql_data_seek( $data["result"], $i );
            $ar                = @mysql_fetch_array( $data["result"] );
            $site[ $ar["id"] ] = $ar["name"]; endfor;
        $usr  = array();
        $data = DB( "SELECT `id`, `nick` FROM `_users` WHERE (`role`>0) ORDER BY `nick` ASC" );
        for($i = 0; $i < $data["total"]; $i++): @mysql_data_seek( $data["result"], $i );
            $ar               = @mysql_fetch_array( $data["result"] );
            $usr[ $ar["id"] ] = $ar["nick"]; endfor;

        $AdminText = '<h2>Добавление материала &laquo;' . $raz["shortname"] . '&raquo;</h2>' . $_SESSION["Msg"];
        $AdminText .= "<form action='" . $_SERVER["REQUEST_URI"] . "' enctype='multipart/form-data' method='post'>";

        if($node["stat"] == 1) {
            $chk = "checked";
        }
        if($node["astat"] == 1) {
            $chk1 = "checked";
        }
        if($node["onind"] == 1) {
            $chk3 = "checked";
        }

        ### Основные данные
        $AdminText .= "<div class='RoundText'><table>" . '<tr class="TRLine0"><td style="width:22%;"></td><td style="width:78%;"></td></tr>
	
	<tr class="TRLine0"><td class="VarText">Заголовок материала<star>*</star></td><td class="LongInput">	<input name="dname" id="dname" type="text" class="JsVerify2" maxlength="80" style="width:500px; float:left;">
	<input id="dcount" type="text" title="Осталось символов" style="width:40px; float:right; text-align:center;" value="" readonly>	</td><tr>
	
	<tr class="TRLine1"><td class="VarText">Категория</td><td class="LongInput"><div class="sdiv"><select name="site">' . GetSelected( $site, 0 ) . '</select></div></td><tr>
	<tr class="TRLine0"><td class="VarText">Ссылка</td><td class="LongInput"><input name="stream_link" id="stream_link" type="text" value="" style="width:500px; float:left;"></td><tr>
	<tr class="TRLine0"><td class="VarText">Код для встраивания</td><td class="LongInput"><input name="stream_iframe" id="stream_iframe" type="text" value="" style="width:500px; float:left;"></td><tr>
	<!--<tr class="TRLine0"><td class="VarName"></td><td><a href="javascript:void(0);" onclick="ShowSets();" id="ShowSets">Показать дополнительные настройки</a></td><tr>-->
	<tr class="TRLine0 ShowSets"><td class="VarName">Автор материала</td><td class="LongInput"><div class="sdiv"><select name="authid">' . GetSelected( $usr, $_SESSION['userid'] ) . '</select></td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Комментарии</td><td class="LongInput"><div class="sdiv"><select name="comms"><option value="0">Чтение и добавление</option><option value="1">Только чтение</option><option value="2">Запретить комментарии</option></select></div></td><tr>	
	<tr class="TRLine1 -ShowSets"><td class="VarName">Дата создания</td><td class="DateInput">' . GetDataSet() . '</td><tr>
	<tr class="TRLine0 -ShowSets"><td class="VarName">Автопубликация</td><td class="DateInput">' . GetDataSet( 0, 1 ) . ' включить таймер: <input type="checkbox" name="autoon" id="autoon" value="1"></td><tr>
	<tr class="TRLine1 -ShowSets"><td class="VarName">Начало трансляции</td><td class="DateInput">' . GetDataSet(0, '-lstart') . '</td><tr>
	<tr class="TRLine1 -ShowSets"><td class="VarName">Конец трансляции (для TV)</td><td class="DateInput">' . GetDataSet(0, '-lend') . '</td><tr>
	' . "</table></div>";

        ### Экспорт материала
        $AdminText .= "<h2>Отображение и экспорт материала</h2><div class='RoundText TagsList'><table>
	<tr class='TRLine0'>
		<td width='1%'><input name='ontv' id='ontv' type='checkbox' value='1' $chk3></td><td width='20%'><b>«Телевизор»</b> ProGorodChelny</td>
		<td width='1%'></td><td width='1%'></td>
		<td width='1%'></td><td width='1%'></td>
	</tr>
	</table></div>";

        ### Список тэгов публикцаций
        $tags = "";
        $data = DB( "SELECT `id`, `name` FROM `_tags` ORDER BY `name` ASC" );
        $line = 1;
        for($i = 0; $i < $data["total"]; $i++): @mysql_data_seek( $data["result"], $i );
            $ar   = @mysql_fetch_array( $data["result"] );
            $tags .= "<td width='1%'><input name='tags[" . $ar["id"] . "]' id='tags[" . $ar["id"] . "]' type='checkbox' class='tags' value='1'></td><td width='20%'>" . $ar["name"] . "</td>";
            if(($i + 1) % 3 == 0) {
                $tags .= "</tr><tr class='TRLine" . ($line % 2) . "'>";
                $line++;
                if($line == 3) {
                    $line = 1;
                }
            } endfor;
        $AdminText .= "<h2>Тэги публикации</h2><div class='InfoH2'>Выберите 2-4 темы, самые подходящие по смыслу публикации:</div><div class='RoundText TagsList' style='max-height:500px;'><table><tr class='TRLine0'>" . $tags . "</tr></table></div>";
        $AdminText .= "<div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Создать запись'></div>";

        // ПРАВАЯ КОЛОНКА
        $AdminRight = "<br><br><div class='SecondMenu2'><a href='" . $_SERVER["REQUEST_URI"] . "'>Основные настройки</a></div><br>После сохранения основных настроек, вы сможете перейти к наполнению публикации контентом, загрузить фотографии и править остальные параметры записи.
	<div class='C20'></div><div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Создать запись'></div></form>";
    }
}
$_SESSION["Msg"] = "";
