# Yummy

A self hosted Delicious (with del.icio.us import).


## Say What?

Now that [Delicious](http://del.icio.us/) is rumored to be shut down / merged (or at least something funky will be happening to it any time soon) I found it necessary to keep my bookmarks safe and have a local, browsable copy of them.

This little app does exactly that: After [having created a backup of your bookmarks](https://secure.delicious.com/settings/bookmarks/export) this app will import your bookmarks into a local database.
Once imported you are able to browse through them, in a true Delicious style.


## Installation

1) Download the source code as located within this repository.
2) Edit `core/includes/config.php` to reflect your settings and upload all files to your web server.
3) Import `yummy.sql` in a MySQL database of choice to create the necessary tables.
4) Point your browser to `index.php` and continue with the installation.


## Note

Currently Yummy only allows you to browse your links. Soon, more features will be added:
- tags list
- ability to add new links
- ability to edit links
- etc.


_—[Bramus!](http://www.bram.us/)_