<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of BibliographicDO
 * This class stores a single blibiographic record
 * @author kpersadsingh
 */
class BibliographicDO {
 private $blibData;
 
 function __construct($blibData) {
  $this->blibData = $blibData;
 }
 
 public function getBlibData() {
  return $this->blibData;
 }
 
 public function setBlibData($blibData) {
  $this->blibData = $blibData;
 }
}

/* End of file BibliographicDO.php */
/* Location: /BibliographicDO.php */