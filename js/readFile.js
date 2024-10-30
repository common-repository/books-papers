document.getElementById("bibUpload").onchange = function(e){ //runs file reader on upload
	"use strict";
	var reader = new FileReader();
	reader.onload = function(event){
		var content = event.target.result;
		document.getElementById("bibInputs").innerHTML = "";
		var inputNum = 0;
		var tag = false;
		var write = false;
		var brackets = 0;
		var text = "";
		var lasttext = "";
		var lastpaper = "";
		var paper = "";
		content = htmlSpecialChars(content);//escapes special characters
		for(var i = 0; i < content.length; i++){
			if(content.substring(i,i+1) == "@"){
				tag = true;
			}
			if(tag){
				paper += content.substring(i,i+1);
			}
			if((tag || write) && content.substring(i,i+1) == "{"){
				write = true;
				tag = false;
				brackets++;
			}
			if(write){
				if(content.substring(i,i+1) != "'" && content.substring(i,i+1) != '"'){
					text += content.substring(i,i+1);
				} else{
					text += "&quot";
				}
			}
			if(content.substring(i,i+1) == "}"){
				brackets--;
				if(brackets<0){
					brackets=0;
				}
			}
			if(brackets==0 && write){
				write = false;
				paper = paper.substring(1,paper.length - 1);
				paper = paper.toLowerCase();
				if(paper == "inbook"){
					paper = "book";
				}
				if(paper == "inproceedings"){
					paper = "conference";
				}
				if (paper == "article" || paper == "book" || paper == "conference")
				{
					document.getElementById("bibInputs").innerHTML += "<input id='" + paper + inputNum + "'  name='" + paper + inputNum + "' required='required' type='hidden' size='150' value='"+text+"'>";
					lasttext = text;
					lastpaper = paper;
					inputNum++;
				}
				text = "";
				paper = "";
			}
		}
		if (inputNum > 1){
			document.getElementById("bibInputs").innerHTML += "<input id='k'  name='k' required='required' type='hidden' value='"+inputNum+"'>";
			document.getElementById("bibInputs").innerHTML += inputNum + " publications were pre-processed."
		} else {
			document.getElementById("bibInputs").innerHTML = "<input style='display:none;' type='text' required='required' value=''><span class='warning'>Pre-processed single article, please verify before upload.</span>";
			document.getElementById("bibInputs").innerHTML += advancedTextProcess(lastpaper, lasttext);
		}
	}
	reader.readAsText(e.target.files[0], 'CP1251');
}
//escapes html special characters
function htmlSpecialChars(text){
	var specChars = { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'};
	return text.replace(/[&<>"']/g, function(temp) {return specChars[temp];});
}
function advancedTextProcess(paper, text){ //if single paper is uploaded, decomposites its and creates fields with its data to be managed
	var result = "<table><tr><td style='min-width:100px;'>Paper Type [article, conference or book]</td><td><input id='paperType' value='"+paper+"'></td></tr>";
	for(var i = 0; i < text.length; i++){
		if(text.substring(i,i+6)=="author"){
			i+=6;
			result+="<tr><td>Authors</td><td><input id='author' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+6)=="editor"){
			i+=6;
			result+="<tr><td>Editors</td><td><input id='editor' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+5)=="title"){
			i+=5;
			result+="<tr><td>Title</td><td><input id='title' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+9)=="booktitle"){
			i+=9;
			result+="<tr><td>Book Title</td><td><input id='booktitle' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+7)=="journal"){
			i+=7;
			result+="<tr><td>Journal</td><td><input id='journal' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+9)=="publisher"){
			i+=9;
			result+="<tr><td>Publisher</td><td><input id='publisher' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+4)=="year"){
			i+=4;
			result+="<tr><td>Year</td><td><input id='year' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+6)=="volume"){
			i+=6;
			result+="<tr><td>Volume</td><td><input id='volume' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+5)=="issue"){
			i+=5;
			result+="<tr><td>Issue</td><td><input id='issue' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+7)=="chapter"){
			i+=7;
			result+="<tr><td>Chapter</td><td><input id='chapter' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+5)=="pages"){
			i+=5;
			result+="<tr><td>Pages</td><td><input id='pages' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+9)=="confpages"){
			i+=9;
			result+="<tr><td>Conference Pages</td><td><input id='confpages' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+3)=="doi"){
			i+=3;
			result+="<tr><td>DOI</td><td><input id='doi' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+3)=="url"){
			i+=3;
			result+="<tr><td>URL</td><td><input id='url' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+4)=="isbn"){
			i+=4;
			result+="<tr><td>ISBN</td><td><input id='isbn' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+4)=="issn"){
			i+=4;
			result+="<tr><td>ISSN</td><td><input id='issn' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+13)=="supplementary"){
			i+=13;
			result+="<tr><td>Supplementary</td><td><input id='supplementary' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+5)=="arxiv"){
			i+=5;
			result+="<tr><td>arXiv</td><td><input id='arxiv' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+4)=="file"){
			i+=4;
			result+="<tr><td>File</td><td><input id='file' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		if(text.substring(i,i+6)=="public"){
			i+=6;
			result+="<tr><td>Is Public [true/false]</td><td><input id='public' value='";
			while(text.substring(i,i+1)!="{"){
				i++;
			}
			i++;
			while(text.substring(i,i+1)!="}"){
				result+=text.substring(i,i+1);
				i++;
			}
			i++;
			result+="'></td></tr>";
		}
		
	}
	result+="</table><span class='button' onclick='textVerification();'>Verify</span>";
	return result;
}
function textVerification(){ //after user varification composites data into needed form and allows to upload it further
	var result = formResult();
	document.getElementById("bibInputs").innerHTML = result + "<input id='k'  name='k' required='required' type='hidden' value='1'>" + "<input id='bibImport'  name='bibImport' required='required' type='hidden' value='1'>" + "<br>Press &quotUpload&quot to continue.";
}
//db import onclick
function dbImportClick(){
	document.getElementById("db_import").innerHTML = '<input style="visibility:hidden" id="dbUpload" name="dbUpload" type="text" value="true">';
	document.getElementById("importDbSubmit").click();
}
//fills data fields on doi import
function doiFillDataFields(){
	var temp = "";
	//fill authors
	try {
		temp = document.getElementById("author_data").innerHTML;
		for(var i = 0;i<temp.length;i++){
			if(temp.substring(i,i+5)!= " and "){
				document.getElementById("author" + authorNum).value += temp.substring(i,i+1);
			} else {
				authorNum++;
				i+=4;
				UpdateAuthorSelection();
				authorNum--;
			}
		}
		authorNum++;
		document.getElementById("author_data").outerHTML = "";
		document.getElementById("author_data_desc").outerHTML = "";
		document.getElementById("author_data_br").outerHTML = "";
	} catch(e) {}
	temp = "";
	//fill editors
	try {
		temp = document.getElementById("editor_data").innerHTML;
		for(var i = 0;i<temp.length;i++){
			if(temp.substring(i,i+5)!= " and "){
				document.getElementById("editor" + editorNum).value += temp.substring(i,i+1);
			} else {
				editorNum++;
				i+=4;
				UpdateEditorsSelection();
				editorNum--;
			}
		}
		editorNum++;
		document.getElementById("editor_data").outerHTML = "";
		document.getElementById("editor_data_desc").outerHTML = "";
		document.getElementById("editor_data_br").outerHTML = "";
	} catch(e) {}
	//fill title
	try {
		document.getElementById("title").value = document.getElementById("title_data").innerHTML;
		document.getElementById("title_data").outerHTML = "";
		document.getElementById("title_data_desc").outerHTML = "";
		document.getElementById("title_data_br").outerHTML = "";
	} catch(e) {}
	//fill journal
	try {
		document.getElementById("journal").value = document.getElementById("journal_data").innerHTML;
		document.getElementById("journal_data").outerHTML = "";
		document.getElementById("journal_data_desc").outerHTML = "";
		document.getElementById("journal_data_br").outerHTML = "";
	} catch(e) {}
	//fill booktitle
	try {
		document.getElementById("bookTitle").value = document.getElementById("booktitle_data").innerHTML;
		document.getElementById("booktitle_data").outerHTML = "";
		document.getElementById("booktitle_data_desc").outerHTML = "";
		document.getElementById("booktitle_data_br").outerHTML = "";
	} catch(e) {}
	//fill publisher
	try {
		document.getElementById("publisher").value = document.getElementById("publisher_data").innerHTML;
		document.getElementById("publisher_data").outerHTML = "";
		document.getElementById("publisher_data_desc").outerHTML = "";
		document.getElementById("publisher_data_br").outerHTML = "";
	} catch(e) {}
	//fill year
	try {
		document.getElementById("year").value = document.getElementById("year_data").innerHTML;
		document.getElementById("year_data").outerHTML = "";
		document.getElementById("year_data_desc").outerHTML = "";
		document.getElementById("year_data_br").outerHTML = "";
	} catch(e) {}
	//fill volume
	try {
		document.getElementById("volume").value = document.getElementById("volume_data").innerHTML;
		document.getElementById("volume_data").outerHTML = "";
		document.getElementById("volume_data_desc").outerHTML = "";
		document.getElementById("volume_data_br").outerHTML = "";
	} catch(e) {}
	//fill issue
	try {
		document.getElementById("issue").value = document.getElementById("issue_data").innerHTML;
		document.getElementById("issue_data").outerHTML = "";
		document.getElementById("issue_data_desc").outerHTML = "";
		document.getElementById("issue_data_br").outerHTML = "";
	} catch(e) {}
	//fill chapter
	try {
		document.getElementById("chapter").value = document.getElementById("chapter_data").innerHTML;
		document.getElementById("chapter_data").outerHTML = "";
		document.getElementById("chapter_data_desc").outerHTML = "";
		document.getElementById("chapter_data_br").outerHTML = "";
	} catch(e) {}
	//fill pages
	try {
		document.getElementById("pages").value = document.getElementById("pages_data").innerHTML;
		document.getElementById("pages_data").outerHTML = "";
		document.getElementById("pages_data_desc").outerHTML = "";
		document.getElementById("pages_data_br").outerHTML = "";
	} catch(e) {}
	//fill confpages
	try {
		document.getElementById("confPages").value = document.getElementById("confpages_data").innerHTML;
		document.getElementById("confpages_data").outerHTML = "";
		document.getElementById("confpages_data_desc").outerHTML = "";
		document.getElementById("confpages_data_br").outerHTML = "";
	} catch(e) {}
	//fill doi
	try {
		document.getElementById("doi_input").value = document.getElementById("doi_data").innerHTML;
		document.getElementById("doi_data").outerHTML = "";
		document.getElementById("doi_data_desc").outerHTML = "";
		document.getElementById("doi_data_br").outerHTML = "";
	} catch(e) {}
	//fill isbn
	try {
		document.getElementById("isbn").value = document.getElementById("isbn_data").innerHTML;
		document.getElementById("isbn_data").outerHTML = "";
		document.getElementById("isbn_data_desc").outerHTML = "";
		document.getElementById("isbn_data_br").outerHTML = "";
	} catch(e) {}
	//fill issn
	try {
		document.getElementById("issn").value = document.getElementById("issn_data").innerHTML;
		document.getElementById("issn_data").outerHTML = "";
		document.getElementById("issn_data_desc").outerHTML = "";
		document.getElementById("issn_data_br").outerHTML = "";
	} catch(e) {}
	//fill supplementary
	try {
		document.getElementById("supplementary").value = document.getElementById("supplementary_data").innerHTML;
		document.getElementById("supplementary_data").outerHTML = "";
		document.getElementById("supplementary_data_desc").outerHTML = "";
		document.getElementById("supplementary_data_br").outerHTML = "";
	} catch(e) {}
	//fill arxiv
	try {
		document.getElementById("arxiv").value = document.getElementById("arxiv_data").innerHTML;
		document.getElementById("arxiv_data").outerHTML = "";
		document.getElementById("arxiv_data_desc").outerHTML = "";
		document.getElementById("arxiv_data_br").outerHTML = "";
	} catch(e) {}
	//fill url
	try {
		document.getElementById("url").value = document.getElementById("url_data").innerHTML;
		document.getElementById("url_data").outerHTML = "";
		document.getElementById("url_data_desc").outerHTML = "";
		document.getElementById("url_data_br").outerHTML = "";
	} catch(e) {}
	if(document.getElementById("unprocessed_body").innerHTML == ""){
		document.getElementById("unprocessed_body").outerHTML = "";
		document.getElementById("unprocessed_header").outerHTML = "";
	}
	initialInputWidth();
}
//sets initial input field width
function initialInputWidth() {
	Array.from(document.getElementsByTagName("input")).forEach(function(element) {
		if(element.type == "text"){
			changeInputWidthDefault(String(element.id));
		}
	});
}
//changes input width oninput
var textLength = document.getElementById("textLength");
function changeInputWidthDefault(element_id) {
	textLength.innerText = document.getElementById(element_id).value;
	document.getElementById(element_id).style = "max-width:90vw;min-width:153px;width:" + (textLength.clientWidth + 12) + "px";
}
function formResult(){
	var result = "<input id='" + document.getElementById("paperType").value + "0' name='" + document.getElementById("paperType").value + "0' required='required' type='hidden' size='150' value='{";
	if(document.getElementById("author")){
		result += "author={" + document.getElementById("author").value + "},";
	}
	if(document.getElementById("editor")){
		result += "editor={" + document.getElementById("editor").value + "},";
	}
	if(document.getElementById("title")){
		result += "title={"+document.getElementById("title").value+"},";
	}
	if(document.getElementById("booktitle")){
		result += "booktitle={"+document.getElementById("booktitle").value+"},";
	}
	if(document.getElementById("journal")){
		result += "journal={"+document.getElementById("journal").value+"},";
	}
	if(document.getElementById("publisher")){
		result += "publisher={"+document.getElementById("publisher").value+"},";
	}
	if(document.getElementById("year")){
		result += "year={"+document.getElementById("year").value+"},";
	}
	if(document.getElementById("volume")){
		result += "volume={"+document.getElementById("volume").value+"},";
	}
	if(document.getElementById("issue")){
		result += "issue={"+document.getElementById("issue").value+"},";
	}
	if(document.getElementById("chapter")){
		result += "chapter={"+document.getElementById("chapter").value+"},";
	}
	if(document.getElementById("pages")){
		result += "pages={"+document.getElementById("pages").value+"},";
	}
	if(document.getElementById("confpages")){
		result += "confpages={"+document.getElementById("confpages").value+"},";
	}
	if(document.getElementById("doi")){
		result += "doi={"+document.getElementById("doi").value+"},";
	}
	if(document.getElementById("url")){
		result += "url={"+document.getElementById("url").value+"},";
	}
	if(document.getElementById("isbn")){
		result += "isbn={"+document.getElementById("isbn").value+"},";
	}
	if(document.getElementById("issn")){
		result += "issn={"+document.getElementById("issn").value+"},";
	}
	if(document.getElementById("supplementary")){
		result += "supplementary={"+document.getElementById("supplementary").value+"},";
	}
	if(document.getElementById("arxiv")){
		result += "arxiv={"+document.getElementById("arxiv").value+"},";
	}
	if(document.getElementById("file")){
		result += "file={"+document.getElementById("file").value+"},";
	}
	if(document.getElementById("public")){
		result += "public={"+document.getElementById("public").value+"},";
	}
	result += "}'>";
	return result;
}
