window.onload = function(){
	try{
	InitialAuthorSelection();
	InitialTagSelection();
	}
	catch (e) {}
	if(document.getElementById("loadAnimWrapper")){
		document.getElementById("loadAnimWrapper").style = "display:none;";
	}
}
var authorNum = 0;
var editorNum = 1;
var tagNum = 0;
function InitialAuthorSelection(){ //onload author selection list creation
	document.getElementById("authorSelection").innerHTML = '<input placeholder="author" list="authors' + authorNum + '" name="author' + authorNum + '" id="author' + authorNum + '"><datalist id="authors' + authorNum + '">' + '<?php echo $option_values; ?>' + '</datalist><br><span class="button" id="addAuthorToSelection" onclick="UpdateAuthorSelection()">Add Another Author</span>';
	authorNum = authorNum + 1;
}
function UpdateAuthorSelection(){ //extends author selection list
	document.getElementById("addAuthorToSelection").outerHTML = '<input placeholder="author" list="authors' + authorNum + '" name="author' + authorNum + '" id="author' + authorNum + '"><datalist id="authors' + authorNum + '">' + '<?php echo $option_values; ?>' + '</datalist><span class="button" id="0author' + authorNum + '" onclick="RemoveAuthorFromSelection(\'author'+authorNum+'\')" >X</span><br id="1author' + authorNum + '"><span class="button" id="addAuthorToSelection" onclick="UpdateAuthorSelection()">Add Another Author</span>';
	authorNum = authorNum + 1;
}
function InitialTagSelection(){ //onload tag selection list creation
	document.getElementById("tagSelection").innerHTML = '<input placeholder="tag" list="tags' + tagNum + '" name="tag' + tagNum + '" id="tag' + tagNum + '"><datalist id="tags' + tagNum + '">' + '<?php echo $t_option_values; ?>' + '</datalist><br><span class="button" id="addTagToSelection" onclick="UpdateTagSelection()">Add Tag</span>';
	tagNum = tagNum + 1;
}
function UpdateTagSelection(){ //extends tag selection list
	document.getElementById("addTagToSelection").outerHTML = '<input placeholder="tag" list="tags' + tagNum + '" name="tag' + tagNum + '" id="tag' + tagNum + '"><datalist id="tags' + tagNum + '">' + '<?php echo $t_option_values; ?>' + '</datalist><span class="button" id="0tag' + tagNum + '" onclick="RemoveAuthorFromSelection(\'tag'+tagNum+'\')" >X</span><br id="1tag' + tagNum + '"><span class="button" id="addTagToSelection" onclick="UpdateTagSelection()">Add Tag</span>';
	tagNum = tagNum + 1;
}
function UpdateEditorSelection(){ //extends editor selection list
	document.getElementById("addEditorToSelection").outerHTML = '<input placeholder="editor" list="editors' + editorNum + '" name="editor' + editorNum + '" id="editor' + editorNum + '"><datalist id="editors' + editorNum + '">' + '<?php echo $option_values; ?>' + '</datalist><span class="button" id="0editor' + editorNum + '" onclick="RemoveAuthorFromSelection(\'editor'+editorNum+'\')" >X</span><br id="1editor' + editorNum + '"><span class="button" id="addEditorToSelection" onclick="UpdateEditorSelection()">Add Another Editor</span>';
	editorNum = editorNum + 1;
}
function RemoveAuthorFromSelection(doc_id){ //remove author/editor from selection list
	document.getElementById(doc_id).outerHTML = "";
	document.getElementById("0"+doc_id).outerHTML = "";
	document.getElementById("1"+doc_id).outerHTML = "";
}