<?php include(bnpp_plugin_dir . "./pages/loadAnimation.php"); ?>
<div class="manageAuthorsPage">
	<h1>Books &amp Papers: Manage Authors</h1>
	<style><?php include(bnpp_plugin_dir . "./pages/style.css"); ?></style>
	<div class="InformationText">
		Here you can manage author data.<br>
		To merge authors and publications related to them: tick the checkboxes of authors to merge, then select author in droplist and click 'Merge' button.<br>
		Sort Method:
		<form action="" method="post" style="display:inline-block">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_sort_authors');
			?>
			<select onchange="updateSortMethod()" style="width:153px" id="sortMethod" name="sortMethod">
				<option value="id" <?php if(get_option('bnpp_author_sort')=='id'){echo "selected";}?>>ID</option>
				<option value="last_name" <?php if(get_option('bnpp_author_sort')=='last_name'){echo "selected";}?>>Last Name</option>
			</select>
			<select onchange="updateSortMethod()" style="width:153px" id="sortMethodOrder" name="sortMethodOrder">
				<option value="ASC" <?php if(get_option('bnpp_author_sort_order')=='ASC'){echo "selected";}?>>ASC</option>
				<option value="DESC" <?php if(get_option('bnpp_author_sort_order')=='DESC'){echo "selected";}?>>DESC</option>
			</select>
			<button id="sortMethodSubmit" class="button" type="submit" style="visibility:hidden">Change</button>
		</form>
	</div><br>
	<div><span class="s_green" id="success"></span></div><div><span class="s_red" id="remove"></span></div>
	<br>
	<form name="updateAuthor" action="" method="post">
		<?php
		if ( function_exists('wp_nonce_field') ) 
			wp_nonce_field('bnp_manage_authors');
		?>
		<table class="manageAuthors" id="refresh">
			<tr class="tabHeader" id="nav_ar_o">
				<th>ID</th><th>Author</th><th>Action</th><th>Merge</th>
			</tr>
			<tr class="tabHeader hidden" id="nav_ar">
				<th>ID</th><th>Author</th><th>Action</th><th>Merge</th>
			</tr>
			<tr></tr>
			<?php
			foreach($data as $author) //generates author table content
			{
				echo "<tr><td>$author->id</td>";
				if($author->personal_url != "") //checks if author has personal url
				{
					echo "<td><a href='$author->personal_url'>$author->first_name $author->last_name</a></td>";
				}
				else
				{
					echo "<td>$author->first_name $author->last_name</td>";
				}
				echo "<td><span class='button' onclick='appendData(\"table$author->id\",true,$author->id,\"$author->first_name\",\"$author->last_name\",\"$author->email\",\"$author->personal_url\",\"$author->slug\")'>Modify</span><span class='button' onclick='appendData(\"table$author->id\",false,$author->id,\"$author->first_name\",\"$author->last_name\",\"$author->email\",\"$author->personal_url\",\"$author->slug\")'>Remove</span></td><td><input id='mergeCheckbox$author->id' type='checkbox' onchange='updateMerge($author->id, \"$author->first_name $author->last_name\")'></td></tr><tr><td colspan='4' id='table$author->id'></td></tr>"; //sets author management buttons
			}
			?>
			<tr id="nav_ar_e"></tr>
		</table></form><br>
	<form id="authorMerge" name="authorMerge" action="" method="post" style="display:none">
		<?php
		if ( function_exists('wp_nonce_field') ) 
			wp_nonce_field('bnp_merge_authors');
		?>
		<input id="numberOfAuthors" name="numberOfAuthors" type="hidden" required="required" value=0>
		<input id="mergeAuthorId" name="mergeAuthorId" type="hidden" required="required" value=0>
		<span id="mergeList"></span>
		<span>Select Author and press 'Merge'</span><br>
		<span style="padding-right:20px">Select correct author entry</span><select name="mergeMainAuthor" id="mergeMainAuthor" onchange="updateMergeAuthor()">
		</select><br>
		<button class="button" type="submit">Merge</button>
	</form>
	<br>
	<span class="button" onclick="toggleUnlistedAuthors()">Show Unlisted Authors</span><br>
	<form name="addAuthor" action="" method="post">
		<?php
		if ( function_exists('wp_nonce_field') ) 
			wp_nonce_field('bnp_promote_authors');
		?>
		<table class="manageAuthors" id="unlisted" style="display:none">
			<tr class="tabHeader" id="nav_co_o">
				<th>Author</th><th>Action</th>
			</tr>
			<tr class="tabHeader hidden" id="nav_co">
				<th>Author</th><th>Action</th>
			</tr>
			<tr></tr>
			<?php
			foreach($dataAA as $author) //generates author table content
			{
				echo "<tr><td>$author->author_name</td>";
				echo "<td><span class='button' onclick='appendUnlisted(\"$author->id\",\"$author->author_name\",\"aa\")'>Promote</span></td></tr><tr><td colspan='2' id='aa_table$author->id'></td></tr>"; //sets author management buttons
			}
			foreach($dataCA as $author) //generates author table content
			{
				echo "<tr><td>$author->author_name</td>";
				echo "<td><span class='button' onclick='appendUnlisted(\"$author->id\",\"$author->author_name\",\"ca\")'>Promote</span></td></tr><tr><td colspan='2' id='ca_table$author->id'></td></tr>"; //sets author management buttons
			}
			foreach($dataBA as $author) //generates author table content
			{
				echo "<tr><td>$author->author_name</td>";
				echo "<td><span class='button' onclick='appendUnlisted(\"$author->id\",\"$author->author_name\",\"ba\")'>Promote</span></td></tr><tr><td colspan='2' id='ba_table$author->id'></td></tr>"; //sets author management buttons
			}
			foreach($dataBE as $author) //generates author table content
			{
				echo "<tr><td>$author->editor_name</td>";
				echo "<td><span class='button' onclick='appendUnlisted(\"$author->id\",\"$author->editor_name\",\"be\")'>Promote</span></td></tr><tr><td colspan='2' id='be_table$author->id'></td></tr>"; //sets author management buttons
			}
			?>
			<tr id="nav_co_e"></tr>
		</table></form>
	<div id="textLength" style="width:auto;display:inline-block;visibility:hidden;font-size:14px;position:fixed"></div>
	<script><?php include(bnpp_plugin_dir . "./js/stickyScroll.js"); ?></script>
	<script><?php include(bnpp_plugin_dir . "./js/append.js"); ?></script>
	<script>
		window.onload = function () {
			if(document.getElementById("loadAnimWrapper")){
				document.getElementById("loadAnimWrapper").style = "display:none;";
			}
		}
	</script>
</div>