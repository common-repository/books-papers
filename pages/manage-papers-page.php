<?php include(bnpp_plugin_dir . "./pages/loadAnimation.php"); ?>
<div class="manageAuthorsPage">
	<h1>Books &amp; Papers: Manage Works</h1>
	<style><?php include(bnpp_plugin_dir . "./pages/style.css"); ?></style>
	<div class="InformationText">
		Here you can manage publication data.<br>
		Click on one of the header below to quickly get to desirable publication list<br>
		<a href="#articles"><span class="button">Articles</span></a><a href="#conferences"><span class="button">Conferences</span></a><a href="#books"><span class="button">Books</span></a><br>
		Sort Method:
		<form action="" method="post" style="display:inline-block">
			<?php
			if ( function_exists('wp_nonce_field') ) 
				wp_nonce_field('bnp_sort_papers');
			?>
			<select onchange="updateSortMethod()" style="width:153px" id="sortMethod" name="sortMethod">
				<option value="id" <?php if(get_option('bnpp_paper_sort')=='id'){echo "selected";}?>>ID</option>
				<option value="title" <?php if(get_option('bnpp_paper_sort')=='title'){echo "selected";}?>>Title</option>
				<option value="year" <?php if(get_option('bnpp_paper_sort')=='year'){echo "selected";}?>>Year</option>
				<option value="date" <?php if(get_option('bnpp_paper_sort')=='date'){echo "selected";}?>>Sort Date</option>
			</select>
			<select onchange="updateSortMethod()" style="width:153px" id="sortMethodOrder" name="sortMethodOrder">
				<option value="ASC" <?php if(get_option('bnpp_paper_sort_order')=='ASC'){echo "selected";}?>>ASC</option>
				<option value="DESC" <?php if(get_option('bnpp_paper_sort_order')=='DESC'){echo "selected";}?>>DESC</option>
			</select>
			<button id="sortMethodSubmit" class="button" type="submit" style="visibility:hidden">Change</button>
		</form><br><br>
	</div>
	<div><span class="s_green" id="success"></span></div><div><span class="s_red" id="remove"></span></div>
	<form enctype="multipart/form-data" name="managePaper" action="" method="post">
		<?php
		if ( function_exists('wp_nonce_field') ) 
			wp_nonce_field('bnp_manage_papers');
		?>
		<h2 id="articles">Articles</h2>
		<div style="overflow-x:auto">
			<table class="manageAuthors">
				<tr class="tabHeader" id="nav_ar_o">
					<th>ID</th><th>Authors</th><th>Title</th><th>Journal</th><th>Year</th><th>Tags</th><th>Sort Date</th><th>Action</th>
				</tr>
				<tr class="tabHeader hidden" id="nav_ar">
					<th>ID</th><th>Authors</th><th>Title</th><th>Journal</th><th>Year</th><th>Tags</th><th>Sort Date</th><th>Action</th>
				</tr>
				<tr></tr>
				<?php
				try{
					$options = '';
					foreach($data as $article) //generates article table content
					{
						$options='';
						echo "<tr><td>$article->id</td><td>";
						$data1 = $wpdb->get_results("SELECT * FROM " . $this->dbArticleAuthor . " WHERE article_id=$article->id");
						foreach($data1 as $author)
						{
							if(is_null($author->author_id))
							{
								echo "$author->author_name<br>";
								$options .= "_" . $author->author_name;
							} else
							{
								$data2 = $wpdb->get_results("SELECT * FROM " . $this->dbAuthors . " WHERE id=$author->author_id");
								foreach($data2 as $au2)
								{
									if($au2->personal_url != "")
									{
										echo "<a href='$au2->personal_url'>$au2->first_name $au2->last_name</a><br>";
									} else
									{
										echo "$au2->first_name $au2->last_name<br>";
									}
									$options .= "_" . $au2->first_name . " " . $au2->last_name;
								}
							}
						}
						echo "</td><td>$article->title</td><td>";
						$data3 = $wpdb->get_results("SELECT journal FROM " . $this->dbJournals . " WHERE id=$article->journal");
						$j_val = "";
						foreach($data3 as $journal)
						{
							echo "$journal->journal";
							$j_val = $journal->journal;
						}
						echo "</td><td>$article->year</td><td>";
						$t_options = "";
						$data8 = $wpdb->get_results("SELECT * FROM " . $this->dbArticleTag . " WHERE article_id=$article->id");
						foreach($data8 as $tag)
						{
							$data9 = $wpdb->get_results("SELECT * FROM " . $this->dbTags . " WHERE id=$tag->tag_id");
							foreach($data9 as $au2)
							{
								{
									echo "$au2->tag<br>";
								}
								$t_options .= "_" . $au2->tag;
							}
						}
						echo "</td><td>$article->date</td>";
						echo "<td><span class='button' onclick='appendData(\"table$article->id\",true,$article->id,\"" . str_replace('"', "&bnpquote;", $article->title) . "\",\"$j_val\",\"$article->year\",\"$article->date\",\"$article->volume\",\"$article->issue\",\"$article->pages\",\"$article->doi\",\"$article->url\",\"$article->issn\",\"$article->supplementary\",\"$article->arxiv\",\"$article->file_link\",\"";
						if(!is_null($article->is_public)) {echo "checked";}
						echo "\",\"";
						if(!is_null($article->preprint)) {echo "checked";}
						echo "\",\"";
						if(!is_null($article->char1)) {echo "checked";}
						echo "\",\"";
						if(!is_null($article->char2)) {echo "checked";}
						echo "\",\"";
						if(!is_null($article->char3)) {echo "checked";}
						echo "\");InitialAuthorSelection($article->id,\"$options\");InitialTagSelection($article->id,\"$t_options\")'>Modify</span><span class='button' onclick='appendData(\"table$article->id\",false,$article->id)'>Remove</span></td></tr><tr><td colspan='7' id='table$article->id'></td></tr>";
					}
				}catch (Exception $e) {}
				?>
				<tr id="nav_ar_e"></tr>
			</table>
		</div>
		<h2 id="conferences">Conferences</h2>
		<div style="overflow-x:auto">
			<table class="manageAuthors">
				<tr class="tabHeader" id="nav_co_o">
					<th>ID</th><th>Authors</th><th>Title</th><th>Book Title</th><th>Year</th><th>Conference Pages</th><th>Tags</th><th>Sort Date</th><th>Action</th>
				</tr>
				<tr class="tabHeader hidden" id="nav_co">
					<th>ID</th><th>Authors</th><th>Title</th><th>Book Title</th><th>Year</th><th>Conference Pages</th><th>Tags</th><th>Sort Date</th><th>Action</th>
				</tr>
				<tr></tr>
				<?php
				try{
					foreach($dataC as $conference) //generates conference table content
					{
						$options='';
						echo "<tr><td>$conference->id</td><td>";
						$data1 = $wpdb->get_results("SELECT * FROM " . $this->dbConferenceAuthor . " WHERE conference_id=$conference->id");
						foreach($data1 as $author)
						{
							if(is_null($author->author_id))
							{
								echo "$author->author_name<br>";
								$options.= "_" . $author->author_name;
							} else
							{
								$data2 = $wpdb->get_results("SELECT * FROM " . $this->dbAuthors . " WHERE id=$author->author_id");
								foreach($data2 as $au2)
								{
									if($au2->personal_url != "")
									{
										echo "<a href='$au2->personal_url'>$au2->first_name $au2->last_name</a><br>";
									} else
									{
										echo "$au2->first_name $au2->last_name<br>";
									}
									$options .= "_" . $au2->first_name . " " . $au2->last_name;
								}
							}
						}
						echo "</td><td>$conference->title</td><td>$conference->book_title</td><td>$conference->year</td><td>$conference->conf_page</td><td>";
						$t_options = "";
						$data8 = $wpdb->get_results("SELECT * FROM " . $this->dbConferenceTag . " WHERE conference_id=$conference->id");
						foreach($data8 as $tag)
						{
							$data9 = $wpdb->get_results("SELECT * FROM " . $this->dbTags . " WHERE id=$tag->tag_id");
							foreach($data9 as $au2)
							{
								{
									echo "$au2->tag<br>";
								}
								$t_options .= "_" . $au2->tag;
							}
						}
						echo "</td><td>$conference->date</td>";
						echo "<td><span class='button' onclick='appendDataC(\"ctable$conference->id\",true,$conference->id,\"" . str_replace('"', "&bnpquote;", $conference->title) . "\",\"$conference->year\",\"$conference->date\",\"$conference->pages\",\"$conference->doi\",\"$conference->url\",\"$conference->issn\",\"$conference->supplementary\",\"$conference->arxiv\",\"$conference->file_link\",\"";
						if(!is_null($conference->is_public)) {echo "checked";}
						echo "\",\"";
						if(!is_null($conference->char1)) {echo "checked";}
						echo "\",\"";
						if(!is_null($conference->char2)) {echo "checked";}
						echo "\",\"";
						if(!is_null($conference->char3)) {echo "checked";}
						echo "\",\"" . str_replace('"', "&bnpquote;", $conference->book_title) . "\",\"$conference->conf_page\");InitialAuthorSelection($conference->id,\"$options\");InitialTagSelection($conference->id,\"$t_options\")'>Modify</span><span class='button' onclick='appendDataC(\"ctable$conference->id\",false,$conference->id)'>Remove</span></td></tr><tr><td colspan='8' id='ctable$conference->id'></td></tr>";
					}
				}catch (Exception $e) {}
				?>
				<tr id="nav_co_e"></tr>
			</table>
		</div>
		<h2 id="books">Books</h2>
		<div style="overflow-x:auto">
			<table class="manageAuthors">
				<tr class="tabHeader" id="nav_bo_o">
					<th>ID</th><th>Authors</th><th>Editors</th><th>Title</th><th>Publisher</th><th>Year</th><th>Tags</th><th>Sort Date</th><th>Action</th>
				</tr>
				<tr class="tabHeader hidden" id="nav_bo">
					<th>ID</th><th>Authors</th><th>Editors</th><th>Title</th><th>Publisher</th><th>Year</th><th>Tags</th><th>Sort Date</th><th>Action</th>
				</tr>
				<tr></tr>
				<?php
				try{
					foreach($dataB as $book) //generates conference table content
					{
						$options='';
						echo "<tr><td>$book->id</td><td>";
						$data1 = $wpdb->get_results("SELECT * FROM " . $this->dbBookAuthor . " WHERE book_id=$book->id");
						foreach($data1 as $author)
						{
							if(is_null($author->author_id))
							{
								echo "$author->author_name<br>";
								$options.="_" . $author->author_name;
							} else
							{
								$data2 = $wpdb->get_results("SELECT * FROM " . $this->dbAuthors . " WHERE id=$author->author_id");
								foreach($data2 as $au2)
								{
									if($au2->personal_url != "")
									{
										echo "<a href='$au2->personal_url'>$au2->first_name $au2->last_name</a><br>";
									} else
									{
										echo "$au2->first_name $au2->last_name<br>";
									}
									$options .= "_" . $au2->first_name . " " . $au2->last_name;
								}
							}
						}
						$options_e='';
						echo "</td><td>";
						$data1 = $wpdb->get_results("SELECT * FROM " . $this->dbBookEditor . " WHERE book_id=$book->id");
						foreach($data1 as $editor)
						{
							if(is_null($editor->editor_id))
							{
								echo "$editor->editor_name<br>";
								$options_e.="_" . $editor->editor_name;
							} else
							{
								$data2 = $wpdb->get_results("SELECT * FROM " . $this->dbAuthors . " WHERE id=$editor->editor_id");
								foreach($data2 as $au2)
								{
									if($au2->personal_url != "")
									{
										echo "<a href='$au2->personal_url'>$au2->first_name $au2->last_name</a><br>";
									} else
									{
										echo "$au2->first_name $au2->last_name<br>";
									}
									$options_e.="_" . $au2->first_name . " " . $au2->last_name;
								}
							}
						}
						echo "</td><td>$book->title</td><td>$book->publisher</td><td>$book->year</td><td>";
						$t_options = "";
						$data8 = $wpdb->get_results("SELECT * FROM " . $this->dbBookTag . " WHERE book_id=$book->id");
						foreach($data8 as $tag)
						{
							$data9 = $wpdb->get_results("SELECT * FROM " . $this->dbTags . " WHERE id=$tag->tag_id");
							foreach($data9 as $au2)
							{
								{
									echo "$au2->tag<br>";
								}
								$t_options .= "_" . $au2->tag;
							}
						}
						echo "</td><td>$book->date</td>";
						echo "<td><span class='button' onclick='appendDataB(\"btable$book->id\",true,$book->id,\"" . str_replace('"', "&bnpquote;", $book->title) . "\",\"$book->year\",\"$book->date\",\"$book->pages\",\"$book->doi\",\"$book->url\",\"$book->issn\",\"$book->supplementary\",\"$book->arxiv\",\"$book->file_link\",\"";
						if(!is_null($book->is_public)) {echo "checked";}
						echo "\",\"";
						if(!is_null($book->char1)) {echo "checked";}
						echo "\",\"";
						if(!is_null($book->char2)) {echo "checked";}
						echo "\",\"";
						if(!is_null($book->char3)) {echo "checked";}
						echo "\",\"" . str_replace('"', "&bnpquote;", $book->publisher) . "\",\"$book->chapter\",\"$book->isbn\");InitialAuthorSelection($book->id,\"$options\");InitialEditorSelection($book->id,\"$options_e\");InitialTagSelection($book->id,\"$t_options\")'>Modify</span><span class='button' onclick='appendDataB(\"btable$book->id\",false,$book->id)'>Remove</span></td></tr><tr><td colspan='8' id='btable$book->id'></td></tr>";
					}
				}catch (Exception $e) {}
				?>
				<tr id="nav_bo_e"></tr>
			</table>
		</div>
	</form>
	<div id="textLength" style="width:auto;display:inline-block;visibility:hidden;font-size:14px;position:fixed"></div>
	<script><?php include(bnpp_plugin_dir . "./js/stickyScroll.js"); ?></script>
	<script><?php include(bnpp_plugin_dir . "./js/appendPapers.js"); ?></script>
	<script><?php include(bnpp_plugin_dir . "./js/authorSelectionPaper.js"); ?></script>
</div>