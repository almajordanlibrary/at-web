<?php

if (!defined('BASEPATH'))
 exit('No direct script access allowed');

/**
 * Description of SearchResults
 * data object to store the search results
 * 
 * @author kpersadsingh
 */
class SearchResultsDO {
 private $resourceId;
 private $resourceTitle;
 private $resourceIdentifier;
 private $componentId;
 private $componentTitle;
 private $componentInstance;
 private $dateExpression;
 
 function __construct($resourceId, $resourceTitle, $resourceIdentifier, $componentId, $componentTitle, $componentInstance, $dateExpression) {
  $this->resourceId = $resourceId;
  $this->resourceTitle = $resourceTitle;
  $this->resourceIdentifier = $resourceIdentifier;
  $this->componentId = $componentId;
  $this->componentTitle = $componentTitle;
  $this->componentInstance = $componentInstance;
  $this->dateExpression = $dateExpression;
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

 public function setResourceId($resourceId) {
  $this->resourceId = $resourceId;
 }

 public function setResourceTitle($resourceTitle) {
  $this->resourceTitle = $resourceTitle;
 }

 public function setResourceIdentifier($resourceIdentifier) {
  $this->resourceIdentifier = $resourceIdentifier;
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

}

/* End of file SearchResults.php */
/* Location: /SearchResults.php */