<?
### СТРОЧНЫЕ ОБЪЯВЛЕНИЯ ######################################################################################################################
$table1 = $link . "_objects";
$table2 = $link . "_razdels";
$table3 = $link . "_users";
$table4 = $link . "_pays";
### ОТДЕЛЬНЫЙ suid - UID для строчек ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### #
if (!$_SESSION["str_userid"]) {
    $_SESSION["str_userid"] = 0;
}
$GLOBAL["suid"] = $_SESSION["str_userid"];

$data = DB("SELECT `sets` FROM `_pages` WHERE (`module`='strochki' && `stat`='1') LIMIT 1");
if ($data["total"] != 1) {
    $text = @file_get_contents($ROOT . "/template/404.html");
    $cap = "Страница не найдена - 404";
    $Page404 = 1;
} else {
    @mysql_data_seek($data["result"], 0);
    $ar = @mysql_fetch_array($data["result"]);
    $SETS = explode("|", $ar["sets"]);
    if ($start == "") {
        list($text, $cap) = GetIndex();
    }
    if ($start == "result") {
        list($text, $cap) = GetResult();
    }
    if ($start == "payonly") {
        list($text, $cap) = GetPayOnly();
    }
    if ($start == "payclear") {
        list($text, $cap) = GetPayClear();
    }
    if ($start == "cabinet") {
        list($text, $cap) = GetCabinet();
    }
    if ($start == "addnew") {
        list($text, $cap) = GetNewAdd();
    }
    if ($start == "mylist") {
        list($text, $cap) = GetMyList();
    }
    if ($start == "myobjs") {
        list($text, $cap) = GetMyObjs();
    }
    if ($start == "setts") {
        list($text, $cap) = GetSetts();
    }
    if ($start == "exit") {
        $_SESSION["str_userid"] = 0;
        @header("location: /" . $dir[0]);
        exit();
    }
}
$Page["Content"] = $text;
$Page["Caption"] = $cap;

$Page["Content"] .= "<script type='text/javascript'>(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=I9syk7CPlr4Q3qGUEAwVUCy/Npck3PXz1k2H4ad8WXGf6vGdqSn4jwxec1Z80bSXZ7lOt7O2DetSrQo6Hz/PElI1eWDLjCqFjot2pI4PvMvBrlLGmNPEnPQjnYmC1N6VyYNj0RCkQvsCSpG/nJgI5dilCQaYfn8cmDKbBStlK3c-';</script>";

#############################################################################################################################################

function GetIndex()
{
    global $SETS, $VARS, $GLOBAL, $dir, $Page, $RealPage, $node, $table1, $table2, $table3, $table4, $C15, $C10, $C, $C5;
    $text = "<div class='WhiteBlock'>" . $node["text"] . "</div>" . $C15;
    $cap = $node["name"];
    if ((int)$_SESSION["str_userid"] == 0) {
        $P = $_SESSION['Data'];
        if (isset($_SESSION['Data']["loginbutton"])) {
            $data = DB("SELECT `id` FROM `" . $table3 . "` WHERE (`login`='" . str_replace(array("select", "in", "or", "delete", "drop", "insert", "update", "<br>", "h1", "h2", "h3", "from", "union", "*"), "", strip_tags($P["login1"])) . "' && `pass`='" . str_replace(array("select", "in", "or", "delete", "drop", "insert", "update", "<br>", "h1", "h2", "h3", "from", "union", "*"), "", strip_tags($P["pass1"])) . "' && `stat`='1') LIMIT 1");
            if ($data["total"] == 1) {
                @mysql_data_seek($data["result"], 0);
                $ar = @mysql_fetch_array($data["result"]);
                $_SESSION["str_userid"] = $ar["id"];
                SD();
                @header("location: /" . $dir[0] . "/cabinet/");
                exit();
            } else {
                $msg1 = '<div class="ErrorDiv">Логин или пароль указаны неверно.</div>';
            }
        }
        if (isset($_SESSION['Data']["registerbutton"])) {
            $er = 0;
            if (!Email_check($P["login2"])) {
                $er = 1;
                $_SESSION["Msg2"] = '<div class="ErrorDiv">Новый логин указан неверно. Введите Email!</div>';
            } else {
                $data = DB("SELECT `id` FROM `" . $table3 . "` WHERE (`login`='" . str_replace(array("select", "in", "or", "delete", "drop", "insert", "update", "<br>", "h1", "h2", "h3", "from", "union", "*"), "", strip_tags($P["login2"])) . "') LIMIT 1");
                if ($data["total"] != 0) {
                    $er = 1;
                    $_SESSION["Msg2"] = "<div class='ErrorDiv'>Данный логин уже занят! <a href='/" . $dir[0] . "/forget/'><u>Забыли пароль</u>?</a></div>";
                }
                if ($er == 0) {
                    $np = rand(11111111, 999999999);
                    $data = DB("INSERT INTO `" . $table3 . "` (`login`,`pass`,`name`) VALUES ('" . str_replace(array("select", "in", "or", "delete", "drop", "insert", "update", "<br>", "h1", "h2", "h3", "from", "union", "*"), "", strip_tags($P["login2"])) . "','" . str_replace(array("select", "in", "or", "delete", "drop", "insert", "update", "<br>", "h1", "h2", "h3", "from", "union", "*"), "", strip_tags($P["pass2"])) . "','" . str_replace(array("select", "in", "or", "delete", "drop", "insert", "update", "<br>", "h1", "h2", "h3", "from", "union", "*"), "", strip_tags($P["name2"])) . "')");
                    $_SESSION["Msg2"] = '<div class="SuccessDiv">Успешно! Пароль отправлен вам на Email</div>';
                    $body = "Здравствуйте, " . $P["name2"] . "<br><br>Вы зарегистрировались на сайте <a href='http://" . $VARS["mdomain"] . "'>" .
                        $VARS["mdomain"] . "</a> в личном кабинете.Теперь вы сможете  размещать  свои объявления в газете \"Pro Город\" Казань и оплачивать их.Для того что бы подать объявление в газету \"Pro Город\" вам нужно перейти в личный кабинет и ввести 
ваш логин и пароль .Если остались какие - либо вопросы вы сможете позвонить  по телефону (843) 236-78-45.Наши специалисты обязательно помогут вам <br><br>Ваш логин: " . $P["login2"] . "<br>Пароль: " . $P["pass2"] .
                        "<br><br><a href='http://" . $VARS["mdomain"] . "/" . $dir[0] . "/'>Перейти в личный кабинет</a>";

                    MailSend($P["login2"], "Регистрация ", $body, $VARS["sitemail"]);
                    SD();
                    $_SESSION["str_userid"] = DBL();
                    @header("location: /" . $dir[0] . "/cabinet/");
                    exit();
                }
            }
        }
        $text .= "<div class='WhiteBlock'><div style='float:left; width:45%; margin-right:5%;'>";
        $text .= "<h3>Войти в личный кабинет</h3>" . $C10 . $msg1 . "Если вы уже регистрировались в кабинете, войдите с помощью логина (ваш Email) и пароля, который мы вам отправляли при регистрации <a href='/" . $dir[0] . "/forget/'><u>Забыли пароль</u>?</a>" . $C10;
        $text .= '<div class="RoundText" id="Tgg"><form action="/modules/SubmitForm.php?bp=' . $RealPage . '" enctype="multipart/form-data" method="post"><table>';
        $text .= '<tr class="TRLine0"><td class="VarText">Логин (Email)</td><td class="LongInput"><input name="login1" type="text" placeholder="Например: test@mail.ru" style="width:100%;"></td></tr>';
        $text .= '<tr class="TRLine0"><td class="VarText">Ваш пароль</td><td class="LongInput"><input name="pass1" type="password" style="width:100%;"></td></tr>';
        $text .= '<tr class="TRLine0"><td class="VarText"></td><td ><input type="submit" name="loginbutton" class="SaveButton" value="Войти в кабинет"></td></tr>';
        $text .= '</table>' . $C10 . '</form></div>';
        $text .= "</div><div style='float:left; width:45%;'>";
        $text .= "<h3>Новая регистрация</h3>" . $C10 . $_SESSION["Msg2"] . "Если у вас нет логина и пароля - зарегистрируйтесь, заполнив следующие поля" . $C10;
        $text .= '<div class="RoundText" id="Tgg"><form action="/modules/SubmitForm.php?bp=' . $RealPage . '" enctype="multipart/form-data" method="post"><table>';
        $text .= '<tr class="TRLine0"><td class="VarText">Логин (Email)</td><td class="LongInput"><input name="login2" type="text" placeholder="Например: test@mail.ru" style="width:100%;"></td></tr>';
        $text .= '<tr class="TRLine0"><td class="VarText">Ваше Ф.И.О.</td><td class="LongInput"><input name="name2" type="text" style="width:100%;"></td></tr>';
        $text .= '<tr class="TRLine0"><td class="VarText">Придумайте пароль</td><td class="LongInput"><input name="pass2" type="password" style="width:100%;"></td></tr>';
        $text .= '<tr class="TRLine0"><td class="VarText"></td><td > Придумайте пароль, который вы сможете запомнить, он потребуется 
для того чтобы вы смогли подавать объявления в будущем</td></tr>';
        $text .= '<tr class="TRLine0"><td class="VarText"></td><td ><input type="submit" name="registerbutton" class="SaveButton" value="Регистрация"></td></tr>';
        $text .= '</table>' . $C10 . '</form></div>';
        $text .= "</div>" . $C5 . "</div>" . $C;
    } else {

        @header("location: /" . $dir[0] . "/cabinet/");
        exit();
    }


    $text .= $C15 . "<table class='TableStrochki' cellspacing='0' cellpadding='0'><tr><td>" . $C10 . "<a href='/" . $dir[0] . "/payonly/' class='PayOnly'>Оплатить объявления, поданные ранее 
или продлить их выходы</a>" . "</td></tr>
<tr><td>Выберите этот сервис, если вы хотите продлить или оплатить ранее поданное по телефону или в офисе объявление. 
Внимание! Для создания нового объявления войдите в Личный Кабинет
(см.выше)</td>  </tr>
</table>";


    return (array($text, $cap));
}

#############################################################################################################################################

function GetPayClear()
{
    global $dir;
    $_SESSION['OrderId'] = 0;
    SD();
    @header("location: /" . $dir[0] . "/");
    exit();
}

function Email_check($Email)
{
    if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", trim($Email))) {
        return false;
    } else {
        return true;
    }
}

#############################################################################################################################################

function GetResult()
{
    global $SETS, $VARS, $GLOBAL, $dir, $RealPage, $Page, $node, $table1, $table2, $table3, $table4, $C15, $C10, $C, $C5;
    if ($dir[2] == "success") {
        $cap = "Успешная оплата";
        $text = '<div class="SuccessDiv">Спасибо! Счет №' . $_SESSION['OrderId'] . ' успешно оплачен.</div>';
        $text .= "<div class='WhiteBlock'><img src='/modules/strochki/success.png' style='float:left; margin:0 15px 0 0;'> <a href='/" . $dir[0] . "/payclear/'><b><u>Вернуться в раздел</u></b></a>" . $C . "</div>" . $C10;
    }
    if ($dir[2] == "fail") {
        $cap = "Ошибка оплаты";
        $text = '<div class="ErrorDiv">Внимание! Возникла ошибка при оплате счета №' . $_SESSION['OrderId'] . '.</div>';
        $text .= "<div class='WhiteBlock'><img src='/modules/strochki/error.png' style='float:left; margin:0 15px 0 0;'> <a href='/" . $dir[0] . "/payclear/'><b><u>Вернуться в раздел</u></b></a>" . $C . "</div>" . $C10;
    }
    $_SESSION['OrderId'] = 0;
    SD();
    return (array($text, $cap));
}

#############################################################################################################################################

function GetPayOnly()
{
    global $SETS, $VARS, $GLOBAL, $dir, $RealPage, $Page, $node, $table1, $table2, $table3, $table4, $C15, $C10, $C, $C5;
    $cap = "Онлайн оплата объявлений";
    if (isset($_SESSION['Data']["sendbutton"])) {
        $P = $_SESSION['Data'];
        if ($P["name"] == "" || (int)$P["price"] == 0) {
            $msg = '<div class="ErrorDiv">Внимание! Поля не заполнены или заполнены неверно.</div>' . $C10;
        } else {
            if ((int)$_SESSION['OrderId'] == 0) {
                DB("INSERT INTO `" . $table4 . "` (`price`,`fio`,`text`,`data`) values ('" . (int)$P["price"] . "','" . str_replace(array("select", "in", "or", "delete", "drop", "insert", "update", "<br>", "h1", "h2", "h3", "from", "union"), "", htmlspecialchars(strip_tags($P["name"]))) . "','" . str_replace(array("select", "in", "or", "delete", "drop", "insert", "update", "<br>", "h1", "h2", "h3", "from", "union"), "", htmlspecialchars(strip_tags($P["textarea"]))) . "','" . time() . "')");
                $_SESSION['OrderId'] = DBL();
            } else {
                $OrderId = $_SESSION['OrderId'];
            }
            $OrderId = $_SESSION['OrderId'];
            $signature = md5($SETS[0] . ":" . (int)$P["price"] . ":" . (int)$OrderId . ":" . $SETS[1]);
            $paylink = $SETS[3] . "?MrchLogin=" . $SETS[0] . "&OutSum=" . (int)$P["price"] . "&InvId=" . (int)$OrderId . "&SignatureValue=" . $signature;
            $msg = "<div class='SuccessDiv'>Спасибо! Счет создан осталось оплатить его, перейти к оплате:$C10<div class='WhiteBlock'>" . $C5 . "<b><a href='" . $paylink . "'><u>Перейти к оплате</u></a></b>$C10<hr>$C10
			Заказ #<b>" . (int)$OrderId . "</b><br>ФИО: <b>" . htmlspecialchars(strip_tags($P["name"])) . "</b><br>Сумма: <b>" . $P["price"] . "</b><br>Комментарий: <b>" . htmlspecialchars(strip_tags($P["textarea"])) . "</b>" .
                $C10 . "<hr>" . $C10 . "<a href='/" . $dir[0] . "/payclear/'><b><u>От платежа отказываюсь</u></b></a>" . $C5 . "</div></div>" . $C10;
            $_SESSION['OrderId'] = 0;
            SD();
        }
    }
    $text = $msg;
    $text .= "<div class='WhiteBlock'>" . $node["pretext"] . "</div>" . $C15 . '<div class="RoundText" id="Tgg"><form action="/modules/SubmitForm.php?bp=' . $RealPage . '" enctype="multipart/form-data" method="post"><table>';
    $text .= '<tr class="TRLine0"><td class="VarText">Ваши Ф.И.О.<star>*</star></td><td class="LongInput"><input name="name" type="text" placeholder="Например: Иванов Петр Сергеевич"></td></tr>';
    $text .= '<tr class="TRLine0"><td class="VarText">Сумма платежа<star>*</star></td><td class="LongInput"><input name="price" type="text" placeholder="Например: 750"><br><span style="font:11px/14px Tahoma;">(Сумму платежа за ваши объявления можно уточнить по телефону)</span><br><br></td></tr>';
    $text .= '<tr class="TRLine0"><td class="VarText" style="vertical-align:top; padding-top:10px;">Сообщение администратору</td><td class="LongInput"><textarea name="textarea" style="height:60px;"></textarea></td></tr>';
    $text .= '</table>' . $C10 . '<div class="CenterText"><input type="submit" name="sendbutton" id="sendbutton" class="SaveButton" value="Оплатить"></div></form></div>';
    return (array($text, $cap));
}

#############################################################################################################################################

function GetCabinet()
{
    global $SETS, $VARS, $GLOBAL, $dir, $Page, $RealPage, $node, $table1, $table2, $table3, $table4, $C15, $C10, $C, $C5;
    if ((int)$_SESSION["str_userid"] == 0) {
        $P = $_SESSION['Data'];
        if (isset($_SESSION['Data']["loginbutton"])) {
            $data = DB("SELECT `id` FROM `" . $table3 . "` WHERE (`login`='" . $P["login1"] . "' && `pass`='" . $P["pass1"] . "' && `stat`='1') LIMIT 1");
            if ($data["total"] == 1) {
                @mysql_data_seek($data["result"], 0);
                $ar = @mysql_fetch_array($data["result"]);
                $_SESSION["str_userid"] = $ar["id"];
                SD();
                @header("location: /" . $dir[0] . "/cabinet/");
                exit();
            } else {
                $msg1 = '<div class="ErrorDiv">Логин или пароль указаны неверно.</div>';
            }
        }
        if (isset($_SESSION['Data']["registerbutton"])) {
            $er = 0;
            if (!Email_check($P["login2"])) {
                $er = 1;
                $_SESSION["Msg2"] = '<div class="ErrorDiv">Новый логин указан неверно. Введите Email!</div>';
            } else {
                $data = DB("SELECT `id` FROM `" . $table3 . "` WHERE (`login`='" . $P["login2"] . "') LIMIT 1");
                if ($data["total"] != 0) {
                    $er = 1;
                    $_SESSION["Msg2"] = "<div class='ErrorDiv'>Данный логин уже занят! <a href='/" . $dir[0] . "/forget/'><u>Забыли пароль</u>?</a></div>";
                }
                if ($er == 0) {
                    $np = rand(11111111, 999999999);
                    $data = DB("INSERT INTO `" . $table3 . "` (`login`,`pass`,`name`) VALUES ('" . $P["login2"] . "','" . $P["pass2"] . "','" . $P["name2"] . "')");
                    $_SESSION["Msg2"] = '<div class="SuccessDiv">Успешно! Пароль отправлен вам на Email</div>';
                    $body = "Здравствуйте, " . $P["name2"] . "<br><br>Вы зарегистрировались на сайте <a href='http://" . $VARS["mdomain"] . "'>" .
                        $VARS["mdomain"] . "</a> в личном кабинете.Теперь вы сможете  размещать  свои объявления в газете \"Pro Город\" Казань и оплачивать их.Для того что бы подать объявление в газету \"Pro Город\" вам нужно перейти в личный кабинет и ввести 
ваш логин и пароль .Если остались какие - либо вопросы вы сможете позвонить  по телефону (843) 236-78-45.Наши специалисты обязательно помогут вам <br><br>Ваш логин: " . $P["login2"] . "<br>Пароль: " . $P["pass2"] .
                        "<br><br><a href='http://" . $VARS["mdomain"] . "/" . $dir[0] . "/'>Перейти в личный кабинет</a>";

                    MailSend($P["login2"], "Регистрация ", $body, $VARS["sitemail"]);
                    SD();
                    @header("location: /" . $dir[0] . "/cabinet/");
                    exit();
                }
            }
        }
        $text .= "<div class='WhiteBlock'><div style='float:left; width:45%; margin-right:5%;'>";
        $text .= "<h3>Войти в личный кабинет</h3>" . $C10 . $msg1 . "Если вы уже регистрировались в кабинете, войдите с помощью логина (ваш Email) и пароля, который мы вам отправляли при регистрации <a href='/" . $dir[0] . "/forget/'><u>Забыли пароль</u>?</a>" . $C10;
        $text .= '<div class="RoundText" id="Tgg"><form action="/modules/SubmitForm.php?bp=' . $RealPage . '" enctype="multipart/form-data" method="post"><table>';
        $text .= '<tr class="TRLine0"><td class="VarText">Логин (Email)</td><td class="LongInput"><input name="login1" type="text" placeholder="Например: test@mail.ru" style="width:100%;"></td></tr>';
        $text .= '<tr class="TRLine0"><td class="VarText">Ваш пароль</td><td class="LongInput"><input name="pass1" type="password" style="width:100%;"></td></tr>';
        $text .= '</table>' . $C10 . '<div class="CenterText"><input type="submit" name="loginbutton" class="SaveButton" value="Войти в кабинет"></div></form></div>';
        $text .= "</div><div style='float:left; width:45%;'>";
        $text .= "<h3>Новая регистрация</h3>" . $C10 . $_SESSION["Msg2"] . "Если у вас нет логина и пароля - зарегистрируйтесь, пароль мы отправим на ваш Email (Email используется как логин, указывайте только существующие адреса)" . $C10;
        $text .= '<div class="RoundText" id="Tgg"><form action="/modules/SubmitForm.php?bp=' . $RealPage . '" enctype="multipart/form-data" method="post"><table>';
        $text .= '<tr class="TRLine0"><td class="VarText">Логин (Email)</td><td class="LongInput"><input name="login2" type="text" placeholder="Например: test@mail.ru" style="width:100%;"></td></tr>';
        $text .= '<tr class="TRLine0"><td class="VarText">Ваше Ф.И.О.</td><td class="LongInput"><input name="name2" type="tyxt" style="width:100%;"></td></tr>';
        $text .= '<tr class="TRLine0"><td class="VarText">Придумайте пароль</td><td class="LongInput"><input name="pass2" type="password" style="width:100%;"></td></tr>';
        $text .= '<tr class="TRLine0"><td class="VarText">Придумайте пароль</td><td class="LongInput"> Придумайте пароль, который вы сможете запомнить, он потребуется 
для того чтобы вы смогли подавать объявления в будущем.</td></tr>';
        $text .= '</table>' . $C10 . '<div class="CenterText"><input type="submit" name="registerbutton" class="SaveButton" value="Регистрация"></div></form></div>';
        $text .= "</div>" . $C5 . "</div>" . $C;
    } else {
        $data = DB("SELECT * FROM `" . $table3 . "` WHERE (`id`='" . (int)$_SESSION["str_userid"] . "') LIMIT 1");
        @mysql_data_seek($data["result"], 0);
        $ar = @mysql_fetch_array($data["result"]);
        $text = "<h3>Кабинет: " . $ar["name"] . "</h3>" . $C10;
        $text .= "<table class='TableStrochki' cellspacing='0' cellpadding='0'><tr><td><a href='/" . $dir[0] . "/addnew/' class='Cabinet1'>Создать новое объявление</a></td>";
        $text .= "<td><a href='/" . $dir[0] . "/myobjs/' class='Cabinet2'>Список моих объявлений</a></td></tr><tr><td><!--<a href='/" . $dir[0] . "/mylist/' class='Cabinet3'>История счетов и оплат</a>--></td>";
        $text .= "<td><a href='/" . $dir[0] . "/setts/' class='Cabinet4'>Настройки личного кабинета</a></td></tr></table>" . $C10 . "<a href='/" . $dir[0] . "/exit/' style='float:right;'><b>Выйти из кабинета</b></a>";
    }
    SD();
    $_SESSION["Msg2"] = "";
    $cap = "Личный кабинет рекламодателя";
    return (array($text, $cap));
}

#############################################################################################################################################

function GetNewAdd()
{
    global $SETS, $VARS, $GLOBAL, $dir, $Page, $RealPage, $node, $table1, $table2, $table3, $table4, $C15, $C10, $C, $C5;

    $raz = array();
    $prses = array();
    $data = DB("SELECT * FROM `" . $dir[0] . "_razdels` WHERE (`stat`='1') ORDER BY `rate` DESC");
    for ($i = 0; $i < $data["total"]; $i++) {
        @mysql_data_seek($data["result"], $i);
        $ar = @mysql_fetch_array($data["result"]);
        $raz[] = $ar;
    }
    $sel = "<option value='0' selected>--- Выберите раздел размещения ---</option>";
    foreach ($raz as $main) {
        if ($main["pid"] == 0) {
            $sel .= "<option style='background:#CCC;' disabled>" . $main["name"] . "</option>";
            foreach ($raz as $sec) {
                if ($main["id"] == $sec["pid"]) {
                    $sel .= "<option value='" . $sec["id"] . "'>... " . $sec["name"] . "</option>";
                    $prses[$sec["id"]] = $sec["price"];
                    $prs .= "prices[" . $sec["id"] . "]='" . $sec["price"] . "';";
                }
            }
        }
    }

    $Data = $_SESSION["Data"];
    if (isset($Data["regbutton"])) {

        if (!isset($Data['csrf_code'], $Data['key_csrf'], $Data['url'])){
            print 'CSRF Error';
            exit;
        } else {
            if ($Data['csrf_code'] !== md5('Progorodchelny.ru' . date('D H'))){
                print 'CSRF ERROR';
                exit;
            }
        }
        if ($Data['url'] !== 'http://progorodchelny.ru'){
            print 'CSRF ErroR';
            exit;
        }
        $er = 0;
        $l1 = mb_strlen(trim($Data["obj"]));
        $l2 = mb_strlen(trim($Data["phone"]));
        $ll = $l1 + $l2;
        $arpr = explode(",", $prses[$Data["cat"]]);
        $price = $arpr[4];
        if ($ll < 101) {
            $price = $arpr[3];
        }
        if ($ll < 71) {
            $price = $arpr[2];
        }
        if ($ll < 51) {
            $price = $arpr[1];
        }
        if ($ll < 31) {
            $price = $arpr[0];
        }
        $sets = $Data["dop1"] . "," . $Data["dop2"] . "," . $Data["dop3"] . "," . $Data["dop4"];
        $onznak = $price;
        $datas = explode(",", trim($Data["datss"], ","));
        $exit = count($datas);
        $dts = "";
        foreach ($datas as $data) {
            $dts .= date("d.m.Y", $data) . ",";
        }
        $dts = trim($dts, ",");

        if ((int)$Data["dop1"] == 1) {
            $price = $price * 3;
        }
        if ((int)$Data["dop2"] == 1) {
            $price = $price * 2;
        }
        if ((int)$Data["dop3"] == 1) {
            $price = $price * 1.5;
        }
        if ((int)$Data["dop4"] == 1) {
            $price = $price * 1.5;
        }
        /*СКИДКА ЗА СРОК!!!*/
        $oneprice = $price;
        $price = $price * $exit * $Data["hs"];
        $q = "INSERT INTO `" . $dir[0] . "_objects` (`uid`, `text`, `phone`, `rid`, `price`, `stat`, `sets`, `data`, `datas`, `dop`) VALUES ('" . $_SESSION["str_userid"] . "', '" . $Data["obj"] . "', '" . $Data["phone"] . "', '" . (int)$Data["cat"] . "', '$price', '0',
	'" . $sets . "', '" . time() . "', '" . $dts . "', 'Выходов: " . $exit . ". Символов: " . $ll . ". По разделу: " . $onznak . "p. Скидка: " . (100 - 100 * $Data["hs"]) . "%. " . $oneprice . "p. x " . $exit . "шт. - " . (100 - 100 * $Data["hs"]) . "% = " . $price . "p.')";
        $d = DB($q);
        $dbl = DBL();
        DB("INSERT INTO `" . $dir[0] . "_pays` (`uid`, `oid`, `price`, `data`) VALUES ('" . $_SESSION["str_userid"] . "', '" . $dbl . "', '" . $price . "', '" . time() . "');");
        SD();
        if ($er == 0) {
            @header("location: /" . $dir[0] . "/myobjs/");
            exit();
        }
    }

    $text .= '<div class="RoundText WhiteBlock" id="Tgg"><script>prices=new Array(); ' . $prs . 'prices[0]=\'0,0,0,0\';</script>
	<form action="/modules/SubmitForm.php?bp=' . $RealPage . '" enctype="multipart/form-data" method="post" onsubmit="return SubmitForm();"><table>';
    $text .= '<tr class="TRLine0"><td class="VarText" style="width:30%;"><b>Выберите категорию</b></td><td class="LongInput" style="width:30%;">
	<select id="cat" name="cat" style="width:315px; font-size:13px;" onchange="ChangePrice();">' . $sel . '</select></td><td style="width:40%;"></td></tr><tr class="TRLine0">
	<td class="VarText" style="width:30%;"><b>Текст объявления</b><br><span style="color:#777; font-size:10px;">(указывайте здесь только текст, телефон указывается ниже)</span></td>
	<td class="LongInput" style="width:30%;"><textarea name="obj" id="obj" style="width:300px; height:70px;" maxlength="140"></textarea><input name="hs" id="hs" value="1" type="hidden" /></td>
	<td style="width:40%; color:#777; font-size:10px;"><div id="stoimost"></div><div class="C10"></div>Символов в объявлении: <b><span id="leng">0</span></b></td></tr>
	<tr class="TRLine0"><td class="VarText"><b>Телефон</b></td><td class="LongInput"><input name="phone" id="phone" style="width:300px;" maxlength="15" /></td><td><span style="color:#777; font-size:10px;">входит в общее количество</span></td></tr>
	<tr class="TRLine0"><td class="VarText" colspan="3"><div class="C5"></div><h3>Даты выходов объявлений. Всего выходов: <span id="texit">1</span></h3><div class="C10"></div>
	<i style="font-size:11px; font-family:Arial;color:red;">Выберите желаемые даты выхода вашего объявления, для этого кликните на соответствующие желтые ячейки календаря. Выход газеты ProГород 
    происходит 1 раз в неделю по субботам. Внимание! Среда - последний день приема объявлений в текущую неделю</i></td></tr><tr class="TRLine0"><td colspan="3">';

    $n = time(); /* if ((date("w")>3 || date("w")==0) && date("d")>24) { $n=$n+7*24*60*60; } */
    $t1 = $n;
    $t2 = $n + 1 * 30 * 24 * 60 * 60;
    $t3 = $n + 2 * 30 * 24 * 60 * 60;
    $t4 = $n + 3 * 30 * 24 * 60 * 60;
    $t5 = $n + 4 * 30 * 24 * 60 * 60;
    $t6 = $n + 5 * 30 * 24 * 60 * 60;
    $t7 = $n + 6 * 30 * 24 * 60 * 60;
    $t8 = $n + 7 * 30 * 24 * 60 * 60;
    $t9 = $n + 8 * 30 * 24 * 60 * 60;
    $WasSetDay = 0;

    $text .= "<table><tr><td width='33%' style='padding:5px;' valign='top'>" . CreateCal($t1) . "</td><td width='34%' style='padding:5px;' valign='top'>" . CreateCal($t2) . "</td><td width='33%' style='padding:5px;' valign='top'>" . CreateCal($t3) . "</td></tr></table>"
        . $C15 . "<table><tr><td width='33%' style='padding:5px;' valign='top'>" . CreateCal($t4) . "</td><td width='34%' style='padding:5px;' valign='top'>" . CreateCal($t5) . "</td><td width='33%' style='padding:5px;' valign='top'>" . CreateCal($t6) . "</td></tr></table>"
        . $C15 . "<table><tr><td width='33%' style='padding:5px;' valign='top'>" . CreateCal($t7) . "</td><td width='34%' style='padding:5px;' valign='top'>" . CreateCal($t8) . "</td><td width='33%' style='padding:5px;' valign='top'>" . CreateCal($t9) . "</td></tr></table>";

    $text .= '<div style="display:none;"><input type="text" name="key_csrf" value="">';
    $text .= '<input type="text" name="csrf_code" value="">';
    $text .= '<input type="text" name="url" value="http://progorodchelny.ru"></div>';

    $text .= '</td></tr><tr class="TRLine0"><td colspan="3"><i style="font-size:11px; font-family:Arial;">При размещении объявления на длительный период (от 4 выходов газеты подряд) действуют значительные скидки: 4 выходов и более - 15%, 12 выходов и более - 20%, 27 выходов и более - 25%</i></td></tr><tr class="TRLine0"><td class="VarText" colspan="3"><h3>Сделайте ваше объявление более заметным, изменив стандартное оформление с помощью дополнительных возможностей!</h3></td></tr><tr class="TRLine0"><td colspan="3">
	<input type="hidden" name="dop1" value="0" /><input type="hidden" name="dop2" value="0" /><input type="hidden" name="dop3" value="0" /><input type="hidden" name="dop4" value="0" />
	<input type="checkbox" id="dop1" name="dop1" value="1" />  <span style="background-color:#FF8C00">выделено цветом</span><span style="color:#777; font-size:10px;"> ... увеличение стоимости объявления в  <b>3 раза</b></span><div class="C5"></div>
   	<input type="checkbox" id="dop2" name="dop2" value="1" /> <span style="border-top:solid black;border-bottom:solid black;border-width:1 px;">выделено  рамкой</span><span style="color:#777; font-size:10px;"> ... увеличение стоимости объявления в  <b>2 раза</b></span><div class="C5"></div>
    <input type="checkbox" id="dop3" name="dop3" value="1" /> <b> выделено  жирным шрифтом</b><span style="color:#777; font-size:10px;"> ... увеличение стоимости объявления в  <b>1,5 раза</b></span><div class="C5"></div>
    <input type="checkbox" id="dop4" name="dop4" value="1" /><b> ВЫДЕЛЕНО ЗАГЛАВНЫМИ БУКВАМИ</b><span style="color:#777; font-size:10px;"> ... увеличение стоимости объявления в  <b>1,5 раза</b></span>
	</td></tr><tr class="TRLine0"><td class="VarText" colspan="3"><hr></td></tr><tr class="TRLine0"><td><b>Стоимость одного выхода:</b><br><div id="sum" class="summa">0<b>р.</b></div></td>
	<td><b>Скидка за долгосрок:</b><br><div id="skidka" class="summa">0<b>%</b></div></td><td><b>Общая стоимость:</b><br><div id="sumall" class="summa">0<b>.</b></div></td></tr><tr class="TRLine0"><td class="VarText" colspan="3" id="tmp">
	<hr>После добавления этого объявления, вы будете перенаправлены на страницу списка ваших объявлений, где сможете оплатить добавленное объявление или любое другое ранее созданное объявление.<hr></td></tr>';
    $text .= '</table>' . $C5 . '<div class="CenterText"><input type="hidden" id="datss" name="datss"><input type="submit" name="regbutton" class="SaveButton" value="Создать объявление"></div></form><div class="C10"></div></div>';
    $text .= $C10 . "<a href='/" . $dir[0] . "/cabinet/' style='float:right;'><b>Вернуться в кабинет</b></a>";
    $cap = "Добавить новое объявление";
    return (array($text, $cap));
}

#############################################################################################################################################

function GetMyList()
{
    global $SETS, $VARS, $GLOBAL, $dir, $Page, $RealPage, $node, $table1, $table2, $table3, $table4, $C15, $C10, $C, $C5;
    $cap = "";
    return (array($text, $cap));
}

#############################################################################################################################################

function GetMyObjs()
{
    global $SETS, $VARS, $GLOBAL, $dir, $Page, $RealPage, $node, $table1, $table2, $table3, $table4, $C15, $C10, $C, $C5;
    $data = DB("SELECT * FROM `" . $dir[0] . "_razdels` WHERE (`stat`='1') ORDER BY `rate` DESC");
    for ($i = 0; $i < $data["total"]; $i++) {
        @mysql_data_seek($data["result"], $i);
        $ar = @mysql_fetch_array($data["result"]);
        $raz[$ar["id"]] = $ar;
    }
    $q = "SELECT `" . $dir[0] . "_objects`.*, `" . $dir[0] . "_pays`.`stat` as `statpay`, `" . $dir[0] . "_pays`.`id` as `idpay`, `" . $dir[0] . "_pays`.`price` as `price2` FROM `" . $dir[0] . "_objects` LEFT JOIN `" . $dir[0] . "_pays`
	ON `" . $dir[0] . "_pays`.`oid`=`" . $dir[0] . "_objects`.`id` WHERE (`" . $dir[0] . "_objects`.`uid`='" . $_SESSION["str_userid"] . "') GROUP BY 1 ORDER BY `" . $dir[0] . "_objects`.`data` DESC";
    $data = DB($q);
    $text .= "<div class='RoundText WhiteBlock'><b>Отлично, ваше объявление успешно создано, осталось только его оплатить.</b><br><br>Это очень просто сделать пластиковой картой, электронными деньгами, через SMS или терминал Qiwi. Для этого нажмите \"оплатить\" справа от объявления, в открывшемся окне выберите удобный вам способ оплаты и следуйте инструкциям системы<br><br>Если у вас появились вопросы мы рады вам помочь по телефону (843) 236-78-45</div>" . $C15;
    $text .= '<div class="RoundText WhiteBlock" id="Tgg"><table>';
    for ($i = 0; $i < $data["total"]; $i++): @mysql_data_seek($data["result"], $i);
        $ar = @mysql_fetch_array($data["result"]);
        $d = ToRusData($ar["data"]);
        $a = explode(",", $ar["sets"]);
        if ($a[0] == 1) {
            $d1 = "<b style='color:green;'>да</b>";
        } else {
            $d1 = "<b>нет</b>";
        }
        if ($a[1] == 1) {
            $d2 = "<b style='color:green;'>да</b>";
        } else {
            $d2 = "<b>нет</b>";
        }
        if ($a[2] == 1) {
            $d3 = "<b style='color:green;'>да</b>";
        } else {
            $d3 = "<b>нет</b>";
        }
        if ($a[3] == 1) {
            $d4 = "<b style='color:green;'>да</b>";
        } else {
            $d4 = "<b>нет</b>";
        }


        if ($ar["statpay"] == 1) {
            $pay = "<i style='color:green;'>Оплачено</i>";
        } else {
            $signature = md5($SETS[0] . ":" . (int)$ar["price"] . ":" . (int)$ar["idpay"] . ":" . $SETS[1]);
            $paylink = $SETS[3] . "?MrchLogin=" . $SETS[0] . "&OutSum=" . (int)$ar["price"] . "&InvId=" . (int)$ar["idpay"] . "&SignatureValue=" . $signature;
            $pay = "<a href='" . $paylink . "' style='color:red;'><b><u>Оплатить</u></b></a>";
        }
        $text .= '<tr class="TRLine0"><td><b>№' . $ar["id"] . '</b><br><span style="color:#777; font-size:10px;">' . $d[5] . '</span></td>
	<td><b>Текст:</b> ' . $ar["text"] . '<div class="C5"></div><b>Телефон:</b> ' . $ar["phone"] . '<div class="C5"></div><b>Дополнительно:</b><br>Выделение цветом:' . $d1 . '<br>Выделено рамкой:' . $d2 . '<br>Выделено жирным шрифтом:' . $d3 . '<br>Выделено ЗАГЛАВНЫМИ БУКВАМИ: ' . $d4 . '<div class="C5"></div><span style="color:#777; font-size:10px;"><b>Дата выхода:</b> ' . str_replace(",", ", ", $ar["datas"]) . '</span></td>
	<td width="3%"> <b style="font-size:15px;">' . $ar["price"] . '</b>р. </td><td width="3%">' . $pay . '</td></tr><tr class="TRLine0"><td colspan="4"><hr></td></tr>';
    endfor;
    $text .= '</table></div>' . $C10 . "<a href='/" . $dir[0] . "/cabinet/' style='float:right;'><b>Вернуться в кабинет</b></a>";

    $cap = "Список ваших объявлений";
    return (array($text, $cap));
}

#############################################################################################################################################

function GetSetts()
{
    global $SETS, $VARS, $GLOBAL, $dir, $Page, $RealPage, $node, $table1, $table2, $table3, $table4, $C15, $C10, $C, $C5;
    $Data = $_SESSION["Data"];
    if (isset($Data["regbutton"])) {
        DB("UPDATE `" . $dir[0] . "_users` SET `name`='" . $Data["name"] . "', `phone`='" . $Data["phone"] . "' WHERE (`id`='" . $_SESSION["str_userid"] . "')");
        if ($Data["pass"] != "") {
            DB("UPDATE `" . $dir[0] . "_users` SET `pass`='" . $Data["pass"] . "' WHERE (`id`='" . $_SESSION["str_userid"] . "')");
        }
        $msg = '<div class="SuccessDiv">Настройки успешно сохранены!</div>';
        SD();
    }
    $data = DB("SELECT `name`, `phone`, `login` FROM `" . $table3 . "` WHERE (`id`='" . $_SESSION["str_userid"] . "')");
    if ($data["total"] == 1) {
        @mysql_data_seek($data["result"], 0);
        $ar = @mysql_fetch_array($data["result"]);
        $text .= $msg . '<div class="RoundText WhiteBlock" id="Tgg"><form action="/modules/SubmitForm.php?bp=' . $RealPage . '" enctype="multipart/form-data" method="post"><table>';
        $text .= '
	<tr class="TRLine0"><td class="VarText"><b>Ваш логин</b></td><td class="LongInput"><input name="log" style="width:300px;" value=\'' . $ar["login"] . '\' readonly /></td><td>изменить нельзя</td></tr>
	<tr class="TRLine0"><td class="VarText"><b>Ваше имя</b></td><td class="LongInput"><input name="name" style="width:300px;" value=\'' . $ar["name"] . '\' /></td><td></td></tr>
	<tr class="TRLine0"><td class="VarText"><b>Ваш телефон</b></td><td class="LongInput"><input name="phone" style="width:300px;" value=\'' . $ar["phone"] . '\' /></td><td>используется только для связи с менеджером</td></tr>
	<tr class="TRLine0"><td colspan="3"><hr></td></tr>
	<tr class="TRLine0"><td class="VarText"><b>Ваш пароль</b></td><td class="LongInput"><input name="pass" style="width:300px;" placeholder="Ваш НОВЫЙ пароль"/></td><td>заполните, если хотите СМЕНИТЬ пароль</td></tr>
	<tr class="TRLine0"><td colspan="3"><hr></td></tr>';
        $text .= '</table>' . $C5 . '<div class="CenterText"><input type="submit" name="regbutton" class="SaveButton" value="Сохранить настройки"></div></form><div class="C10"></div></div>';
        $text .= $C10 . "<a href='/" . $dir[0] . "/cabinet/' style='float:right;'><b>Вернуться в кабинет</b></a>";
    }
    $cap = "Настройки личного кабинета";
    return (array($text, $cap));
}

#############################################################################################################################################

function CreateCal($tm)
{
    global $GLOBAL, $WasSetDay;
    list($n, $Y, $t, $m) = explode(".", date("n.Y.t.m", $tm));
    $dw = 1;
    $text = "<div style='text-align:center; margin:0 0 7px 0;'><b>" . $GLOBAL["mothi"][$n] . " " . $Y . "</b></div><table class='Calendar'>
	<tr class='DayWeek'><td>ПН</td><td>ВТ</td><td>СР</td><td>ЧТ</td><td>ПТ</td><td>СБ</td><td>ВС</td></tr><tr>";
    $fd = date("w", strtotime("01-$m-$Y"));
    if ($fd == 0) {
        $fd = 7;
    }
    if ($fd != 1) {
        for ($j = 1; $j < $fd; $j++) {
            $text .= "<td></td>";
            $dw++;
        }
    }
    if ($dw % 7 == 1) {
        $dw = 1;
    }
    for ($i = 1; $i <= $t; $i++) {
        if ($i < 10) {
            $j = "0" . $i;
        } else {
            $j = $i;
        }
        $mk = strtotime("$j-$m-$Y");
        if ($dw % 4 != 0 || $mk < time()) {
            $text .= "<td class='DayNoExit'>" . $i . "</td>";
        } else {
            if ($WasSetDay == 0) {
                $WasSetDay = 1;
                $text .= "<td class='DaySel DayEnabled' title='" . $mk . "'>" . $i . "</td>";
            } else {
                $text .= "<td class='DaySel' title='$mk'>" . $i . "</td>";
            }
        }
        $dw++;
        if ($dw % 7 == 1) {
            $text .= "</tr><tr>";
            $dw = 1;
        }
    }
    if ($dw != 1) {
        for ($j = $dw; $j < 8; $j++) {
            $text .= "<td></td>";
        }
    }
    $text .= "</tr></table>";
    return $text;
}

?>