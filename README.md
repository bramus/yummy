# DeliciousBackup

Imports your del.icio.us bookmarks from a backup into a local database and lets you browse them


## Say What?

Now that [Delicious](http://del.icio.us/) is being shut down / merged I found it necessary to keep my bookmarks safe and have a local, browsable copy of them.

This little app does exactly that: After [having created a backup of your bookmarks](https://secure.delicious.com/settings/bookmarks/export) this app will import your bookmarks into a local database.
Once imported you are able to browse through them.


## Installation

1) Download the source code as located within this repository, and upload it to your web server.  
2) Import `delicious.sql` in a MySQL database of choice to create the necessary tables.
4) [Export your delicious bookmarks into an .html file](https://secure.delicious.com/settings/bookmarks/export) and upload it your web server.
3) Edit `config.php` and enter your database credentials + define the path to the exported `.html` file.
4) Point your browser to `import.php` to import your links into the local database. After having done so, you may delete the exported `.html` file.
5) Point your browser to `index.php` 


## Note

Note that this app is totally unstyled (for now). Feel free to fork off if you can't await an update! ;-)


_— [Bramus!](http://www.bram.us/)_