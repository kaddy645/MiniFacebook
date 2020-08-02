DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS comments;

CREATE TABLE users (
		id INTEGER NOT NULL AUTO_INCREMENT,
	     username CHAR(255) NOT NULL,
	     password VARCHAR(512) NOT NULL,
	     fullname VARCHAR(256) NOT NULL,
	     role INTEGER ,
	     status INTEGER,
	     PRIMARY KEY (id)

);
ALTER TABLE users ADD UNIQUE INDEX username(username);


CREATE TABLE posts (
		id INTEGER NOT NULL AUTO_INCREMENT,
	     userId INTEGER,
	     message TEXT,
	     index posts_userId(userId),
	     created_at DATETIME,
	     updated_at DATETIME,
	     FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
	     PRIMARY KEY (id)

);


CREATE TABLE comments (
		id INTEGER NOT NULL AUTO_INCREMENT,
	     userId INTEGER,
	     postId INTEGER,
	     comment TEXT,
	     index comment_userId(userId),
	     index comment_postId(postId),
	     created_at DATETIME,
	     updated_at DATETIME,
	     FOREIGN KEY (postId) REFERENCES posts(id) ON DELETE CASCADE,
	     FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
	     PRIMARY KEY (id)

);

INSERT INTO users SET username='admin',password=PASSWORD('admin@secad'),fullname='Admin Secad',role=1,status=1;
INSERT INTO users SET username='summer',password=PASSWORD('summer@secad'),fullname='Summer Secad',role=0,status=1;
INSERT INTO users SET username='kartik',password=PASSWORD('kartik@secad'),fullname='Kartik Secad',role=0,status=1;


CREATE TABLE chatauth (
	     token VARCHAR(256),
	     userId INTEGER,	   
	     expired_at BIGINT,	  
	     FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,   
	     PRIMARY KEY (token)

);