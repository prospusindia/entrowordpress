<?php
class User extends AppModel{
	public $name = 'User';
	public $validate  =  array(
            'first_name'=>array(
                'notEmpty'=>array(
                    'rule'=>array('custom', '/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/i'),
                    'message'=>'Valid characters are numbers, alphabets only.',
                ),
                'validUserName'=>array(
                    'rule'=>array('preventOnlyNumbers'),
                    'message'=>'Valid characters are numbers, alphabets only.',
                ),
            ),
            'last_name'=>array(
                'pattern'=>array(
                    'rule'=>array('custom', '/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/i'),
                    'allowEmpty'=>true,
                    'message'=>'Valid characters are numbers, alphabets only.',
                ),
                'validUserName'=>array(
                    'rule'=>array('preventOnlyNumbers'),
                    'message'=>'Valid characters are numbers, alphabets only.',
                ),
            ),
            'gender'=>array(
                'required'=>array(
                    'rule'=>array('notEmpty'),
                    'message'=>'Gender is required.',
                ),
            ),
            'email_address'=>array(
                'email' => array(
                    'rule' => array('custom','/^[A-Za-z0-9._%+-]+@([A-Za-z0-9-]+\.)+([A-Za-z0-9]{2,4}|museum)$/i'),
                    'message' => 'Please enter a valid email address.',
                 ),
                'isUnique' => array(
                    'rule' => 'isUnique',
                    'message' => 'Email address already in use.',
                ),
                'validEmail'=>array(
                    'rule'=>array('validateEmail'),
                    'message'=>'Please provide a valid email address.',
                ),
            ),

            'confirm_email_address'=>array(
                
            'equaltofield' => array(
            'rule' => array('equaltofield','email_address'),
            'message' => 'Your email and email confirmation does not match.',
            'allowEmpty' => false,
            'required' => false,
            //'last' => false, // Stop validation after this rule
            'on' => 'create', // Limit validation to 'create' or 'update' operations
            )
        
                ),


            'password'=>array(               
                'required'=>array(
                    'rule'=>array('minLength', 3),
                    'message'=>'Passwords must be at least 3 characters long.',
                 ),
                'pattern'=>array(
                    'rule'=>array('custom', '/^[a-zA-z0-9=@$#*-+_\.()\!]+$/i'),
                    'message'=>'Please use valid characters as password',
                ),
             ),
               
                'country_id' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Please choose your country.'
                ),
                 'stage_id' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Please choose your stage.'
                ),
                 'decision_type_id' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Please choose category.'
                ),
                  'group_code' => array(
                     'Exsist'=>array(
                     'allowEmpty' => true,
                     'required' => false,
                    'rule'=>array('checkGroupCode'),
                    'message'=>'Group code does not exist.',
                ),
                ), 

                  // 'user_current_status_id'=>array(
                  //        'rule' => 'notEmpty',
                  //       'message' => 'Please provide your current status.'
                  //            ),            
               // 'influence_network'=>array(
               //                  'rule' => 'notEmpty',
               //                'message' => 'Please select.'

               //               ),  
            // 'linkedin_network'=>array(
            //     'validUrl'=>array(
            //          'required' => false,
            //          'allowEmpty' => true, 
            //         'rule'=>array('custom', '@^(http\:\/\/|https\:\/\/)?([a-z0-9][a-z0-9\-]*\.)+[a-z0-9][a-z0-9\-]*$@i'),
             
            //          'message'=>'Please enter valid url.',
            //     ),
            // ),
            // 'twitter_followers'=>array(
            //     'validUrl'=>array(
            //          'required' => false,
            //          'allowEmpty' => true, 
            //          // 'rule'=>array('custom', '@^(http\:\/\/|https\:\/\/)?([a-z0-9][a-z0-9\-]*\.)+[a-z0-9][a-z0-9\-]*$@i'),
            //          'rule' => array('url', true), 
            //          'message'=>'Please enter valid url.',
            //     ),
            // ),
            // 'blog'=>array(
            //     'validUrl'=>array(
            //          'required' => false,
            //          'allowEmpty' => true, 
            //          // 'rule'=>array('custom', '@^(http\:\/\/|https\:\/\/)?([a-z0-9][a-z0-9\-]*\.)+[a-z0-9][a-z0-9\-]*$@i'),
            //             'rule' => array('url', true), 
            //          'message'=>'Please enter valid url.',
            //     ),
            // ),


                 
         );
   
        // relation between users and context_role_users
         public $hasMany = array(
		'ContextRoleUser' => array(
		    'className' => 'ContextRoleUser',
		    'foreignKey' => 'user_id'	
		),
                'UserEntropolisGuideline' => array(
		    'className' => 'UserEntropolisGuideline',
		    'foreignKey' => 'user_id'			
		),);
         
public $hasOne=array(
         'UserProfile' => array(
            'className' => 'UserProfile',
            'foreignKey' => 'user_id',
                    
        )
    );
         
        /**
         * To prevent from input only numbers
         * @param type $data
         * @return int
         */
        public function preventOnlyNumbers($data){
            $value = array_values($data);
            $value = $value[0];
            if(preg_match('|^[0-9]*$|', $value)){
                return 0;
            }
            
            return 1;
        }
        
        /**
         * To validate email 
         * @param type $data
         * @return int
         */
        public function validateEmail($data){
            $value = array_values($data);
            $value = $value[0];
            
            $values = explode('@', $value);
                       
            if($values[0][0] == '.'){
                // leading dot in address should not allow
                return 0;
            }    
            // trailing dot in address should not allow
            $lenbeforAtr = strlen($values[0])-1;
            if($values[0][$lenbeforAtr] == '.'){
                return 0;
            }
            // leading das infront of domain should not allow
            if($values[1][0] == '-'){
                return 0;
            }
            // only numeric value should not allow in email            
            if(preg_match('|^[0-9]*$|', $values[0])){
               // return 0;
            }
            
            return 1;
        }
        function urlvalidation($data)
        {
            return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $data);
        }

        function checkGroupCode($data=null)
        {
           $code = $data['group_code'] ;
           App::import("Model", "GroupCode");
           $GroupCode = new GroupCode();
           if($data['group_code']!='' && $data['group_code'] ='NULL')
           {
              //$this->setSource('group_codes');
              // $this->recursive ='-1';
               $result = $GroupCode->find('first',array('conditions'=>array('name'=>$code),'fields'=>('name')));
            //$res = "SELECT group_codes.name FROM group_codes WHERE group_codes.name = '$code'";
            //$result = $this->query($res);

              if(!empty($result))
              {
                return 1;
              }
              else
              {
                return 0;
              }
             
           }
        }

        
  
        
        /**
         * To check users data for login
         * @param type $data
         * @return type
         */
        public function checkUserData($data){
            
            $userEmail = trim($data['Users']['email_address']);
            $userPass  = trim(md5($data['Users']['password']));
            
            $condition = array('email_address'=>$userEmail, 'password'=>$userPass);
            
            $userData = $this->find('first', array('conditions'=>$condition));
            return $userData;
        }
        
        /**
         * To get user's role by cotext_role_id
         * @param type $cotextId
         * @return type
         */
        public function getRoleByContextId($contextId){
            
            $result = $this->query("select contexts.name, roles.role from context_role_users cru 
                left join context_roles on cru.context_role_id=context_roles.id
                left join contexts on context_roles.context_id=contexts.id
                left join roles on context_roles.role_id=roles.id where cru.id='$contextId' ");
            
            if(!empty($result)){
                $returnRole['contexts'] = $result[0]['contexts']['name'];
                $returnRole['roles'] = $result[0]['roles']['role'];
            }            
            
            return $returnRole;       
        }
        
        /**
         * To get context_id by context
         * @param type $context
         * @return int
         */
        public function getContextId($context){
            if($context == ''){
                return;
            }
            $this->setSource('contexts');
            $condition = array('name'=>$context);
            $conData = $this->find('first', array('conditions'=>$condition, 'fields'=>'id'));
            $this->setSource('users');
            if($conData){
                return $conData['User']['id']; 
            }
            else{
                return 0;
            }
            
        }
        
        /**
         * To get role_id by role 
         * @param type $role
         */
        public function getRoleId($role){
            if($role == ''){
                return;
            }
            $this->setSource('roles');
            $condition = array('role'=>$role);
            $roleDetail = $this->find('first', array('conditions'=>$condition, 'fields'=>'id'));
            $this->setSource('users');
            if($roleDetail){
                return $roleDetail['User']['id'];
            }
            else{
                return 0;
            }
        }
        
        /**
         * To get context_role_id by context_id and role_id
         * @param type $contextId
         * @param type $roleId
         * @return int
         */
        public function getContextRoleId($contextId, $roleId){
            $this->setSource('context_roles');
            //$this->VerifyParent->setSource('context_roles');
            $condition = array('context_id'=>$contextId, 'role_id'=>$roleId);
            $contRole = $this->find('first', array('conditions'=>$condition, 'fields'=>'id'));
            $this->setSource('users');
            if($contRole){
                return $contRole['User']['id'];
            }
            else{
                return 0;
            }
        }
        
        /**
         * Function to get user full name by user_id
         * @param type $userId
         * @return type
         */
        public function getUserName($userId){
            $userDetail = $this->find('first', array('conditions'=>array('User.id'=>$userId), 'fields'=>array('first_name', 'last_name')));
            return $userName = $userDetail['User']['first_name'].' '.$userDetail['User']['last_name'];
        }
        /**
         * Function to get user profile pic
         * @param type $userId
         * @return type
         */
        public function getUserProfilePic($userId){
            @$userDetail = $this->find('first', array('conditions'=>array('User.id'=>@$userId), 'fields'=>array('user_image')));
            return $userPic = @$userDetail['User']['user_image'];
        }
        
        /**
         * To get numbers of users
         */
        public function getNumUsers(){
            $numUsers = $this->find('count');
            return $numUsers;
        }
        /**
         * To get numbers of users only sage seeker active
         */
        public function getSageSeekerActiveUser(){
            $numUsers = $this->find('count');
            //SELECT u.id,first_name,r.role FROM users u LEFT JOIN context_role_users crs ON u.id = crs.user_id LEFT JOIN context_roles cr ON crs.context_role_id = cr.id LEFT JOIN roles r ON cr.role_id = r.id WHERE (role='Sage' OR role='Seeker')AND u.registration_status ='1'
            //SELECT u.id,first_name,r.role,u.registration_status FROM users u LEFT JOIN context_role_users crs ON u.id = crs.user_id LEFT JOIN context_roles cr ON crs.context_role_id = cr.id LEFT JOIN roles r ON cr.role_id = r.id
            $sql = "SELECT count(u.id) as sage_seeker FROM users u LEFT JOIN context_role_users crs ON u.id = crs.user_id LEFT JOIN context_roles cr ON crs.context_role_id = cr.id LEFT JOIN roles r ON cr.role_id = r.id WHERE (role='Sage' OR role='Seeker') and ( registration_status=0 OR registration_status=1)";
            $user = $this->query($sql);
            $num = $user[0][0]['sage_seeker'];
            return $num;
        }

        
        /**
         * To get number of experts == sage
         */
        public function getNumExpert(){
            $sql = "select count(id) as num_experts from context_role_users where context_role_id in (select id from context_roles where role_id in 
                (select id from roles where role like 'Sage%'))";
            $user = $this->query($sql);
            $numExperts = $user[0][0]['num_experts'];
            return $numExperts;
           
        }

        /**
         * To get number of experts == sage
         */
        public function getNumActiveExpert(){
            $sql = "SELECT count(u.id) as num_experts FROM users u LEFT JOIN context_role_users crs ON u.id = crs.user_id LEFT JOIN context_roles cr ON crs.context_role_id = cr.id LEFT JOIN roles r ON cr.role_id = r.id WHERE (role='Sage') and ( registration_status=0 OR registration_status=1)";
            $user = $this->query($sql);
            $numExperts = $user[0][0]['num_experts'];
            return $numExperts;
           
        }

        /**
         * To get number of active seeker
         */
        public function getNumActiveSeeker(){
            $sql = "SELECT count(u.id) as num_seekers FROM users u LEFT JOIN context_role_users crs ON u.id = crs.user_id LEFT JOIN context_roles cr ON crs.context_role_id = cr.id LEFT JOIN roles r ON cr.role_id = r.id WHERE (role='Seeker') and ( registration_status=0 OR registration_status=1)";
            $user = $this->query($sql);
            $numExperts = $user[0][0]['num_seekers'];
            return $numExperts;
           
        }
          /**
         * To get number of seekers = enterprenur
         */
        public function getNumSeekers(){
            $sql = "select count(id) as num_seekers from context_role_users where context_role_id in (select id from context_roles where role_id in 
                (select id from roles where role like 'Seeker%'))";
            $user = $this->query($sql);
            $numExperts = $user[0][0]['num_seekers'];
            return $numExperts;
           
        }
        
        /**
         * To get user detail by context role user id
         * @param type $contextRoleUserId
         */
        public function userByContextRoleUserId($contextRoleUserId) {
            $sql = "select users.id, users.first_name, users.last_name, users.user_image from users left join context_role_users cru on users.id=cru.user_id
                 where cru.id=$contextRoleUserId ";
            $userDetail = $this->query($sql);
            $userDetail['users'] = $userDetail[0]['users'];
            return $userDetail;
        }
        
        /**
         * To get user role by user id 
         * @param type $userId
         * @return string
         */
        public function userRoleByUserId($userId){
            $result = $this->query("select contexts.name, roles.role from context_role_users cru 
                left join context_roles on cru.context_role_id=context_roles.id
                left join contexts on context_roles.context_id=contexts.id
                left join roles on context_roles.role_id=roles.id where cru.user_id='$userId' ");
            
            $userRole = '';
            if(!empty($result)){
                $context = $result[0]['contexts']['name'];
                $role = $result[0]['roles']['role'];
                
                if(strtoupper($role) == 'JUDGE'){
                    $userRole = 'Judge';
                }
                else if(strtoupper($context) == 'PIONEER' ){
                    $userRole = 'Pioneer';
                }
                elseif(strtoupper($context) == 'GENERAL' && strtoupper($role) == 'ADMINISTRATOR'){
                    $userRole = 'Administrator';                           
                }
                else{
                    $userRole = 'Visitor';
                }
            }            
            
            return $userRole; 
        }
        
        /**
         * Function to get user short profile
         * @param type $userId
         * @return type
         */
        public function getUserShortProfile($userId){
            $userDetail = $this->find('first', array('conditions'=>array('User.id'=>$userId), 'fields'=>array('first_name', 'last_name', 'user_image','country_id','registration_date','gender','group_code','username')));
   

            $detail['image'] = $userDetail['User']['user_image'];
            $detail['first_name'] = $userDetail['User']['first_name'];
            $detail['last_name'] = $userDetail['User']['last_name'];
            $detail['country_id'] = $userDetail['User']['country_id'];
            $detail['registration_date'] = $userDetail['User']['registration_date'];
            $detail['gender'] = $userDetail['User']['gender'];
	    $detail['group_code'] = $userDetail['User']['group_code'];
            $detail['context_role_id'] = $userDetail['ContextRoleUser'][0]['context_role_id'];
            $detail['context_user_role_id'] = $userDetail['ContextRoleUser'][0]['id'];
            $detail['username'] = $userDetail['User']['username'];
              

            return $detail;
        }
        
        /**
         * To get numbers of online users
         */
        public function getNumOnlineUsers(){
            $numUsers = $this->find('count', array('conditions'=>array('login_status'=>1)));
            return $numUsers;
        }

        function equaltofield($check,$otherfield)
       {
        //get name of field
        $fname = '';
        foreach ($check as $key => $value){
            $fname = $key;
            break;
        }
        return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname];
       } 

       public function getUserCountryName($country_id){
  
          $this->setSource('countries');
          $sql = "select country_title FROM countries WHERE id = '$country_id'";
        return $result = $this->query($sql);
    }

    

}

?>