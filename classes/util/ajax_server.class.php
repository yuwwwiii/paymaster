<?php

class clsAJAX_Server {

    function getNameArray($key = null) {
         $namelist = array(
           1 => array(
              'name'  => "name 1"
             ,'age'   => 20
           )
           ,2 => array(
              'name'  => "name 1"
             ,'age'   => 20
           )
         );

         return $namelist;
    }

    function sampleAjaxAutoSuggest($q = null) {
        return "1|sample,2|sample2";
    }

}

?>