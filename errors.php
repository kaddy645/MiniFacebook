	<?php  if (count($errors) > 0) : ?>
		<head>
	  <link href="style.css" rel="stylesheet">
	</head>
	<body>
	  <div id="Err" class="error">
	  	<?php foreach ($errors as $error) : ?>
	  	  <p><?php echo $error ?></p>
	  	<?php endforeach ?>
	  </div>
	<?php echo '<script type="text/JavaScript">
	setTimeout(function(){ document.getElementById("Err").style.display = "none"; }, 3000);
	</script>'
	;

	?>
	
	  </body>
	<?php  endif ?>
