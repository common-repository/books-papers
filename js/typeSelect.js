//adds and removes additional iput fields for specific paper type
function typeSelection(){
	var val = document.getElementById("paperType").value;
	document.getElementById("article0").innerHTML = '';
	document.getElementById("article1").innerHTML = '';
	document.getElementById("article2").innerHTML = '';
	document.getElementById("article3").innerHTML = '';
	document.getElementById("conf0").innerHTML = '';
	document.getElementById("conf1").innerHTML = '';
	document.getElementById("book0").innerHTML = '';
	document.getElementById("book1").innerHTML = '';
	document.getElementById("book2").innerHTML = '';
	document.getElementById("book3").innerHTML = '';
	document.getElementById("book4").innerHTML = '';
	document.getElementById("type").innerHTML = '';
	document.getElementById("help").innerHTML = '';
	if(val=="conference"){
		document.getElementById("conf0").innerHTML = '<td><label for="bookTitle">Book Title</label></td><td><input oninput="changeInputWidthDefault(\'bookTitle\')" id="bookTitle" name="bookTitle" type="text" placeholder="book title"/></td>';
		document.getElementById("conf1").innerHTML = '<td><label for="confPages">Conference Pages</label></td><td><input oninput="changeInputWidthDefault(\'confPages\')" id="confPages" name="confPages" type="text" placeholder="conference pages"/></td>';
		document.getElementById("type").innerHTML = 'Conference';
		document.getElementById("help").innerHTML = 'Authors;<br>Title;<br>Book Title;<br>Year';
	}else if(val=="book"){
		document.getElementById("book0").innerHTML = '<label for="editors0">Editors:</label>';
		document.getElementById("book1").innerHTML = '<input placeholder="editor" list="editors0" name="editor0"><datalist id="editors0">' + '<?php echo $option_values; ?>' + '</datalist><br><span class="button" id="addEditorToSelection" onclick="UpdateEditorSelection()">Add Another Editor</span>';
		document.getElementById("book2").innerHTML = '<td><label for="publisher">Publisher</label></td><td><input oninput="changeInputWidthDefault(\'publisher\')" id="publisher" name="publisher" type="text" placeholder="publisher"/></td>';
		document.getElementById("book3").innerHTML = '<td><label for="chapter">Chapter</label></td><td><input oninput="changeInputWidthDefault(\'chapter\')" id="chapter" name="chapter" type="text" placeholder="chapter"/></td>';
		document.getElementById("book4").innerHTML = '<td><label for="isbn">ISBN</label></td><td><input oninput="changeInputWidthDefault(\'isbn\')" id="isbn" name="isbn" type="text" placeholder="isbn"/></td>';
		document.getElementById("type").innerHTML = 'Book';
		document.getElementById("help").innerHTML = 'Authors;<br>Editors;<br>Title;<br>Year';
	}else{
		document.getElementById("article0").innerHTML = '<td><label for="journal">Journal</label></td><td><input oninput="changeInputWidthDefault(\'journal\')" id="journal" list="j_list" name="journal" type="text" placeholder="journal"/><datalist id="j_list"><?php echo $j_option_values; ?></datalist></td>';
		document.getElementById("article1").innerHTML = '<td><label for="volume">Volume</label></td><td><input id="volume" name="volume" type="number" min="0" placeholder="volume"/></td>';
		document.getElementById("article2").innerHTML = '<td><label for="issue">Issue</label></td><td><input id="issue" name="issue" type="number" min="0" placeholder="issue"/></td>';
		document.getElementById("article3").innerHTML = '<td><label for="preprint">It is Preprint</label></td><td><input id="preprint" name="preprint" type="checkbox" value="true"/></td>';
		document.getElementById("type").innerHTML = 'Article';
		document.getElementById("help").innerHTML = 'Authors;<br>Title;<br>Journal;<br>Year';
	}
}