<?php


class WPS_Stream {

    public static function send($file)
    {
        if( !file_exists($file) )
            return false;

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('CacheHelper-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));

        ignore_user_abort(true);

        $myInputStream = fopen($file, 'rb');
        $myOutputStream = fopen('php://output', 'wb');

        stream_copy_to_stream($myInputStream, $myOutputStream);

        fclose($myOutputStream);
        fclose($myInputStream);

        return true;
    }
}


function wp_stream($file){
    return WPS_Stream::send($file);
}
