<?php include(bnpp_plugin_dir . "./pages/loadAnimation.php"); ?>
<div class="addPaperPage">
	<style><?php include(bnpp_plugin_dir . "./pages/style.css"); ?></style>
	<h1>Books &amp Papers: Add Work</h1>
	<div class="InformationText">
		Here you can add publication data.<br>
		Required fields are highlighted by red.<br>
		If you disable 'Paper is public' checkbox, it will not be displayed in lists.<br>
		For <span id="type">Article</span> publication next fields are necessary:<br>
		<span id="help">Authors;<br>Title;<br>Journal;<br>Year</span><br>
	</div><br>
	<div><span class="s_green" id="success"></span></div>
	<div class="enterInfo">
		<form enctype="multipart/form-data" name="addPaper" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_add_paper');
			?>
			<table>
				<tr>
					<td style="min-width: 100px;"><label for="paperType">Work Type</label></td>
					<td><select id="paperType" name="paperType" onchange="typeSelection()">
					<option value="article" selected>Article</option>
					<option value="conference">Conference</option>
					<option value="book">Book</option>
				</select><script><?php include(bnpp_plugin_dir . "./js/typeSelect.js"); ?></script></td>
				</tr>
				<tr>
					<script><?php include(bnpp_plugin_dir . "./js/authorSelection.js"); ?></script>
					<td style="vertical-align:top;"><label for="authors0">Authors:</label></td>
					<td id="authorSelection"></td>
				</tr>
				<tr>
					<td id="book0" style="vertical-align:top;"></td>
					<td id="book1"></td>
				</tr>
				<tr>
					<td><label for="title">Title</label></td>
					<td><input oninput="changeInputWidthDefault('title')" id="title" name="title" required="required" type="text" placeholder="title"/></td>
				</tr>
				<tr id="book2"></tr>
				<tr id="conf0"></tr>
				<tr id="article0">
					<td><label for="journal">Journal</label></td>
					<td><input oninput="changeInputWidthDefault('journal')" id="journal" list="j_list" name="journal" type="text" placeholder="journal"/><datalist id="j_list"><?php echo $j_option_values; ?></datalist></td>
				</tr>
				<tr>
					<td><label for="year">Year</label></td>
					<td><input style="width:153px" id="year" name="year" type="number" min="0" max="2100" placeholder="year"/></td>
				</tr>
				<tr id="book3"></tr>
				<tr id="article1">
					<td><label for="volume">Volume</label></td>
					<td><input id="volume" name="volume" type="number" min="0" placeholder="volume"/></td>
				</tr>
				<tr id="article2">
					<td><label for="issue">Issue</label></td>
					<td><input id="issue" name="issue" type="number" min="0" placeholder="issue"/></td>
				</tr>
				<tr>
					<td><label for="pages">Pages</label></td>
					<td><input oninput="changeInputWidthDefault('pages')" id="pages" name="pages" type="text" placeholder="pages"/></td>
				</tr>
				<tr id="conf1"></tr>
				<tr>
					<td><label for="doi">DOI</label></td>
					<td><input oninput="changeInputWidthDefault('doi')" id="doi" name="doi" type="text" placeholder="doi"/></td>
				</tr>
				<tr>
					<td><label for="url">URL</label></td>
					<td><input oninput="changeInputWidthDefault('url')" id="url" name="url" type="text" placeholder="url"/></td>
				</tr>
				<tr id="book4"></tr>
				<tr>
					<td><label for="issn">ISSN</label></td>
					<td><input oninput="changeInputWidthDefault('issn')" id="issn" name="issn" type="text" placeholder="issn"/></td>
				</tr>
				<tr>
					<td><label for="supp">Supplementary</label></td>
					<td><input oninput="changeInputWidthDefault('supp')" id="supp" name="supp" type="text" placeholder="supplementary"/></td>
				</tr>
				<tr>
					<td><label for="arxiv">arXiv</label></td>
					<td><input oninput="changeInputWidthDefault('arxiv')" id="arxiv" name="arxiv" type="text" placeholder="arxiv"/></td>
				</tr>
				<tr>
					<td style="vertical-align:top;"><label for="tags0">Tags:</label></td>
					<td id="tagSelection"></td>
				</tr>
				<tr>
					<td><label for="file">File (Max: <?php echo floor(wp_max_upload_size()/1000000); ?>MB)</label></td>
					<td><input id="file" name="file" type="file"/><br>Or enter path manually<br>
					<input oninput="changeInputWidthDefault('file_m')" id="file_m" name="file_m" type="text" placeholder="folder/file.txt"></td>
				</tr>
				<tr>
					<td><label for="date">Date</label></td>
					<td><input id="date" name="date" type="date"/></td>
				</tr>
				<tr>
					<td><label for="public">Paper is public</label></td>
					<td><input id="public" name="public" type="checkbox" value="true" checked/></td>
				</tr>
				<tr id="article3">
					<td><label for="preprint">It is Preprint</label></td>
					<td><input id="preprint" name="preprint" type="checkbox" value="true"/></td>
				</tr>
				<?php 
				if(get_option('bnpp_custom_char1_name')!="") //checks if custom characteristics were set
				{
					echo "<tr><td><label for='char1'>".get_option('bnpp_custom_char1_name')."</label></td><td><input id='char1' name='char1' type='checkbox' value='true'/></td></tr>";
				}
				if(get_option('bnpp_custom_char2_name')!="")
				{
					echo "<tr><td><label for='char2'>".get_option('bnpp_custom_char2_name')."</label></td><td><input id='char2' name='char2' type='checkbox' value='true'/></td></tr>";
				}
				if(get_option('bnpp_custom_char3_name')!="")
				{
					echo "<tr><td><label for='char3'>".get_option('bnpp_custom_char3_name')."</label></td><td><input id='char3' name='char3' type='checkbox' value='true'/></td></tr>";
				}
				?>
			</table>
			<button class="button" id="addPaperSubmit" type="submit">Add Work</button>
		</form>
		<div id="textLength" style="width:auto;display:inline-block;visibility:hidden;font-size:14px;position:fixed"></div>
	</div>
	<script>
		var textLength = document.getElementById("textLength");
		function changeInputWidthDefault(element_id) {
			textLength.innerText = document.getElementById(element_id).value;
			document.getElementById(element_id).style = "max-width:90vw;min-width:153px;width:" + (textLength.clientWidth + 12) + "px";
		}
	</script>
</div>
