<?php
require(APPPATH.'libraries/REST_Controller.php');

class Games extends REST_Controller {
    
   function allgames_get() {
      {  $this->load->database();
        
        $sql = 'SELECT * FROM games;';
        $query = $this->db->query($sql);
        $data = $query->result();
        
      }  $this->response($data, 200);
    }

   function id_get($id = null) {
      //{
        // $this->load->database();
         //$sql = 'SELECT * FROM games;';
         //$query = $this->db->query($sql);
         //$data = $query->result();
     
         //$this->response($data, 200);
      //}  
  
      {
         if (!isset($id)) {
            $info->status = 'failure';
            $info->error->code = 36;
            $info->error->text = 'game ID not specified in URI';
            $this->response($info, 400);
         }
      }
  
      {   // checking to see if the game resource exists
            $this->load->database();
            $sql = 'SELECT COUNT(id) AS records FROM games WHERE id = '.$id.';';
            //$this->response($sql, 200);
            $query = $this->db->query($sql);
            $data = $query->row();
            if ($data->records == "0") {
                $info->status = 'failure';
                $info->error->code = 44;
                $info->error->text = 'game id resource does not exist';
                $this->response($info, 404);
            }
      }   
   
      {   // retrieve the game data...
            $sql = 'SELECT * FROM games WHERE id = "'.$id.'";';
            $headers = apache_request_headers();
            if (isset($headers['content']) && $headers['content'] == 'summary') {
                $sql = 'SELECT id, title, genre, publisher, developer, platform, release_date FROM games WHERE id = "'.$id.'";';
            }
            $query = $this->db->query($sql);
            $data = $query->row();
            unset($data->publicdomain);
            $data->id = intval($data->id);
            if (!empty($data->pages)) $data->pages = intval($data->pages);
            //if (!empty($data->genreid)) $data->genreid = intval($data->genreid);
            $info->status = 'success';
            $info->bytes = 0;
            $info->games = $data;
            //$this->response($data, 200);
            $json = json_encode($info);
            $info->bytes = strlen($json);
            $this->response($info, 200);
        }   
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
            $headers = apache_request_headers();
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
   
   
   
}
