<div id="home" class="container">
            <div class="content-wrap">
                <div class="row margin-bottom">
                    <div class="col-md-9">
                        <div class="video-section">
                            <div class="row">
                                <div class="col-md-4"><span class="welcome">welcome</span></div>
                                <div class="col-md-8"><span class="video-title">TO THE ULTIMATE HIVE OF ENTREPRENEURIAL ACTIVITY&trade; </span></div>
                            </div>
                            <div class="video-para">
                                <p>Entropolis is a fully curated online ecosystem and powerful private business network for entrepreneurs, providing an enabling environment and fast access to a curated collection of vital resources to help build successful, fast growth businesses in the real world.</p>
                            </div>
                            <div  class="align-center video-icon">
                                    
                                    <?php echo $this->Html->image('video-icon.png');?>
                            </div>                      
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="side-wrap">
                            <div class="sidebar-panel align-center">
                                <a href="#">
                                    <h2>Total Population</h2>
                                   <!--  <img src="images/population.png" alt=""> -->
                                    <?php echo $this->Html->image('population.png');?>
                                    <h3><?php $numUser = $this->User->numUsers();
                        if($numUser >= 1000){
                            $numUser = $numUser/1000;
                            $numUser = $numUser.'K';
                        }
                        echo $numUser; ?></h3>
                                </a>
                            </div>
                            <div class="sidebar-panel align-center">
                                <a href="#">
                                    <h2>Seeker Hindsight</h2>
                             
                                     <?php echo $this->Html->image('seeker-hindsight.png');?>
                                    <h3><?php $numHindsight = $this->Hindsight->numHindsights();
                        
                        if($numHindsight >= 1000){
                            $numHindsight = $numHindsight/1000;
                            $numHindsight = $numHindsight.'K';
                        }
                        echo $numHindsight;
                        ?></h3>
                                </a>
                            </div>
                            <div class="sidebar-panel align-center">
                                <a href="#">
                                    <h2>Sage Advice</h2>
                                    <!-- <img src="images/sage-advice.png" alt=""> -->
                                    <?php echo $this->Html->image('sage-advice.png');?>
                                    <h3><?php $numAdvices = $this->Advice->numAdvices();
                        if($numAdvices >= 1000){
                            $numAdvices = $numAdvices/1000;
                            $numAdvices = $numAdvices.'K';
                        }
                        echo $numAdvices;
                        ?></h3>
                                </a>
                            </div>
                    
                        </div>
                    </div>
                </div>
                <div class="row margin-bottom">
                    <div class="col-md-3"> <a href="<?php echo Router::url(array('controller'=>'users', 'action'=>'register'));?>" class="btn btn-orange full">REGISTER | EMAIL</a>

                    </div>
                    <div class="col-md-3"><a href="<?php echo Router::url(array('controller'=>'users', 'action'=>'index'))?>" class="btn btn-blue full">REGISTER | LINKEDIN</a></div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>                
                </div>
                <div class="panel margin-bottom">
                    <div class="fieldset-detail">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Trailblazing</legend>
                           australian entrepreneurs &amp; entrepreneurship experts
                          </fieldset>   
                          <div class="fieldset-bottom">we need your help to pioneer this future colony for entrepreneurs </div>                 
                    </div>                  
                    <p class="align-center font-BrandonGrotesque">Become a Founding Entropolis Citizen now! <br>Let us profile the massive talent in our homegrown ecosystem and then take your awesomeness to the world!</p>
                </div>
                <div class="row margin-bottom partner">
                    <div class="col-md-2"><?php echo $this->Html->image('piommer.png');?></div>
                    <div class="col-md-10">
                        <h1>BECOME A PIONEER </h1>
                        <p class="font-narrow-book">Over the next 4 months we will work with you, our pioneers, to lay strong foundations for the colony. Together, we will build a thriving and responsive metropolis which will offer enormous value and support for the next wave of entrepreneurial citizens.</p>
                    </div>
                </div>
                <div class="row margin-bottom partner">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-3"><a href="<?php echo Router::url(array('controller'=>'users', 'action'=>'register'));?>" class="btn btn-orange  small">REGISTER | EMAIL</a></div>
                            <div class="col-md-3"><a href="<?php echo Router::url(array('controller'=>'users', 'action'=>'index'));?>" class="btn btn-blue  small">REGISTER | LINKEDIN</a></div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3"></div>
                        </div>
                        <div class="row margin-top">
                            <div class="col-md-6">
                                <div class="icon"><?php echo $this->Html->image('icon1.png');?></div>
                                <div class="box">
                                    <h3>SHARE YOUR MENTOR ADVICE WISDOM</h3>
                                    <p>Combined experience and wisdom are the powerhouse of Entropolis.  Enter the decisions you have had to make as an entrepreneur – the good, the bad and the downright ugly – and your corresponding hindsight wisdom into Decision Bank for the benefit of all.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icon"><?php echo $this->Html->image('icon2.png');?></div>
                                <div class="box">
                                    <h3>SHAMELESSLY PROMOTE</h3>
                                    <p>Don’t be shy! Please introduce Entropolis to your network so they can all be pioneers of this entrepreneurial crusade.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="icon"><?php echo $this->Html->image('icon3.png');?></div>
                                <div class="box">
                                    <h3>OR SHARE YOUR SAGE ADVICE</h3>
                                    <p>If you are an expert, go to the Advice Publishing App to share your insights and expertise. </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="icon"><?php echo $this->Html->image('icon4.png');?></div>
                                <div class="box">
                                    <h3>BE HONEST</h3>
                                    <p>We need your feedback through this iterative development phase. This is your chance to mould the city’s infrastructure to your needs. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="side-panel">
                            <div class="side-panel-top purpel">
                                <i><?php echo $this->Html->image('seeker-hindsight.png');?></i>
                                <h4>Seeker Hindsight</h4>
                            </div>
                            <div id="Carousel" class="carousel slide " data-interval="3000" data-ride="carousel">
                                <div class="carousel-inner">

                                     <?php
                                     $decisionbank = $this->Hindsight->getAllDecisionBankHindsight();
                                    // pr( $decisionbank );
                                    //die;
                                 foreach($decisionbank as $key=>$decision_bank){
                                             $decision_bankUserId = $decision_bank['ContextRoleUser']['user_id'];
                                             $decision_bankUserName = $this->User->userName($decision_bankUserId);
                                            $decision_bankUserImg = $this->User->userProfilePic($decision_bankUserId);

                                           //to get rate
                                            $i = 0;
                                            $rate = 0;
                                            $rates ='';
                                            foreach($decision_bank['Comment'] as $keyCom=>$comment){                                
                                                if($comment['rating'] != ''){
                                                   $i++;
                                                   $rate = $rate+$comment['rating'];
                                                }
                                            }
                                            if($i > 0){
                                                $rates = $rate/$i;
                                            }

                                    ?>

                                    <div class="item <?php echo $key == 0 ? 'active': '';?>">  
                                        <div class="side-panel-detail">
                                            <div class="side-panel-heading">
                                                <span><?php echo $decision_bankUserName;?></span>  
                                                <h6><?php $titleLen = strlen($decision_bank['DecisionBank']['hindsight_title']);
                                            echo $titleLen > 50 ? substr($decision_bank['DecisionBank']['hindsight_title'], 0, 50).'..':$decision_bank['DecisionBank']['hindsight_title'];?>
                                            </h6>
                                                <a class="anchor-heading"><?php echo $decision_bank['DecisionType']['decision_type'];?></a>
                                            </div>
                                            <p><?php echo $this->Eluminati->text_cut($decision_bank['DecisionBank']['short_description'], $length = 200, $dots = true);?></p>                                      
                                        </div>
                                         <?php //echo $this->Html->link('View Details', array('controller'=>'pages', 'action'=>'viewHindsight', $decision_bank['DecisionBank']['id']), array('class'=>'anchor-heading right'));?>

                                         <a href="#seeker-hindsight"  data-id = <?php echo $decision_bank['DecisionBank']['id'];?> data-toggle="modal">View Details</a>
                                       
                                    </div>
                                    <?php } ?>
                                   
                                   
                                </div>
                            </div>
                            <div class="purpel side-panel-bottom"></div>            
                        </div>
                    </div>  
                </div>
                <div class="row margin-bottom partner">
                    <div class="col-md-9">
                        <h1>PIONEER BENEFITS </h1>
                        <p class="font-narrow-book">Entropolis is a start-up and we are in Beta. This means you are looking at our MVP. As trailblazers and risk takers, we know you are used to roughing it, but we really want you to join our colony and help us build the ultimate hive of entrepreneurial activity.</p>
                        <div class="row margin-top">
                            <div class="col-md-6">
                                <div class="box-wrap">
                                    <div class="icon"><?php echo $this->Html->image('icon5.png');?></div>
                                    <div class="box">
                                        <p>You will be recognised as one of only 1,000 pioneers of Entropolis and the Pioneer badge will feature on your profile FOREVER! In recognition of your contribution, you can use your Pioneer status to cash in on exclusive offers and participate in invitation only events from January 2015.</p>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="icon"><?php echo $this->Html->image('icon7.png');?></div>
                                    <div class="box">
                                        <p>Be the architect and designer of an exciting new world. Engineer your own entrepreneurial utopia without spending a dollar for the first six months. That's a US$600 saving for Entropolis Pioneers.</p>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="icon"><?php echo $this->Html->image('icon9.png');?></div>
                                    <div class="box">
                                        <p>Get a head-start on building your entrepreneur or expert 'cred' and a robust health profile which can lead to great business opportunities in this exclusive community.  </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box-wrap">
                                    <div class="icon"><?php echo $this->Html->image('icon6.png');?></div>
                                    <div class="box">
                                        <p>Start your journey to peak business performance with our FREE trial of our proprietary Decisionship tools at<br> <a href="http://www.decisionship.com">www.decisionship.com.</a> <br>This is an offer limited to our pioneers. When we go live in January 2015, Decisionship will be brand spanking new and ready to make the world of entrepreneurship even better ... but for a price! So get in now and see what Decisionship can do for you.</p>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="icon"><?php echo $this->Html->image('icon8.png');?></div>
                                    <div class="box">
                                        <p>Invitations to exclusive educational and social events for Entropolis Pioneers. Keep an eye out for your invitation to the Global Entropolis Launch in February 2015.</p>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="icon"><?php echo $this->Html->image('icon10.png');?></div>
                                    <div class="box">
                                        <p>Still need convincing? If we haven't buttered you up enough yet, please get in touch and let us know what would make signing up a no-brainer. We promise we will thank you if you have a great idea which we can actually use.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div><h5 class="font-narrow-bold">PLEASE HELP US TO MAKE THIS THE BEST EVER ONLINE ECOSYSTEM FOR ENTREPRENEURS ... SIGN UP NOW.</h5></div>
                        <div class="row margin-top">
                            <div class="col-md-3"><a href="<?php echo Router::url(array('controller'=>'users', 'action'=>'register'));?>" class="btn btn-orange  small">REGISTER | EMAIL</a></div>
                            <div class="col-md-3"><a href="<?php echo Router::url(array('controller'=>'users', 'action'=>'index'));?>" class="btn btn-blue  small">REGISTER | LINKEDIN</a></div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="side-panel">
                            <div class="side-panel-top yellow">
                                <i><?php echo $this->Html->image('sage-advice.png');?></i>
                                <h4>sage adivce</h4>
                            </div>
                            <div id="Carousel" class="carousel slide " data-interval="3000" data-ride="carousel">
                                <div class="carousel-inner">
                                    <?php 
                                     $advicedetail = $this->Advice->openChallengeDetailForAdvice();
                                     // pr($advicedetail);
                                    foreach($advicedetail['advices'] as $key=>$advices){
                                         $adviceUserId = $advices['ContextRoleUser']['user_id'];
                                         $adviceUserName = $this->User->userName($adviceUserId);
                                         $adviceUserImg = $this->User->userProfilePic($adviceUserId);
                                         $adviceId = $advices['Advice']['id'];
                                         //to get rate
                                         $i = 0;
                                         $rate = 0;
                                         $rates ='';
                                         foreach($advices['Comment'] as $keyCom=>$comment){                                
                                             if($comment['rating'] != ''){
                                                $i++;
                                                $rate = $rate+$comment['rating'];
                                             }
                                         }
                                         if($i > 0){
                                             $rates = $rate/$i;
                                         }
                                         
                                         ?>

                                    <div class="item <?php echo $key == 0 ? 'active': '';?>">  
                                        <div class="side-panel-detail">
                                            <div class="side-panel-heading">
                                                <span><?php echo $adviceUserName;?></span>  
                                                <h6><?php $titleLen = strlen($advices['Advice']['advice_title']);
                                    echo $titleLen > 50 ? substr($advices['Advice']['advice_title'], 0, 50).'..':$advices['Advice']['advice_title'];?></h6>
                                                <a class="anchor-heading"><?php echo $advices['DecisionType']['decision_type'];?></a>
                                            </div>
                                            <p><?php echo $this->Eluminati->text_cut($advices['Advice']['executive_summary'], $length = 200, $dots = true);?></p>                                      
                                        </div>
                                         <?php echo $this->Html->link('View Details', array('controller'=>'pages', 'action'=>'viewAdvice', $adviceId), array('class'=>'anchor-heading right'));?>
                                    </div>
                                    <?php } ?>  
                                                                     
                                </div>
                            </div>
                            <div class="yellow side-panel-bottom"></div>            
                        </div>
                    </div>  
                </div>
            </div>
        </div>

        <!-- seeker-hindsight -->
<div class="modal fade elumanati-popup" id="seeker-hindsight" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content purpel-bg">
    	<div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><i class="icons close-icon"></i></button>
	    </div>
       <div class="modal-body">
       	<div class="elumanati-wrap">
       		<div class="row">
       			<div class="col-md-4">
       				<div class="">
    					<img alt="" src="images/title-icon.png">
    				</div>
    				
    			</div>
       			<div class="col-md-8">
       				<div class="eluminate-icon">
						<i><img src="images/seeker-hindsight-icon.png" alt=""></i>
						<h3>SEEKER HINDSIGHT</h3>
					</div>
					<div class="row ">
						<div class="col-md-12">
							<div class="elumanati-wrap-left">
								<span>Wed 19 March 2014</span>
								<span><h4>The opportunity of adversity</h4></span>
								<span>Category | Sub-category</span>
								<span>Published:</span>
								<span class="date">Last Updated: March 2014	</span>
							</div>
						</div>						
					</div>					
       			</div>
       		</div>
       		<div class="row margin-top">
       			<div class="col-md-4">
       				<h2>dave kerpen</h2>
       			</div>
       			<div class="col-md-8">
       				<div class="url">
						<input type="text" class="form-control" placeholder="Original Source URL">
					</div>
       			</div>
       		</div>
       		<div class="row eluminti-box-wrap">
       			<div class="col-md-4">
       				<div class="relative">
       					<textarea class="form-control" rows="3"></textarea>  
       					<i class="fa fa-plus add"></i>     					
       				</div>
       				<div class="relative">
       					<textarea class="form-control" rows="3"></textarea>  
       					<i class="fa fa-plus add"></i>     					
       				</div>
       				<div class="relative">
       					<textarea class="form-control" rows="3"></textarea>  
       					<i class="fa fa-plus add"></i>     					
       				</div>
       			</div>
       			<div class="col-md-8">
              <div class="relative">
                <textarea class="form-control" rows="3" placeholder="Executive Summary"></textarea>  
                <i class="fa fa-plus add"></i>              
              </div>
              <div class="relative">
                <textarea class="form-control" rows="3" placeholder="The Entrepreneurship Challenge"></textarea>
                <i class="fa fa-plus add"></i>                  
              </div>
              <div class="relative">
                <textarea class="form-control" rows="3" placeholder="Key Advice Points"></textarea>
                <i class="fa fa-plus add"></i>                  
              </div>
            </div>
       		</div>

       	</div>
      
      </div>
      <div class="modal-footer align-center">
      	<a href="#"><img src="images/black-icon1.png" alt=""></a>
      	<a href="#"><img src="images/black-icon2.png" alt=""></a>
      	<a href="#"><img src="images/black-icon3.png" alt=""></a>
      	<a href="#"><img src="images/black-icon4.png" alt=""></a>
      	<a href="#"><img src="images/black-icon5.png" alt=""></a>
      	<a href="#"><img src="images/black-icon6.png" alt=""></a>        
      </div>
    </div>
  </div>
</div>