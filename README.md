 MiniFacebook

 ##### Team Members:-
Huu Ha Nguyen 
Kartik Desai 



 #####  Purpose :-
 Study Secure Programming Principles and Practices


## Introduction:-

In this final project, we have applied secure programming principles and practices and web development technologies to develop a  simple yet secure “miniFacebook” web application with the all technical requirements. We have focused on all the aspects of project Design,Implemetaion and Mainly Security.







## Design:-

###### Database :-

Our basic approach for the database project was to take understand the basic prototype (developed in PHP/mySQL) and optimize the database design.
 -  Database has following functionality :-
    - Add/Get User.
    - Create Post.
    - Add/Edit Post
    - Add comment
    - Handle auth for chat system

  ###### The user Interface :-
  -  Design Idea : - taken from Original Facebook
     -   We have primarily used Html,Css,Javascript ,Jquery, PHP.
     -  html , css to create basic pages and layout.
     -  Javascript and Jquery to manipulate DOM.
     -  All this things are embedded in Php as our main focus was to secure application which php can do best.
  
###### Functionalities of our application :-
Users and Superusers can be seprated based on their role 0/1

  Users can :- 
- Anyone can register for an account 
- Registered users can (if the account is not disabled) =>
   - Login
   - Change password
   - Add a new post
   - Edit their own posts
   - Add comments on any post

 Superusers can :- 
   - Login (Database added directly in the database, no registration)
   - Disable (not delete) a registered user
   - Enable a registered user
   - Logged-in users can have real-time chat with others 
    
   
## Implementation & security analysis :-

-  We have done Input Validation.	
-  We are deploying the application over HTTPS for this project we are using self signed  certicate.
-  We are handling small erros e.g Trying to log in without providng credentials.
-  We have implemented Encapsulation.
-  We have seprated privileged/super and normal user
-  Session Hijacking is prevented as it has specific lifetime and it will be
  accessed over secure https connection only and also the broswer as a user agent.
- we are generate a token, store it in the session and sending it to the client to re-verify to prevent CSRF Attacks.
- For Preventing xss(Cross-Site Scripting) attack we are using htmlentities which
function converts characters to HTML entities. We can validate sql statements using
prepared statements(Parameterized Queries). So attacks can be prevented.
- Passwords are hashed in database for security.

##  Demo (screenshots)

###### Everyone can register a new account and then login:- 

![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/Login-Register.png?raw=true)

###### Superuser can disable an account :-
![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/Enable.png?raw=true)

###### Superuser can enable the disabled account :- 

![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/Admin.png?raw=true)

###### A regular logged-in user can delete her own existing posts, but cannot delete the posts of others Here john logged-in so he can delete his own post!
![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/post.png?raw=true)
function to check originality of user :-
![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/checkowner.png?raw=true)


###### Add Post :-
![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/post.png?raw=true)


###### A regular logged-in user cannot access the link for superusers. Only admin has button/link called Admin to enable/Disable user :-
![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/OnlineUsers.png?raw=true) 



###### A logged-in user can have realtime chat with other logged-in users :-

![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/chat.png?raw=true)

![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/chat1.png?raw=true)

![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/chat2.png?raw=true)

![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/chat22.png?raw=true)

###### Change Password:- (Token is used to get username from session to prevent CSRF Attack ) : - 
![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/ChangePass.png?raw=true)

![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/ChangePass2.png?raw=true)

###### Adding comment (To your or others post..Even admin can do that) :-

![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/comment.png?raw=true)

###### Input validation using Html5 pattern and required :-
![Image is not loading](https://github.com/kaddy645/HybridSecure/blob/master/screenshotFacebook/Input%20validation.png?raw=true)




## Built with
- [HTML](https://developer.mozilla.org/en-US/docs/Web/HTML)
- [CSS](https://www.w3.org/Style/CSS/Overview.en.html)
- [Javascript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
- [Jquery](https://jquery.com/)
- [PHP](https://www.php.net/)
- [Socket](https://socket.io/)
- [MySql](https://www.mysql.com/)





   
    
## Credits :-

#####  Team Members -  Summer and Kartik






