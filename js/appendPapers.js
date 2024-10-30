var active_element = "";
var isActive = false;
var isModify = false;
//updates sort method
function updateSortMethod() {
	document.getElementById("sortMethodSubmit").click();
}
//appends table with article data modification fields or article deletion button
function appendData(element_id,modify,article_id,article_title,article_journal,article_year,article_date,article_volume,article_issue,article_pages,article_doi,article_url,article_issn,article_supplementary,article_arxiv,article_file,article_public,article_preprint,article_char1,article_char2,article_char3) {
	"use strict";
	if (active_element != ""){ //checks if no management fields were called (first time only)
		document.getElementById(active_element).innerHTML = '';
	}
	if (active_element == element_id && isModify == modify){ //checks if this button already been pressed
		isActive = !isActive; //if true closes or opens management fields
	} else {
		isActive = false; //if false opens management fields
	}
	active_element = element_id; //sets new active management fields
	if (!isActive){
		if(modify){ //sets modification fields
			isModify = true;
			authorNum=0;
			editorNum=0;
			tagNum=0;
			document.getElementById(active_element).innerHTML = '<div class="enterInfo"><input type="hidden" name="checkManage" value="update"><input type="hidden" name="articleID" value=' + article_id + '><table><tr><td style="vertical-align:top;"><label for="authors0">Authors:</label></td><td id="authorSelection' + article_id + '"></td></tr><tr><td><label for="title">Title</label></td><td><input oninput="changeInputWidthDefault(\'title\')" id="title" name="title" required="required" type="text" placeholder="title" value="' + article_title.replaceAll("&bnpquote;", "&quot;") + '"/></td></tr><tr><td style="min-width: 100px;"><label for="journal">Journal</label></td><td><input oninput="changeInputWidthDefault(\'journal\')" id="journal" list="j_list" name="journal" type="text" placeholder="journal" value="' + article_journal + '"/><datalist id="j_list">' + '<?php echo $j_option_values ?>' + '</datalist></td></tr><tr><td><label for="year">Year</label></td><td><input style="width:153px" id="year" name="year" type="number" min="0" max="2100" placeholder="year" value="' + article_year + '"/></td></tr><tr><td><label for="volume">Volume</label></td><td><input id="volume" name="volume" type="number" min="0" placeholder="volume" value="' + article_volume + '"/></td></tr><tr id="article2"><td><label for="issue">Issue</label></td><td><input id="issue" name="issue" type="number" min="0" placeholder="issue" value="' + article_issue + '"/></td></tr><tr><td><label for="pages">Pages</label></td><td><input oninput="changeInputWidthDefault(\'pages\')" id="pages" name="pages" type="text" placeholder="pages" value="' + article_pages + '"/></td></tr><tr><td><label for="doi">DOI</label></td><td><input oninput="changeInputWidthDefault(\'doi\')" id="doi" name="doi" type="text" placeholder="doi" value="' + article_doi + '"/></td></tr><tr><td><label for="url">URL</label></td><td><input oninput="changeInputWidthDefault(\'url\')" id="url" name="url" type="text" placeholder="url" value="' + article_url + '"/></td></tr><tr><td><label for="issn">ISSN</label></td><td><input  oninput="changeInputWidthDefault(\'issn\')" id="issn" name="issn" type="text" placeholder="issn" value="' + article_issn + '"/></td></tr><tr><td><label for="supp">Supplementary</label></td><td><input oninput="changeInputWidthDefault(\'supp\')" id="supp" name="supp" type="text" placeholder="supplementary" value="' + article_supplementary + '"/></td></tr><tr><td><label for="arxiv">arXiv</label></td><td><input oninput="changeInputWidthDefault(\'arxiv\')" id="arxiv" name="arxiv" type="text" placeholder="arxiv" value="' + article_arxiv + '"/></td></tr><tr><td style="vertical-align:top;"><label for="tags0">Tags:</label></td><td id="tagSelection' + article_id + '"></td></tr><tr><td><label for="file">File (Max: <?php echo floor(wp_max_upload_size()/1000000); ?>MB)</label></td><td><input id="file" name="file" type="file" value="' + article_file + '"/><br>Or enter path manually<br><input oninput="changeInputWidthDefault(\'file_m\')" id="file_m" name="file_m" type="text" placeholder="folder/file.txt" value="'+article_file+'"><input id="file_d_name" name="file_d_name" type="hidden" value="'+article_file+'"><input id="file_d" name="file_d" type="hidden" value="false"><span onclick="deleteFile()" class="button" id="delFileButton">Delete Previous File</span><span id="delFileText"></span></td></tr><tr><td><label for="date">Date</label></td><td><input id="date" name="date" type="date" value="' + article_date + '"/></td></tr><tr><td><label for="public">Paper is public</label></td><td><input id="public" name="public" type="checkbox" value="true" ' + article_public + '/></td></tr><tr><td><label for="preprint">It is Preprint</label></td><td><input id="preprint" name="preprint" type="checkbox" value="true" ' + article_preprint + '/></td></tr>' + '<?php if(get_option("bnpp_custom_char1_name")!=""){echo "<tr><td><label for=\"char1\">".get_option("bnpp_custom_char1_name")."</label></td><td><input id=\"char1\" name=\"char1\" type=\"checkbox\" value=\"true\" ' + article_char1 + '/></td></tr>";} if(get_option("bnpp_custom_char2_name")!=""){echo "<tr><td><label for=\"char2\">".get_option("bnpp_custom_char2_name")."</label></td><td><input id=\"char2\" name=\"char2\" type=\"checkbox\" value=\"true\" ' + article_char2 + '/></td></tr>";} if(get_option("bnpp_custom_char3_name")!=""){echo "<tr><td><label for=\"char3\">".get_option("bnpp_custom_char3_name")."</label></td><td><input id=\"char3\" name=\"char3\" type=\"checkbox\" value=\"true\" ' + article_char3 + '/></td></tr>";} ?> ' + '</table><button class="button" id="addPaperSubmit" type="submit">Update Article</button></div>';
			changeInputWidthDefault('title');
			changeInputWidthDefault('journal');
			changeInputWidthDefault('pages');
			changeInputWidthDefault('doi');
			changeInputWidthDefault('url');
			changeInputWidthDefault('issn');
			changeInputWidthDefault('supp');
			changeInputWidthDefault('arxiv');
		} else { //sets deletion fields
			isModify = false;
			document.getElementById(active_element).innerHTML = '<div class="enterInfo"><input type="hidden" name="checkManage" value="remove"><input name="journal" type="hidden" value="true"><input type="hidden" name="articleID" value=' + article_id + '><p><b>Are you sure you want to remove this article?</b></p><p><button name="answer" value="yes" class="button" type="submit">Yes</button><button name="answer" value="no" class="button" type="submit">No</button></p></div>';
		}
	}
}
function appendDataC(element_id,modify,article_id,article_title,article_year,article_date,article_pages,article_doi,article_url,article_issn,article_supplementary,article_arxiv,article_file,article_public,article_char1,article_char2,article_char3,article_btitle,article_cpages) {
	"use strict";
	if (active_element != ""){ //checks if no management fields were called (first time only)
		document.getElementById(active_element).innerHTML = '';
	}
	if (active_element == element_id && isModify == modify){ //checks if this button already been pressed
		isActive = !isActive; //if true closes or opens management fields
	} else {
		isActive = false; //if false opens management fields
	}
	active_element = element_id; //sets new active management fields
	if (!isActive){
		if(modify){ //sets modification fields
			isModify = true;
			authorNum=0;
			editorNum=0;
			document.getElementById(active_element).innerHTML = '<div class="enterInfo"><input type="hidden" name="checkManage" value="update"><input type="hidden" name="articleID" value=' + article_id + '><table><tr><td style="vertical-align:top;"><label for="authors0">Authors:</label></td><td id="authorSelection' + article_id + '"></td></tr><tr><td><label for="title">Title</label></td><td><input oninput="changeInputWidthDefault(\'title\')" id="title" name="title" required="required" type="text" placeholder="title" value="' + article_title.replaceAll("&bnpquote;", "&quot;") + '"/></td></tr><tr><td><label for="bookTitle">Book Title</label></td><td><input oninput="changeInputWidthDefault(\'bookTitle\')" id="bookTitle" name="bookTitle" type="text" placeholder="book title" value="' + article_btitle.replaceAll("&bnpquote;", "&quot;") + '"/></td></tr><tr><td><label for="year">Year</label></td><td><input style="width:153px" id="year" name="year" type="number" min="0" max="2100" placeholder="year" value="' + article_year + '"/></td></tr><tr><td><label for="pages">Pages</label></td><td><input oninput="changeInputWidthDefault(\'pages\')" id="pages" name="pages" type="text" placeholder="pages" value="' + article_pages + '"/></td></tr><tr><td><label for="confPages">Conference Pages</label></td><td><input oninput="changeInputWidthDefault(\'confPages\')" id="confPages" name="confPages" type="text" placeholder="conference pages" value="' + article_cpages + '"/></td></tr><tr><td><label for="doi">DOI</label></td><td><input oninput="changeInputWidthDefault(\'doi\')" id="doi" name="doi" type="text" placeholder="doi" value="' + article_doi + '"/></td></tr><tr><td><label for="url">URL</label></td><td><input oninput="changeInputWidthDefault(\'url\')" id="url" name="url" type="text" placeholder="url" value="' + article_url + '"/></td></tr><tr><td><label for="issn">ISSN</label></td><td><input oninput="changeInputWidthDefault(\'issn\')" id="issn" name="issn" type="text" placeholder="issn" value="' + article_issn + '"/></td></tr><tr><td><label for="supp">Supplementary</label></td><td><input oninput="changeInputWidthDefault(\'supp\')" id="supp" name="supp" type="text" placeholder="supplementary" value="' + article_supplementary + '"/></td></tr><tr><td><label for="arxiv">arXiv</label></td><td><input oninput="changeInputWidthDefault(\'arxiv\')" id="arxiv" name="arxiv" type="text" placeholder="arxiv" value="' + article_arxiv + '"/></td></tr><tr><td style="vertical-align:top;"><label for="tags0">Tags:</label></td><td id="tagSelection' + article_id + '"></td></tr><tr><td><label for="file">File (Max: <?php echo floor(wp_max_upload_size()/1000000); ?>MB)</label></td><td><input id="file" name="file" type="file" value="' + article_file + '"/><br>Or enter path manually<br><input oninput="changeInputWidthDefault(\'file_m\')" id="file_m" name="file_m" type="text" placeholder="folder/file.txt" value="'+article_file+'"><input id="file_d_name" name="file_d_name" type="hidden" value="'+article_file+'"><input id="file_d" name="file_d" type="hidden" value="false"><span onclick="deleteFile()" class="button" id="delFileButton">Delete Previous File</span><span id="delFileText"></span></td></tr><tr><td><label for="date">Date</label></td><td><input id="date" name="date" type="date" value="' + article_date + '"/></td></tr><tr><td><label for="public">Paper is public</label></td><td><input id="public" name="public" type="checkbox" value="true" ' + article_public + '/></td></tr>' + '<?php if(get_option("bnpp_custom_char1_name")!=""){echo "<tr><td><label for=\"char1\">".get_option("bnpp_custom_char1_name")."</label></td><td><input id=\"char1\" name=\"char1\" type=\"checkbox\" value=\"true\" ' + article_char1 + '/></td></tr>";} if(get_option("bnpp_custom_char2_name")!=""){echo "<tr><td><label for=\"char2\">".get_option("bnpp_custom_char2_name")."</label></td><td><input id=\"char2\" name=\"char2\" type=\"checkbox\" value=\"true\" ' + article_char2 + '/></td></tr>";} if(get_option("bnpp_custom_char3_name")!=""){echo "<tr><td><label for=\"char3\">".get_option("bnpp_custom_char3_name")."</label></td><td><input id=\"char3\" name=\"char3\" type=\"checkbox\" value=\"true\" ' + article_char3 + '/></td></tr>";} ?> ' + '</table><button class="button" id="addPaperSubmit" type="submit">Update Conference</button></div>';
			changeInputWidthDefault('title');
			changeInputWidthDefault('bookTitle');
			changeInputWidthDefault('pages');
			changeInputWidthDefault('confPages');
			changeInputWidthDefault('doi');
			changeInputWidthDefault('url');
			changeInputWidthDefault('issn');
			changeInputWidthDefault('supp');
			changeInputWidthDefault('arxiv');
		} else { //sets deletion fields
			isModify = false;
			document.getElementById(active_element).innerHTML = '<div class="enterInfo"><input type="hidden" name="checkManage" value="remove"><input name="bookTitle" type="hidden" value="true"><input type="hidden" name="articleID" value=' + article_id + '><p><b>Are you sure you want to remove this article?</b></p><p><button name="answer" value="yes" class="button" type="submit">Yes</button><button name="answer" value="no" class="button" type="submit">No</button></p></div>';
		}
	}
}
function appendDataB(element_id,modify,article_id,article_title,article_year,article_date,article_pages,article_doi,article_url,article_issn,article_supplementary,article_arxiv,article_file,article_public,article_char1,article_char2,article_char3,article_publisher,article_chapter,article_isbn) {
	"use strict";
	if (active_element != ""){ //checks if no management fields were called (first time only)
		document.getElementById(active_element).innerHTML = '';
	}
	if (active_element == element_id && isModify == modify){ //checks if this button already been pressed
		isActive = !isActive; //if true closes or opens management fields
	} else {
		isActive = false; //if false opens management fields
	}
	active_element = element_id; //sets new active management fields
	if (!isActive){
		if(modify){ //sets modification fields
			isModify = true;
			authorNum=0;
			editorNum=0;
			document.getElementById(active_element).innerHTML = '<div class="enterInfo"><input type="hidden" name="checkManage" value="update"><input type="hidden" name="articleID" value=' + article_id + '><table><tr><td style="vertical-align:top;"><label for="authors0">Authors:</label></td><td id="authorSelection' + article_id + '"></td></tr><tr><td style="vertical-align:top;"><label for="editors0">Editors:</label></td><td id="editorSelection' + article_id + '"></td></tr><tr><td><label for="title">Title</label></td><td><input oninput="changeInputWidthDefault(\'title\')" id="title" name="title" required="required" type="text" placeholder="title" value="' + article_title.replaceAll("&bnpquote;", "&quot;") + '"/></td></tr><tr><td><label for="publisher">Publisher</label></td><td><input oninput="changeInputWidthDefault(\'publisher\')" id="publisher" name="publisher" type="text" placeholder="publisher" value="' + article_publisher.replaceAll("&bnpquote;", "&quot;") + '"/></td></tr><tr><td><label for="year">Year</label></td><td><input style="width:153px" id="year" name="year" type="number" min="0" max="2100" placeholder="year" value="' + article_year + '"/></td></tr><tr><td><label for="chapter">Chapter</label></td><td><input oninput="changeInputWidthDefault(\'chapter\')" id="chapter" name="chapter" type="text" placeholder="chapter" value="' + article_chapter + '"/></td></tr><tr><td><label for="pages">Pages</label></td><td><input oninput="changeInputWidthDefault(\'pages\')" id="pages" name="pages" type="text" placeholder="pages" value="' + article_pages + '"/></td></tr><tr><td><label for="doi">DOI</label></td><td><input oninput="changeInputWidthDefault(\'doi\')" id="doi" name="doi" type="text" placeholder="doi" value="' + article_doi + '"/></td></tr><tr><td><label for="url">URL</label></td><td><input oninput="changeInputWidthDefault(\'url\')" id="url" name="url" type="text" placeholder="url" value="' + article_url + '"/></td></tr><tr><td><label for="isbn">ISBN</label></td><td><input oninput="changeInputWidthDefault(\'isbn\')" id="isbn" name="isbn" type="text" placeholder="isbn" value="' + article_isbn + '"/></td></tr><tr><td><label for="issn">ISSN</label></td><td><input oninput="changeInputWidthDefault(\'issn\')" id="issn" name="issn" type="text" placeholder="issn" value="' + article_issn + '"/></td></tr><tr><td><label for="supp">Supplementary</label></td><td><input oninput="changeInputWidthDefault(\'supp\')" id="supp" name="supp" type="text" placeholder="supplementary" value="' + article_supplementary + '"/></td></tr><tr><td><label for="arxiv">arXiv</label></td><td><input oninput="changeInputWidthDefault(\'arxiv\')" id="arxiv" name="arxiv" type="text" placeholder="arxiv" value="' + article_arxiv + '"/></td></tr><tr><td style="vertical-align:top;"><label for="tags0">Tags:</label></td><td id="tagSelection' + article_id + '"></td></tr><tr><td><label for="file">File (Max: <?php echo floor(wp_max_upload_size()/1000000); ?>MB)</label></td><td><input id="file" name="file" type="file" value="' + article_file + '"/><br>Or enter path manually<br><input oninput="changeInputWidthDefault(\'file_m\')" id="file_m" name="file_m" type="text" placeholder="folder/file.txt" value="'+article_file+'"><input id="file_d_name" name="file_d_name" type="hidden" value="'+article_file+'"><input id="file_d" name="file_d" type="hidden" value="false"><span onclick="deleteFile()" class="button" id="delFileButton">Delete Previous File</span><span id="delFileText"></span></td></tr><tr><td><label for="date">Date</label></td><td><input id="date" name="date" type="date" value="' + article_date + '"/></td></tr><tr><td><label for="public">Paper is public</label></td><td><input id="public" name="public" type="checkbox" value="true" ' + article_public + '/></td></tr>' + '<?php if(get_option("bnpp_custom_char1_name")!=""){echo "<tr><td><label for=\"char1\">".get_option("bnpp_custom_char1_name")."</label></td><td><input id=\"char1\" name=\"char1\" type=\"checkbox\" value=\"true\" ' + article_char1 + '/></td></tr>";} if(get_option("bnpp_custom_char2_name")!=""){echo "<tr><td><label for=\"char2\">".get_option("bnpp_custom_char2_name")."</label></td><td><input id=\"char2\" name=\"char2\" type=\"checkbox\" value=\"true\" ' + article_char2 + '/></td></tr>";} if(get_option("bnpp_custom_char3_name")!=""){echo "<tr><td><label for=\"char3\">".get_option("bnpp_custom_char3_name")."</label></td><td><input id=\"char3\" name=\"char3\" type=\"checkbox\" value=\"true\" ' + article_char3 + '/></td></tr>";} ?> ' + '</table><button class="button" id="addPaperSubmit" type="submit">Update Book</button></div>';
			changeInputWidthDefault('title');
			changeInputWidthDefault('publisher');
			changeInputWidthDefault('chapter');
			changeInputWidthDefault('pages');
			changeInputWidthDefault('doi');
			changeInputWidthDefault('url');
			changeInputWidthDefault('isbn');
			changeInputWidthDefault('issn');
			changeInputWidthDefault('supp');
			changeInputWidthDefault('arxiv');
		} else { //sets deletion fields
			isModify = false;
			document.getElementById(active_element).innerHTML = '<div class="enterInfo"><input type="hidden" name="checkManage" value="remove"><input name="publisher" type="hidden" value="true"><input type="hidden" name="articleID" value=' + article_id + '><p><b>Are you sure you want to remove this article?</b></p><p><button name="answer" value="yes" class="button" type="submit">Yes</button><button name="answer" value="no" class="button" type="submit">No</button></p></div>';
		}
	}
}
var textLength = document.getElementById("textLength");
function changeInputWidthDefault(element_id) {
	textLength.innerText = document.getElementById(element_id).value;
	document.getElementById(element_id).style = "max-width:90vw;min-width:153px;width:" + (textLength.clientWidth + 12) + "px";
}
function deleteFile() {
	if(document.getElementById("file_d").value == "false") {
		document.getElementById("file_m").value = "";
		document.getElementById("file_d").value = "true";
		document.getElementById("delFileButton").innerHTML = "Cancel Deletion";
		document.getElementById("delFileText").innerHTML = "<br>Previous File will be deleted.";
	} else {
		document.getElementById("file_m").value = document.getElementById("file_d_name").value;
		document.getElementById("file_d").value = "false";
		document.getElementById("delFileButton").innerHTML = "Delete Previous File";
		document.getElementById("delFileText").innerHTML = "";
	}
}