<?php

$SessionParentChildId = array();
  if(!empty($this->request->data['groupcode_search'])){
    $groupCode = $this->request->data['groupcode_search'];
  }
  $group_code_user_id = substr($group_code_user_id,0,-1);
    $SessionParentChildId = array_unique(explode(",",$group_code_user_id));

     $is_admin = $this->Session->read('isAdmin');
 if( $is_admin)
 {
    $class_disabled_admin = 'disabled ';
    $class_disabled = '';
    $class_style = '';
 }
 else
 {
      $class_disabled = 'disabled ';
      $class_style = 'style ="display:none;"';
        $class_disabled_admin ='';
 }
 if($fetch_type != 'loadmore') { ?>
<div class="tab-pane active" id="<?php echo $tab_name; ?>" >
     <?php  if(!empty($advice_data)) {  ?>
<button type="button" name="advice" class="btn search-bar-button1 delete-advice del_advice_list" >Delete</button> 
     <?php } ?>
   <!--  <table class="table table-striped table-condensed my_challenge showroom-table"> -->
    <table class="table table-striped advices-table table-condensed advice_management admin-advice remove-scroll" >
      
      <thead>
       <tr>
         <th><input  type="checkbox" class="check_all" name="" value="" ></th> 
                <th>Sub-Category</th> 
                <th>Date</th>
                <th>Posted By</th>
                <th>Title</th>                                   
                <th>Rating</th>
                 <th>Actions</th>
             </tr>
      </thead> 
      <?php if(!empty($advice_data)) { ?>     
      <tbody>
          <?php foreach($advice_data as $rec) {

                                              echo   $this->element('listing_advice_element',array('rec'=>$rec,'SessionParentChildId'=>$SessionParentChildId,'class_disabled'=>$class_disabled,'class_style'=>$class_style));
                                                  ?>
                                        <?php } ?>
      </tbody>
      <?php } else {  ?>
       <tr><td colspan= '7' style = "background-color:#f2f2f2; text-align:center;" class="no-record">No records found.</td></tr>
      <?php } ?>
    </table>

    <?php if($total>10){?>
                    <div class="margin-bottom clearfix load-more-btn" id="loadmorebtn">
                        <button class="btn btn-orange-small margin-top-small large right" onclick="loadmoredata();">Load More</button>
                    </div>
                    <?php }?>
</div>
 <?php } else {?>  
  <?php foreach($advice_data as $rec) {

                                      echo   $this->element('listing_advice_element',array('rec'=>$rec,'SessionParentChildId'=>$SessionParentChildId,'class_disabled'=>$class_disabled,'class_style'=>$class_style));
 //echo $this->element('edit_advice_elements', array('adviceData'=>$rec)); ?>
                                        <?php } ?>
<?php } ?>
<?php echo '#$$#'.$total;?>