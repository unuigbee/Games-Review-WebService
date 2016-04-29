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
        url: 'http://creative.coventry.ac.uk/~4086947/greview/v1.1/index.php/reviews/allreviews', //Link to API 
        dataType: 'json',
        success: function(data) {$('#rusername').append
		$.each(data.reviews, function(i,reviews){
			$('#output4').append('<tbody><th class="label"><a href="" onclick="Getreview('+reviews.review_id+')"><h3>Title: '+reviews.review_title+'</h3></a><h3>Review by: '+reviews.review_username+'</h3></th></tbody>');//table Structure for reviewlist
                                   
                });
		$.mobile.changePage("#reviewlist"); //show the results page 
		$('#output4').table('refresh');
                
                },
	error: function (response) {
		var r = jQuery.parseJSON(response.responseText);
		alert("Message: " + r.Message);
				   }
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