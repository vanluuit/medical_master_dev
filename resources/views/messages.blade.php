<html>

<head>
    <title>Demo chat</title>
</head>
<body>
    <div id="data">
        
    </div>
    <div>
        <form action="send-message" method="POST">
        {{csrf_field()}}
        Name: <input type="text" name="author">
        <br>
        <br>
        Content: <textarea name="content" rows="5" style="width:100%"></textarea>
        <button type="submit" name="send">Send</button>
        </form>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.1/socket.io.js"></script>
        <script>
            
        var socket = io('http://visoftech.com:6001')
        socket.on('chat:message',function(data){
            // console.log(data)
            if($('#'+data.id).length == 0){
                $('#data').append('<p><strong>'+data.author+'</strong>: '+data.content+'</p>')
            }
            else{
                console.log('Đã có tin nhắn')
            }
        })

        </script>
    </div>
</body>

</html>