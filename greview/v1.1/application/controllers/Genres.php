<?php
require(APPPATH.'libraries/REST_Controller.php');

class Genres extends REST_Controller {

    function allgenres_get() {
        {
            $this->load->database();
        
            $sql = 'SELECT game_genre.game_id as id, genres.genretitle, COUNT(game_genre.game_id) as records FROM game_genre ';
            $sql .= 'INNER JOIN genres ON game_genre.genre_id = genres.genreid GROUP by genres.genreid;';
            $query = $this->db->query($sql);
            $data = $query->result();
        
            //$this->response($data, 200);
            
            foreach ($data as $genres) {
                $genres->selfLink = 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/genres/id/'.$genres->id;
            }
            
            $info->status = 'success';
            $info->bytes = 0;
            $info->selfLink = 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/genres/allgenres/';
            $info->genres = $data;
            $json = json_encode($info);
            $info->bytes = strlen($json);
            $this->response($info, 200);
            
        }   
    }
    
    function id_get($id = null) {
        {
            if (!isset($id)) {
                $info->status = 'failure';
                $info->error->code = 36;
                $info->error->text = 'genres ID not specified in URI';
                $this->response($info, 400);
            }
        }
        
        {   // checking to see if the genres resource exists
            $this->load->database();
            $sql = 'SELECT COUNT(game_genre.genre_id) as records FROM game_genre ';
            $sql .= 'INNER JOIN genres ON game_genre.genre_id = genres.genreid WHERE game_genre.game_id = '.$id.';';
            //$this->response($sql, 200);
            $query = $this->db->query($sql);
            $data = $query->row();
            $gameCount = $data->records;
            if ($data->records == "0") {
                $info->status = 'failure';
                $info->error->code = 44;
                $info->error->text = 'No games found that match the specified id';
                $this->response($info, 404);
            }
        }
        
        {   // retrieve the matching records...
            $headers = apache_request_headers();
            $sql ='SELECT games.id as id, games.title, games.publisher, games.thumbnail, games.developer, games.platform, games.release_date FROM game_genre ';
            $sql .= 'INNER JOIN games ON game_genre.game_id = games.id WHERE game_genre.genre_id = '.$id.';';
           if (isset($headers['content']) && $headers['content'] == 'summary') {
                    $sql ='SELECT games.id as id, games.title, games.publisher, games.thumbnail, games.developer, games.platform, games.release_date FROM game_genre ';
                    $sql .= 'INNER JOIN games ON game_genre.game_id = games.id WHERE game_genre.genre_id = '.$id.';';   
            }
            $query = $this->db->query($sql);
            $data = $query->result();
            foreach ($data as $genres) {
                $game->selfLink = 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/games/id/'.$genres->id;
            }
            $info->status = 'success';
            $info->gameCount = $gameCount;
            $info->bytes = 0;
            $info->selfLink = 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/genres/id/'.$id;
            $info->games = $data;
            $json = json_encode($info);
            $info->bytes = strlen($json);
            $this->response($info, 200);
        }
        
    }    
    
    
}
