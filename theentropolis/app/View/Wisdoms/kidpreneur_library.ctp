<div class="col-md-10 content-wraaper">
  <div class="sage-dash-wrap full-wrap">
    <div class="title dashboard-title fixed-ipad-top">
        <h1 style="text-transform:uppercase;margin-top:0px !important"><?php echo $title_for_layout; ?></h1>
    </div>
    <div class="home-display row">
        <div class="col-md-12">
           <div class="title_text">
           <p> 
           Our archive of Sage wisdom collected and curated from online publications, blogs, business information portals and other reputable sources of entrepreneurship inspiration and advice around the web. We have done our research to find the best of the best in the area of entrepreneurship and provide easy searchability and direct connection to the original source right from your dashboard.
           </p>
           </div>
           
            <div class="search-bar clearfix black-btn-wrap margin_top_15">
                <div class="row">
                    <div class="col-md-8">
                        <?php echo $this->Form->create('Search', array('url'=>'#','id'=>'SearchFrm','class'=>"form-inline",'role'=>'form'));?>      
                        <div class="form-group new-form-group">
                            <?php echo $this->Form->input('search_keyword_search', array('class'=>'form-control','id'=>'search_keyword_search', 'placeholder'=>"Search Advice", 'style'=>'width:64%; margin-right:5px; float: left', 'label'=>false, 'div'=>false));?> 
                          <button type="button" class="btn search-bar-button1"  onclick="search();">Go</button>        
                        </div>
                        
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="right add-hindsight ">
                            <ul>
                                <li><a class="advanced-search"><i class="fa fa-search"></i> Advanced Search</a></li>
                                
                            </ul>
                            <div class="advanced-search-form arrow_box-a" style="display: none;">
                                <p>Advanced Search <span  style = 'float:right;'><i class="icons close-icon closeAdvanceSearch"></i></span></p>
                                <?php echo $this->Form->create('AdvanceSearch', array('url'=>'#','id'=>'AdvanceSearchFrm','role'=>'form'));?>    
                                <div class="form-group">
                                    <?php echo $this->Form->input('advance_search_decision_type_id', array('options'=>$decision_types_new,'id'=>'advance_search_decision_type_id', 'class'=>'form-control disabled', 'label'=>false));?>        
                                </div>
                                <div class="form-group add-category" style = "display:none">
                                    <?php echo $this->Form->input('advance_search_category_id', array('options'=>array(''=>'Sub-Category'), 'id'=>'advance_search_category_id','class'=>'form-control disabled', 'label'=>false));?>  
                                </div>
                                <div class="form-group">
                                    <?php echo $this->Form->input('advance_search_keyword_search', array('class'=>'form-control','id'=>'advance_search_keyword_search','title'=>'Alphanumeric or special characters only', 'placeholder'=>"Keyword Search", 'label'=>false));?> 
                                </div>
                                <button type="button" class="btn btn-black  right closeAdvanceSearch">Cancel</button>
                                <button type="submit" class="btn btn-black margin-right right">Submit</button>
                                <?php echo $this->Form->end();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
       
          echo $this->element('advice_all_modal_element');
          echo $this->element('blog_js_element');

        ?>
        <div class="appendHere">
        
          <div class="hindsight-tab my_decisionbank">
          
            <div class="categories-wrapper clearfix">
                <div class="cat-left-col remove-purpel">
                    <h3>Category</h3>
                    <ul class="nav nav-tabs tabs  setting-tab ">
               <!--    <?php foreach($decision_types as $key => $tab_name) { ?> 
                 
               <?php if(strtoupper($tab_name)==strtoupper('Category')) { 
				  			$selectedDecisionType == '' ? ($class = 'active') : ($class = '');
				  			?>
                        
                            <li data-id="0" class="<?php echo $class;?> decision-type-lists"><a href="#all-hindsights" data-toggle="tab" id="all-hindsights-tab" onclick="getListData('0', '0', 'tab');">All</a></li>
                  <?php } 
						else { 
						    $selectedDecisionType == $key ? ($class = 'active') : ($class = '');
						    $tabname = str_replace(array(' ',',','&'),'',$tab_name);?>
                            <li data-id="<?php echo $key ?>" class="<?php echo $class;?> decision-type-lists"><a href="#<?php echo $tabname ?>" data-toggle="tab" id="<?php echo $tabname ?>-tab" onclick="getListData('<?php echo $key ?>', '0', 'tab');"><?php echo $tab_name; ?></a></li>
                        
                        <?php } ?>
                        
                 <?php } ?>    --> 
            <!--      <li data-id="0" class="<?php echo $class;?> decision-type-lists custom_scroll admintCatgyHT"><a href="#all-hindsights" data-toggle="tab" id="all-hindsights-tab" onclick="getListData('0', '0', 'tab');"></a></li> -->
                    </ul>
                </div>
                <div class="cat-right-col">
                    <div class="tab-content-hindsight">
                        <?php foreach($decision_types as $tab_name) { ?> 
                        <?php if(strtoupper($tab_name)==strtoupper('Category')) { ?>   
                        <div class="tab-pane active" id="all-hindsights">
                            <table class="table table-striped table-condensed remove-purpel my_decisionbank my_challenge kidpreneur_libraray tableHT ">
                                <?php 
                                    if(!empty($publication_data)) { ?>   
                                <thead>
                                    <tr >
                                    	<th class="mwidth-hindsight" style="width:9%;">Sub-Category</th>
                                        <th>Name</th>
                                        <th>Date</th>                                       
                                        
                                        <th>Title</th>
                                        <th>Link</th>                                     
                                    </tr>
                                </thead>
                                <tbody class="AdminTableHT">
                                    <?php foreach($publication_data as $rec) { ?>
                                    <tr>
                                    	<td> </td>
                                        <td><?php echo $rec['Publication']['author_first'].' '.ucfirst(htmlspecialchars($rec['Publication']['author_last'])) ;?></td>
                                        <td><?php echo $rec['Publication']['date_published']!= '0000-00-00' ?  date('j M Y', strtotime($rec['Publication']['date_published'])) : '';?></td>      
                                        
                                        <td class="p-5" title ="<?php  echo $rec['Publication']['source_name'];?>"><?php if(strlen($rec['Publication']['source_name']) > 25){ 
                      echo substr($rec['Publication']['source_name'], 0, 25).'..';
                    }
                    else{  
                      echo $rec['Publication']['source_name']; 
                    } ?></td>
                                        <td><a href="<?php echo $rec['Publication']['rss_feed'];?>" target="_blank">
							   <?php if(strlen($rec['Publication']['rss_feed']) > 25){ 
										  echo substr($rec['Publication']['rss_feed'], 0, 25).'..';
									  }
									  else{  
										  echo $rec['Publication']['rss_feed']; 
									  } ?></a></td>                                     
                                    </tr>
                                    <?php } ?>
                                    
                                </tbody>
                                <?php } else { ?>
                                <tr>
                                    <td style="background-color: #f7f7f7; text-align: center;" class="no-record"
>No records found.</td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                        <?php } } ?>  
                    </div>
                   
                   <div class="search-category-id" style="display:none"></div>
                   <div class="search-keyword" style="display:none"></div>
                  
                    <?php 
					
					if($total>10){?>
                    <div class="clearfix " id="loadmorebtn">
                        <div class="per-page-limit-data" style="display:none"><?php echo $perPageLimit;?></div>
                          <button class="btn  btn-orange-small margin-top-small large right" onclick="loadmoredata();">Load More</button>                 
                    </div>
                    <?php }?>
                </div>
            </div> <!-- categories-wrapper ends -->
          
        </div>
          
          
        </div>   
    </div>
  </div>
</div>



<script>
    $('body').on('change','#decision_type_id' , function(){
    jQuery(".category").show();
       $.ajax({
           url:'<?php echo $this->webroot?>DecisionBanks/decision_category/',
           data:{
             id:this.value
           },
           type:'get',
           success:function(data){ 
               $('#category_id').html(data);
           }
           
       });
    });
    
    $('body').on('change','#advance_search_decision_type_id' , function(){
     jQuery('.add-category').show();
       $.ajax({
           url:'<?php echo $this->webroot?>DecisionBanks/decision_category/',
           data:{
             id:this.value,
             advance_search :'advance_search'
           },
           type:'get',
           success:function(data){ 
               $('#advance_search_category_id').html(data);
           }
           
       });
    });
    
	$(document).ready(function(){
		$('.decision-type-lists').click(function(){
			// To put data into hidden field
			$('.search-category-id').text('');
			$('.search-keyword').text('');
	    });

   


    });
    

    function getListData(decision_type_id, offset, type, category_id, keyword_search)
    { 
      $('.loading').show();  
	  // To get per page limit data
	  var perPageLimit = $('.per-page-limit-data').text().trim();
      /*if(type == 'tab' || type == 'advance_search')
      {    
           $('#active_tab_name').val(tabname);
           $('#active_tab_id').val(decision_type_id);
           
           if(type == 'tab')
           {    
               $('#active_category_id').val('');
               $('#active_keyword_search').val('');
           }
      }*/
      
      if (typeof keyword_search == "undefined") {
          $("#search_keyword_search").val('');
      }
      jQuery.ajax({
           url:'<?php echo $this->webroot?>wisdoms/kidLibraryList/',
           data:{
             'start':offset,  
             'decision_type_id':decision_type_id,  
			 'type':type,
			 'category_id':category_id,
			 'keyword_search':keyword_search, 
           },
           type:'POST',
		   dataType: 'html',
           success:function(data){
               $('.loading').hide();  
               var dataarr = data.split('#$$#');
			  		   
			   if(type == 'loadmore'){
			       $(""+dataarr[0]+"").appendTo('.kidpreneur_libraray');             
               //jQuery('.tab-content-hindsight').html(dataarr[0]);       
			       
			   }
			   else{


				   $('.kidpreneur_libraray').find('tbody tr').remove();
				   // To hide thead from table in case of "no record found"
				   if($(dataarr[0]).hasClass('no-record')){
					   //$('.kidpreneur_libraray').find('thead').hide();
				   }
				   else{
					   $('.kidpreneur_libraray').find('thead').show();
				  }
				   
				   $('.kidpreneur_libraray').find('tbody').append(dataarr[0]);
				            
			   }
			    var outputLen = $('.numData').text().trim();
			   //console.log(outputLen+'>>'+perPageLimit);
			   
			   if( parseInt(outputLen) < parseInt(perPageLimit)){
				   //console.log('hello');
			       //$('.load-more-btn').prop('disabled', true);   
				   $('#loadmorebtn').hide(); 
           
			   }
			   else{
				   //$('.load-more-btn').prop('disabled', false);   
				   $('#loadmorebtn').show();   
			   }
             // categoryHT();
                
                       // var colHIG = $('.cat-right-col').height();
                       // $('.cat-left-col').css({minHeight: colHIG});
			   
			   // To remove numData
	           $('.table-condensed').find('.numberRecord').remove();
               
           }
           
       });
    }
    
    function loadmoredata() { 
	    var wrap_tab = $('.categories-wrapper');
       //alert("tab name=> "+$('#active_tab_name').val() + "tab id=> "+$('#active_tab_id').val());
       var tabname = $('#active_tab_name').val();
       var decision_type_id = wrap_tab.find('.active').data('id');
	   
	   
       var rowCount = $('.table-condensed >tbody >tr').length;
	   if(rowCount > 0){
		   //rowCount = parseInt(rowCount-1);   
		}
	   	   	   
	   var category_id = $('.search-category-id').text();
	   var keyword_search =  $('.search-keyword').text();
	   
       getListData(decision_type_id, rowCount, 'loadmore', category_id, keyword_search);
	   
    }
    
    function advanceSearch()
    {
        if($('#advance_search_keyword_search').val()=='') {
            bootbox.alert("Keyword search cannot be left blank.");
            return;
        }
        //$('#advance_search_keyword_search').val('');
        var tabname = $("#advance_search_decision_type_id option:selected").text();
        var tabname = tabname.replace(/\s/g, '');
        var decision_type_id = $('#advance_search_decision_type_id').val();
        var category_id = $('#advance_search_category_id').val();
        var keyword_search = $('#advance_search_keyword_search').val();
        $('#active_tab_name').val(tabname);
        $('#active_tab_id').val(decision_type_id);
        $('#active_category_id').val(category_id);
        $('#active_keyword_search').val(keyword_search);
		
		// To put data into hidden field
	    $('.search-category-id').text(category_id);
		$('.search-keyword').text(keyword_search);
		//alert(category_id);
		
        getListData(decision_type_id, 0, 'advance_search', category_id, keyword_search);
        
		$('ul.tabs li').removeClass('active');
        $('#'+tabname+'-tab').closest('li').addClass('active');
        $('.advanced-search-form').hide();
        //alert(tabname + ' => ' + decision_type_id + ' => ' + category_id + '==> ' + keyword_search);
        // $('#advance_search_keyword_search').val('');
    }
	
    
    $('#AdvanceSearchFrm').bind("submit", function(e) {        
       e.preventDefault();
	   
       advanceSearch();
       return false;
    
    });
	
    function search()
    {   
        var keyword_search = $('#search_keyword_search').val();
        var tabname = $('ul.tabs li.active a').attr('id'); 
        var tabname = $('#active_tab_name').val();
		var wrap_tab = $('.categories-wrapper');
		var decision_type_id = wrap_tab.find('.active').data('id');
        //var decision_type_id = $('#active_tab_id').val();
        $('#active_category_id').val('');
        $('#active_keyword_search').val(keyword_search);
		// To put data into hidden field
	    $('.search-category-id').text('0');
		$('.search-keyword').text(keyword_search);
		
		
        getListData(decision_type_id, 0, 'search', 0, keyword_search);
    
    }
	
     $('#SearchFrm').bind("submit", function(e) {     
       e.preventDefault();
       search();
       return false;
    
    });
    
    $('body').on('change','#user_id' , function(){
       $('#active_user_id').val(this.value);
    });
    $('#showmy').change(function(){
       var decision_type_id = $('#active_tab_id').val();
      if(this.checked){
        $('#user_id').val(<?php echo $this->Session->read('context_role_user_id');?>);
          $('#active_user_id').val(<?php echo $this->Session->read('context_role_user_id');?>);
          getListData('',decision_type_id,'search','tab');       
      }
      else{
          $('#user_id').val('');
          $('#active_user_id').val('');
           getListData('',decision_type_id,'search','tab');  
          
      }
    });

$(function() {
  //tableBodyHeight('#all-hindsights tbody');
  categorySectionHeight('.setting-tab', '#all-hindsights tbody')
})

//#sourceURL=ExpertAdvices\advice_list.ctpjs
</script>



<style>

.arrow_box-a:after, .arrow_box:before {
/*    border: medium solid rgba(0, 0, 0, 0);*/
    bottom: 100%;
    content: " ";
    height: 0;
    left: 81%;
    pointer-events: none;
    position: absolute;
    width: 0;
}

.advanced-search-form {
    background-color: #fff;
    border: 1px solid #ddd;
    border-top: none;
    display: none;
    padding: 10px 15px 15px;
    position: absolute;
    right: 5px;
    top: 40px;
    z-index: 2;
    width: 350px;
}
</style>
