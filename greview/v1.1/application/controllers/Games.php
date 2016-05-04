<?php
require(APPPATH.'libraries/REST_Controller.php');

class Games extends REST_Controller {

   function allgames_get() {  //defines a get method for getting all games *note the _get appended to function name to mark it as a get method*

            $this->load->database(); //$this is a pseudo variable that refers to the current object or class.

            $sql = 'SELECT id, title, developer, thumbnail FROM games;'; //Defined a variable that has an assigned string representing an sql statement
            $query = $this->db->query($sql); //$query variable that holds the query method that takes an sql statement of type string
            $data = $query->result(); //calls the result method to return the result of querying the databse. returns an array of objects

            foreach ($data as $game) {//loops through each game object and assigns the property selfLink property to $game object
                $game->selfLink = site_url('games/id/') . $game->id;
            }

            $info = new stdClass();
            $info->status = 'success'; //assigns properties to the $info object
            $info->bytes = 0;
            $info->selfLink = site_url('games/allgames');
            $info->games = $data;
            $json = json_encode($info); //converts and encodes the $info object to a JSON format
            $info->bytes = strlen($json); //gets the string length of the $json object and assigns it to the bytes property
            $this->response($info, 200); //calls the response method of the current object which takes the $info object and a status code as parameters.
                                        //the response method is used to send the $info data and the status code back to the client
    }

   function id_get($id = null) {//defines a get method to get and return to the client a game by id

        //first we write some defensive code to catch when the id is not specified
         if (!isset($id)) {
            $info->status = 'failure';
            $info->error->code = 36;
            $info->error->text = 'game ID not specified in URI';
            $this->response($info, 400);
         }

          // checking to see if the game resource exists
          $this->load->database();
          $sql = 'SELECT COUNT(id) AS records FROM games WHERE id = '.$id.';';

          $query = $this->db->query($sql); //query the databse using the sql statement
          $data = $query->row(); //returns a single row of data representing a single object
          if ($data->records == "0") { //if statement to check if a records exists and runs the
              $info->status = 'failure';
              $info->error->code = 44;
              $info->error->text = 'game id resource does not exist';
              $this->response($info, 404);
          }

          // retrieve the game data...
          $sql = 'SELECT * FROM games WHERE id = "'.$id.'";';
          $headers = apache_request_headers(); //requests and returns an array of header information from the client request
          if (isset($headers['content']) && $headers['content'] == 'summary') {//if header parameters or array has parameter or key called content with a value summary
              $sql = 'SELECT id, title, genre, publisher, developer, platform, release_date FROM games WHERE id = "'.$id.'";';
          }
          $query = $this->db->query($sql);
          $data = $query->row();
          //unset($data->publicdomain);
          $data->id = intval($data->id);  //intval() converts a variable or value to an integer. In this case the id.
          //if (!empty($data->pages)) $data->pages = intval($data->pages);
          //if (!empty($data->genreid)) $data->genreid = intval($data->genreid);
          $info->status = 'success';
          $info->bytes = 0;
          $info->games = $data;
          //$this->response($data, 200);
          $json = json_encode($info);
          $info->bytes = strlen($json);
          $this->response($info, 200);
   }

   function title_get($title = null) {

        {   // checking for valid parameters...
            if (!isset($title)) {
                $info->status = 'failure';
                $info->error->code = 36;
                $info->error->text = 'title not specified in URI';
                $this->response($info, 400);
            }
        }
        {   // see if any records are returned...
            $this->load->database();
            $sql  = 'SELECT COUNT(id) AS records FROM games ';
            $sql .= 'WHERE MATCH(title) AGAINST ("'.$title.'");';
            $query = $this->db->query($sql);
            $data = $query->row();
            $gameCount = $data->records;
            if ($data->records == "0") {
                $info->status = 'failure';
                $info->error->code = 44;
                $info->error->text = 'No games found that match the specified title';
                $this->response($info, 400);
            }
        }
        {   // retrieve the matching records...
            $headers = apache_request_headers(); //returns an associative array of all the HTTP headers in the current request
            $sql  = 'SELECT * FROM games WHERE MATCH(title)';
            $sql .= 'AGAINST("'.$title.'") ORDER BY release_date;';
            if (isset($headers['content']) && $headers['content'] == 'summary') {
                $sql  = 'SELECT id, title, genre, publisher, developer, platform, release_date FROM games ';
                $sql .= 'WHERE MATCH(title) AGAINST("'.$title.'") ORDER BY release_date;';
            }
            $query = $this->db->query($sql);
            $data = $query->result();
            foreach ($data as $game) {
                $game->link = 'http://creative.coventry.ac.uk/~4086947/greview/v1.0/index.php/games/id/'.$game->id;
            }
            $info->status = 'success';
            $info->gameCount = $gameCount;
            $info->bytes = 0;
            $info->selfLink = 'http://creative.coventry.ac.uk/~4086947/greview/v1.0/index.php/games/title/'.$title;
            $info->games = $data;
            $json = json_encode($info);
            $info->bytes = strlen($json);
            $this->response($info, 200);
        }
    }

   function addid_post($id = null) {


   }

}
