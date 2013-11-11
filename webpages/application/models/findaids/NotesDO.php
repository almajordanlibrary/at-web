<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of NotesDO
 * data object for notes
 *
 * @author kpersadsingh
 */
class NotesDO {
 private $notesEtcLabel;
 private $noteContent;
 
 function __construct($notesEtcLabel, $noteContent) {
  $this->notesEtcLabel = $notesEtcLabel;
  $this->noteContent = $noteContent;
 }
 
 //getters and setters
 public function getNotesEtcLabel() {
  return $this->notesEtcLabel;
 }

 public function getNoteContent() {
  return $this->noteContent;
 }

 public function setNotesEtcLabel($notesEtcLabel) {
  $this->notesEtcLabel = $notesEtcLabel;
 }

 public function setNoteContent($noteContent) {
  $this->noteContent = $noteContent;
 }
}

/* End of file NotesDO.php */
/* Location: /NotesDO.php */