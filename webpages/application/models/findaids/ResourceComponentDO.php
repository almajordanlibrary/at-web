<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of ResourceComponentDO
 * data object for a resource component
 * the component can have multiple notes, blibliography, personal names and subjects
 * these are stored as arrays
 * 
 * @author kpersadsingh
 */
class ResourceComponentDO {
 private $componentId;
 private $componentTitle;
 private $componentInstance;
 private $dateExpression;
 private $compOrder;
 private $componentNotes = array();
 private $componentNotesIter;
 private $componentBilb = array();
 private $componentBilbIter;
 private $componentPersonalNames = array();
 private $componentPersonalNamesIter;
 private $componentSubject = array();
 private $componentSubjectIter;
 
 function __construct($componentId, $componentTitle, $componentInstance, $dateExpression, $compOrder) {
  $this->componentId = $componentId;
  $this->componentTitle = $componentTitle;
  $this->componentInstance = $componentInstance;
  $this->dateExpression = $dateExpression;
  $this->compOrder = $compOrder;
  $this->componentBilbIter = 0;
  $this->componentNotesIter = 0;
  $this->componentPersonalNamesIter = 0;
  $this->componentSubjectIter = 0;
 }
 
 //getters and setters
 public function getComponentId() {
  return $this->componentId;
 }

 public function getComponentTitle() {
  return $this->componentTitle;
 }

 public function getComponentInstance() {
  return $this->componentInstance;
 }

 public function getDateExpression() {
  return $this->dateExpression;
 }
 
 public function getCompOrder() {
  return $this->compOrder;
 }
 
 public function getComponentNotes() {
  return $this->componentNotes;
 }
 
 public function getNumComponentNotes() {
  return $this->componentNotesIter;
 }

 public function getComponentBilb() {
  return $this->componentBilb;
 }
 
 public function getNumComponentBlib() {
  return $this->componentBilbIter;
 }

 public function getComponentPersonalNames() {
  return $this->componentPersonalNames;
 }
 
 public function getNumComponentPersonalNames() {
  return $this->componentPersonalNamesIter;
 }

 public function getComponentSubject() {
  return $this->componentSubject;
 }
 
 public function getNumComponentSubject() {
  return $this->componentSubjectIter;
 }

 public function setComponentId($componentId) {
  $this->componentId = $componentId;
 }

 public function setComponentTitle($componentTitle) {
  $this->componentTitle = $componentTitle;
 }

 public function setComponentInstance($componentInstance) {
  $this->componentInstance = $componentInstance;
 }

 public function setDateExpression($dateExpression) {
  $this->dateExpression = $dateExpression;
 }
 
 public function setCompOrder($compOrder) {
  $this->compOrder = $compOrder;
 }
 
 //add to arrays
 //add component notes 
 public function addComponentNote(NotesDO $note) {
  $this->componentNotes[$this->componentNotesIter] = $note;
  $this->componentNotesIter++;
 }
 
 //add component blibiography
 public function addComponentBilbiography(BibliographicDO $bilb) {
  $this->componentBilb[$this->componentBilbIter] = $bilb;
  $this->componentBilbIter++;
 }
 
 //add personal names
 public function addComponentPersonalName(PersonalNamesDO $name) {
  $this->componentPersonalNames[$this->componentPersonalNamesIter] = $name;
  $this->componentPersonalNamesIter++;
 }
 
 //add subject
 public function addComponentSubject(SubjectDO $subj) {
  $this->componentSubject[$this->componentSubjectIter] = $subj;
  $this->componentSubjectIter++;
 }
}

/* End of file ResourceComponentDO.php */
/* Location: /ResourceComponentDO.php */