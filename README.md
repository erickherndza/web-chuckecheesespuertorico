## New Features: Mega Navigation and Homepage Gallery

No files were modified, only new ones added.

Files added:
- home.html
- home.css
- ie7.css
- ie8.css
- main.css
- normalize.css
- print.css
- site.css
- _fonts/*
- _images/homepage_gallery/*
- _images/meganav/*
- chucke-pager.png
- ribbon_best-deals*.png
- social-icons.png
- css_browser_selector.js
- jquery.gallery.js
- main.js


## Quick Page Build Instructions:

To build a page from the provided templates, copy header.html and rename the new file. If you open the file in a browser, you can preview the five page templates by clicking one of the names in the dropdown menu under "Page Templates". When you have selected a layout, open the html file for that layout. Copy all the code nested in the body and add it to the new file, right before the closing body tag. Edit the content as desired.


## Font Note:

The brush style font used on ChuckECheese.com is called Bello Caps. This font is not free and requires an individual license for each domain that uses it. A simple Google search will return multiple options for where you can buy a license for it. ChuckECheese.com uses TypeKit to license and implement the font.

We have substituted a free font called Open Sans, which is being loaded via Google Fonts. If you decide to purchase Bello Caps, you will need to edit some code to make the switch.

The easiest way to do this will be to do a global find and replace in the _css directory with the search term "Open Sans" and replace with "bello-caps". You will have to adjust font-sizes, line-heights, and some margins and paddings to accomodate differnces in the font blocks. Please refer to chuckecheese.com and inspect elements with your prefered debugging tool to find the values that work with Bello Caps.

The {text-transfom: uppercase;} property is being used to mimic the all-caps font. It is perfectly safe to leave this property alone if making the switch, but removing it in the presence of bello caps is ok too.