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
        $bst = "";

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
            $ar     = explode( ".", $P["ddata1-lstart"] );
            $live_start = mktime( $P["ddata2-lstart"], $P["ddata3-lstart"], $P["ddata4-lstart"], $ar[1], $ar[0], $ar[2] );
            $ar     = explode( ".", $P["ddata1-lend"] );
            $live_end = mktime( $P["ddata2-lend"], $P["ddata3-lend"], $P["ddata4-lend"], $ar[1], $ar[0], $ar[2] );

            if( empty($P['stream_link']) && ! empty($P['stream_iframe']) ) {
                preg_match('#<iframe.*?src="([^"]*)"#', $P['stream_iframe'], $m);
                if( ! empty($m[1]) ) {
                    $P['stream_link'] = $m[1];
                }
            }

            $q = "UPDATE `" . $alias . "_lenta` SET 
		`uid`='" . (int) $P['authid'] . "',
		`stream_link`='" . $P['stream_link'] . "',
		`stream_iframe`='" . $P['stream_iframe'] . "',
		`bid`='" . (int) $P['bid'] . "',
		`name`='" . str_replace( "'", '&#039;', $P["dname"] ) . "',
		`cat`='" . $P["site"] . "',
		`kw`='" . str_replace( "'", '&#039;', $P["dkw"] ) . "', 
		`ds`='" . str_replace( "'", '&#039;', $P["dds"] ) . "', 
		`comments`='" . $P["comms"] . "', 
		`data`='" . $sdata1 . "',
		`astat`='" . $P["autoon"] . "', 
		`adata`='" . $sdata2 . "', 
		`promo`='" . $P["comrs"] . "', 
		`spromo`='" . $P["scomrs"] . "',
		`onind`='" . $P["ontv"] . "', 
		`spec`='" . $P["spec"] . "', 
		`yarss`='" . $P["yarss"] . "', 
		`mailrss`='" . $P["mailrss"] . "', 
		`tavto`='" . $P["tavto"] . "', 
		`tags`='" . $dtags . "',
		`redak`='" . $P["redak"] . "', 
		`gis`='" . $P["gis"] . "', 
		`mailtizer`='" . $P["mailtizer"] . "',
		`showauthor`='" . $P['showauthor'] . "',
		`start`='" . $live_start . "',
		`end`='" . $live_end . "'
		WHERE (id='" . (int) $id . "')";
            DB( "INSERT INTO `_lentalog` (`link`, `id`, `uid`, `data`, `ip`, `text`) VALUES ('" . $alias . "', '" . $id . "', '" . $_SESSION['userid'] . "', '" . time() . "', '" . $_SERVER['REMOTE_ADDR'] . "', 'Сохранение (name): " . str_replace( "'",
                                                                                                                                                                                                                                                       '&#039;',
                                                                                                                                                                                                                                                       $P["dname"] ) . "')" );
            DB( $q );
            $_SESSION["Msg"] = "<div class='SuccessDiv'>Запись успешно сохранена!</div>";
            @header( "location: " . $_SERVER["REQUEST_URI"] );
            exit();
        }

        // ВЫВОД ПОЛЕЙ И ФОРМ
        $data = DB( "SELECT * FROM `" . $alias . "_lenta` WHERE (`id`='" . (int) $id . "') LIMIT 1" );
        if($data["total"] != 1) {
            $AdminText       = ATextReplace( 'ItemError', $raz["shortname"] . " (" . $alias . ")", $id );
            $GLOBAL["error"] = 1;
        } else {
            ### Заполнение данных
            @mysql_data_seek( $data["result"], 0 );
            $node = @mysql_fetch_array( $data["result"] );
            if($node["stat"] == 1) {
                $chk = "checked";
            }
            if($node["astat"] == 1) {
                $chk1 = "checked";
            }
            if($node["onind"] == 1) {
                $chk3 = "checked";
            }

            if($node["comments"] == 0) {
                $c1 = "selected";
            } elseif($node["comments"] == 1) {
                $c2 = "selected";
            } else {
                $c3 = "selected";
            }
            $utags = explode( ",", trim( $node["tags"], "," ) );
            $site  = array();
            $data  = DB( "SELECT `id`, `name` FROM `" . $alias . "_cats` ORDER BY `rate` DESC" );
            for($i = 0; $i < $data["total"]; $i++): @mysql_data_seek( $data["result"], $i );
                $ar                = @mysql_fetch_array( $data["result"] );
                $site[ $ar["id"] ] = $ar["name"]; endfor;
            $usr  = array();
            $data = DB( "SELECT `id`, `nick` FROM `_users` WHERE (`role`>0) ORDER BY `nick` ASC" );
            for($i = 0; $i < $data["total"]; $i++) {
                @mysql_data_seek( $data["result"], $i );
                $ar = @mysql_fetch_array( $data["result"] );
                $usr[ $ar["id"] ] = $ar["nick"];
            }

            /* заявка из баннерной системы */
            if($node["bid"] != 0) {
                $bb = DB( "SELECT * FROM `_banners_orders` WHERE (`zid`='" . (int) $node["bid"] . "') LIMIT 1" );
                if($bb["total"] != 1) {
                    $bst = "<div style='font-size:10px; color:red; margin-top:3px;'>Не найдено выходов статей в баннерной системе с ZID=" . (int) $node["bid"] . "</div>";
                } else {
                    @mysql_data_seek( $bb["result"], 0 );
                    $br  = @mysql_fetch_array( $bb["result"] );
                    $bst = "<div style='font-size:11px; color:#777; margin-top:4px;'>ZID = <a href='?cat=banners_editorder&id=" . (int) $node["bid"] . "' target='_blank'>" . (int) $node["bid"] . "</a>; Выходы: " . str_replace( ",", " ... ", $br["dataart"] ) . "</div>";
                }
            }

            $AdminText = '<h2>Редактирование: &laquo' . $node["name"] . '&raquo;</h2>' . $_SESSION["Msg"] . "<form action='" . $_SERVER["REQUEST_URI"] . "' enctype='multipart/form-data' method='post'>";
            $AdminText .= "<div class='RoundText'><table>" . '<tr class="TRLine0"><td style="width:22%;"></td><td style="width:78%;"></td></tr>
	
	<tr class="TRLine0"><td class="VarText">Заголовок материала<star>*</star></td><td class="LongInput">	<input name="dname" id="dname" type="text" value=\'' . $node["name"] . '\' maxlength="80" style="width:500px; float:left;">
	<input id="dcount" type="text" title="Осталось символов" style="width:40px; float:right; text-align:center;" value="" readonly>	</td><tr>

	<tr class="TRLine1"><td class="VarText">Категория</td><td class="LongInput"><div class="sdiv"><select name="site">' . GetSelected( $site, $node["cat"] ) . '</select></div></td><tr>
	<tr class="TRLine0"><td class="VarText">Ссылка</td><td class="LongInput"><input name="stream_link" id="stream_link" type="text" value=\'' . $node["stream_link"] . '\' style="width:500px; float:left;"></td><tr>
	<tr class="TRLine0"><td class="VarText">Код для встраивания</td><td class="LongInput"><input name="stream_iframe" id="stream_iframe" type="text" value=\'' . $node["stream_iframe"] . '\' style="width:500px; float:left;"></td><tr>
	<!--<tr class="TRLine0"><td class="VarName"></td><td><a href="javascript:void(0);" onclick="ShowSets();" id="ShowSets">Показать дополнительные настройки</a></td><tr>-->
	<tr class="TRLine1 ShowSets"><td class="VarName">Автор материала</td><td class="LongInput"><div class="sdiv"><select name="authid">' . GetSelected( $usr, $node["uid"] ) . '</select></td><tr>
	<tr class="TRLine0 ShowSets"><td class="VarName">Комментарии</td><td class="LongInput">
	<div class="sdiv"><select name="comms"><option value="0" ' . $c1 . '>Чтение и добавление</option><option value="1" ' . $c2 . '>Только чтение</option><option value="2" ' . $c3 . '>Запретить комментарии</option></select></div></td><tr>	
	<tr class="TRLine1 -ShowSets"><td class="VarName">Дата создания</td><td class="DateInput">' . GetDataSet( $node["data"], "" ) . '</td><tr>
	<tr class="TRLine0 -ShowSets"><td class="VarName">Автопубликация</td><td class="DateInput">' . GetDataSet( $node["adata"], 1 ) . ' включить таймер: <input type="checkbox" name="autoon" id="autoon" value="1" ' . $chk1 . '></td><tr>
	<tr class="TRLine1 -ShowSets"><td class="VarName">Начало трансляции</td><td class="DateInput">' . GetDataSet($node["start"], '-lstart') . '</td><tr>
	<tr class="TRLine0 -ShowSets"><td class="VarName">Конец трансляции (для TV)</td><td class="DateInput">' . GetDataSet($node["end"], '-lend') . '</td><tr>
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
                $ar = @mysql_fetch_array( $data["result"] );
                if(in_array( $ar["id"], $utags )) {
                    $chkt = "checked";
                } else {
                    $chkt = "";
                }
                $tags .= "<td width='1%'><input name='tags[" . $ar["id"] . "]' id='tags[" . $ar["id"] . "]' type='checkbox' class='tags' value='1' $chkt></td>
	<td width='20%'>" . $ar["name"] . "</td>";
                if(($i + 1) % 3 == 0) {
                    $tags .= "</tr><tr class='TRLine" . ($line % 2) . "'>";
                    $line++;
                    if($line == 3) {
                        $line = 1;
                    }
                } endfor;
            $AdminText .= "<h2>Тэги публикации</h2><div class='InfoH2'>Выберите 2-4 темы, самые подходящие по смыслу публикации:</div><div class='RoundText TagsList' style='max-height:500px;'><table><tr class='TRLine0'>" . $tags . "</tr></table></div>";

            ### Сохранение
            $AdminText .= "<div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить данные'></div>";

            // ПРАВАЯ КОЛОНКА
            $AdminRight = "<br><br>
	<div class='RoundText'><table><tr class='TRLine'><td class='CheckInput'><input type='checkbox' id='RS-" . $id . "-" . $alias . "_lenta' " . $chk . "></td><td><b>Опубликовано</b></td></tr>
	<tr><td colspan='2'><hr><div id='dataNow' align='center'><a href='javascript:void(0);' onclick='stanUpData();'>Поставить текущие дату и время</a></div></td></tr></table></div>
	<div class='SecondMenu2'><a href='?cat=" . $alias . "_edit&id=" . $id . "'>Основные настройки</a></div>
	<div class='SecondMenu'><a href='?cat=" . $alias . "_photo&id=" . $id . "'>Основная фотография</a></div>
	<br><div class='CenterText'><input type='submit' name='savebutton' id='savebutton' class='SaveButton' value='Сохранить данные'></div></form>";
            if($_SESSION['userrole'] > 2) {
                $AdminRight .= "<div class='SecondMenu'><a href='?cat=" . $alias . "_log&id=" . $id . "'>Лог редактирования записи</a></div>";
            }
        }
    }
}
$_SESSION["Msg"] = "";
