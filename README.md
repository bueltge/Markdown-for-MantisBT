# Markdown plugin for MantisBT
It's help convert some Markdown to html-style.

## Description
It's help convert some Markdown to html-style in Mantis Bug Tracker.
For highlighting the source use the Manits plugin [highlightcode](https://github.com/mantisbt-plugins/highlightcode).

[Mantis Bug Tracker](http://www.mantisbt.org/) (MantisBT) is a free popular web-based bugtracking system.

###Caution
 * Installing this plugin will disable the default MantisBT text and URL formatting.
 * Check the settings of the MantisBT Formatting plugin. If the "Text Processing" and "URL Processing" options is not disabled, then please deactivate manually.

### Optional settings
 * Use Markdown for all texts in MantisBT
 * Use Markdown in RSS
 * Use Markdown in EMails
 * Use Markdown Extended, a enhancement for MarkDown Extra. it is possible to use same Markdown syntax like Github. See the [description](https://github.com/kierate/php-markdown-extra-extended) for this enhancement.
 * Use Markdwn Extra, a special version of PHP Markdown with extra features. See the [description](http://michelf.ca/projects/php-markdown/extra/).
 * Filter on BBCode. If you use BBCode in MantisBT, then active this and the filter works only, if insde the strings is not an bbcode string.

## Screenshot
A example in MantisBT with default Markdown parser, not the extra modul
![Screenshot on a mantis install](https://raw.github.com/bueltge/Markdown-for-MantisBT/master/screenshot-1.png)

The settings for the Markdown plugin
![Screenshot from the settings](https://raw.github.com/bueltge/Markdown-for-MantisBT/master/screenshot-2.png)

## Installation
 1. Just unpack (with folder Markdown) in MantisBT_Root_Folder/plugins/
 2. Install optional also the [highlightcode](https://github.com/mantisbt-plugins/highlightcode) plugin
 3. Go `/manage_plugin_page.php`
 4. Install MantisBT Markdown Plugin
 5. Check the settings on `plugin.php?page=Markdown/config`
 6. Check the settings of the MantisBT Formatting plugin. If was not change automaticly, set the option for "Text Processing" to _OFF_.
 7. Use it ;)

## Other Notes
### Version/Changelog
See in VERSION.txt

### License
Good news, this plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog. But if you enjoy this plugin, you can thank me and leave a [small donation](http://bueltge.de/wunschliste/ "Wishliste and Donate") for the time I've spent writing and supporting this plugin. And I really don't want to know how many hours of my life this plugin has already eaten ;)

### Contributors
see the [contributors graph](https://github.com/bueltge/Markdown-for-MantisBT/graphs/contributors) for the current status

### Contact & Feedback
The plugin is designed and developed by me ([Frank Bültge](http://bueltge.de))

Please let me know if you like the plugin or you hate it or whatever ... Please fork it, add an issue for ideas and bugs.

### Disclaimer
I'm German and my English might be gruesome here and there. So please be patient with me and let me know of typos or grammatical farts. Thanks
