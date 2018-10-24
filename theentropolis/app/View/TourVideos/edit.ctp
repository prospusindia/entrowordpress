<div class="col-md-10 content-wraaper admin-wrap">
    <div class="sage-dash-wrap full-wrap">
        <div class="title dashboard-title take_tour_title relative">
            <h1>Edit Start Here</h1>
            <?php echo $this->Html->link('Back', array('controller'=>'tourVideos', 'action'=>'view'),array('class'=>'right btn btn-orange-small')); ?>          
        </div>
        <div class="invite-user-form forms add-elum">

          <?php echo $this->Form->create('TourVideo', array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'));
                echo $this->Form->input('id');
          ?>

            <div class="form-group new-form-group">
                <label class="col-sm-2 control-label">Tag*</label>
                <div class="col-sm-7">
                    <?php echo $this->Form->input('tag_id', array('class'=>'form-control', 'label'=>false,'id'=>'tour-tagging'));?>
                </div>
            </div>
            
            <div class="form-group new-form-group">
                <label class="col-sm-2 control-label">Title*</label>
                <div class="col-sm-7">
                    <?php echo $this->Form->input('title', array('class'=>'form-control', 'label'=>false));?>
                </div>
            </div>
            
            <div class="form-group new-form-group non-mandatory">
                <label class="col-sm-2 control-label">Detail</label>
                <div class="col-sm-7">
                    <?php echo $this->Form->input('blog_detail', array('class'=>'form-control', 'label'=>false));?>
                </div>
            </div>

            <div class="form-group new-form-group">
                <label class="col-sm-2 control-label">URL*</label>
                <div class="col-sm-7">
                        <?php echo $this->Form->input('video_url', array('class'=>'form-control', 'label'=>false));?>
                    <div style="display:none;" class="error-message"></div>
                </div>
            </div>

            <div class="form-group new-form-group non-mandatory">
                <label class="col-sm-2 control-label dashboard-label">Upload Image</label>
                <div class="col-sm-8">
                    <div class="attachment clearfix">
                        <div class="atch-wrapper clearfix">
                            <?php
                            echo $this->Form->input('upload_thumbnail', array('type'=>'hidden', 'class'=>'img-url', 'value'=>$this->request->data['TourVideo']['upload_thumbnail'], 'required'=>true));
                            ?>
                            <input type="hidden" class="img-url" name = "data[Video][image_url]" value = "<?php echo $this->request->data['TourVideo']['upload_thumbnail']; ?>" >  
                            <div class="image-bind">
                                 <?php if( $this->request->data['TourVideo']['upload_thumbnail'] != ''){ ?>
                                <img src="<?php echo $this->Html->url('/').$this->Image->resize($this->request->data['TourVideo']['upload_thumbnail'] . '', 80, 80, true);?>" class = "add-post-img img-thumbnail"> 
                                <?php }else{?>
                                <img src="<?php echo $this->Html->url('/').$this->Image->resize('upload/avatar-male-1.png' . '', 80, 80, true);?>" class="add-post-img img-thumbnail"> 
                                <?php } ?> 
                            </div>
                        </div>
                        <!-- atch-wrapper end --> 
                    </div>
                    <div class="brws-btn">    
                        <a class="escene-action-right" href="javascript:void(0);">
                        <input class="atch-new-image escene-action-input" type="file" name="files[]" data-url="<?php echo Router::url(array('controller'=>'tourVideos', 'action'=>'upload'));?>"/>
                        Browse</a> 
                    </div>
                </div>
            </div>


            <div class="form-group new-form-group">
                <div class="col-sm-offset-2 col-sm-10"> 
                        <?php echo $this->Form->submit('Save', array('class'=>'btn btn-orange-small save-profile', 'div'=>false));?>


                </div>
            </div>
            <?php echo $this->Form->end();?>
        </div>
    </div>
</div> <!-- content-wraaper ends -->
<script type="text/javascript">
    var imageattach = {};
    //--------------------------- Attachments (File Upload)
    imageattach = {
        button: $('.atch-button'),
        wrapper: $('.atch-wrapper'),
        newFileButton: $('.atch-new-box'),
        newFile: $('.atch-new-image'),
        tempObject: null,
        bindUploader: function (object) {
            if (!object || typeof object == 'undefined')
                return;
            object.fileupload({
                dataType: 'json',
                async: false,
                add: function (e, data) {
                    //console.log(data);
                    var goUpload = true;
                    var uploadFile = data.files[0];

                    //alert(uploadFile.name);
                    if (!(/\.(gif|jpg|jpeg|tiff|png)$/i).test(uploadFile.name)) {
                        // alert message
                        bootbox.alert("Please select image file of  jpg, jpeg, png or gif type only.");
                        goUpload = false;
                        return;
                    }

                    if (uploadFile.size > 5242880) { // 5mb
                        // alert message
                        bootbox.alert("Please upload a smaller image, max size is 5 MB.");
                        goUpload = false;
                        return;
                    }
                    if (goUpload == true) {
                        var img = data.submit();
                        var imgName = img.responseText;
                        //console.log(img.responseText);

                        var str = '<div class="upload-post-img"><img class="add-post-img img-thumbnail" src="<?php echo Router::url('/', true); ?>' + imgName + '" width="80px" height="80px">\n\
<input type="hidden" name="filesPath[]" value="' + imgName + '"></div>';

                        //$('.upload-progress-value').html(str);
                        jQuery(".image-bind").html('');
                        jQuery(".image-bind").append(str);
                        jQuery(".img-url").val(imgName);
                    }
                },

                progressall: function (e, data) {
                    var $this = $(this);

                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('.upload-progress-wrapper:hidden').fadeIn(100);
                    $('.upload-progress-wrapper').find('.upload-progress-value span').text(progress);
                    console.log(data);
                }
            });
        }
    };

    imageattach.newFile.each(function () {
        imageattach.bindUploader($(this));
    });

    var attachnewimage = {};
    //--------------------------- Attachments (File Upload)
    attachnewimage = {
        button: $('.atch-button'),
        wrapper: $('.atch-wrapper'),
        newFileButton: $('.atch-new-box'),
        newFile: $('.atch-new-img'),
        tempObject: null,
        bindUploader: function (object) {
            if (!object || typeof object == 'undefined')
                return;
            object.fileupload({
                dataType: 'json',
                async: false,
                add: function (e, data) {
                    //console.log(data);
                    var goUpload = true;
                    var uploadFile = data.files[0];
                    //alert(uploadFile.name);
                    if (!(/\.(gif|jpg|jpeg|tiff|png)$/i).test(uploadFile.name)) {
                        // alert message
                        bootbox.alert("Please select image file of  jpg, jpeg, png or gif type only.");
                        goUpload = false;
                    }

                    if (uploadFile.size > 5000000) { // 5mb
                        // alert message
                        bootbox.alert("Please upload a smaller image, max size is 5 MB.");
                        goUpload = false;
                    }
                    if (goUpload == true) {
                        var img = data.submit();
                        var imgName = img.responseText;
                        //console.log(img.responseText);

                        var str = '<div class="upload-post-img"><img class="add-post-img img-thumbnail" src="<?php echo Router::url('/', true); ?>' + imgName + '" width="80" height="80">\n\
<input type="hidden" name="filesPath[]" value="' + imgName + '"></div>';

                        //$('.upload-progress-value').html(str);
                        jQuery(".image-bind-data").html('');
                        jQuery(".image-bind-data").append(str);
                        jQuery(".badge-url").val(imgName);
                    }
                },

                progressall: function (e, data) {
                    var $this = $(this);

                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('.upload-progress-wrapper:hidden').fadeIn(100);
                    $('.upload-progress-wrapper').find('.upload-progress-value span').text(progress);
                    console.log(data);
                }
            });
        }
    };

    attachnewimage.newFile.each(function () {
        attachnewimage.bindUploader($(this));
    });

    $(document).ready(function () {
        if($('#tour-tagging').val()==6) 
            $(".non-mandatory").addClass("hide");
        
        $(".save-profile").click(function (e) {
            var VideoUrl = $.trim($('#tourVideoVideoUrl').val());
            var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
            var matches = VideoUrl.match(p);

            if (VideoUrl == "") {
                $('.error-message').html('').hide();
                $(this).attr('type', 'submit');
            } else if (matches) {
                isHandlerActive = false;
                $(this).attr('type', 'submit');
                isHandlerActive = true;
                $('.error-message').html('').hide();
            } else {
                $('.error-message').html('Please provide valid url.').show();
                $(this).attr('type', '');
                $(this).css('width', '80px');
                isHandlerActive = false;
            }
        });
        $('.atch-new-image').on('change', function () {
            var img_url = $.trim($('.img-url').val());
            if (img_url != "") {
                $('.atch-new-image').removeAttr('required');
            } else {
                $('.atch-new-image').attr('required', 'required');
            }
        });
    });

</script>