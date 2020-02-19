<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Title Page</title>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{route('socket.sendMessage')}}" method="POST" role="form">
                    @csrf
                        <legend>Form title</legend>
                    
                        <div class="form-group">
                            <input type="text" name="message" class="form-control" id="message" placeholder="message">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- jQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>
        <script>
            // $(document).ready(function(){
            //     var socket = io.connect('http://visoftech.com:9000');
            //     socket.emit('message',  'dadadasd'); 
            // });

        </script>
        <script>
            // var socket = io.connect('http://visoftech.com:9000');

            // socket.on('connect', function () {
            //     console.log('connected');

            //     socket.on('broadcast', function (data) {
            //         //console.log(data);
            //         socket.emit("message", 'data');
            //         alert(data.text);
            //     });

            //     socket.on('disconnect', function () {
            //         console.log('disconnected');
            //     });
            // });
        </script>
    </body>
</html>