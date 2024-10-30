<?php include(bnpp_plugin_dir . "./pages/loadAnimation.php"); ?>
<div class="mainAdminPage">
	<style><?php include(bnpp_plugin_dir . "./pages/style.css"); ?></style>
	<h1>Books &amp Papers: Import Works</h1>
	<div class="InformationText">
		<p>Here you can import data from third party resources.</p>
	</div>
	<div class="enterInfo" id="bib">
		<h2>Upload BibTeX Data</h2>
		<p>Import paper data from BibTeX using <span class="marker">.bib</span> file. Currently supports next types of publications: @article, @conference, @book .</p>
		<div style="overflow-x:auto;">
		<table class="infoTable">
			<tr>
				<th>
					<h4>BibTeX file example</h4>
				</th>
				<th>
					<h4>Article necessary fields</h4>
				</th>
				<th>
					<h4>Conference necessary fields</h4>
				</th>
				<th>
					<h4>Book necessary fields</h4>
				</th>
			</tr>
			<tr>
				<td>
					@article{small,<br>
					author = {Freely, I.P.},<br>
					title = {A small paper},<br>
					journal = {The journal of small papers},<br>
					year = {1997},<br>
					volume = {7}<br>
					}<br>
				</td>
				<td>
					author<br>title<br>journal<br>year<br>
				</td>
				<td>
					author<br>title<br>booktitle<br>year<br>confpages<br>
				</td>
				<td>
					author<br>editor<br>title<br>publisher<br>year<br>
				</td>
			</tr>
		</table>
		</div>
		<p>
			<span class="warning">Large files may take some time to process. Don't Panic!</span>
		</p>
		<form name="addBib" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_bib_upload');
			?>
			<table>
				<tr>
					<td style="min-width:100px;"><label for="bibUpload">Select .bib file</label></td>
					<td><input style="border:none;" id="bibUpload" name="bibUpload" type="file" accept=".bib" required="required"></td>
				</tr>
			</table>
			<p id="bibInputs"></p>
			<button class="button" id="importBibSubmit" type="submit">Upload</button>
		</form>
	</div>
	<div class="enterInfo" id="doi">
		<h2>Upload Data Using DOI</h2>
		<p>
			Import data using Ditigal Object Identifier <span class="marker">(DOI)</span>.
		</p>
		<p>
			<span class="warning">Enter file DOI and press Upload. The request will be formed and handled.<br>After page auto-reloads, make needed changes to obtained data and press Import.</span>
		</p>
		<form name="addDoi" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_doi_upload');
			?>
			<table id="doiInputHide">
				<tr>
					<td style="min-width:100px;"><label for="doiUpload">Enter file DOI</label></td>
					<td><input oninput="changeInputWidthDefault('doiUpload')" id="doiUpload" name="doiUpload" type="text" required="required"></td>
				</tr>
			</table>
			<p id="doiInputFields"></p>
			<button class="button" id="importDoiSubmit" type="submit">Get</button>
		</form>
	</div>
	<div class="enterInfo" id="abstract">
		<h2>Get Publication Abstract</h2>
		<p>
			Get abstract data using Ditigal Object Identifier <span class="marker">(DOI)</span>.
		</p>
		<p>
			<span class="warning">This feature is currently under development. You can use DOI to obtain abstracts, but it won't be stored or associated with the publications.</span>
		</p>
		<form name="getAbstract" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_get_abstract');
			?>
			<table id="abstractInputHide">
				<tr>
					<td style="min-width:100px;"><label for="absManUpload">Enter DOI manually</label></td>
					<td><input id="absManUpload" name="absManUpload" type="text"></td>
					<td><button class="button" id="importAbstractManSubmit" type="submit">Get</button></td>
				</tr>
			</table>
			<p id="abstractInputFields"></p>
		</form>
	</div>
	<div id="textLength" style="width:auto;display:inline-block;visibility:hidden;font-size:14px;position:fixed"></div>
	<script><?php include(bnpp_plugin_dir . "./js/readFile.js"); ?></script>
	<script><?php include(bnpp_plugin_dir . "./js/typeSelect.js"); ?></script>
	<script><?php include(bnpp_plugin_dir . "./js/authorSelection.js"); ?></script>
</div>