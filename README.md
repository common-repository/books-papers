Plugin provides a simple bibliography management tool for collecting and 
providing online access to list of publication of scientists, writers and 
people who manages collections of publications.

Using Books & Papers plugin one can manage lists of authors and their publications 
(books, journal publications like scientific papers/[arXiv](https://arxiv.org/) preprints and conference proceedings). 
Additional features are available like import via [DOI](http://dx.doi.org/) and [BibTeX](http://www.bibtex.org/) database.

User fills database records, provides author identifiers and then can display lists of publications by their type for each
author in the given page of website.

Each author is characterized by the following fields:
* First name
* Last name
* E-mail: john@doe.com
* Personal URL: https://doe.com/john, author name becomes a link if URL is provided
* Identifier: internal identifier used for referencing the concrete person (johndoe, jonny1 etc).

First of all, authors can be added manually. Authors are added during import of external publication. 
If they are not recognized automaticallly among already existing ones, new records are created. You can
merge duplicate records later as well as modify existing ones.

Three types of publications are supported: 
* Article is a journal publication like [this one](https://journals.aps.org/prb/abstract/10.1103/PhysRevB.99.054422). 
It is characterized by list of authors (may be selected from existing records as well as just mentioned as external ones), 
title, journal, link to supplementary materials, date for sorting etc.
* Conference work is characterized mainly with the same fields as Artcile with additional Book Title field.
* Book is a publication, also characterized by editors, publisher, chaprter (for the referencing separate chapters in a textbook).

One can provide custom characteristics (max. three characteristics are supported) displaying by special stylesheet for the selected
record. 

1. Install the plugin usind WordPress plugins screen or upload the plugin and paste its content to the `/wp-content/plugins/books-and-papers` directory.
1. Activate the plugin on the 'Plugins' screen in WordPress.
1. Go to Settings->Books and Papers screen and configure the plugin.

Contributors: Vitalii Kominko, Oleksandr V. Pylypovskyi