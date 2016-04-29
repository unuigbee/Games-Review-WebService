<?php
require(APPPATH.'libraries/REST_Controller.php');

class reviews extends REST_Controller {
    function allreviews_get() {
        {
          $this->load->database();

          $sql = 'SELECT review_id, review_title, review_article, DATE_FORMAT(review_date, "%D %M %Y") as date, review_rating FROM reviews;';
          $query = $this->db->query($sql);
          $data = $query->result();

          foreach ($data as $reviews) {
                  $reviews->selfLink = 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/reviews/id/'.$reviews->review_id;
          }

          $info->status = 'success';
          $info->bytes = 0;
          $info->selfLink = 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/reviews/allreviews/';
          $info->reviews = $data;
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
            $info->error->text = 'review ID not specified in URI';
            $this->response($info, 400);
         }
      }

      {   // checking to see if the game resource exists
            $this->load->database();
            $sql = 'SELECT COUNT(review_id) AS records FROM reviews WHERE review_id = '.$id.';';
            //$this->response($sql, 200);
            $query = $this->db->query($sql);
            $data = $query->row();
            if ($data->records == "0") {
                $info->status = 'failure';
                $info->error->code = 44;
                $info->error->text = 'review id resource does not exist';
                $this->response($info, 404);
            }
      }

      {   // retrieve the review data...
            $sql = 'SELECT review_id, review_title, review_article, DATE_FORMAT(review_date, "%D %M %Y") as date, review_rating FROM reviews;';
            $headers = apache_request_headers();
            if (isset($headers['content']) && $headers['content'] == 'summary') {
                 $sql = 'SELECT review_id, review_title, review_article, DATE_FORMAT(review_date, "%D %M %Y") as date, review_rating FROM reviews;';
            }
            $query = $this->db->query($sql);
            $data = $query->row();
            unset($data->publicdomain);
            $data->review_id = intval($data->review_id);
            if (!empty($data->pages)) $data->pages = intval($data->pages);
            //if (!empty($data->genreid)) $data->genreid = intval($data->genreid);
            $info->status = 'success';
            $info->bytes = 0;
            $info->reviews = $data;
            //$this->response($data, 200);
            $json = json_encode($info);
            $info->bytes = strlen($json);
            $this->response($info, 200);
      }
   }

    function newreview_post() {

        $this->load->database();
        $review_title = $_POST['review_title'];
        $review_article = $_POST['review_article'];
        $review_rating = $_POST['review_rating'];
        $review_date = $_POST['review_date'];
        $info = array('review_id'=>null, 'review_title'=>$review_title, 'review_article'=>$review_article, 'review_rating'=>$review_rating, 'review_date'=>$review_date);
        $this->db->insert('users', $info);
        $data->review_id = $this->db->insert_id();
        $data->review_title = $review_title;
        $data->review_article = $review_article;
        $data->review_rating = $review_rating;
        $data->review_date = $review_date;



        $this->response($data, 200);
    }



}
