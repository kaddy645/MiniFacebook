const request = require('request')

var https = require('https'), fs = require('fs') ,path = require('path');
var sslcertificate  = {
  key: fs.readFileSync('/etc/ssl/secad.key'), 
  cert: fs.readFileSync('/etc/ssl/secad.crt') //ensure you have these two files
};



var httpsServer = https.createServer(sslcertificate,(function(req, res){

    // if(req.url === "/"){
    //     fs.readFile("chatClient.php", "UTF-8", function(err, html){
    //         res.writeHead(200, {"Content-Type": "text/html"});
    //         console.log("HTTPS server is listenning on port 4430");
    //         res.end(html);
    //     });
    // }else if(req.url.match("\.css$")){
    //     var cssPath = path.join(__dirname, req.url);
    //     var fileStream = fs.createReadStream(cssPath, "UTF-8");
    //     //res.writeHead(200, {"Content-Type": "text/css"});
    //     fileStream.pipe(res);
    
    //         }else if(req.url.match("\.png$")){
    //     var imagePath = path.join(__dirname, '../', req.url);
    //     console.log(imagePath);
    //     var fileStream = fs.createReadStream(imagePath);
    //     //res.writeHead(200, {"Content-Type": "image/png"});
    //     fileStream.pipe(res);
    // }

    // else if(req.url.match("\.js$")){
    //     var jsPath = path.join(__dirname, '../', req.url);
    //     console.log(jsPath);
    //     var fileStream = fs.createReadStream(jsPath);
    //     //res.writeHead(200, {"Content-Type": "text/javascript"});
    //     fileStream.pipe(res);
    // }
    // else{
    //     res.writeHead(404, {"Content-Type": "text/html"});
    //     res.end("No Page Found");
    // }

})).listen(4430);



// usernames which are currently connected to the chat
var connections = {};

function get_userid(v)
{
  var val = '';
  
  for(var key in usernames)
    {
    if(key == v)
    val = usernames[key];
  }

  return val;
}


var socketio = require('socket.io')(httpsServer);


function getUserLists(exclude_user){
  const userList = {};

  for (var connection in connections){
    var user = connections[connection];

    console.log(user)
    if(user!="Guest"){
      if (!(user.username  in userList)){

        if(exclude_user.username != user.username)
        {
           userList[user.username] = user
      }
      }
      
    }
  }

  console.log("Exclude: "+exclude_user.username)
  console.log(userList);
  console.log("-----");
  return userList;


}

function sendMessageToUser(sender,toUsername,msg,excludeConnectionId){

  for (var connection in connections){
    if(connection==excludeConnectionId){
      continue;
    }

    var user = connections[connection]
    if(user!="Guest"){
      if(user.username==toUsername){

        console.log("Sending to :"+connection)

        socketio.to(connection).emit('message', {sender:sender ,msg:msg});

      }
      
    }
  }
}

function broadcastUserList(){

  console.log("XXXX")


  for (var connection in connections){
    var user = connections[connection]
    if(user!="Guest"){

      socketio.to(connection).emit("newuser_connected",  getUserLists(user));
      
      }
  }

}

socketio.on('connection', (socketclient) => {


  console.log("A new socket.IO client is connected: "+ socketclient.client.conn.remoteAddress+
               ":"+socketclient.id);
   // add the client's username to the global list
   
    // we store the username in the socket session for this client
    // socketclient.username = username;
    // // add the client's username to the global list
    connections[socketclient.id]=  "Guest";
    

    
  
 
  socketio.on('disconnect', (socketclient) => {
    delete connections[socketclient.id];
  });


// when the user sends a private message to a user
 socketclient.on('send_msg', (to_user, msg) => {
     console.log("From user: "+ socketclient.id);
     console.log("To user: "+ to_user);
    socketio.to(to_user).emit('message', {id:socketclient.id ,msg:msg});
    sender = connections[socketclient.id]
    if(sender!="Guest"){
      sendMessageToUser(sender,to_user,msg,socketclient.id)
    }
    
  });

 // when the user sends a private message to a user
 socketclient.on('authenticate', (token) => {

    request_data = {
          action: 'verify_token',
          token: token
        }

  
      request(
        
        { 
          url:"https://localhost/secad-project/chat_auth.php?action=verify_token&token="+token,
          method:"GET",
          insecure: true,
          strictSSL:false,
          
        },
        (error, res, body) => {

          // console.log(body)
          if (error) {
            console.error(error)
            return
          }

          json_data = JSON.parse(body)
          if(json_data.status){


           connections[socketclient.id]= json_data.data;
          broadcastUserList();
           

          }else{
            console.log("Authentication fail")
          }
        }
    )
     
    
  });



  socketclient.on("typing",(data) => {
   socketio.emit("typing");

})



});