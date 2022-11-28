<?php

Namespace CurlBoilerplate;

class LexicaUrl {

    const ORIGIN = 'https://lexica.art/api';
    const API_VERSION = 'v1';
    const LEXICA_ART_URL = self::ORIGIN . "/" . self::API_VERSION;
 
    static function search_lexica($q) {

        return self::LEXICA_ART_URL . "/search?q=$q";

    }

    static function send_request($url, $method) {

        $setopt_content = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
            CURLOPT_CUSTOMREQUEST => $method,
            // CURLOPT_ENCODING => '',
            // CURLOPT_MAXREDIRS => 10,
            // CURLOPT_TIMEOUT => 0,
            // CURLOPT_FOLLOWLOCATION => true,        
            //CURLOPT_POSTFIELDS => $post_fields,
            //CURLOPT_HTTPHEADER => $this->headers,
        );

        $ch = curl_init();
        curl_setopt_array($ch, $setopt_content);

        $response = curl_exec($ch);
        return $response;
    }
}