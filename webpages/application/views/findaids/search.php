<div class="row">
 <h1><?php echo $page_title; ?></h1>
</div>
<div class="row">
 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
 <?php
  $this->load->helper('form');   
  $attributes = array('class'=>'form-inline searchForm','role'=>'form');
  echo form_open('FindingAidsSearch/searchResults', $attributes);
 ?>
 <fieldset>
  <legend></legend>
  <div class="form-group">
   <label  class="sr-only" for="searchStr">Search Terms</label>
   <input type="text" name="searchStr" class="form-control" value="<?php echo $searchStr; ?>" size="50" placeholder="Search Terms"/>
  </div>
  <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Search</button>
 </fieldset>
 </form>
 </div>
</div>
<div class="row">
 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <?php 
   if (isset($results) && is_array($results)) {
    if (count($results) == 0) {
     echo '<div class="alert alert-info">No collections found</div>';
    }
    else {
     echo '<table class="table table-striped table-bordered">';
     echo '<thead><tr><th>Call Number</th><th>Collection Title</th><th>Component Title</th><th>Location</th><th>Date</th></tr></thead>';
     echo '<tbody>';
     for ($i = 0; $i < count($results); $i++) {
      echo '<tr><td>'.$results[$i]->getResourceIdentifier().'</td><td>'.anchor_popup('FindingAidsSearch/viewItem/'.$results[$i]->getResourceId(),$results[$i]->getResourceTitle()).'</td><td>'.$results[$i]->getComponentTitle().'</td><td>'.$results[$i]->getComponentInstance().'</td><td>'.$results[$i]->getDateExpression().'</td></tr>';
     }
     echo '</tbody></table>';
    }
   }
  ?>
 </div>
</div>
