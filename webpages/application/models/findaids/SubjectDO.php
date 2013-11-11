<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of SubjectDO
 * data object for subject terms
 * 
 * @author kpersadsingh
 */
class SubjectDO {
 private $subjectTermType;
 private $subjectTerm;
 
 function __construct($subjectTermType, $subjectTerm) {
  $this->subjectTermType = $subjectTermType;
  $this->subjectTerm = $subjectTerm;
 }
 
 //getters and setters
 public function getSubjectTermType() {
  return $this->subjectTermType;
 }

 public function getSubjectTerm() {
  return $this->subjectTerm;
 }
 
 public function setSubjectTermType($subjectTermType) {
  $this->subjectTermType = $subjectTermType;
 }

 public function setSubjectTerm($subjectTerm) {
  $this->subjectTerm = $subjectTerm;
 }
}

/* End of file SubjectDO.php */
/* Location: /SubjectDO.php */