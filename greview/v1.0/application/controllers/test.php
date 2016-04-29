

<?php
require(APPPATH.'libraries/REST_Controller.php');

class Test extends REST_Controller {
	
	function simpletest_get() {
		$data->name = 'HELLO';
		$data->university = 'coventry';
		$this->response($data, 200);	
	}

    function books_get() {
        $this->load->database();
        $sql = 'SELECT * FROM books;';
        $query = $this->db->query($sql);
        $data = $query->result();
        
        $this->response($data, 200);
    }
    
    function book_get($book_id) {
        $this->load->database();
        $sql = 'SELECT * FROM books WHERE code = "'.$book_id.'";';
        $query = $this->db->query($sql);
        $data = $query->row();
        
        $this->response($data, 200);
    }
}


