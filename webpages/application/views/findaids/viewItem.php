<div class="row">
 <h1><?php echo $page_title; ?></h1>
</div>
<div class="row">
 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <?php 
  if (isset($msg) && strlen($msg) > 0) { //display the message
   echo '<div class="alert alert-error">'.$msg.validation_errors().'</div>';
  }
  if (isset($resource)) {
  ?>
  <table class="table table-striped table-bordered">
   <tbody>
    <tr>
     <td>Title</td>
     <td><?php echo $resource->getResourceTitle();?></td>
    </tr>
    <tr>
     <td>Repository</td>
     <td><?php echo $resource->getDisplayRepository();?></td>
    </tr>
    <?php /* creator */
     if ($resource->getNumCreator() > 0) {
      echo '<tr><td>Creator</td><td>';
      $a = $resource->getResourceCreatedBy();
      for ($i = 0; $i < $resource->getNumCreator(); $i++) {
       echo '<p>'.$a[$i]->getCreatorName().'</p>';
      }
      echo '</td></tr>';
     }
    ?>
    <tr>
     <td>Identifier</td>
     <td><?php echo $resource->getResourceIdentifier();?></td>
    </tr>
    <tr>
     <td>Date</td>
     <td><?php echo $resource->getDateExpression();?></td>
    </tr>
    <tr>
     <td>Extent</td>
     <td><?php echo $resource->getExtentDesc();?></td>
    </tr>
    <tr>
     <td>Language</td>
     <td><?php echo $resource->getLanguageCode();?></td>
    </tr>
    <tr>
     <td>Preferred Citation note</td>
     <td><?php echo $resource->getCitationNote();?></td>
    </tr>
    <?php /* notes */
     $a = $resource->getResourceNotes();
     for ($i = 0; $i < $resource->getNumResourceNotes(); $i++) {
      echo '<tr><td colspan="2"><table class="table table-bordered"><thead><tr><th>'.$a[$i]->getNotesEtcLabel().'</th></tr></thead><tbody><tr><td>'.$a[$i]->getNoteContent().'</td></tr></tbody></table></td></tr>';
     }
     /* controlled access headings */
     if ($resource->getNumResourcePersonalNames() + $resource->getNumResourceSubjects() > 0)
     echo '<tr><td colspan="2"><table class="table table-bordered"><thead><tr><th>Controlled Access Headings</th></tr></thead><tbody>';
     $a = $resource->getResourcePersonalNames();
     if ($resource->getNumResourcePersonalNames() > 0) {
      echo '<tr><td><strong>Personal Name(s)</strong><ul>';
     }
     for ($i = 0; $i < $resource->getNumResourcePersonalNames(); $i++) {
      echo '<li>'.$a[$i]->getPersonName().'</li>';
     }
     if ($resource->getNumResourcePersonalNames() > 0) {
      echo '</ul></td></tr>';
     }
     $a = $resource->getResourceSubject();
     if ($resource->getNumResourceSubjects() > 0) {
      $prevHeading = $a[0]->getSubjectTermType();
      echo '<tr><td><strong>'.$prevHeading.'</strong><ul>';
      for ($i = 0; $i < $resource->getNumResourceSubjects(); $i++) {
       if (strcmp($a[$i]->getSubjectTermType(), $prevHeading) != 0) {
        $prevHeading = $a[$i]->getSubjectTermType();
        echo '</ul></td></tr><tr><td><strong>'.$prevHeading.'</strong><ul>';
       }
       echo '<li>'.$a[$i]->getSubjectTerm().'</li>';
      }
      echo '</ul></td></tr>';
     }
     echo '</tbody></table></td></tr>';
     /*Bibliography*/
     $a = $resource->getResourceBilb();
     if ($resource->getNumResourceBlib() > 0) {
      echo '<tr><td colspan="2"><table class="table table-bordered"><thead><tr><th>Bibliography</th></tr></thead><tbody><tr><td><ul>';
     }
     for ($i = 0; $i < $resource->getNumResourceBlib(); $i++) {
      echo '<li>'.$a[$i]->getBlibData().'</li>';
     }
     if ($resource->getNumResourceBlib() > 0) {
      echo '</ul></td></tr></tbody></table></td></tr>';
     }
     /* components */
     if ($resource->getNumResourceComponents() > 0) {
      $a = $resource->getResourceComponents();
      $s = '';
      echo '<tr><td colspan="2"><table class="table table-bordered"><thead><tr><th>Collection Inventory</th></tr></thead><tbody><tr><td><table class="table table-bordered"><tbody>';
      for ($i = 0; $i < $resource->getNumResourceComponents(); $i++) {
       if (stripos($a[$i]->getCompOrder(),'_') === FALSE) { /* new component set */
        $s1 = '<strong>';
        $s2 = '</strong>';
        $s = '</tbody></table><table class="table table-bordered"><tbody>';
       }
       echo $s.'<tr><td><p>'.$s1.$a[$i]->getComponentTitle().' '.$a[$i]->getDateExpression().$s2.'</p>';
       /* notes */
       if ($a[$i]->getNumComponentNotes() > 0) {
        $b = $a[$i]->getComponentNotes();
        for ($j = 0; $j < $a[$i]->getNumComponentNotes(); $j++) {
         echo '<p><strong>'.$b[$j]->getNotesEtcLabel().'</strong></p><p>'.$b[$j]->getNoteContent().'</p>';
        }
       }
       /* controlled access headings */
       if ($a[$i]->getNumComponentPersonalNames() + $a[$i]->getNumComponentSubject() > 0) {
        echo '<p><strong>Controlled Access Headings</strong></p>';
        $b = $a[$i]->getComponentPersonalNames();
        if ($a[$i]->getNumComponentPersonalNames() > 0) {
         echo '<p>Personal Name(s)</p><ul>';
        }
        for ($j = 0; $j < $a[$i]->getNumComponentPersonalNames(); $j++) {
         echo '<li>'.$b[$j]->getPersonName().'</li>';
        }
        if ($a[$i]->getNumComponentPersonalNames() > 0) {
         echo '</ul>';
        }
        $b = $a[$i]->getComponentSubject();
        if ($a[$i]->getNumComponentSubject() > 0) {
         echo '<p>Personal Name(s)</p><ul>';
        }
        for ($j = 0; $j < $a[$i]->getNumComponentSubject(); $j++) {
         echo '<li>'.$b[$j]->getSubjectTerm().'</li>';
        }
        if ($a[$i]->getNumComponentSubject() > 0) {
         echo '</ul>';
        }
       }
       /*Bibliography*/
       if ($a[$i]->getNumComponentBlib() > 0) {
        echo '<p><strong>Bibliography</strong></p><ul>';
        $b = $a[$i]->getComponentBilb();
        for ($j = 0; $j < $a[$i]->getNumComponentBlib(); $j++) {
         echo '<li>'.$b[$j]->getBlibData().'</li>';
        }
        echo '</ul>';
       }
       echo '</td><td width="15%">'.$s1.$a[$i]->getComponentInstance().$s2.'</td></tr>';
       $s1 = '';
       $s2 = '';
       $s = '';
      }
      echo '</tbody></table></td></tr></tbody></table></td></tr>';
     }
    ?>
    
   </tbody>
  </table>
  <?php } ?>
 </div>
</div>
