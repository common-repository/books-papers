var authorNum = 0;
var editorNum = 0;
var tagNum = 0;
function InitialAuthorSelection(paper_id, options){ //onload author selection list creation
	var content = '<input placeholder="author" list="authors' + authorNum + '" name="author' + authorNum + '" id="author'+authorNum + '" value="';
	var i = 0;
	var num = "";
	var db="";
	options = String(options);
	while(i < options.length){
		if(options.substring(i,i+1)=="_"){
			i++;
			num = "";
			while(i<options.length && options.substring(i,i+1)!="_"){
				num+=options.substring(i,i+1);
				i++;
			}
			content+=num + '"><datalist id="authors' + authorNum + '">' + "<?php echo $option_values; ?>" + '</datalist><span class="button" id="0author' + authorNum + '" onclick="RemoveAuthorFromSelection(\'author'+authorNum+'\')" >X</span><br id="1author' + authorNum + '">';
			authorNum++;
			content+='<input placeholder="author" list="authors' + authorNum + '" name="author' + authorNum + '" id="author'+authorNum + '" value="';
			i--;
		}
		i++;
	}
	content+='"><datalist id="authors' + authorNum + '">' + "<?php echo $option_values; ?>" + '</datalist><br><span class="button" id="addAuthorToSelection" onclick="UpdateAuthorSelection()">Add Another Author</span>';
	document.getElementById("authorSelection"+paper_id).innerHTML = content;
	authorNum = authorNum + 1;
}
function InitialTagSelection(paper_id, options){ //onload tag selection list creation
	var content = '<input placeholder="tag" list="tags' + tagNum + '" name="tag' + tagNum + '" id="tag'+tagNum + '" value="';
	var i = 0;
	var num = "";
	var db="";
	options = String(options);
	while(i < options.length){
		if(options.substring(i,i+1)=="_"){
			i++;
			num = "";
			while(i<options.length && options.substring(i,i+1)!="_"){
				num+=options.substring(i,i+1);
				i++;
			}
			content+=num + '"><datalist id="tags' + tagNum + '">' + "<?php echo $t_option_values; ?>" + '</datalist><span class="button" id="0tag' + tagNum + '" onclick="RemoveAuthorFromSelection(\'tag'+tagNum+'\')" >X</span><br id="1tag' + tagNum + '">';
			tagNum++;
			content+='<input placeholder="tag" list="tags' + tagNum + '" name="tag' + tagNum + '" id="tag'+tagNum + '" value="';
			i--;
		}
		i++;
	}
	content+='"><datalist id="tags' + tagNum + '">' + "<?php echo $t_option_values; ?>" + '</datalist><br><span class="button" id="addTagToSelection" onclick="UpdateTagSelection()">Add Tag</span>';
	document.getElementById("tagSelection"+paper_id).innerHTML = content;
	tagNum = tagNum + 1;
}
function UpdateAuthorSelection(){ //extends author selection list
	document.getElementById("addAuthorToSelection").outerHTML = '<input placeholder="author" list="authors' + authorNum + '" name="author' + authorNum + '" id="author' + authorNum + '"><datalist id="authors' + authorNum + '">' + '<?php echo $option_values; ?>' + '</datalist><span class="button" id="0author' + authorNum + '" onclick="RemoveAuthorFromSelection(\'author'+authorNum+'\')" >X</span><br id="1author' + authorNum + '"><span class="button" id="addAuthorToSelection" onclick="UpdateAuthorSelection()">Add Another Author</span>';
	authorNum = authorNum + 1;
}
function UpdateTagSelection(){ //extends tag selection list
	document.getElementById("addTagToSelection").outerHTML = '<input placeholder="tag" list="tags' + tagNum + '" name="tag' + tagNum + '" id="tag' + tagNum + '"><datalist id="tags' + tagNum + '">' + '<?php echo $t_option_values; ?>' + '</datalist><span class="button" id="0tag' + tagNum + '" onclick="RemoveAuthorFromSelection(\'tag'+tagNum+'\')" >X</span><br id="1tag' + tagNum + '"><span class="button" id="addTagToSelection" onclick="UpdateTagSelection()">Add Tag</span>';
	tagNum = tagNum + 1;
}
function InitialEditorSelection(paper_id, options){
	var temp = '<input placeholder="editor" list="editors0" name="editor0"><datalist id="editors0">' + '<?php echo $option_values; ?>' + '</datalist><br><span class="button" id="addEditorToSelection" onclick="UpdateEditorSelection()">Add Another Editor</span>';
	var content = '<input placeholder="editor" list="editors' + editorNum + '" name="editor' + editorNum + '" id="editor'+editorNum + '" value="';
	var i = 0;
	var num = "";
	var db="";
	options = String(options);
	while(i < options.length){
		if(options.substring(i,i+1)=="_"){
			i++;
			num = "";
			while(i<options.length && options.substring(i,i+1)!="_"){
				num+=options.substring(i,i+1);
				i++;
			}
			content+=num + '"><datalist id="editors' + editorNum + '">' + "<?php echo $option_values; ?>" + '</datalist><span class="button" id="0editor' + editorNum + '" onclick="RemoveAuthorFromSelection(\'editor'+editorNum+'\')" >X</span><br id="1editor' + editorNum + '">';
			editorNum++;
			content+='<input placeholder="editor" list="editors' + editorNum + '" name="editor' + editorNum + '" id="editor'+editorNum + '" value="';
			i--;
		}
		i++;
	}
	content+='"><datalist id="editors' + editorNum + '">' + "<?php echo $option_values; ?>" + '</datalist><br><span class="button" id="addEditorToSelection" onclick="UpdateEditorSelection()">Add Another Editor</span>';
	document.getElementById("editorSelection"+paper_id).innerHTML = content;
	editorNum = editorNum + 1;
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