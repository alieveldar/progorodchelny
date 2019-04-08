<?php

/**
 * Скрипт Для подключения Wordpress и не только
 * Class Spacepush_Ads_Anti_Adblock
 * WordPress
 * 1) Добавьте файл в директорию с вашим сайтом
 * 2) Замените в этом файле в строке private $token = "<TOKEN>"; на- ваш токен полученый у вашего менеджера
 * 3) Отредактируйтей файл header.php в папке с вашей темой wp-content/themes/ВАША_ТЕМА
 * 3.2) После тега <head> добавьте строку:
 *    -- Wordpress  <?php get_template_part( 'antiadblock' ); ?>
 *    -- Любая Другая PHP система <?php include('path_to_antiadblock.php')?>
 *
 */
class Spacepush_Ads_Anti_Adblock {
    private $token = "5bc5da316020c4542b6bc386"; //<TOKEN> заменить на- ваш токен полученый у вашего менеджера
    private $host = "http://adblockrecovery.ru/api/code";


    private function getCurl( $url ) {
        if ( ( ! extension_loaded( 'curl' ) ) || ( ! function_exists( 'curl_version' ) ) ) {
            return false;
        }

        $curl = curl_init();
        curl_setopt_array( $curl, array(
            CURLOPT_URL                 => $url, #
            CURLOPT_TIMEOUT_MS          => 200,  # Таймаут 100ms
            CURLOPT_CONNECTTIMEOUT_MS   => 200,  # Таймаут соединения 100ms
            CURLOPT_RETURNTRANSFER      => true, # Получить результат
            CURLOPT_NOSIGNAL            => true
        ));

        $result = curl_exec( $curl );
        curl_close( $curl );
        echo "<!-- 1 -->";
        return $result;
    }

    private function getFileGetContents( $url ) {
        if ( ! function_exists( 'file_get_contents' ) || ! ini_get( 'allow_url_fopen' ) ||
            ( ( function_exists( 'stream_get_wrappers' ) ) && ( ! in_array( 'http', stream_get_wrappers() ) ) )
        ) {
            return false;
        }
        echo "<!-- 2 -->";
        return file_get_contents($url);
    }

    private function getFsockopen( $url ) {
        $fp = null;

        if ( ( ! $fp ) && ( ! ( $fp = fsockopen( 'tcp://' . parse_url($url, PHP_URL_HOST), 80, $enum, $estr, 10 ) ) ) ) {
            return false;
        }

        $out = "GET " . $url . " HTTP/1.1\r\n";
        $out .= "Host: ".$_SERVER['HTTP_HOST']."\r\n";
        $out .= "User-Agent: AntiAdBlock API Client (WordPress)\r\n";
        $out .= "Connection: close\r\n\r\n";
        fputs( $fp, $out );
        $header = '';
        $body   = '';
        do // loop until the end of the header
        {
            $header .= fgets ( $fp, 128 );

        } while ( strpos ( $header, "\r\n\r\n" ) === false );

        while ( ! feof ( $fp ) )
        {
            $body .= fgets ( $fp, 128 );
        }
        fclose( $fp );
        echo "<!-- 3 -->";
        $body = substr( $body,  4 );
        return substr( $body, 0, strpos( $body, "\n0" ));
    }

    private function findTmpDir() {
        // WordPress temp dir
        if ( function_exists('get_temp_dir') ) {
            return get_temp_dir();
        }

        if ( ! function_exists( 'sys_get_temp_dir' ) ) {
            if ( ! empty( $_ENV['TMP'] ) ) {
                return realpath( $_ENV['TMP'] );
            }
            if ( ! empty( $_ENV['TMPDIR'] ) ) {
                return realpath( $_ENV['TMPDIR'] );
            }
            if ( ! empty( $_ENV['TEMP'] ) ) {
                return realpath( $_ENV['TEMP'] );
            }
            // this will try to create file in dirname(__FILE__) and should fall back to /tmp or wherever
            $tempfile = tempnam( dirname( __FILE__ ), '' );
            if ( file_exists( $tempfile ) ) {
                unlink( $tempfile );

                return realpath( dirname( $tempfile ) );
            }

            return null;
        }

        return sys_get_temp_dir();
    }

    public function get() {
        $e = error_reporting( 0 );

        $url = $this->host .'?'. http_build_query( array( 'adphash' => $this->token ) );
        $file = $this->findTmpDir() . '/pa-code-' . md5( $url );

        $data = false;
        if ( file_exists( $file ) ) {
            error_reporting( $e );
            $data = file_get_contents( $file );
        }
        // expires in 10 minutes || or no-cache
        if(( time() - filemtime( $file ) > 5 * 60 ) || empty($data)) {
            $code = $this->getCurl($url);
            if (!$code) {
                $code = $this->getFileGetContents($url);
            }
            if (!$code) {
                $code = $this->getFsockopen($url);
            }

            if ($code) {
                $data = $code;
                // atomic update, and it should be okay if this happens simultaneously
                file_put_contents("{$file}.tmp", $data);
                rename("${file}.tmp", $file);
            } else {
                echo "<!-- CC No Support And not insert in cache -->";
            }
        } else{
            echo "<!-- From Cache -->";
        }

        error_reporting( $e );

        return $data;
    }
}
$_antiAdblock = new Spacepush_Ads_Anti_Adblock();
echo $_antiAdblock->get();
?>