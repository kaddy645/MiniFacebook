var count = 0;
  
  var arr = []; // List of users 

   
function displayChatBox(){ 
     i = 270 ; // start position
  j = 260;  //next position
  
  $.each( arr, function( index, value ) {  
     if(index < 4){
          $('[rel="'+value+'"]').css("right",i);
    $('[rel="'+value+'"]').show();
       i = i+j;    
     }
     else{
    $('[rel="'+value+'"]').hide();
     }
        });  
 }  


function startTime() {
    document.getElementById('clock').innerHTML = new Date();
    setTimeout(startTime, 500);
}

var sanitizeHTML = function(str){
var temp = document.createElement('div');
temp.textContent = str;
return temp.innerHTML;

}
if (window.WebSocket) {
 console.log("HTML5 WebSocket is supported");
} else {
  alert("HTML5 WebSocket is not supported");
}
var myWebSocket =  io.connect("https://192.168.56.101:4430");

myWebSocket.on('connect' ,function() { 

  console.log("Connect");
  $.post("chat_auth.php", { 
            action: "request_token"
            
        }, function (data) {
            console.log(data);

            if(data.status){
              myWebSocket.emit('authenticate', data.data);
            }else{
              console.log(data)
            }

            
        }, "json");
   
   
})

 myWebSocket.on("newuser_connected",function(userList) {


  $("#chat-sidebar").empty();
  for(var username in userList){
     newusr =  '<div id="sidebar-user-box" class="'+ username+'">'+
 '<img src="assets/profile.png" />'+
'<span id="'+ username +'"">' + username +'</span>'+
'</div> '
     $("#chat-sidebar").prepend(newusr);


  }
  
 
 });



myWebSocket.on("message", (data) => {
  console.log(data);
  var senderInfo = data.sender;
  var sender=senderInfo.username
  var msg = data.msg;
  console.log('Received from '+  sender + "  " + msg);
  // usr is id attached to chatbox reprsents sendID

  var chatbox = document.getElementById("chatbox_"+sender);
  console.log(sender)
  console.log(chatbox)
  if(chatbox == null){


    chatPopup =  '<div class="msg_box" style="right:270px" id="chatbox_'+ sender +'" rel="'+ sender +'">'  +
     '<div class="msg_head">'+ sender +
     '<div class="close">x</div> </div>'+
     '<div class="msg_wrap"> <div class="msg_body"> <div class="msg_push"></div> </div>'+
     '<div class="msg_footer"><textarea id="message" onkeypress="entertoSend(event)" onkeyup="myWebSocket.emit(typing)" class="msg_input" rows="4"></textarea></div>  </div>  </div>' ;     
    
     $("body").append(  chatPopup  );
     displayChatBox();

     chatbox = document.getElementById("chatbox_"+sender);
   }

   /// At this time, the chatbox must be appeared

 if(msg.trim().length != 0){    
   var chatbox_rel = $(chatbox).attr("rel") ;

   var msg_body =$(chatbox).find(".msg_body")

   msg_body.append('<div class="msg-left">'+msg+'</div>')

   //msg_body.scrollTop($(msg_body).scrollHeight)

   // $('<div class="msg-left">'+msg+'</div>').insertBefore('[rel="'+chatbox_rel+'"] .msg_push');
   $('.msg_body').scrollTop($('.msg_body')[0].scrollHeight);
   }

});


myWebSocket.on('typing',function(msg) {
console.log('Received from server: '+ msg);
document.getElementById("typing").innerHTML += "someone is typing...<br>";
 setTimeout(function(){document.getElementById("typing").innerHTML += "<br>";}, 500);

});

myWebSocket.onclose = function() { 
  console.log('WebSocket closed');
}

function doSend(msg){
    if (myWebSocket) {
        //myWebSocket.emit("message",msg);
        //console.log('Sent to server: ' +msg);
    }
}
function entertoSend(e){
  //alert("keycode =" + e.keyCode);
  if(e.keyCode==13){//enter key
    doSend(document.getElementById("message").value);
    //document.getElementById("message").value = "";

  }
}




$(document).ready(function () {
var usr = " ";
  count++;
 
 $(document).on('click', '.msg_head', function() { 
  var chatbox = $(this).parents().attr("rel") ;
  $('[rel="'+chatbox+'"] .msg_wrap').slideToggle('slow');
  return false;
 });
 
 
 $(document).on('click', '.close', function() { 
  var chatbox = $(this).parents().parents().attr("rel") ;
  $('[rel="'+chatbox+'"]').hide();
  arr.splice($.inArray(chatbox, arr), 1);
  displayChatBox();
  return false;
 })


 $(document).on('click', '#sidebar-user-box', function() {
  
  var userID = $(this).attr("class");
   to_usr = $(this).children().text() ;
  
 

  if ($.inArray(userID, arr) != -1)
  {
      arr.splice($.inArray(userID, arr), 1);
     }


     arr.unshift(userID);

  chatPopup =  '<div class="msg_box" style="right:270px" id="'+ to_usr +'" rel="'+ userID+'">'  +
     '<div class="msg_head">'+ to_usr +
     '<div class="close">x</div> </div>'+
     '<div class="msg_wrap"> <div class="msg_body"> <div class="msg_push"></div> </div>'+
     '<div class="msg_footer"><textarea id="message" onkeypress="entertoSend(event)" onkeyup="myWebSocket.emit(typing)" class="msg_input" rows="4"></textarea></div>  </div>  </div>' ;     
    
     $("body").append(  chatPopup  );
  displayChatBox();
 })

$(document).on('keypress', '.msg_footer textarea' , function(e) {       
        if (e.keyCode == 13 ) {   

    var msg = $(this).val(); 
  var to_usr = $(this).parents().parents().parents().attr("rel") ;;
    console.log("chatbox      " + msg)
    console.log("usr     " + to_usr)
   
   myWebSocket.emit('send_msg',  to_usr,  msg);

   $(this).val('');
   if(msg.trim().length != 0){    
   var chatbox = $(this).parents().parents().parents().attr("rel") ;
   $('<div class="msg-right">'+msg+'</div>').insertBefore('[rel="'+chatbox+'"] .msg_push');
   $('.msg_body').scrollTop($('.msg_body')[0].scrollHeight);
   }
        }
    });




 

});
