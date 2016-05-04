<?php
require(APPPATH.'libraries/REST_Controller.php');

class Users extends REST_Controller {
    
    function allusers_get() {
        $this->load->database();
        
        $sql = 'SELECT * FROM users;';
        $query = $this->db->query($sql);
        $data = $query->result();
        
        $this->response($data, 200);
    }
    
    function userid_get($id = null) {       
  
      {
         if (!isset($id)) {
            $info->status = 'failure';
            $info->error->code = 36;
            $info->error->text = 'users ID not specified in URI';
            $this->response($info, 400);
         }
      }
  
      {   // checking to see if the game resource exists
            $this->load->database();
            $sql = 'SELECT COUNT(id) AS records FROM users WHERE id = '.$id.';';
            //$this->response($sql, 200);
            $query = $this->db->query($sql);
            $data = $query->row();
            if ($data->records == "0") {
                $info->status = 'failure';
                $info->error->code = 44;
                $info->error->text = 'users id resource does not exist';
                $this->response($info, 404);
            }
      }   
   
        {   // retrieve the game data...
            $sql = 'SELECT * FROM users WHERE id = "'.$id.'";';
            $headers = apache_request_headers();
            if (isset($headers['content']) && $headers['content'] == 'summary') {
                $sql = 'SELECT id, username, password, firstname, lastname, email FROM users WHERE id = "'.$id.'";';
            }
            $query = $this->db->query($sql);
            $data = $query->row();
            unset($data->publicdomain);
            $data->id = intval($data->id);
            if (!empty($data->pages))
            $data->pages = intval($data->pages);
            $info->status = 'success';
            $info->bytes = 0;
            $info->users = $data;
            //$this->response($data, 200);
            $json = json_encode($info);
            $info->bytes = strlen($json);
            $this->response($info, 200);
        }
    }    
  
    function newaccount_post() {
        
        $this->load->database();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $variable= base64_encode($username.":".$password);  
       
        
        {   // checking to see if the username already exists
            $this->load->database();
            $sql = 'SELECT COUNT(username) as records from users WHERE username="'.$username.'";';
            //$this->response($sql, 200);
            $query = $this->db->query($sql);
            $data = $query->row();
            
            if ($data->records == "1") {
                $info->status = 'failure';
                $info->error->code = 37;
                $info->error->text = 'Username already exits';
                $this->response($info, 404);
            }
        }
        $info = array('id'=>null, 'username'=>$username, 'password'=>MD5($password), 'firstname'=>$firstname, 'lastname'=>$lastname, 'email'=>$email, 'basic_http_auth'=>$variable);
        $this->db->insert('users', $info);
        $data->id = $this->db->insert_id();
        $data->username = $username;
        $data->password = $password;
        $data->firstname = $firstname;
        $data->lastname = $lastname;
        $data->email = $email;
        $data->basic_http_auth = $variable;
        $this->response($data, 200);
    }
    
    
    function login_post() {
        
       
        $this->load->database();
        //$username = $_POST['username'];
        //$password = $_POST['password'];
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];    
            // do whatever here
        
       // if (isset($_POST['password'])) {
       //     $password = $_POST['password'];
       //     // do whatever here
           
        $headers = apache_request_headers();
        
        
            {
           // checking to see if the username and password  is correct
            $this->load->database();
            $sql = 'SELECT COUNT(username) as records from users WHERE username="'.$username.'" AND password="'.MD5($password).'";';
            //$sql = 'SELECT COUNT(username) as records FROM users WHERE username = '.$username.' AND password = '.$password.' LIMIT 1;';
            //$this->response($sql, 200);
            $query = $this->db->query($sql);
            $data = $query->row();
            $account = $data->records; 
            if ($data->records == "0") {
                $info->status = 'failure';
                $info->error->code = 34;
                $info->error->text = 'invalid login credentials';
                $this->response($info, 404);
            }
            }
        
        
        
            {    
            if(empty($headers['Authorization'])){
               //token missing
                
                $info->status = 'failure';
                $info->error->code = 47;
                $info->error->text = 'Basic Authentication required to login';
                $this->response($info, 401);
            }
            }
            {
                
                
                
                $string = base64_decode($headers['Authorization']);
                list($username,$password) = explode(':',$string);
                
                
                $sql  = 'SELECT username, password FROM users WHERE username="'.$username.'" AND password="'.MD5($password).'";';
                if (isset($headers['content']) && $headers['content'] == 'summary') {
                $sql  = 'SELECT username, password FROM users WHERE username="'.$username.'" AND password="'.MD5($password).'";';
                }
                
                //$sql .= 'AGAINST("'.$username.'");';
                $query = $this->db->query($sql);
                $data = $query->row();
                $info->status = 'success';
                
                $info->bytes = 0;
                $info->selfLink = 'http://creative.coventry.ac.uk/~4086947/greview/v1.0/index.php/users/login/';
                $info->users = $data;
                $json = json_encode($info);
                $info->bytes = strlen($json);
                $this->response($info, 200);
                
                
          
            }
            
           
          }  
        
        
        
    }
}
