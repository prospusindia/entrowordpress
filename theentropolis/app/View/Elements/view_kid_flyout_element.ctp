<div class="modal-dialog">
    <!-- Modal content-->
    <?php
    if ($action == 'Edit') {

        $edit_div_show = '';
        $view_div_show = 'hide';
    } else {
        $edit_div_show = 'hide';
        $view_div_show = '';
    }
    $readonly = 'readonly';
    $readonlyparent = ($this->Session->read('roles') == "Teacher") ? 'readonly' : ""; //pr($teacher_info);
    ?>
    <div class="modal-content"><!-- custom_scroll-->
        <div class="modal-header">
            <h4 class="modal-title"><?php echo $formtitle = $students["User"]["username"]; ?></h4>
            <!--   data-toggle="modal" data-target="#editStudentFlyout"-->
            <ul class="students-action-links">

<?php if ($action == 'Edit') {
    ?>
                                     <!--    <a href="#" class="btn line-btn kid-new-theme-btn-transparent view_business_profile <?php echo $class_disable; ?>   kid_business_profile_flyin" data-toggle="modal" data-id="<?php echo @$business_profile['KidBusinessProfile']['id']; ?>" data-action ="View" data-event = 'Business'>My Business page</a> -->


                    <li class=""><a href="javascript:void(0);" onclick = "$('.submit-kidform').trigger('click')">Save Updates</a></li>
                    <li class="close-student-modal" ><a href="javascript:void(0);">Cancel</a></li>

<?php
} else {
    $class_disable = '';
    $business_profile = $this->Kidpreneur->getPublishedBusinessProfile($students['User']['id']);
    $business_profile_count = $this->Kidpreneur->getPublishedBusinessProfileCount($students['User']['id']);
    if (empty($business_profile)) {
        $class_disable = 'disabled';
    }
    ?>
                    <li ><a href=""  data-toggle="modal" data-id="<?php echo @$business_profile[0]['KidBusinessProfile']['id']; ?>" data-action ="View" data-event = 'Business' data-limit = '0' data-count =  '<?php echo $business_profile_count; ?>' class  = 'business_flyin_modal   view_business_profile kid_business_profile_flyin <?php echo $class_disable; ?> ' >View Business Profile</a></li>

                    <li class=""><a href="" data-id="<?= $students["User"]["id"] ?>" data-toggle="modal" data-role ="<?php echo $role_belongs; ?>"  class="kid-edit-modal" data-formtitle ='' data-action ="Edit">Edit</a></li>
                    <li class=""><a href="javascript:void(0);" data-id="<?= $students["User"]["id"] ?>" data-role= "<?php echo $role_belongs; ?>" class="remove-student hide-flyout">Delete</a></li>

<?php } ?>
                <!--  <li class="disabled"><a href="">Save Updates</a></li> -->
            </ul>
        </div>
        <form class="form-horizontal <?php echo $view_div_show; ?>">
            <div class="modal-body custom_scroll addmodalstudentscroll">
                <!-- <div class=""> -->
                <div class="form-group">
                    <label class="control-label col-sm-12" for="">First Initial</label>
                    <div class="col-sm-12">
                        <div class="filled-details">
<?php echo $students["User"]["first_name"]; ?>
                        </div>   
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for="">Last Name</label>
                    <div class="col-sm-12">
                        <div class="filled-details">
<?php echo $students["User"]["last_name"]; ?>
                        </div>   
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for="">Avatar Name</label>
                    <div class="col-sm-12">
                        <div class="filled-details">
<?php echo $students["User"]["username"]; ?>
                        </div>   
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for="">Date of Birth</label>
                    <div class="col-sm-12">
                        <div class="filled-details">
<?php echo date('m/d/Y', strtotime($students["UserTeacherProfile"]["birth_date"])); ?>
                        </div>   
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for="">Gender</label>
                    <div class="col-sm-12">
                        <div class="filled-details">
<?php echo $students["User"]["gender"]; ?>
                        </div>   
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for="">School Name</label>
                    <div class="col-sm-12">
                        <div class="filled-details">
<?php echo $students["UserTeacherProfile"]["organization"]; ?>
                        </div>   
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for="">School KBN / Promotional Code</label>
                    <div class="col-sm-12">
                        <div class="filled-details">
<?php echo $students["UserTeacherProfile"]["kbn_number"]; ?>
                        </div>   
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for="">School Year Level</label>
                    <div class="col-sm-12">
                        <div class="filled-details">
<?php echo $students["UserTeacherProfile"]["year_group"]; ?>
                        </div>   
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for=""><?php echo $role_belongs; ?> Username</label>
                    <div class="col-sm-12">
                        <div class="filled-details">
<?php echo $teacher_info["User"]["username"]; ?>
                        </div>   
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12" for=""><?php echo $role_belongs; ?> Email Address</label>
                    <div class="col-sm-12">
                        <div class="filled-details">
<?php echo $teacher_info["User"]["email_address"]; ?>
                        </div>   
                    </div>
                </div>
                <!--  </div> -->
            </div><!--end of modal body-->
        </form>

        <form class="form-horizontal studentFromValidate  <?php echo $edit_div_show; ?> " name="addStudent" id="addStudent" data-action ='<?php echo $action; ?>' data-role ='<?php echo $role; ?>'>
            <div class="modal-body custom_scroll addmodalstudentscroll">
                <!-- <div class=""> -->
                <div class="form-group">
                    <div class="col-sm-6">
                        <input type="text" class="form-control"  value = "<?php echo $students['User']['first_name']; ?>"id="student_fname" name="first_name" placeholder="First Initial*" maxlength = '20'>
                    </div>
                    <div class="col-sm-6 manage-left-pad">
                        <input type="text" class="form-control" value = "<?php echo $students['User']['last_name']; ?>" id="student_lname" name="last_name" placeholder="Last Name*" maxlength = '20'>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="text" autocomplete="off" class="form-control"  id="student_avatar_name" value = "<?php echo $students['User']['username']; ?>" name="student_avatar_name" placeholder="Kidpreneur Avatar Name*" <?php if ($students['User']['username'] != '') {
    echo $readonly;
} ?>>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="password" class="form-control" id="student_password" name="student_password" placeholder="Create a Secret Password*" value = "<?php echo base64_decode($students['UserTeacherProfile']['teacher_password']); ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="password" class="form-control" name="student_confirm_password" id="student_confirm_password" placeholder="Confirm your Secret Password*" value = "<?php echo base64_decode($students['UserTeacherProfile']['teacher_password']); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="text" class="form-control datepicker  " id="birth_date" readonly name="birth_date"  value = "<?php if ($action == 'Edit') {
    echo date('m/d/Y', strtotime($students["UserTeacherProfile"]["birth_date"]));
} ?>"  placeholder="Date of Birth*">
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-sm-12">
                        <select class="form-control" name="gender" >
                            <option value =''>Gender*</option>
                            <option value ='Male' <?php if ($students["User"]["gender"] == 'Male') {
    echo 'selected="selected"';
} ?>>Male</option>
                            <option value ='Female' <?php if ($students["User"]["gender"] == 'Female') {
    echo 'selected="selected"';
} ?>>Female</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 ui-widget">
                        <input type="text" class="form-control"  id="student_school_name" name="student_school_name"  value = "<?php if ($students['UserTeacherProfile']['organization'] != '') {
    echo $students['UserTeacherProfile']['organization'];
} ?>"placeholder="School Name*" <?php if ($students['UserTeacherProfile']['organization'] != '') {
    echo $readonlyparent;
} ?>>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="student_promotional"   value="<?php if ($students['UserTeacherProfile']['kbn_number'] != '') {
    echo $students['UserTeacherProfile']['kbn_number'];
} ?>" name="student_promotional" placeholder="School KBN / Promotional Code" <?php echo $readonly; ?> >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="" name="year_group"   value="<?php if ($students['UserTeacherProfile']['year_group'] != '') {
    echo $students['UserTeacherProfile']['year_group'];
} ?>"   placeholder="School Year Level*" <?php if ($students['UserTeacherProfile']['year_group'] != '') {
    echo $readonly;
} ?>>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="" name="student_teachername" value = "<?php echo $teacher_info['User']['username']; ?>" placeholder="My Teacher / Principal's Full Name" readonly >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="email" class="form-control" id="" name="student_teacher_email" placeholder="My Teacher / Principal's Email Address" value="<?php echo $teacher_info['User']['email_address']; ?>" readonly >
                    </div>
                </div>
                <input type="hidden" class="form-control" id="role" name="role" value="<?php echo $role; ?>">
                <input type="hidden" class="form-control" id="role" name="action_type" value="<?php echo $action; ?>">
                <input type="hidden" class="form-control" id="role" name="student_id" value="<?php echo $students["User"]["id"]; ?>">
                <!--  </div> -->
            </div><!--end of modal body-->

<?php
if ($action == 'Edit') {
    $cls = 'hide';
}
?>
            <div class="modal-footer">
                <button type="submit" class="form-control btn btn-blue submit-kidform <?php echo $cls ?>" >Register</button>
            </div>
        </form>
    </div>
</div>
<style>
    .ui-datepicker-days-cell-over a.ui-state-default {
    color: #ffffff;
    background: #7e9198;
}
    </style>
<script type="text/javascript">
    $(function () {
        $("#student_school_name").keyup(function () {
            $("#student_promotional").val('');
        });
        $("#student_school_name").autocomplete({
            source: "<?php echo Router::url(array('controller' => 'Users', 'action' => 'get_schoolnames')); ?>",
            appendTo: ".ui-widget",
            select: function (event, ui) {
                ui.item.value;
                $.ajax({
                    url: "<?php echo Router::url(array('controller' => 'Users', 'action' => 'get_kbn')); ?>",
                    dataType: "json",
                    data: {
                        term: ui.item.value
                    },
                    success: function (data) {
                        console.log(data);
                        $("#student_promotional").val(data[0]);
                    }
                });
            }
        });
        $('#birth_date').datepicker({ 
//                        minDate: "-12Y", 
                           defaultDate: '<?php echo MIN_AGE;?>',
            //         maxDate: '<?php echo MIN_AGE;?>',                     
//                    Removed as per discussion on 20 march 18
                   });

        $('#birth_date').on('change', function () {
            if ($(this).val() != '')
            {
                $(this).removeClass('selected-error-fields');
                $('#birth_date-error').css('display', "none")
            }

        })

    });


    var addmodalstudentscroll = '.addmodalstudentscroll';
    containerHeightTemp(addmodalstudentscroll)
//rightFlyoutContainerHeightTemp();
    customScroll();
    function containerHeightTemp(selector) {
        setTimeout(function () {
            var totalHeight = $('.modal-content:visible').outerHeight(true),
                    bufferH = 26,
                    profileDetailsH = $('.top-section:visible').outerHeight(true)
                    + $('.modal-footer:visible').outerHeight(true)
                    + parseInt($('.modal-dialog:visible').css('margin-top'));


            var calculatedHeight = totalHeight - (profileDetailsH) - 150;

            $(selector + ":visible").css('height', calculatedHeight + 'px');
        }, 200);
    }
</script>