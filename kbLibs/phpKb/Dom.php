<?php

namespace phpKb;
require_once __DIR__ . "/simple_dom.php";

use phpKb\Curl;


class Dom {

    public $_dom = null;

    public function __construct($url_or_html = "")
    {
        if($url_or_html!="") {
            $first4 = strtolower(substr($url_or_html,0,4));
            if($first4 === "http") {
                $cURL = new Curl();
                $url = $url_or_html;
                $url_or_html = $cURL->get($url);
            }
            $this->_dom = str_get_html($url_or_html);
        }
    }

    public function getDom() {
        if(!$this->_dom || is_null($this->_dom)) {
            return false;
        }
        return $this->_dom;
        // trigger
        return str_get_html("");
    }

    public function getElement($search) {
        if(!$this->isLoaded()) {
            return false;
        }

       $result = $this->getDom()->find($search, 0);
       return !$result ? false : $result;
    }

    public function getElements($search) {
        if(!$this->isLoaded()) {
            return false;
        }

        $result = $this->getDom()->find($search);
        return !$result ? false : $result;
    }


    public function isLoaded() {
        return is_null($this->_dom) ? false : true;
    }

}