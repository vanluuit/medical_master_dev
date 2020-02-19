 <!doctype html>
    <html>
        <head>
            <script src='http://code.jquery.com/jquery-1.10.1.min.js'></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js'></script>
            
        </head>
        <body>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div id="message">
                            hiển thi message
                        </div>
                    </div>
                </div>
            </div>
            
<script src="https://www.gstatic.com/firebasejs/5.8.6/firebase.js"></script>
<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyBYI-FLZuNnKu4nzOSpiiqTjxXwo-Xco5U",
    authDomain: "medicalpush-d21c0.firebaseapp.com",
    databaseURL: "https://medicalpush-d21c0.firebaseio.com",
    projectId: "medicalpush-d21c0",
    storageBucket: "medicalpush-d21c0.appspot.com",
    messagingSenderId: "609785824782"
  };
  firebase.initializeApp(config);
</script>
<!--             <script>
                $(document).ready(function(){
                    var socket = io.connect('http://visoftech.com:9001');
                    socket.on('notify', function(data){
                        console.log(data);
                        $('#message').append("<p>"+data+"</p>");
                    });
                });
            </script> -->
        </body>
    </html>