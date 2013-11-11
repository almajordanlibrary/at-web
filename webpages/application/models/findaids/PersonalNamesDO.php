<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of PersonalNamesDO
 * data object for personal names
 * 
 * @author kpersadsingh
 */
class PersonalNamesDO {
 private $personName;
 
 function __construct($personName) {
  $this->personName = $personName;
 }
 
 //getters and setters
 public function getPersonName() {
  return $this->personName;
 }
 
 public function setPersonName($personName) {
  $this->personName = $personName;
 }
}

/* End of file PersonalNamesDO.php */
/* Location: /PersonalNamesDO.php */