<?	
$file="_leftblock-lsе_default"; if (RetCache($file, "cacheblock")=="true") { list($Page["LeftContent"], $cap)=GetCache($file, 0); } else { list($Page["LeftContent"], $cap)=CreateLeftBlock(); SetCache($file, $Page["LeftContent"], "", "cacheblock"); }	
if ($link!="uncensored") { $Page["LeftContent"]=str_replace('<!--ADS-->', '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:inline-block;width:200px;height:200px;" data-ad-client="ca-pub-2073806235209608" data-ad-slot="9007081016"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>', $Page["LeftContent"]); }

if ($link!="uncensored" && $link!="live") { $Page["LeftContent"]=str_replace('<!--ADS-->', '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:inline-block;width:200px;height:200px; margin-left:20px;" data-ad-client="ca-pub-2073806235209608" data-ad-slot="9007081016"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>', $Page["LeftContent"]); }
$Page["LeftContent"].=$C25.'<div id="yandex_ad"></div>
<script type="text/javascript">
(function(w, d, n, s, t) {
    w[n] = w[n] || [];
    w[n].push(function() {
        Ya.Direct.insertInto(125901, "yandex_ad", {
            ad_format: "direct",
            font_size: 0.9,
            type: "vertical",
            border_type: "block",
            limit: 4,
            title_font_size: 1,
            site_bg_color: "FFFFFF",
            header_bg_color: "CCCCCC",
            border_color: "CCCCCC",
            title_color: "0066CC",
            url_color: "006600",
            text_color: "000000",
            hover_color: "0066FF",
            no_sitelinks: true
        });
    });
    t = d.getElementsByTagName("script")[0];
    s = d.createElement("script");
    s.src = "//an.yandex.ru/system/context.js";
    s.type = "text/javascript";
    s.async = true;
    t.parentNode.insertBefore(s, t);
})(window, document, "yandex_context_callbacks");
</script>'; 


function CreateLeftBlock() {
	global $Domains, $SubDomain, $GLOBAL, $C20, $C10, $C25, $C; $text='';
	// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		
		$text.="<capt>«Семья» - гид по трендам</capt>".$C.LSTheNewestInKazan(7);
		
		
		
		$text.='<script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script><div id="vk_groupsVK"></div>';
		$text.='<script type="text/javascript">VK.Widgets.Group("vk_groupsVK", {mode: 1, width: "200", height: "140", color1: "FFFFFF", color2: "622b6b", color3: "622b6b"}, 40881158);</script>'.$C25;
		
		$text.="<div style='overflow:hidden; font-size:11px;'><a href='http://instagram.com/katalog_semya' target='_blank' rel='nofollow'><img src='/template/standart/voting/insta.gif' style='float:left; width:42px; height:42px; border:none; margin-right:10px;'>«Семья» - гид по трендам<br> в Инстаграм</a></div>".$C25;
		
		$text.='<div class="banner3" id="Banner-26-1"></div>';
		
		$text.='<!--ADS-->'.$C25;
		
		$text.='<div class="banner3" id="Banner-10-1"></div>';
		
		$text.="<capt>Интересное в Казани</capt>".$C.TheCommerceInKazan(4, 0);
		
		$text.='<div class="banner3" id="Banner-10-3"></div>';
		
		$text.="<div style='overflow:hidden;'><capt>Мода казанских улиц</capt>".$C.ShowStreetFashioBlock(3, 0)."</div>";
		
		$text.='<div class="banner" id="Banner-9-2"></div>';
		
		$text.='<div class="banner3" id="Banner-10-5"></div>';
		
		$text.='<!--ADS-->'.$C25;
		
		#$text.="<capt>Интересное в Казани</capt>".$C.TheCommerceInKazan(4, 8);
		
		$text.='<div class="banner3" id="Banner-10-7"></div>';
	
				
		
		
	// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
	$text.="<div class='C10'></div>"; return(array($text, ""));
}

// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---\
// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---



?>