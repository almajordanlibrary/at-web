<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of SearchParamsDO
 * data object to store search parameters
 * @author kpersadsingh
 */
class SearchParamsDO {
 private $vSearchStr;
 
 function __construct($vSearchStr) {
  $this->vSearchStr = $vSearchStr;
 }
 
 //getter and setters
 public function getVSearchStr() {
  return $this->vSearchStr;
 }

 public function setVSearchStr($vSearchStr) {
  $this->vSearchStr = $vSearchStr;
 }
}

/* End of file SearchParamsDO.php */
/* Location: /SearchParamsDO.php */