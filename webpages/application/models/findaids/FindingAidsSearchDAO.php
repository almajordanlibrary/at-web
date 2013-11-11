<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of FindingAidsSearchDAO
 * This class performs the search on the database based on the user input
 * 
 * @author kpersadsingh
 */
require_once 'FindingAidsModel.php';

class FindingAidsSearchDAO extends FindingAidsModel {
 function __construct() {
  // Call the parent constructor
  parent::__construct();
 }
 
 function getSearchParamDO() {
  return new SearchParamsDO('');
 }
 
 //function to remove stop words as well as the special regex characters 
 //@access public
 //@param string [$pStr] - the string to sanitise
 //@return string - the sanitsed string
 private function replaceChars($pStr) {
  $chars = array(" from "," or "," and "," an "," if "," this "," that ","%"," the "," a ","-","*","+","?","|","(",")","^","$","{","}","[","]");
  return str_replace($chars, " ", $pStr);
 }
 
 //search function
 //
 //@access public
 //@param SearchParamsDO [$vParams] - the search string entered by the user
 //@return array of SearchResultsDO
 public function doSearch(SearchParamsDO $vParams) {
  $results = array();
  //stored procedure requires the search string to be broken up into individual words and passed in as a comma delimited list
  //remove the stop words
  $words = explode(' ',$this->replaceChars(' '.$vParams->getVSearchStr().' '));
  $sanitisedStr = '';
  $delim = '';
  for ($i = 0; $i < count($words); $i++) {
   if (strlen(trim($words[$i])) > 0) {
    $sanitisedStr = $sanitisedStr.$delim.trim($words[$i]);
    $delim = ',';
   }
  }
  if (strlen($sanitisedStr) > 0) {
   //call the stored procedure
   $sql = 'call searchFindingAids('.$this->spcoldb->escape($sanitisedStr).')';
   $rs = $this->spcoldb->query($sql);
   $i = 0;
   if ($rs->num_rows() > 0) {
    foreach ($rs->result() as $row) {
	 //stored procedure returns resourceId, resourceTitle, resourceIdentifier, componentId, componentTitle, componentInstance and dateExpression
	 //create a SearchResultsDO object with these and add it to the results array
     $results[$i] = new SearchResultsDO($row->resourceId, $row->resourceTitle, $row->resourceIdentifier, $row->componentId, $row->componentTitle, $row->componentInstance, $row->dateExpression);
     $i = $i + 1;
    }
   }
   //$rs->next_result();
   $rs->free_result();
  }
  
  return $results;
 }
}

/* End of file FindingAidsSearchDAO.php */
/* Location: /FindingAidsSearchDAO.php */