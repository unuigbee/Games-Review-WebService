var reviewid; //book isbn to pass to other functions once logged in
var Authorisation; //login details to pass to other functions after login

function Getgames(){
    $.ajax({
        url: 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/games/allgames',
        dataType: 'json',
        success: function(data) {
    		  $.each(data.games, function(i,games){
    			     $('#output1').append('<tbody><th class="label"><a href="" onclick="Getgame('+games.id+')">'+games.title+'</a><h4>'+games.developer+'</h4><a href=""></th><th><img src="images/'+games.thumbnail+'.png" width="100px" height="150px"></img></a></th></tbody>');

          });
		      $.mobile.changePage("#productlist"); //show the results page
		      $('#output1').table('refresh');

        },
	      error: function (response) {
		        var r = jQuery.parseJSON(response.responseText);
		        alert("Message: " + r.Message);
				}
	  });
}

function Getgame(id){
    $.ajax({
        url: "http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/games/id/"+id,
        dataType: 'json',
        success: function(data) {
	          $('#gthumbnail').empty();       //Empty games related data to stop duplication of data upon execution of back button
            $('#gtitle').empty();
            $('#gdescription').empty();
            $('#gplatform').empty();
            $('#gdeveloper').empty();
            $('#gpublisher').empty();
            $('#grelease_date').empty();
	          $('#gscore').empty();
            $('#grating').empty();


	          $('#gthumbnail').append('<img src="images/'+data.games.thumbnail+'.png" width="100px" height="150px">');//different id names to add flexibility to deciding where to add data in html page
            $('#gtitle').append('<p>'+data.games.title+'</p>');
            $('#gscore').append('<h3>Gamer Score: '+data.games.rating+'</h3>');
            $('#grating').append('<label for="slider-10">Ratings:</label><input name="slider-10" id="slider-10"  disabled="disabled" min="0" max="10" step=".1" value="'+data.games.rating+'" type="range">');
            $('#gdescription').append('<p>'+data.games.description+'</p>');
            $('#gplatform').append('<h4>platform: '+data.games.platform+'</h4>');
            $('#gdeveloper').append('<h4>developer: '+data.games.developer+'</h4>');
            $('#gpublisher').append('<h4>publisher: '+data.games.publisher+'</h4>');
            $('#grelease_date').append('<h4>Release Date: '+data.games.release_date+'</h4>');
	          $.mobile.changePage("#productdisplay"); //show the results page
		    },
	      error: function (response) {
		        var r = jQuery.parseJSON(response.responseText);
		        alert("Message: " + r.Message);
			  }
	  });
  }

function imgError(image) {
    image.onerror = "";
    image.src = "placeholder.jpg";
    image.height = (150);
    image.width = (150);
    return true;
}


function Getgenre(){
    $.ajax({
        url: 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/genres/allgenres', //Link to API
        dataType: 'json',
        success: function(data) {
  		    $.each(data.genres, function(i,genres){
  			       $('#output2').append('<tbody><th class="label"><a href="" onclick="Getgenres('+genres.id+')">'+genres.genretitle+'</a></th></tbody>');//table Structure for genrelist
          });
  		    $.mobile.changePage("#genrelist"); //show the results page
  		    $('#output2').table('refresh');

        },
	      error: function (response) {
  		    var r = jQuery.parseJSON(response.responseText);
  		    alert("Message: " + r.Message);
				}
	  });
}

function Getgenres(id){
    $.ajax({
        url: 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/genres/id/'+id, //Link to API
        dataType: 'json',
        success: function(data) {
          $('#output3').empty();
          reviewsid = id;
		      $.each(data.games, function(i,games){

            $('#output3').append('<tbody><th class="label"><a href="" onclick="Getgame('+games.id+')">'+games.title+'</a><h4>'+games.developer+'</h4><a href=""></th><th><img src="images/'+games.thumbnail+'.png" width="100px" height="150px"></img></a></th></tbody>');//table Structure for gamesbygenre list

          });
          $.mobile.changePage("#gamesbygenre");
        },
	      error: function (response) {
		        var r = jQuery.parseJSON(response.responseText);
		        alert("Message: " + r.Message);
				}
	  });
}


function Getreviews(){
    $.ajax({
        url: "http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/reviews/allreviews/", //Link to API
        dataType: 'json',
	      success: function(data) {
		        //reviewsid = id;
		        $.each(data.reviews, function(i,reviews){
			       $('#output4').append('<tbody><th class="label"><a href="" onclick="Getreview('+reviews.review_id+')"><h3>Title: '+reviews.review_title+'</h3></a><h3>Review by: '+reviews.review_username+'</h3></th></tbody>');//table Structure for reviewlist

            });
		        $.mobile.changePage("#reviewlist"); //show the results page
		        $('#output4').table('refresh');
        },
	      error: function (response) {
  		    var r = jQuery.parseJSON(response.responseText);
  		    alert("Message: " + r.Message)
				},

	  });
}



function Getreview(review_id){
    $.ajax({
        url: 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/reviews/id/'+review_id,
        dataType: 'json',
        success: function(data) {
	          $('#rarticle').empty();       //Empty games related data to stop duplication of data upon execution of back button
            $('#rtitle').empty();
            $('#rrating').empty();
            $('#rdate').empty();
            $('#rusername').empty();
            $('#rgametitle').empty();

            $('#rarticle').append(' '+data.reviews.review_article+'');
	          $('#rtitle').append(''+data.reviews.review_title+'');//different id names to add flexibility to deciding where to add data in html page
            $('#rrating').append('Rating: '+data.reviews.review_rating+'');
            $('#rdate').append(' Review Date: '+data.reviews.date+'');
	          $('#rusername').append(' Reviewed By: '+data.reviews.review_username+'');
            $('#rgametitle').append(' Game Title: '+data.reviews.review_game_title+'');
	          $.mobile.changePage("#reviewpage"); //show the results page
		    },
	      error: function (response) {
		        var r = jQuery.parseJSON(response.responseText);
		        alert("Message: " + r.Message);
				}
	  });
}

function Postaccount(){ //create a new user account using POST

    $("#Register").submit(function(evt){
      evt.preventDefault();   //stops default submission process from executing. calls ajax instead
	    $.ajax({
        type:'POST',
	      url: 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/users/newaccount/',
	      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
        dataType: 'json',
        data: $(this).serialize(),
	      success: function(data) {
          alert("Account created. Please now login");
			    $.mobile.changePage("#logreg"); //show the login page
        },
	      error: function (response) {
	         var r = jQuery.parseJSON(response.responseText);
	         console.log(r);
		       alert("Error:" + r.error.text);
				}
			});
	    this.reset();//clears form details after submission
    });
}

function createCookie(name,value,days) { //create a cookie to store authorisation details on the clients machine
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) { //read the cookie on the clients machine
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}


function Login(){

    $("#Login").submit(function(evt){
      evt.preventDefault();
      $.ajax({
          type:'POST',
  	      url: 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/users/login/',
  	      beforeSend: function(xhr){xhr.setRequestHeader("Authorization", window.btoa($("#username").val()+':'+$("#password").val()));},
  	      headers: {Authorization:window.btoa($("#username").val()+':'+$("#password").val())},
  	      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
          dataType: 'json',
          data: $(this).serialize(),
  	      success: function(data) {
  			       Authorisation = window.btoa($("#username").val()+':'+$("#password").val()); //create a variable to pass to the cookie
  			       createCookie('auth', Authorisation, 0); //create our authorisation cookie for the client
  			       alert("Details correct. Please continue.");
  			       $("#reglog").hide(); //hide the login button
        			//$("#create").hide(); //hide the create an account buttons
        			//$("#logout").show(); //show the logout button
        			//$("#addreview").show(); //show the add a review button
        			//$("#adminpanel").show();//show the admin panel page

  			       $("#username").val(''); //clear the name box
  			       $.mobile.changePage("#home"); //show the menu
          },
  	      error: function (response) {
  	       var r = jQuery.parseJSON(response.responseText);
  	       console.log(r);
  		     alert("Error:" + r.error.text);
  				}
  			});
    });
}

function AddGameTitle(){//function for choosing games title for posting review
    $.ajax({
        url: 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/games/allgames',
        dataType: 'json',
        success: function(data) {
  		    $.each(data.games, function(i,games){
            $('#output9').append('<div class = "ui-bar ui-bar-b"><label for="review_game_title">'+games.title+'</label><input type="radio" name="review_game_title" id="review_game_title" value="'+games.title+'"></div>');
            $("#button").hide();//disables button
          });
  		    $.mobile.changePage("#reviewpost"); //show the results page
  		    $('#output9').select('refresh');
        },
	      error: function (response) {
		        var r = jQuery.parseJSON(response.responseText);
		        alert("Message: " + r.Message);
				}
	     });
       this.reset();
}

function Addreview(){ //Add a review to the site
    $("#postreview").submit(function(evt){
    evt.preventDefault();
    $.ajax({
	     type:'POST',
		url: 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/reviews/newreview/',
	     headers: {Authorization:readCookie('auth')}, //use the cookie for authentication
	     data: $(this).serialize(),//creates a text string in standard URL-encoded
       dataType: 'json', //isbn:reviewisbn
       success: function(data) {
  			alert("Review Added. Please check your review in home page to view your review.");
  			$("#review_title").val(''); //clear the text boxes
  			$("#review_article").val('');
  			$("#review_rating").val('');
  			$("#review_username").val('');
  			$("#review_game_title").val('');
  			$.mobile.changePage("#home");
       },
	     error: function (response) {
  	    var r = jQuery.parseJSON(response.responseText);
  	    console.log(r);
		    alert("Error:" + r.error.text);
			 }
			});
      this.reset();
    });
}

function Deleteagame(){ //delete a book
    $("#deletegame").submit(function(evt){
      evt.preventDefault();
      $.ajax({
  	     type:'DELETE',
         url: 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/games/deletegamebyid/'+$("#gidtodelete").val(),
         dataType: 'json',
  	     headers: {Authorization:readCookie('auth')},//use the cookie for authentication
         data: $(this).serialize(),
  	     success: function(data) {
    	     $("#gidtodelete").val('');
    	     alert("Game " +$("#gidtodelete").val()+ " deleted");
    	     $.mobile.changePage("#adminpage"); //show the menu
  		   },
  	     error: function (response) {
  		      var r = jQuery.parseJSON(response.responseText);
  		      alert("Message: " + r.Message);
  			 }
  	  });
  });
}

function Deleteareview(){ //delete a book
    $("#deletereview").submit(function(evt){
      evt.preventDefault();
      $.ajax({
  	      type:'delete',
          url: 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/reviews/deletereviewbyid/'+$("#ridtodelete").val(),
          headers: {Authorization: readCookie('auth')},  //use the cookie for authentication
  	      dataType: 'json',
          data: $(this).serialize(),
  	      success: function(data) {
      	    $("#ridtodelete").val('');
      	    alert("Review " +$("#ridtodelete").val()+ " deleted");
      	    $.mobile.changePage("#adminpage"); //show the menu
  		    },
  	      error: function (response) {
  		        var r = jQuery.parseJSON(response.responseText);
  		        alert("Message: " + r.Message);
  				}
  	  });
    });
}
