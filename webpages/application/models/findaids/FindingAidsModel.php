<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of FindingAidsModel
 * parent of the DAO classes and includes the DO classes to be used
 * intialises the database object
 * 
 * @author kpersadsingh
 */

include 'SearchParamsDO.php';
include 'SearchResultsDO.php';
include 'BibliographicDO.php';
include 'NotesDO.php';
include 'PersonalNamesDO.php';
include 'SubjectDO.php';
include 'ItemCreatorDO.php';
include 'ResourceComponentDO.php';
include 'ResourceDO.php';


class FindingAidsModel extends CI_Model {
 protected $spcoldb;
  
  function __construct() {
   // Call the Model constructon
   parent::__construct();
   $this->spcoldb = $this->load->database('spcol', TRUE);
  }
}

/* End of file FindingAidsModel.php */
/* Location: /FindingAidsModel.php */