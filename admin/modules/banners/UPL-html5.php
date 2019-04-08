<?php

if ($GLOBAL["sitekey"]==1 && $GLOBAL["database"]==1) {
    $msg = '';
    $zip = $_FILES['userhtml5']['tmp_name'];
    $html5banner = $ROOT . '/advert/files/html5/' . date('Y-m-d') . '-html5-' . $P['zay'] . '-' . rand(1111,9999) . '.zip';
    $dir = str_replace('.zip', '/*.html', $html5banner);
    mkdir(str_replace('/*.html', '', $dir));
    if (move_uploaded_file($zip, $html5banner)){
        $z =new ZipArchive();
        $res = $z->open($html5banner);
        if ($res === true){
            $z->extractTo( str_replace('.zip', '', $html5banner) );
            $z->close();
            foreach(glob($dir) as $htmlFile){
                $html5 = replaceSrcHtml5($htmlFile, $html5banner);
            }
        } else {
            $msg = 'Файл не ZIP-архив';
        }
    }
} else {
    $msg = 'Ошибка сервера. Отказано в доступе!';
}

if ($msg !== '') { $_SESSION["Msg"]="<div class='ErrorDiv'>$msg</div>"; }

function replaceSrcHtml5($html5file, $dir){
    global $ROOT;

    $code = file_get_contents($html5file);
    $dir = str_replace(array('*.html', '.zip', $ROOT), '', $dir) . '/';

    $hrefRegexp = '#(href=\"[^http].*?\")#';
    $srcRegexp = '#(src=\"[^http].*?\")#';
    $sourceRegexp = '#((\"\s|\-)source=\"[^http].*?\")#';
    preg_match_all($hrefRegexp, $code, $hrefs);
    preg_match_all($srcRegexp, $code, $srcs);
    preg_match_all($sourceRegexp, $code, $sources);

    foreach ($hrefs[0] as $href){
        $newHref = str_replace('href="', 'href="' . $dir, $href);
        $code = str_replace($href, $newHref, $code);
    }

    foreach ($srcs[0] as $src){
        $newSrc = str_replace('src="', 'src="' . $dir, $src);
        $code = str_replace($src, $newSrc, $code);
    }

    foreach ($sources[0] as $source){
        $newSource = str_replace('source="', 'source="' . $dir, $source);
        $code = str_replace($source, $newSource, $code);
    }

    $newHtml5File = str_replace(basename($html5file), 'html5.html', $html5file);

    file_put_contents($newHtml5File, $code);

    return ltrim(str_replace(array($ROOT, 'advert/files/html5/'), '', $newHtml5File), '/');
}