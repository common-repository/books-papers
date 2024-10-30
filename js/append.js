var active_element = "";
var isActive = false;
var isModify = false;
//changes width of input fields
var textLength = document.getElementById("textLength");
function changeInputWidthDefault(element_id) {
	textLength.innerText = document.getElementById(element_id).value;
	document.getElementById(element_id).style = "max-width:90%;min-width:153px;width:" + (textLength.clientWidth + 12) + "px";
}
//updates sort method
function updateSortMethod() {
	document.getElementById("sortMethodSubmit").click();
}
//appends table with author data modification fields or author deletion button
function appendData(element_id,modify,author_id,author_name,author_surname,author_email,author_url,author_slug) {
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
			document.getElementById(active_element).innerHTML = '<div class="enterInfo"><input type="hidden" name="checkManage" value="update"><input type="hidden" name="authorID" value=' + author_id + '><table><tr><td style="min-width:100px;"><label for="firstName">First Name</label></td><td><input oninput="changeInputWidthDefault(\'firstName\')" id="firstName" name="firstName" required="required" type="text" placeholder="name" value="' + author_name + '"/></td></tr><tr><td><label for="lastName">Last Name</label></td><td><input oninput="changeInputWidthDefault(\'lastName\')" id="lastName" name="lastName" required="required" type="text" placeholder="surname" value="' + author_surname + '"/></td></tr><tr><td><label for="email">Email</label></td><td><input oninput="changeInputWidthDefault(\'email\')" id="email" name="email" type="email" placeholder="user@mail.com" value="' + author_email + '"/></td></tr><tr><td><label for="url">Personal URL</label></td><td><input oninput="changeInputWidthDefault(\'url\')" id="url" name="url" type="text" placeholder="http://site.com/author" value="' + author_url + '"/></td></tr><tr><td><label for="slug">Identifier</label></td><td><input oninput="changeInputWidthDefault(\'slug\')" id="slug" name="slug" type="text" placeholder="author" value="' + author_slug + '"/></td></tr></table><button class="button" id="updateAuthorSubmit" type="submit">Update Author</button></div>';
			changeInputWidthDefault('firstName');
			changeInputWidthDefault('lastName');
			changeInputWidthDefault('email');
			changeInputWidthDefault('url');
			changeInputWidthDefault('slug');
		} else { //sets deletion fields
			isModify = false;
			document.getElementById(active_element).innerHTML = '<div class="enterInfo"><input type="hidden" name="checkManage" value="remove"><input type="hidden" name="authorID" value=' + author_id + '><p><b>Are you sure you want to remove this author?</b></p><p><button name="answer" value="yes" class="button" type="submit">Yes</button><button name="answer" value="no" class="button" type="submit">No</button></p></div>';
		}
	}
}
//updates list of authors to merge on checkbox change
function updateMerge(author_id, author_name) {
	if(document.getElementById("mergeCheckbox" + author_id).checked){
		if(document.getElementById("numberOfAuthors").value==0) {
		   document.getElementById("mergeAuthorId").value = author_id;
		}
		document.getElementById("numberOfAuthors").value++;
		document.getElementById("mergeList").innerHTML+= "<input type='hidden' id='mergeList" + document.getElementById("numberOfAuthors").value + "' name='mergeList" + document.getElementById("numberOfAuthors").value + "' value='" + author_id + "'>";
		document.getElementById("mergeMainAuthor").innerHTML += "<option id='mergeOption"+document.getElementById("numberOfAuthors").value+"' data-value='"+author_id+"'>" + author_name + "</option>";
	} else {
		document.getElementById("numberOfAuthors").value--;
		var mergeListcheck = false;
		var i=1;
		for(;i<=document.getElementById("numberOfAuthors").value;i++){
			if(document.getElementById("mergeList"+i).value==author_id){
				mergeListcheck = true;
			}
			if(mergeListcheck){
				document.getElementById("mergeList"+i).value = document.getElementById("mergeList"+(i+1)).value;
				document.getElementById("mergeOption"+i).dataset.value = document.getElementById("mergeOption"+(i+1)).dataset.value;
				document.getElementById("mergeOption"+i).innerHTML = document.getElementById("mergeOption"+(i+1)).innerHTML;
			}
		}
		if(document.getElementById("mergeOption"+i)){
			document.getElementById("mergeOption"+i).outerHTML="";
			document.getElementById("mergeList"+i).outerHTML="";
		}
		if(document.getElementById("mergeAuthorId").value==author_id){
			if(document.getElementById("mergeList1")){
				document.getElementById("mergeAuthorId").value = document.getElementById("mergeList1").value;
			} else {
				document.getElementById("mergeAuthorId").value = 0;
			}
		}
	}
	if(document.getElementById("numberOfAuthors").value==0){
		document.getElementById("authorMerge").style = "display:none";
	} else {
		document.getElementById("authorMerge").style = "display:block";
	}
}
//changes main author
function updateMergeAuthor() {
	for(var i=1;i<=document.getElementById("numberOfAuthors").value;i++){
		if(document.getElementById("mergeMainAuthor").value==document.getElementById("mergeOption"+i).innerHTML){
			document.getElementById("mergeAuthorId").value = document.getElementById("mergeOption"+i).dataset.value;
		}
	}
}
//show unlisted authors
var displayed = false;
function toggleUnlistedAuthors(){
	if(displayed){
		document.getElementById("unlisted").style = "display:none";
		displayed = false;
	} else {
		document.getElementById("unlisted").style = "display:block";
		displayed = true;
	}
}
//append unlisted authors
function appendUnlisted(author_id,author_name,relation){
	var element_id = relation + "_table" + author_id;
	if (active_element != ""){ //checks if no management fields were called (first time only)
		document.getElementById(active_element).innerHTML = '';
	}
	if (active_element == element_id){ //checks if this button already been pressed
		isActive = !isActive; //if true closes or opens management fields
	} else {
		isActive = false; //if false opens management fields
	}
	active_element = element_id; //sets new active management fields
	var names = author_name.split(' ');
	var lastName = names[names.length - 1];
	author_name = author_name.replace(" "+names[names.length - 1],'');
	if (!isActive){
		document.getElementById(active_element).innerHTML = "<input type=\"hidden\" name=\"promote\" value=\""+author_id+"\"><input type=\"hidden\" name=\"relation\" value=\""+relation+"\"><table><tr><td style=\"min-width:100px;\"><label for=\"firstName\">First Name</label></td><td><input oninput=\"changeInputWidthDefault(\'firstName\')\" id=\"firstName\" name=\"firstName\" required=\"required\" type=\"text\" placeholder=\"name\" value=\""+author_name+"\"/></td></tr><tr><td><label for=\"lastName\">Last Name</label></td><td><input oninput=\"changeInputWidthDefault(\'lastName\')\" id=\"lastName\" name=\"lastName\" required=\"required\" type=\"text\" placeholder=\"surname\" value=\""+lastName+"\"/></td></tr><tr><td><label for=\"email\">Email</label></td><td><input oninput=\"changeInputWidthDefault(\'email\')\" id=\"email\" name=\"email\" type=\"email\" placeholder=\"user@mail.com\"/></td></tr><tr><td><label for=\"url\">Personal URL</label></td><td><input oninput=\"changeInputWidthDefault(\'url\')\" id=\"url\" name=\"url\" type=\"text\" placeholder=\"http://site.com/author\"/></td></tr><tr><td><label for=\"slug\">Identifier</label></td><td><input oninput=\"changeInputWidthDefault(\'slug\')\" id=\"slug\" name=\"slug\" type=\"text\" placeholder=\"author\"/></td></tr></table><button class=\"button\" id=\"addAuthorSubmit\" type=\"submit\">Add Author</button>";
	}
}
//displays hint if author name contains brackets
window.addEventListener("mousemove", function(event){
	if(document.getElementById("firstName")){
		if(document.getElementById("nameHintF")){
			document.getElementById("nameHintF").style = "display:block;position:fixed;z-index:1;left:"+(event.clientX+10)+"px;top:"+event.clientY+"px;";
		}
		document.getElementById("firstName").addEventListener("mouseover", function(){
			if(document.getElementById("firstName").value.match(/((?![a-zA-Z\-\. ]).)/)){
				if(!document.getElementById("nameHintF")){
					document.getElementById("firstName").outerHTML += "<div id=\"nameHint\" style=\"display:none;\"><span style=\"display:block;background:rgba(125,125,125,0.2);padding:5px;\">Special Characters in Name were detected.<br>Please, try using escape codes like:<br>&amp;#228; for &#228;<br>&amp;#196; for &#196;<br>&amp;#246; for &#246;<br>&amp;#214; for &#214;<br>&amp;#252; for &#252;<br>&amp;#220; for &#220;<br>&amp;#223; for &#223;<br></span></div>";
				}
			}
		});
		document.getElementById("firstName").addEventListener("mouseout", function(){
			if(document.getElementById("nameHintF")){
				document.getElementById("nameHintF").outerHTML = "";
			}
		});
	}
	if(document.getElementById("lastName")){
		if(document.getElementById("nameHint")){
			document.getElementById("nameHint").style = "display:block;position:fixed;z-index:1;left:"+(event.clientX+10)+"px;top:"+event.clientY+"px;";
		}
		document.getElementById("lastName").addEventListener("mouseover", function(){
			if(document.getElementById("lastName").value.match(/((?![a-zA-Z\-\. ]).)/)){
				if(!document.getElementById("nameHint")){
					document.getElementById("lastName").outerHTML += "<div id=\"nameHint\" style=\"display:none;\"><span style=\"display:block;background:rgba(125,125,125,0.2);padding:5px;\">Special Characters in Name were detected.<br>Please, try using escape codes like:<br>&amp;#228; for &#228;<br>&amp;#196; for &#196;<br>&amp;#246; for &#246;<br>&amp;#214; for &#214;<br>&amp;#252; for &#252;<br>&amp;#220; for &#220;<br>&amp;#223; for &#223;<br></span></div>";
				}
			}
		});
		document.getElementById("lastName").addEventListener("mouseout", function(){
			if(document.getElementById("nameHint")){
				document.getElementById("nameHint").outerHTML = "";
			}
		});
	}
});