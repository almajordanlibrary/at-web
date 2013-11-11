<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of FindingAidsItemDAO
 * This class queries the database to and returns a resourceDO object containing the resource, components, creators, notes, bibliography, personal names and subjects
 * for a resourceId. 
 * 
 * @author kpersadsingh
 */
require_once 'FindingAidsModel.php';

class FindingAidsItemDAO extends FindingAidsModel {
 function __construct() {
  // Call the parent constructor
  parent::__construct();
 }
 
 //function to get the resource, creator, notes, blibiography, personal names, subject and components for a resource id
 //@access public
 //@param int [$resourceId] - the resource id of the item to return
 //@result a ResourceDO object containing the resource data
 public function getItem($resourceId) {
  $resource = new ResourceDO(0, 'Resource Not Found', '', '', '', '', '', '', '');
  /* get the resource basic defination */
  $sql = 'call findingAidItem('.$resourceId.')';
  $rs = $this->spcoldb->query($sql);
  if ($rs->num_rows() > 0) {
   $row = $rs->row();
   $resource->setResourceId($row->resourceId);
   $resource->setResourceTitle($row->resourceTitle);
   $resource->setResourceIdentifier($row->resourceIdentifier);
   $resource->setDateExpression($row->dateExpression);
   $resource->setDisplayRepository($row->displayRepository);
   $resource->setExtentDesc($row->extentDesc);
   $resource->setLanguageCode($row->languageCode);
   $resource->setCitationNote($row->citationNote);
   
   $rs->next_result();
   $rs->free_result();
   
   /* get the creators */
   $sql = 'call finindAidItemCreator('.$resourceId.')';
   $rs1 = $this->spcoldb->query($sql);
   foreach ($rs1->result() as $row) {
    $resource->addCreator(new ItemCreatorDO($row->creator));
   }
   $rs1->next_result();
   $rs1->free_result();
   
   /* get the notes for the resource */
   $sql = 'call findingAidItemNotes('.$resourceId.')';
   $rs1 = $this->spcoldb->query($sql);
   foreach ($rs1->result() as $row) {
    $resource->addNote(new NotesDO($row->notesEtcLabel, $row->noteContent));
   }
   $rs1->next_result();
   $rs1->free_result();
   
   /* get the bibliography for the resource */
   $sql = 'call findingAidItemBibliography('.$resourceId.')';
   $rs1 = $this->spcoldb->query($sql);
   foreach ($rs1->result() as $row) {
    $resource->addBibliography(new BibliographicDO($row->itemValue));
   }
   $rs1->next_result();
   $rs1->free_result();
   
   /* get the personal names for the resource */
   $sql = 'call findingAidItemPersonalName('.$resourceId.')';
   $rs1 = $this->spcoldb->query($sql);
   foreach ($rs1->result() as $row) {
    $resource->addPersonalName(new PersonalNamesDO($row->personalName));
   }
   $rs1->next_result();
   $rs1->free_result();
   
   /* get the subjects for the resource */
   $sql = 'call findingAidItemSubjects('.$resourceId.')';
   $rs1 = $this->spcoldb->query($sql);
   foreach ($rs1->result() as $row) {
    $resource->addSubject(new SubjectDO($row->subjectTermType, $row->subjectTerm));
   }
   $rs1->next_result();
   $rs1->free_result();
   
   /* get the components for the resource */
   $compArray = array();
   $i = 0;
   $sql = 'call findingAidItemComponents('.$resourceId.')';
   $rs1 = $this->spcoldb->query($sql);
   foreach ($rs1->result() as $row) {
    $compArray[$i] = new ResourceComponentDO($row->componentId, $row->componentTitle, $row->componentInstance, $row->dateExpression, $row->compOrder);
    $i = $i + 1;
   }
   $rs1->next_result();
   $rs1->free_result();
   
   for ($i = 0; $i < count($compArray); $i++) {
    /* get the notes for the component */
    $sql = 'call findingAidItemComponentNotes('.$compArray[$i]->getComponentId().')';
    $rs2 = $this->spcoldb->query($sql);
    foreach ($rs2->result() as $row1) {
     $compArray[$i]->addComponentNote(new NotesDO($row1->notesEtcLabel, $row1->noteContent));
    }
    $rs2->next_result();
    $rs2->free_result();
    
    /* get the bibliography for the component */
    $sql = 'call findingAidItemComponentBibliography('.$compArray[$i]->getComponentId().')';
    $rs2 = $this->spcoldb->query($sql);
    foreach ($rs2->result() as $row1) {
     $compArray[$i]->addComponentBilbiography(new BibliographicDO($row1->itemValue));
    }
    $rs2->next_result();
    $rs2->free_result();
    
    /* get the personal names for the component */
    $sql = 'call findingAidItemComponentPersonalName('.$compArray[$i]->getComponentId().')';
    $rs2 = $this->spcoldb->query($sql);
    foreach ($rs2->result() as $row1) {
     $compArray[$i]->addComponentPersonalName(new PersonalNamesDO($compArray[$i]->getComponentId()));
    }
    $rs2->next_result();
    $rs2->free_result();
   
    /* get the subjects for the component */
    $sql = 'call findingAidItemComponentSubjects('.$compArray[$i]->getComponentId().')';
    $rs2 = $this->spcoldb->query($sql);
    foreach ($rs2->result() as $row1) {
     $compArray[$i]->addComponentSubject(new SubjectDO($row1->subjectTermType, $row1->subjectTerm));
    }
    $rs2->next_result();
    $rs2->free_result();
    
	/* add component to resource */
    $resource->addComponent($compArray[$i]);
   }
   $rs1->next_result();
   $rs1->free_result();
   
  }
  
  return $resource;
 }
}

/* End of file FindingAidsItemDAO.php */
/* Location: /FindingAidsItemDAO.php */