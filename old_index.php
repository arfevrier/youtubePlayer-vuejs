<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lecteur vidéo et audio YouTube</title>
<meta name="content-language" content="fr">
<meta name="description" content="Lecteur vidéo et audio d'une vidéo YouTube">
<meta name="reply-to" content="me@arfevrier.fr">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/lumen/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <style>
    @import "https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,400italic"
    </style>
<script type="text/javascript">

function youtubeGetID(link){
	const regex = /(http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-\_]*))|([\w\-\_]*)/g;
	let m;
	while ((m = regex.exec(link)) !== null) {
   		 if (m.index === regex.lastIndex) {
	       	 	regex.lastIndex++;
	    	 }

		if(m[2] != undefined){
			return m[2];
		}
		if(m[3] != undefined){
			return m[3];
		}
	}
	return;
}

function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
function addbox(name, msg){
	txt = '<div class="panel panel-info"><div class="panel-heading" style="background-color: ' + getRandomColor() + ';"><h3 class="panel-title">' + name + '</h3></div><div class="panel-body" style="text-align:center;">' + msg + '</div></div>'
	$("#global").prepend(txt);
}
function anagram_start(){
    $.get('https://api.arfevrier.fr/v2/youtube/video/'+encodeURI(youtubeGetID($('#video_id_value').val()))+'?url', function(data){
        console.log(data)
        $('#navbar').html(youtubeGetID($('#video_id_value').val()));
        $('#form_to_delete').remove();
        addbox('Vidéo', '<video controls width="300" src="'+data+'"></video>')
    });
    $.get('https://api.arfevrier.fr/v2/youtube/audio/'+encodeURI(youtubeGetID($('#video_id_value').val()))+'?url', function(data){
        console.log(data)
        addbox('Audio', '<audio controls src="'+data+'"></audio>')
    });
}
</script>
</head>
<body>
<div class="container">
<div class="bs-docs-section">
 <div class="row">
            <div class="page-header">
              <h1 id="navbar">Lecteur vidéo et audio YouTube</h1>
            </div>
<div id="global">
<div id="form_to_delete" class="col-md-6 col-md-offset-3">
            <div class="well bs-component">
              <form class="form-horizontal">
                <fieldset>
                  <div class="form-group">
                    <label for="inputUrl" class="col-lg-3 control-label">ID de la vidéo:</label>
                    <div class="col-lg-9">
                      <input id="video_id_value" onabort="anagram_start()" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                      <button onclick="anagram_start()" type="button" class="btn btn-primary" type="button">Lancer</button>
                    </div>
                  </div>
                </fieldset>
              </form>
            <div id="source-button" class="btn btn-primary btn-xs" style="display: none;">&lt; &gt;</div></div>
</div>
</div>
</div>
</div>
</div>
<script>
    $('form').submit(false);
</script>
</body>
</html>
