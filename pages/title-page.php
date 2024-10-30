<?php include(bnpp_plugin_dir . "./pages/loadAnimation.php"); ?>
<div class="mainAdminPage">
	<style><?php include(bnpp_plugin_dir . "./pages/style.css"); ?></style>
	<h1>Books &amp Papers Plugin Page</h1>
	<p>
		The plugin provides a simple bibliography management tool for collecting and displaying lists of publications, suitable for scientists, writers and anyone who manages collections of publications.
	</p>
	<h2>Features</h2>
	<p>Use the admin menu on the left to select one of the following features:</p>
	<ul>
		<li><span class="marker">Add Author</span> and <span class="marker">Manage Authors</span> contain tools for author management.</li>
		<li><span class="marker">Add Work</span> and <span class="marker">Manage Works</span> contain tools for publication management.</li>
		<li><span class="marker">Import Works</span> contains data import tools (use DOI or local <a href="https://en.wikipedia.org/wiki/BibTeX">.bib</a> file).</li>
	</ul>
	<h2>How to use</h2>
	<p>
		Publication data is displayed in a generated list, which is placed on your site page by replacing a previously entered shortcode. The appearence of the list is determined by a several factors such as options in the shortcode and settings selected on the plugin settings page.
	</p>
	<p>
		Use this structure to make your shortcode:<br>
		<span class="marker">[publications auth={all/author} subj={all/mixed/articles/conferences/books} prep={all/only/ex} {year=YYYY / tag={tag} / sort={sort_option} / btitle={book_title}}]</span> to obtain a publication list.<br>
		or <span class="marker">[publications subj={publication} id={id}]</span> to get a specific publication.
	</p>
	<p>
		In the structures above: <span class="marker">{author}</span> should be replaced with the identifier of an existing author (see the corresponding field in the author properties menu); one of the options should be selected for <span class="marker">subj</span> and <span class="marker">prep</span> fields; and optionally you can add <span class="marker">year</span> or <span class="marker">tag</span> options fields.
	</p>
	<p>
		For example, to select every possible publication use this shortcode:<br>
		<span class="marker">[publications auth=all subj=all prep=all]</span><br>
		To list all articles of John Doe with johndoe identifier use:<br>
		<span class="marker">[publications auth=johndoe subj=articles prep=all]</span>
	</p>
	<p>
		Preprint option applies to articles only and should be <span class="marker">all</span>, <span class="marker">only</span>or <span class="marker">ex</span>, where:
	</p>
	<ul>
		<li><span class="marker">all</span> means all articles will be printed</li>
		<li><span class="marker">only</span> means only articles marked as &quot;preprint&quot; will be printed</li>
		<li><span class="marker">ex</span> means only articles that are NOT marked will be printed</li>
	</ul>
	<p>
		Optional field <span class="marker">year</span> specifies the year of shown publications.
		Optional field <span class="marker">tag</span> displays publications with certain tag.
	</p>
	<br>
	<p>See more details on the <a href="https://gitlab.com/engraver/sci-bib-online">plugin page</a> and its Wiki.</p>
</div>
<script>
	window.onload = function () {
		if(document.getElementById("loadAnimWrapper")){
			document.getElementById("loadAnimWrapper").style = "display:none;";
		}
	}
</script>
