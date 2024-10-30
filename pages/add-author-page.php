<?php include(bnpp_plugin_dir . "./pages/loadAnimation.php"); ?>
<div class="addAuthorPage" style="max-width:100%">
	<style><?php include(bnpp_plugin_dir . "./pages/style.css"); ?></style>
	<h1>Books &amp Papers: Add Author</h1>
	<div class="InformationText">
		Here you can add author data.<br>
		'URL' field must contain a link starting with "http://" or "https://".<br>
		'Identifier' field is used for listing all publications of given author. For John Doe it may be johndoe etc.<br>
		Required fields are highlighted by red.<br>
	</div><br>
	<div><span class="s_green" id="success"></span></div>
	<div class="enterInfo">
		<form name="addAuthor" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_add_author');
			?>
			<table>
				<tr>
					<td style="min-width:100px;"><label for="firstName">First Name</label></td>
					<td><input oninput="changeInputWidthDefault('firstName')" id="firstName" name="firstName" required="required" type="text" placeholder="name"/></td>
				</tr>
				<tr>
					<td><label for="lastName">Last Name</label></td>
					<td><input oninput="changeInputWidthDefault('lastName')" id="lastName" name="lastName" required="required" type="text" placeholder="surname"/></td>
				</tr>
				<tr>
					<td><label for="email">Email</label></td>
					<td><input oninput="changeInputWidthDefault('email')" id="email" name="email" type="email" placeholder="user@mail.com"/></td>
				</tr>
				<tr>
					<td><label for="url">Personal URL</label></td>
					<td><input oninput="changeInputWidthDefault('url')" id="url" name="url" type="text" placeholder="http://site.com/author"/></td>
				</tr>
				<tr>
					<td><label for="slug">Identifier</label></td>
					<td><input oninput="changeInputWidthDefault('slug')" id="slug" name="slug" type="text" placeholder="author"/></td>
				</tr>
			</table>
			<button class="button" id="addAuthorSubmit" type="submit">Add Author</button>
		</form>
		<div id="textLength" style="width:auto;display:inline-block;visibility:hidden;font-size:14px;position:fixed"></div>
		<script>
			var textLength = document.getElementById("textLength");
			function changeInputWidthDefault(element_id) {
				textLength.innerText = document.getElementById(element_id).value;
				document.getElementById(element_id).style = "max-width:90vw;min-width:153px;width:" + (textLength.clientWidth + 12) + "px";
			}
			window.onload = function () {
				if(document.getElementById("loadAnimWrapper")){
					document.getElementById("loadAnimWrapper").style = "display:none;";
				}
			}
		</script>
	</div>
</div>
