//hides and shows help fields on button click
var activeEl = "";
var activated = false;
function showPrefixHelp(){
	if(activated && activeEl == "prefixHelp"){
		document.getElementById(activeEl).innerHTML = "";
		activated = false;
	} else {
		if (activated){
			document.getElementById(activeEl).innerHTML = "";
		}
		activeEl = "prefixHelp";
		document.getElementById(activeEl).innerHTML = "<span class='help'>If custom prefix is set as <span class='marker'>example</span> , article table in database will be <span class='marker'>wp_example_articles</span></span>";
		activated = true;
	}
}
function showCompHelp(){
	document.getElementById("compTagHelp").innerHTML = document.getElementById("comp_rbracket").value + " auth=johndoe subj=articles prep=all" + document.getElementById("comp_lbracket").value;
}
function showUploadHelp(){
	if (document.getElementById("upload_abs").checked)
	{
		document.getElementById("uploadHelp").innerHTML = document.getElementById("upload_abs_hidden").value + document.getElementById("upload").value;
	} else {
		document.getElementById("uploadHelp").innerHTML = document.getElementById("upload").value;
	}
}
function showChar1Help(){
	if(activated && activeEl == "char1Help"){
		document.getElementById(activeEl).innerHTML = "";
		activated = false;
	} else {
		if (activated){
			document.getElementById(activeEl).innerHTML = "";
		}
		activeEl = "char1Help";
		document.getElementById(activeEl).innerHTML = "<span class='char1' style='" + "<?php echo get_option('bnpp_custom_char1_value'); ?>" + "'>Article by Author / Journal / 2018</span>";
		activated = true;
	}
}
function showChar2Help(){
	if(activated && activeEl == "char2Help"){
		document.getElementById(activeEl).innerHTML = "";
		activated = false;
	} else {
		if (activated){
			document.getElementById(activeEl).innerHTML = "";
		}
		activeEl = "char2Help";
		document.getElementById(activeEl).innerHTML = "<span class='char2' style='" + "<?php echo get_option('bnpp_custom_char2_value'); ?>" + "'>Article by Author / Journal / 2018</span>";
		activated = true;
	}
}
function showChar3Help(){
	if(activated && activeEl == "char3Help"){
		document.getElementById(activeEl).innerHTML = "";
		activated = false;
	} else {
		if (activated){
			document.getElementById(activeEl).innerHTML = "";
		}
		activeEl = "char3Help";
		document.getElementById(activeEl).innerHTML = "<span class='char3' style='" + "<?php echo get_option('bnpp_custom_char3_value'); ?>" + "'>Article by Author / Journal / 2018</span>";
		activated = true;
	}
}
//drop database tables
function dropPluginTables(){
	document.getElementById("dropInput").innerHTML = '<input style="visibility:hidden" id="drop_db" name="drop_db" type="text" value="true">';
	document.getElementById("dropSubmit").click();
}
//publication list constructor
var activeElement = document.getElementById("article_list");
function selectActiveElement(element_id) {
	activeElement = document.getElementById(element_id);
	document.getElementById("pubListSelected").innerHTML = element_id;
	refreshButtonList(element_id);
}
function refreshButtonList(element_id) {
	switch(element_id) {
		case 'article_head':
		case 'conference_head':
		case 'book_head':
			document.getElementById("pubListButtons").innerHTML = "<button onclick=\"appendActiveElementNoBrackets('<h1>')\">&lth1&gt</button><button onclick=\"appendActiveElementNoBrackets('<h2>')\">&lth2&gt</button><button onclick=\"appendActiveElementNoBrackets('<h3>')\">&lth3&gt</button><button onclick=\"appendActiveElementNoBrackets('<h4>')\">&lth4&gt</button><button onclick=\"appendActiveElementNoBrackets('</h1>')\">&lt/h1&gt</button><button onclick=\"appendActiveElementNoBrackets('</h2>')\">&lt/h2&gt</button><button onclick=\"appendActiveElementNoBrackets('</h3>')\">&lt/h3&gt</button><button onclick=\"appendActiveElementNoBrackets('</h4>')\">&lt/h4&gt</button>";
			break;
		case 'article_list':
			document.getElementById("pubListButtons").innerHTML = "<button onclick=\"appendActiveElement('title')\">Title</button><button onclick=\"appendActiveElement('authors')\">Authors</button><button onclick=\"appendActiveElement('journal')\">Journal</button><button onclick=\"appendActiveElement('year')\">Year</button><button onclick=\"appendActiveElement('pages')\">Pages</button><button onclick=\"appendActiveElement('doi')\">DOI</button><button onclick=\"appendActiveElement('url')\">URL</button><button onclick=\"appendActiveElement('issn')\">ISSN</button><button onclick=\"appendActiveElement('supplementary')\">Supplementary</button><button onclick=\"appendActiveElement('filelink')\">File Link</button><button onclick=\"appendActiveElement('date')\">Date</button><button onclick=\"appendActiveElement('volume')\">Volume</button><button onclick=\"appendActiveElement('issue')\">Issue</button><button onclick=\"appendActiveElement('arxiv')\">arXiv</button><button onclick=\"appendActiveElement('tags')\">Tags</button>";
			break;
		case 'conference_list':
			document.getElementById("pubListButtons").innerHTML = "<button onclick=\"appendActiveElement('title')\">Title</button><button onclick=\"appendActiveElement('authors')\">Authors</button><button onclick=\"appendActiveElement('year')\">Year</button><button onclick=\"appendActiveElement('pages')\">Pages</button><button onclick=\"appendActiveElement('doi')\">DOI</button><button onclick=\"appendActiveElement('url')\">URL</button><button onclick=\"appendActiveElement('issn')\">ISSN</button><button onclick=\"appendActiveElement('supplementary')\">Supplementary</button><button onclick=\"appendActiveElement('filelink')\">File Link</button><button onclick=\"appendActiveElement('date')\">Date</button><button onclick=\"appendActiveElement('booktitle')\">Book Title</button><button onclick=\"appendActiveElement('confpages')\">Conference Pages</button><button onclick=\"appendActiveElement('arxiv')\">arXiv</button><button onclick=\"appendActiveElement('tags')\">Tags</button>";
			break;
		case 'book_list':
			document.getElementById("pubListButtons").innerHTML = "<button onclick=\"appendActiveElement('title')\">Title</button><button onclick=\"appendActiveElement('authors')\">Authors</button><button onclick=\"appendActiveElement('editors')\">Editors</button><button onclick=\"appendActiveElement('year')\">Year</button><button onclick=\"appendActiveElement('pages')\">Pages</button><button onclick=\"appendActiveElement('doi')\">DOI</button><button onclick=\"appendActiveElement('url')\">URL</button><button onclick=\"appendActiveElement('issn')\">ISSN</button><button onclick=\"appendActiveElement('supplementary')\">Supplementary</button><button onclick=\"appendActiveElement('filelink')\">File Link</button><button onclick=\"appendActiveElement('date')\">Date</button><button onclick=\"appendActiveElement('publisher')\">Publisher</button><button onclick=\"appendActiveElement('chapter')\">Chapter</button><button onclick=\"appendActiveElement('isbn')\">ISBN</button><button onclick=\"appendActiveElement('arxiv')\">arXiv</button><button onclick=\"appendActiveElement('tags')\">Tags</button>";
			break;
		default:
			document.getElementById("pubListButtons").innerHTML = "";
			break;
	}
}
function appendActiveElement(text) {
	activeElement.value += "[" + text + "]";
}
function appendActiveElementNoBrackets(text) {
	activeElement.value += text;
}
function deleteWordFromActiveElement() {
	var text = activeElement.value;
	while(text.substring(text.length - 1, text.length) != " " && text.length != 0){
		text = text.substring(0,text.length - 1);
	}
	if(text.length>0) {
		text = text.substring(0,text.length - 1);
	}
	activeElement.value = text;
}
//changes width of input blocks
var textLength = document.getElementById("textLength");
function changeInputWidth(element_id) {
	textLength.innerText = document.getElementById(element_id).value;
	document.getElementById(element_id).style = "max-width:90vw;min-width:350px;width:" + (textLength.clientWidth + 12) + "px";
}
function changeInputWidthDefault(element_id) {
	textLength.innerText = document.getElementById(element_id).value;
	document.getElementById(element_id).style = "max-width:90vw;min-width:153px;width:" + (textLength.clientWidth + 12) + "px";
}
var textHeight = document.getElementById("textHeight");
function changeInputHeight(element_id) {
	document.getElementById(element_id).style = "line-height:20px;overflow:hidden;width:350px";
	document.getElementById(element_id).rows = 1;
	var taLineHeight = 20;
	var taHeight = document.getElementById(element_id).scrollHeight;
	document.getElementById(element_id).style.height = taHeight;
	var numberOfLines = Math.floor(taHeight/taLineHeight);
	document.getElementById(element_id).rows = numberOfLines;
}
//sets initial length
window.onload = function() {
	//custom publication lists
	changeInputWidth("article_head");
	changeInputHeight("article_list");
	changeInputWidth("conference_head");
	changeInputHeight("conference_list");
	changeInputWidth("book_head");
	changeInputHeight("book_list");
	//custom characteristics
	changeInputWidthDefault("char1name");
	changeInputWidthDefault("char1value");
	changeInputWidthDefault("char2name");
	changeInputWidthDefault("char2value");
	changeInputWidthDefault("char3name");
	changeInputWidthDefault("char3value");
	//cancels load animation
	if(document.getElementById("loadAnimWrapper")){
		document.getElementById("loadAnimWrapper").style = "display:none;";
	}
}
function showHint(){
	document.getElementById("optContentHint").style = "display:block;background-color:#FFF;padding:5px;line-height:1.6;position:fixed;z-index:1;left:"+(document.getElementById("optContentButton").getBoundingClientRect().left+document.getElementById("optContentButton").offsetWidth)+"px;top:"+document.getElementById("optContentButton").getBoundingClientRect().top+"px;";
}
function hideHint(){
	document.getElementById("optContentHint").style = "display:none;";
}
function showHint1(){
	document.getElementById("optContentHint1").style = "display:block;background-color:#FFF;padding:5px;line-height:1.6;position:fixed;z-index:1;left:"+(document.getElementById("optContentButton1").getBoundingClientRect().left+document.getElementById("optContentButton1").offsetWidth)+"px;top:"+document.getElementById("optContentButton1").getBoundingClientRect().top+"px;";
}
function hideHint1(){
	document.getElementById("optContentHint1").style = "display:none;";
}
var cit_displayed = false;
function toggleCitExamples(){
	if (cit_displayed){
		document.getElementById("cit_ex").style = "display:none;";
	} else {
		document.getElementById("cit_ex").style = "display:block;";
	}
	cit_displayed = !cit_displayed;
}