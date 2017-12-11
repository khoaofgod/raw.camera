<?php


namespace phpKb;

class Curl {

    function get($url) {
        // create curl resource
        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        /*
         * $encoded = substr($encoded, 0, strlen($encoded)-1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
         */
        // $output contains the output string
        $output = curl_exec($ch);
        // close curl resource to free up system resources

        curl_close($ch);
        return $output;
    }

    function safeHtml($html) {
    	return str_replace(array("<script","<SCRIPT"),"",$html);
    }

    function post() {

    }

    function put() {

    }

    function del() {

    }

}
