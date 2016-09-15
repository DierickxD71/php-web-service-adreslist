# php-web-service-adreslist
This is a simple address list web service I created to allow people to try out SoapUI. 

The WSDL was created using Eclipse, using the visual editor available with plugin PHP Development Tool.
The code was mostly written in Notepad++ using lots of trial-and-error.

Although i had little-to-no previous knowledge of PHP, the WSDL and PHP work well together thanks to 100th's of hours searching the web.
Apparently using objects (*bleh*) is the easiest way to accomplish this and still understand how it works. (for me anyway)

I also include an export of the MySql database containing a table with 1000 addresses.

Just put the WSDL and PHP together in a directory on a web server, import the database file and voila. (I used XAMPP)

WARNING 1
  If your web server is NOT 'localhost' you will have to edit the WSDL.
  Near the bottom you'll find the line:
    <soap:address location="http://localhost/adreslist.php" />
  Change this to the URL of your server:
    <soap:address location="http://<my_server_url>/adreslist.php" />

WARNING 2
  The PHP will try to contact the database 'adreslist' (or name your own) on 'localhost' (no need to change this).
  The database is contacted using a user 'adreslist', password 'adreslist', and this user only has access to database 'adreslist'.
  Figure out your own MySql settings and replace the line:
    $my_DB_obj = new mysqli('localhost', 'adreslist', 'adreslist', 'adreslist');
  With:
    $my_DB_obj = new mysqli('localhost', 'my_user', 'my_password', 'my_db');

Good luck.
LLAP.
