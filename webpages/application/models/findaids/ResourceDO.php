<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of ResourceDO
 * data object for a resource
 * a resource can have multiple notes, blibiography, personal names, subjects, components and created by
 * these are stored in arrays
 * @author kpersadsingh
 */
class ResourceDO {
 private $resourceId;
 private $resourceTitle;
 private $resourceIdentifier;
 private $dateExpression;
 private $displayRepository;
 private $extentDesc;
 private $languageCode;
 private $citationNote;
 private $resourceNotes = array();
 private $resourceNotesIter;
 private $resourceBilb = array();
 private $resourceBilbIter;
 private $resourcePersonalNames = array();
 private $resourcePersonalNamesIter;
 private $resourceSubject = array();
 private $resourceSubjectIter;
 private $resourceComponents = array();
 private $resourceComponentIter;
 private $resourceCreatedBy = array();
 private $resourceCreatedByIter;
         
 function __construct($resourceId, $resourceTitle, $resourceIdentifier, $dateExpression, $displayRepository, $extentDesc, $languageCode, $citationNote) {
  $this->resourceId = $resourceId;
  $this->resourceTitle = $resourceTitle;
  $this->resourceIdentifier = $resourceIdentifier;
  $this->dateExpression = $dateExpression;
  $this->displayRepository = $displayRepository;
  $this->extentDesc = $extentDesc;
  $this->languageCode = $languageCode;
  $this->citationNote = $citationNote;
  $this->resourceBilbIter = 0;
  $this->resourceNotesIter = 0;
  $this->resourcePersonalNamesIter = 0;
  $this->resourceSubjectIter = 0;
  $this->resourceComponentIter = 0;
  $this->resourceCreatedByIter = 0;
 }
 
 //getters and setters
 public function getResourceId() {
  return $this->resourceId;
 }

 public function getResourceTitle() {
  return $this->resourceTitle;
 }

 public function getResourceIdentifier() {
  return $this->resourceIdentifier;
 }

 public function getDateExpression() {
  return $this->dateExpression;
 }

 public function getDisplayRepository() {
  return $this->displayRepository;
 }

 public function getExtentDesc() {
  return $this->extentDesc;
 }

 public function getLanguageCode() {
  return $this->languageCode;
 }

 public function getCitationNote() {
  return $this->citationNote;
 }
 
 public function getResourceNotes() {
  return $this->resourceNotes;
 }
 
 public function getNumResourceNotes() {
  return $this->resourceNotesIter;
 }

 public function getResourceBilb() {
  return $this->resourceBilb;
 }
 
 public function getNumResourceBlib() {
  return $this->resourceBilbIter;
 }

 public function getResourcePersonalNames() {
  return $this->resourcePersonalNames;
 }
 
 public function getNumResourcePersonalNames() {
  return $this->resourcePersonalNamesIter;
 }

 public function getResourceSubject() {
  return $this->resourceSubject;
 }
 
 public function getNumResourceSubjects() {
  return $this->resourceSubjectIter;
 }

 public function getResourceComponents() {
  return $this->resourceComponents;
 }
 
 public function getNumResourceComponents() {
  return $this->resourceComponentIter;
 }
 
 public function getResourceCreatedBy() {
  return $this->resourceCreatedBy;
 }
 
 public function getNumCreator() {
  return $this->resourceCreatedByIter;
 }

 public function setResourceId($resourceId) {
  $this->resourceId = $resourceId;
 }

 public function setResourceTitle($resourceTitle) {
  $this->resourceTitle = $resourceTitle;
 }

 public function setResourceIdentifier($resourceIdentifier) {
  $this->resourceIdentifier = $resourceIdentifier;
 }

 public function setDateExpression($dateExpression) {
  $this->dateExpression = $dateExpression;
 }

 public function setDisplayRepository($displayRepository) {
  $this->displayRepository = $displayRepository;
 }

 public function setExtentDesc($extentDesc) {
  $this->extentDesc = $extentDesc;
 }

 public function setLanguageCode($languageCode) {
  $this->languageCode = $languageCode;
 }

 public function setCitationNote($citationNote) {
  $this->citationNote = $citationNote;
 }
 
 //add to array functions
 //add notes
 public function addNote(NotesDO $note) {
  $this->resourceNotes[$this->resourceNotesIter] = $note;
  $this->resourceNotesIter++;
 }
 
 //add blibiogrpahy
 public function addBibliography(BibliographicDO $bibl) {
  $this->resourceBilb[$this->resourceBilbIter] = $bibl;
  $this->resourceBilbIter++;
 }
 
 //add personal names
 public function addPersonalName(PersonalNamesDO $pers) {
  $this->resourcePersonalNames[$this->resourcePersonalNamesIter] = $pers;
  $this->resourcePersonalNamesIter++;
 }
 
 //add subject
 public function addSubject(SubjectDO $subj) {
  $this->resourceSubject[$this->resourceSubjectIter] = $subj;
  $this->resourceSubjectIter++;
 }
 
 //add components
 public function addComponent(ResourceComponentDO $comp) {
  $this->resourceComponents[$this->resourceComponentIter] = $comp;
  $this->resourceComponentIter++;
 }
 
 //add creators
 public function addCreator(ItemCreatorDO $creator) {
  $this->resourceCreatedBy[$this->resourceCreatedByIter] = $creator;
  $this->resourceCreatedByIter++;
 }
}

/* End of file ResourceDO.php */
/* Location: /ResourceDO.php */