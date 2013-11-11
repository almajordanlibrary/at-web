<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of ItemCreatorDO
 * data object for the item creator
 * 
 * @author kpersadsingh
 */
class ItemCreatorDO {
 private $creatorName;
 
 function __construct($creatorName) {
  $this->creatorName = $creatorName;
 }
 
 public function getCreatorName() {
  return $this->creatorName;
 }

 public function setCreatorName($creatorName) {
  $this->creatorName = $creatorName;
 }

}

/* End of file ItemCreatorDO.php */
/* Location: /ItemCreatorDO.php */