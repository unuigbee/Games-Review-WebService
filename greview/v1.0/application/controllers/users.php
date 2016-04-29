<?php
require(APPPATH.'libraries/REST_Controller.php');

class users extends REST_Controller {
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
            $info->games = $data;
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
        $info = array('id'=>null, 'username'=>$username, 'password'=>MD5($password), 'firstname'=>$firstname, 'lastname'=>$lastname, 'email'=>$email);
        $this->db->insert('users', $info);
        $data->id = $this->db->insert_id();
        $data->username = $username;
        $data->password = $password;
        $data->firstname = $firstname;
        $data->lastname = $lastname;
        $data->email = $email;
        
        $this->response($data, 200);
    }
    
    function login_post() {
        
            $headers = apache_request_headers();
        {
            if(empty($headers['Authorization'])){
               //token missing
                $info->status = 'failure';
                $info->error->code = 47;
                $info->error->text = 'Basic Authentication required to login';
                $this->response($info, 401);
            }
            //$this->load->database();
            //$username =$_POST['username'];
           // $password =$_POST['password'];
            
           else {
                $string =base64_decode($headers['Authorization']);
                //$sql  = 'SELECT * FROM users WHERE MATCH(username)';
                //$sql .= 'AGAINST("'.$username.'");';
                //if (isset($headers['content']) && $headers['content'] == 'summary') {
                    //$sql  = 'SELECT username, password FROM users ';
                   // $sql .= 'WHERE MATCH(username) AGAINST("'.$username.'");';
                 //}
                
              //$query = $this->db->query($sql);
              //$data = $query->result();
                list($username,$password) = explode(':',$string);
                
                $info = array('password'=>$password,'username'=>$username);
                
                $data->username = $username;
                $data->password = $password;
               
                $this->response($data, 200);
                }
            
        }
        }

}


  

       // $username = $_POST['username'];
      //  $password = $_POST['password'];
   //     $password = MD5($password);
        
    //    $this->load->database();
   //     $sql= "SELECT username, password FROM users WHERE username = '$username' AND password = '$password'";
   //    $query = $this->db->query($sql);
       
        
     //   $data->data = $query->row();
  //      $result = mysql_query($sql);
  //      $data->username = $username;
   //     $data->password = $password;
   //     if (!empty($data->pages))
   //         $data->pages = intval($data->pages);
  //          $info->status = 'success';
   //         $info->bytes = 0;
   //         $info->users = $data;
            //$this->response($data, 200);
    //        $json = json_encode($info);
     //       $info->bytes = strlen($json);
    //        $this->response($info, 200);
          //$data->username = $username;
      //  $data->password = $password;
        
        //$this->response($data, 200);
