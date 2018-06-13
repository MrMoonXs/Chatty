<?php 
	//start a session for the logged in user
	session_start(); 
	
	//checking logout status
	if(isset($_GET['logout']))
		{
			//Simple Exit Message
			$fp = fopen("log.html",'a');
			fwrite($fp,"<div class='msgln'><i>User ". $_SESSION['name'] ." has left the chat.</i><br /></div>". PHP_EOL ."");
			fclose($fp);
			
			session_destroy();//end session
			header("Location: index.php");//Redirect the user
		}

	//make a login form 
	function loginForm()
		{
			echo
			'
				<div id="loginform">
					<form action="index.php" method="post">
						<p>please enter your name to continue:</p>
						<label for="name" >Name:</label>
						<input type="text" name="name" id="name" />
						<input type="submit" name="enter" id="enter" value="Enter" />
					</form>
				</div>
			';
		}
	//user input validation
	if(isset($_POST['enter']))
		{
			if($_POST['name'] != "")
				{
					$_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
				}
			else
				{
					echo '<span class="error">Please type in a name</span>';
				}
		}
?>


<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- the title of the site -->
<title>chatty</title>
<!-- load the Css file -->
<link type="text/css" rel="stylesheet" href="style.css" />
</head>

<body>
<?php 
	if(!isset($_SESSION['name']))
		{
			loginForm();
		}
	else
		{
?>
<!-- put all of the stuff inside the wrapper div -->
<div id="wrapper">
	<!-- menu has two parts: first will be welcome message 
		 and will float left, second will be an log out link 
		 and will be float right. include a div to clear the elements.
	-->
	<div id="menu">
		<p class="welcome" >Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
		<p class="logout" ><a id="exit" href="#">Exit</a></p>
		<div style="clear:both"></div>
	</div>
	<!-- the #chatbox will contain the chatlog. load the log from an external 
		 file using jQuery ajax request.
	-->
	<div id="chatbox">
		<?php 
			if(file_exists("log.html") && filesize("log.html") > 0 )
				{
					$handle = fopen("log.html","r");
					$contents = fread($handle,filesize("log.html"));
					fclose($handle);
					echo $contents;
				}
		?>
	</div>
	<!-- 
		the form which include a text input for the user message 
		and a submit button.
	-->
	<form name="message" action="">
		<input name="usermsge" type="text" id="usermsg" size="63" />
		<input name="submitmsge" type="submit" id="submitmsg" value="send" />
	</form>
</div>
<!-- 
	 added scripts here to load the page faster.the first script is link to 
	 the GOOGLE jQuery CDN (use jQuery library)
	 the second script tag will be where we will be working on.the code 
	 will be loaded after the document
	 is ready.
-->
<script typ="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
	//jQuery Document to end the session if user wants to end it .
	$(document).ready(function(){
					//If the user wants to submit the form
					$("#submitmsg").click(function(){
							 var clientmsg = $("#usermsg").val();
							$.post("post.php", {text: clientmsg});
							$("#usermsg").attr("value", "");
							return false;
					});
					//Load the file containing the chat log
					function loadLog(){
							var oldscrollHeight = $("#chatbox").attr("scrollHeight")-20;
							$.ajax
								({
									url:"log.html",
									cache:false,
									success:function(html){
										$("#chatbox").html(html);  //insert chat log into the #chatbox div
										var newscrollHeight = $("#chatbox").attr("scrollHeight")-20;
										if (newscrollHeight > oldscrollHeight){
											$("#chatbox").animate({scrollTop:newscrollHeight}, 'normal'); //autoscroll to bottom of div
										}
									},
								});
						}
					//Reload file every 2.5 seconds
					setInterval(loadLog,2500);
					//If user wants to end session
					$("#exit").click(function(){
								var exit = confirm("Are you sure you want to exit?");
								if(exit==true){window.location = "index.php?logout=true";}
								
							}
					);
				}
		);
</script>
<?php
	}
?>
</body>

</html>
