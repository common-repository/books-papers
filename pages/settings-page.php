<?php include(bnpp_plugin_dir . "./pages/loadAnimation.php"); ?>
<div class="settingsPage">
	<style><?php include(bnpp_plugin_dir . "./pages/style.css"); ?></style>
	<h1>Books &amp; Papers: Settings</h1>
	<div class="InformationText">
		Here you can edit some settings of <span class="marker">Book &amp; Papers</span> plugin
	</div>
	<div class="enterInfo">
		<h2>Custom DB Prefix</h2>
		<p>
			<span class="warning">Prefix change may take a while. Please be patient!</span>
		</p>
		<form name="customPrefixForm" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_prefix_setting');
			?>
			<table>
				<tr>
					<td style="min-width:100px;"><label for="prefix">Enter Your Custom Prefix</label></td>
					<td><input id="prefix" name="prefix" type="text" required="required" placeholder="prefix" value="<?php echo get_option('bnpp_custom_db_prefix');?>"></td>
					<td><span class="button" onclick="showPrefixHelp();">Example</span></td>
					<td id="prefixHelp"></td>
				</tr>
			</table>
			<button class="button" id="prefixSubmit" type="submit">Change Prefix</button>
		</form>
		
		<h2>Compatibility</h2>
		<form name="compatibilityForm" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_compatibility_setting');
			?>
			<table>
				<tr>
					<th colspan=2 style="text-align:left;">Switches plugin to work on previous shortcode replacement system (before 2021 update). Use if newer causes issues.<br></th>
				</tr>
				<tr>
					<td><label for="comp_legacy">Use legacy system</label></td>
					<td><input id="comp_legacy" name="comp_legacy" type="checkbox" value="true" <?php if (get_option('bnpp_comp_legacy')) { echo "checked";}?>>
					</td>
				</tr>
				<tr>
					<th colspan=2 style="text-align:left;">Options that affect core plugin functionality. Use in case of conflicts with other plugins.<br></th>
				</tr>
				<tr>
					<td style="min-width:100px;"><label for="comp_pub">Replace plugin shortcode</label></td>
					<td><input id="comp_pub" name="comp_pub" type="checkbox" value="true" <?php if (get_option('bnpp_comp_pub')) { echo "checked";}?>>
					</td>
				</tr>
				<tr>
					<td><label for="comp_auth">Replace Authors Names</label></td>
					<td><input id="comp_auth" name="comp_auth" type="checkbox" value="true" <?php if (get_option('bnpp_comp_auth')) { echo "checked";}?>>
					</td>
				</tr>
				<tr>
					<td><label for="comp_clear">Clear tag if no match has been found</label></td>
					<td><input id="comp_clear" name="comp_clear" type="checkbox" value="true" <?php if (get_option('bnpp_comp_clear')) { echo "checked";}?>>
					</td>
				</tr>
				<tr>
					<td><label for="comp_rbracket">Right Bracket</label></td>
					<td><input oninput="showCompHelp();" id="comp_rbracket" name="comp_rbracket" type="text" placeholder="[publications" value="<?php echo get_option('bnpp_comp_rbracket');?>"></td>
				</tr>
				<tr>
					<td><label for="comp_lbracket">Left Bracket</label></td>
					<td><input oninput="showCompHelp();" id="comp_lbracket" name="comp_lbracket" type="text" placeholder="]" value="<?php echo get_option('bnpp_comp_lbracket');?>"></td>
				</tr>
				<tr><td colspan=2>Current plugin shortcode: </td></tr>
				<tr><td id="compTagHelp" colspan=2><?php echo get_option('bnpp_comp_rbracket');?> auth=johndoe subj=articles prep=all<?php echo get_option('bnpp_comp_lbracket');?></td></tr>
				<tr><th colspan=2 style="text-align:left;">Performance improvements (For legacy system only):</th></tr>
				<tr>
					<td style="min-width:100px;"><label for="comp_perf_default">Don't use additional options in shortcode.<br>(Publication display by tag, year and id will be disabled)</label></td>
					<td><input id="comp_perf_default" name="comp_perf_default" type="checkbox" value="true" <?php if (get_option('bnpp_comp_perf_default')) { echo "checked";}?>>
					</td>
				</tr>
				<tr>
					<td style="min-width:100px;"><label for="comp_perf_auth">Don't use author options in shortcode.<br>(Publication display by author will be disabled, but you still have to write "auth=all" in the shortcode)</label></td>
					<td><input id="comp_perf_auth" name="comp_perf_auth" type="checkbox" value="true" <?php if (get_option('bnpp_comp_perf_auth')) { echo "checked";}?>>
					</td>
				</tr>
			</table>
			<button class="button" id="compSubmit" type="submit">Update Compatibility Settings</button>
		</form>
		
		<h2>File Upload Directory</h2>
		<p>
			You can change file upload directory.<br>
			<span class="warning">Maximum upload size: <?php echo floor(wp_max_upload_size()/1000000); ?>MB.</span>
		</p>
		<form name="customUploadForm" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_upload_dir_setting');
			?>
			<table>
				<tr>
					<td style="min-width:100px;"><label for="upload_abs">Use Wordpress Absolute Path</label></td>
					<td><input onchange="showUploadHelp();" id="upload_abs" name="upload_abs" type="checkbox" value="true" <?php if (get_option('bnpp_upload_dir_abs')) { echo "checked";}?>>
					<input id="upload_abs_hidden" name="upload_abs_hidden" type="hidden" value="<?php echo ABSPATH;?>"></td>
				</tr>
				<tr>
					<td><label for="upload">File Upload Directory</label></td>
					<td><input oninput="showUploadHelp();" id="upload" name="upload" type="text" placeholder="downloads" value="<?php echo get_option('bnpp_upload_dir');?>"></td>
				</tr>
				<tr><td colspan=2>Current directory: </td></tr>
				<tr><td id="uploadHelp" colspan=2><?php if (get_option('bnpp_upload_dir')) { echo ABSPATH;} echo get_option('bnpp_upload_dir');?></td></tr>
			</table>
			<button class="button" id="uploadSubmit" type="submit">Change Upload Folder</button>
		</form>
		<h2>Custom Publication Lists</h2>
		<p>
			Use fields to manually enter the structure of publication list elements.<br>
			You can also select one of the element and use buttons below to construct the structure.<br>
			If you want to include optional element use <span class="marker">{{</span> and <span class="marker">}}</span>.<span id="optContentButton" class="warning" onmouseover="showHint();" onmouseout="hideHint();">Example</span><br><div id="optContentHint" style="display:none;">If [url] field is <?php echo get_site_url(); ?> ,<br> field <span class="warning">some text {{&lt;a href=&quot;[url]&quot;&gt;Site&lt;/a&gt;}}</span> will look like <span class="warning">some text <a href="<?php echo get_site_url(); ?>">Site</a></span><br>
			Otherwise, if [url] is empty it will show <span class="warning">some text </span></div>
			<span class="warning">Warning! Some HTML tags and attributes may be filtered for security reasons.<br><span style="padding-left:5px;">Allowed tags: h1-h8, a, i, b, strong, div, p, br</span></span><br>
			<span onclick="toggleCitExamples();" class="button">Show List Examples</span><br>
			<div id="cit_ex" style="display:none">
				<table>
					<tr>
						<td style="min-width:100px;"><span style="font-weight:bold">APA Citation Style</span></td>
						<td>[authors] ([year]). [title]. [journal], [issue], [pages]. doi: [doi]</td>
					</tr>
					<tr>
						<td><span style="font-weight:bold">MLA Citation Style</span></td>
						<td>[authors]. [title] &lt;i&gt;[journal]&lt;/i&gt;, vol. [volume], [year], pp. [pages], doi: [doi]</td>
					</tr>
					<tr>
						<td><span style="font-weight:bold">Chicago/Turabian Citation Style</span></td>
						<td>[authors], &quot;[title]&quot;, [journal] [issue] ([year]): [pages].</td>
					</tr>
				</table>
			</div>
			Selected element: <span id="pubListSelected">article_list</span><br>
			<button onclick="deleteWordFromActiveElement()">&lt;--</button>
			<span id="pubListButtons"><button onclick="appendActiveElement('title')">Title</button><button onclick="appendActiveElement('authors')">Authors</button><button onclick="appendActiveElement('journal')">Journal</button><button onclick="appendActiveElement('year')">Year</button><button onclick="appendActiveElement('pages')">Pages</button><button onclick="appendActiveElement('doi')">DOI</button><button onclick="appendActiveElement('url')">URL</button><button onclick="appendActiveElement('issn')">ISSN</button><button onclick="appendActiveElement('supplementary')">Supplementary</button><button onclick="appendActiveElement('filelink')">File Link</button><button onclick="appendActiveElement('date')">Date</button><button onclick="appendActiveElement('volume')">Volume</button><button onclick="appendActiveElement('issue')">Issue</button><button onclick="appendActiveElement('arxiv')">arXiv</button><button onclick="appendActiveElement('tags')">Tags</button></span>
		</p>
		<form name="customPublicationForm" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_list_setting');
			?>
			<div style="overflow-x:auto;">
			<table>
				<tr>
					<td style="min-width:100px;"><label for="article_head">Articles Header</label></td>
					<td><input oninput="changeInputWidth('article_head')" onselect="selectActiveElement('article_head')" onclick="selectActiveElement('article_head')" style="min-width:350px" id="article_head" name="article_head" type="text" placeholder="Articles" value="<?php echo str_replace('\&quot;',"&quot;",str_replace('"',"&quot;",get_option('bnpp_article_head')));?>"></td>
				</tr>
				<tr>
					<td><label for="article_list">Articles List</label></td>
					<td><textarea oninput="changeInputHeight('article_list')" onselect="selectActiveElement('article_list')" onclick="selectActiveElement('article_list')" style="min-width:350px;height:auto;overflow:hidden" id="article_list" name="article_list" type="text" placeholder="Article List"><?php echo str_replace('\&quot;',"&quot;",str_replace('"',"&quot;",get_option('bnpp_article_list')));?></textarea></td>
				</tr>
				<tr>
					<td><label for="conference_head">Conferences Header</label></td>
					<td><input oninput="changeInputWidth('conference_head')" onselect="selectActiveElement('conference_head')" onclick="selectActiveElement('conference_head')" style="min-width:350px" id="conference_head" name="conference_head" type="text" placeholder="Conferences" value="<?php echo str_replace('\&quot;',"&quot;",str_replace('"',"&quot;",get_option('bnpp_conference_head')));?>"></td>
				</tr>
				<tr>
					<td><label for="conference_list">Conferences List</label></td>
					<td><textarea oninput="changeInputHeight('conference_list')" onselect="selectActiveElement('conference_list')" onclick="selectActiveElement('conference_list')" style="min-width:350px;height:auto;overflow:hidden" id="conference_list" name="conference_list" type="text" placeholder="Conference List"><?php echo str_replace('\&quot;',"&quot;",str_replace('"',"&quot;",get_option('bnpp_conference_list')));?></textarea></td>
				</tr>
				<tr>
					<td><label for="book_head">Books Header</label></td>
					<td><input oninput="changeInputWidth('book_head')" onselect="selectActiveElement('book_head')" onclick="selectActiveElement('book_head')" style="min-width:350px" id="book_head" name="book_head" type="text" placeholder="Books" value="<?php echo str_replace('\&quot;',"&quot;",str_replace('"',"&quot;",get_option('bnpp_book_head')));?>"></td>
				</tr>
				<tr>
					<td><label for="book_list">Books List</label></td>
					<td><textarea oninput="changeInputHeight('book_list')" onselect="selectActiveElement('book_list')" onclick="selectActiveElement('book_list')" style="min-width:350px;height:auto;overflow:hidden" id="book_list" name="book_list" type="text" placeholder="Book List"><?php echo str_replace('\&quot;',"&quot;",str_replace('"',"&quot;",get_option('bnpp_book_list')));?></textarea></td>
				</tr>
				<tr>
					<th style="text-align:left;">Extra Options</th>
					<th style="font-weight:normal;text-align:left;padding-left:10px;"><span id="optContentButton1" class="warning" onmouseover="showHint1();" onmouseout="hideHint1();">Hint</span><br><div id="optContentHint1" style="display:none;">If you want to divide publications in groups by year:<br>use <span class="warning">Year</span> in &quot;Divide List by&quot;<br>If you want to simply sort all publications by title:<br>use <span class="warning">title</span> in &quot;Divide List by&quot; and <span class="warning">display:none</span> in &quot;Division style&quot;</div></th>
				</tr>
				<tr>
					<td><label for="orderedList">Numbered List</label></td>
					<td><input type="checkbox" id="orderedList" name="orderedList" <?php echo get_option('bnpp_ordered_list'); ?>></td>
				</tr>
				<tr>
					<td><label for="listDivision">Divide List by</label></td>
					<td><select style="width:153px;" id="listDivision" name="listDivision">
						<option value="none" <?php if(get_option('bnpp_list_division')=='none'){echo "selected";} ?>>None</option>
						<option value="year" <?php if(get_option('bnpp_list_division')=='year'){echo "selected";} ?>>Year</option>
						<option value="title" <?php if(get_option('bnpp_list_division')=='title'){echo "selected";} ?>>Title</option>
						<option value="journal" <?php if(get_option('bnpp_list_division')=='journal'){echo "selected";} ?>>Journal</option>
						<option value="book_title" <?php if(get_option('bnpp_list_division')=='book_title'){echo "selected";} ?>>Book Title</option>
						<option value="publisher" <?php if(get_option('bnpp_list_division')=='publisher'){echo "selected";} ?>>Publisher</option>
						</select></td>
				</tr>
				<tr>
					<td><label for="listDivisionStyle">Division style</label></td>
					<td><input style="width:153px;" id="listDivisionStyle" name="listDivisionStyle" oninput="changeInputWidth('listDivisionStyle')" type="text" placeholder="division style" value="<?php echo get_option('bnpp_list_division_style');?>"></td>
				</tr>
				<tr>
					<td><label for="listOrder">List Sort Order</label></td>
					<td><select style="width:153px;" id="listOrder" name="listOrder">
						<option value="ASC" <?php if(get_option('bnpp_list_order')=='ASC'){echo "selected";} ?>>ASC</option>
						<option value="DESC" <?php if(get_option('bnpp_list_order')=='DESC'){echo "selected";} ?>>DESC</option>
						</select></td>
				</tr>
			</table>
			</div>
			<button class="button" id="pubListSubmit" type="submit">Save Changes</button>
			<div id="textLength" style="width:auto;display:inline-block;visibility:hidden;font-size:14px;position:fixed"></div>
		</form>
		<h2>General List Element Style</h2>
		<p>
			Set the style of the whole publication element. If you want to change style of some subelement (for example change the styling of links only), use "style" attributes in the list inputs in the section above.
		</p>
		<form name="lstyle" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_lstyle_setting');
			?>
			<input oninput="changeInputWidthDefault('lstylevalue')" id="lstylevalue" name="lstylevalue" type="text" placeholder="" value="<?php echo get_option('bnpp_lstyle_value');?>">
			<button class="button" id="lstyleSubmit" type="submit">Set List Element Style</button>
		</form>
		<h2>Custom Paper Display Characteristics</h2>
		<p>
			In case you want to display some of the publications in some specific way, you can enter the name of characteristic and its' CSS style.<br>
			To enable this characterics go to <span class="marker">Manage Papers</span> page, find your paper, click <span class="marker">modify</span> and tick checkbox representing the characteristic.<br>
		</p>
		<h4>First Characteristic</h4>
		<form name="char1" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_char1_setting');
			?>
			<table>
				<tr>
					<td style="min-width:100px;"><label for="char1name">Enter First Characteristic Name</label></td>
					<td><input oninput="changeInputWidthDefault('char1name')" id="char1name" name="char1name" type="text" placeholder="char name" value="<?php echo get_option('bnpp_custom_char1_name');?>"></td>
				</tr>
				<tr>
					<td><label for="char1value">Enter First Characteristic Style</label></td>
					<td><input oninput="changeInputWidthDefault('char1value')" id="char1value" name="char1value" type="text" placeholder="char style" value="<?php echo get_option('bnpp_custom_char1_value');?>"></td>
				</tr>
			</table>
			<button class="button" id="char1Submit" type="submit">Set Characteristic</button>
		</form>
		<span class="button" onclick="showChar1Help();">Show Expected Result</span><br>
		<p id="char1Help"></p>
		<h4>Second Characteristic</h4>
		<form name="char2" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_char2_setting');
			?>
			<table>
				<tr>
					<td style="min-width:100px;"><label for="char2name">Enter Second Characteristic Name</label></td>
					<td><input oninput="changeInputWidthDefault('char2name')" id="char2name" name="char2name" type="text" placeholder="char name" value="<?php echo get_option('bnpp_custom_char2_name');?>"></td>
				</tr>
				<tr>
					<td><label for="char2value">Enter Second Characteristic Style</label></td>
					<td><input oninput="changeInputWidthDefault('char2value')" id="char2value" name="char2value" type="text" placeholder="char style" value="<?php echo get_option('bnpp_custom_char2_value');?>"></td>
				</tr>
			</table>
			<button class="button" id="char2Submit" type="submit">Set Characteristic</button>
		</form>
		<span class="button" onclick="showChar2Help();">Show Expected Result</span><br>
		<p id="char2Help"></p>
		<h4>Third Characteristic</h4>
		<form name="char3" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_char3_setting');
			?>
			<table>
				<tr>
					<td style="min-width:100px;"><label for="char3name">Enter Third Characteristic Name</label></td>
					<td><input oninput="changeInputWidthDefault('char3name')" id="char3name" name="char3name" type="text" placeholder="char name" value="<?php echo get_option('bnpp_custom_char3_name');?>"></td>
				</tr>
				<tr>
					<td><label for="char3value">Enter Third Characteristic Style</label></td>
					<td><input oninput="changeInputWidthDefault('char3value')" id="char3value" name="char3value" type="text" placeholder="char style" value="<?php echo get_option('bnpp_custom_char3_value');?>"></td>
				</tr>
			</table>
			<button class="button" id="char3Submit" type="submit">Set Characteristic</button>
		</form>
		<span class="button" onclick="showChar3Help();">Show Expected Result</span><br>
		<p id="char3Help"></p>
		<h2>Custom Refresh Timeout</h2>
		<p>
			The information about action success or failure like this: <span class="s_green" id="success">Author 1 has been updated.</span> will be displayed shown for certain time.<br>
			You can set the time it'll be on screen in the field below.<br>
			Value 10 equals 1 second.<br>
		</p>
		<form name="timeout" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_timestep_setting');
			?>
			<table>
				<tr>
					<td style="mid-width:100px;"><label for="timestep">Timeout</label></td>
					<td><input id="timestep" name="timestep" type="number" min=0 max=100 step=1 placeholder="10" value="<?php echo get_option('bnpp_timeout_step');?>"></td>
				</tr>
			</table>
			<button class="button" id="timeSubmit" type="submit">Set Timeout</button>
		</form>
		<h2>
			<?php
			if(get_option('bnpp_drop_db_tables'))
			{
				echo "Create";
			} else
			{
				echo "Drop";
			}
			?> Plugin Database Tables</h2>
		<p>
			You can <?php
			if(get_option('bnpp_drop_db_tables'))
			{
				echo "restore";
			} else
			{
				echo "drop";
			}
			?> database tables essential to this plugin.
		</p>
		<button class="button" onclick="dropPluginTables();"><?php
			if(get_option('bnpp_drop_db_tables'))
			{
				echo "Create";
			} else
			{
				echo "Drop";
			}
			?></button>
		<form name="dropTables" action="" method="post">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_dropdb_setting');
			?>
			<span id="dropInput"></span>
			<button style="visibility:hidden" class="button" id="dropSubmit" type="submit">Change</button>
		</form>
	</div>
	<script><?php include(bnpp_plugin_dir . "./js/help.js");?></script>
</div>
