<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of FindingAidsSearch
 * controller for the search
 * 
 * @author kpersadsingh
 */

class FindingAidsSearch extends CI_Controller {
 function __construct(){
  parent::__construct();
  $this->load->model('findaids/FindingAidsSearchDAO');
 }
 
 //display function for the view pages
 //the view is broken up into header, a page fragment for the data and footer
 //@access public
 //@param string[$page] - the name of the view file to include between the header and footer
 //@param array[$data] - an associative array containing the data to be displayed to the user
 function display($page, $data) {
  if (!isset($data['page_title'])) {
   $data['page_title'] = PAGE_TITLE;
  }
  $data['html_title'] = HTML_TITLE;
  
  $this->load->view('header',$data);
  $this->load->view($page, $data);
  $this->load->view('footer', $data);
 }
 
 //function to display the search form
 //@access private
 //@param array[$data] - the data to be displayed to the user
 //@param SearchParamsDO[$params] - the search parameters entered by the user
 private function showSearchForm($data, $params) {
  $data['searchStr'] = $params->getVSearchStr();
  $this->display('findaids/search', $data);
 }
 
 //function to perform the search if $doSearch is set to 1
 //@access private
 //@param SearchParamsDO[$params] - the search parameters entered by the user
 //@param int $doSearch - flag to say if to do search, 0 means do not perform search, 1 means perform search
 private function searchForm($params, $doSearch) {
  $data['page_title'] = PAGE_TITLE;
  
  if ($doSearch == 1) {
   $data['results'] = $this->FindingAidsSearchDAO->doSearch($params);
  }
  
  $this->showSearchForm($data, $params);
 }
 
 //default function
 //display the search form with empty search parameters
 function index() {
  $params = $this->FindingAidsSearchDAO->getSearchParamDO();
  $this->searchForm($params, 0);
 }
 
 //search form would post to this function
 //validate the input and call searchForm with the search parameters
 //the search is performed if the length of the search string is greater than zero
 function searchResults() {
  $this->load->library('form_validation');
  $this->form_validation->set_rules('searchStr','Search','trim|xss_clean');
  $this->form_validation->run();
  $params = $this->FindingAidsSearchDAO->getSearchParamDO();
  if (strlen(set_value('searchStr')) > 0) {
   $params->setVSearchStr(set_value('searchStr'));
   $this->searchForm($params, 1);
  }
  else {
   $this->searchForm($params, 0);
  }
 }
 
 //the search results page would have links to this function
 //the resource id would be passed to it
 //the function validates that the resource id is valid (numeric and greater than 0) and then displays that resource
 function viewItem($pResourceId = null) {
  if (isset($pResourceId) && is_numeric($pResourceId) && ($pResourceId > 0)) {
   $this->load->model('findaids/FindingAidsItemDAO');
   $data['resource'] = $this->FindingAidsItemDAO->getItem($pResourceId); //get the resource data from the database
  }
  else {
   $data['msg'] = 'Resource not found';
  }
  
  $this->display('findaids/viewItem', $data);
 }
}

/* End of file FindingAidsSearch.php */
/* Location: /FindingAidsSearch.php */