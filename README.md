# A free "addresses" web service, using PHP
This is a simple address list web service I "created" (ahem) to allow people to try out SoapUI.

I designed the WSDL first, in Eclipse, using the visual editor available with the PHP Development Tool plugin.
The PHP code was mostly written (read: copy-paste-edit) in Notepad++ using lots of trial-and-error.

## About me
I'm a software tester, and also do test automation. From there: SoapUI.
I have a history as developer (C++, Delphi, VB), but over time evolved into testing.

I had little-to-no previous knowledge of PHP (mostly in Wordpress contexts), but succeeded anyway to write this little piece of PHP code, and even used objects... Ugh, not my cup of tea.

## The "code"
They play well together.
All of the input checking is done using the WSDL. Since this is not designed for a production environment, but for learning SoapUI, I didn't bother doing any checks in the PHP.
I also didn't bother to split the WSDL into a WSDL+XSD.
I did however bother to try avoid SQL injection attacks, by passing values to the queries as parameters.

## The WSDL
### Operations
#### GetCount
Get the total number of records in the table. Row numbers are not necessarily the same as the id of a record.
Request:
```
<soapenv:Body>
  <myws:GetCount/>
</soapenv:Body>
```
Response:
```
<SOAP-ENV:Body>
  <ns1:GetCountResponse>1000</ns1:GetCountResponse>
</SOAP-ENV:Body>
```
#### GetAll
Returns all records in the table.
Request:
```
<soapenv:Body>
  <myws:GetAll/>
</soapenv:Body>
```
Response:
```
<SOAP-ENV:Body>
  <ns1:GetAllResponse>
    <record>
      <id>1</id>
      <firstName>James</firstName>
      <lastName>Butt</lastName>
      <streetName>Blue Gum Street</streetName>
      <houseNumber>6649</houseNumber>
      <postalCode>7011</postalCode>
      <cityName>New Orleans</cityName>
      <phoneNumber>050 621 892</phoneNumber>
    </record>
         ...
         ...
    <record>
      <id>1000</id>
      <firstName>Mi</firstName>
      <lastName>Richan</lastName>
      <streetName>Norwood Grove</streetName>
      <houseNumber>6</houseNumber>
      <postalCode>3280</postalCode>
      <cityName>Tanworth-in-Arden</cityName>
      <phoneNumber>041 407 842</phoneNumber>
    </record>
  </ns1:GetAllResponse>
</SOAP-ENV:Body>

```
#### GetOne
Get one specific record, by id. (not = row number)
Request:
```
<soapenv:Body>
  <myws:GetOne>
    <RecordNumber>666</RecordNumber>
  </myws:GetOne>
</soapenv:Body>
```
Response:
```
<SOAP-ENV:Body>
  <ns1:GetOneResponse>
    <record>
      <id>666</id>
      <firstName>Marshall</firstName>
      <lastName>Kozikowski</lastName>
      <streetName>Elwy Street</streetName>
      <houseNumber>47</houseNumber>
      <postalCode>8370</postalCode>
      <cityName>Kilpatrick Ward</cityName>
      <phoneNumber>064 20 73 73</phoneNumber>
    </record>
  </ns1:GetOneResponse>
</SOAP-ENV:Body>
```
#### AddOne (does 'add' or 'update')
Either adds the record, or if the id already exists, updates the record. No check is performed.
Request:
```
<soapenv:Body>
  <myws:AddOne>
    <record>
      <id>666</id>
      <firstName>Marshall</firstName>
      <lastName>Kozikowski</lastName>
      <streetName>Elwy Street</streetName>
      <houseNumber>47</houseNumber>
      <postalCode>8370</postalCode>
      <cityName>Kilpatrick Ward</cityName>
      <phoneNumber>064 20 73 73</phoneNumber>
    </record>
  </myws:AddOne>
</soapenv:Body>
```
Response:
```
<SOAP-ENV:Body>
  <ns1:AddOneResponse>ok</ns1:AddOneResponse>
</SOAP-ENV:Body>
```
#### DeleteOne
Deletes a record. Wether it exists or not.
Request:
```
<soapenv:Body>
  <myws:DeleteOne>
    <RecordNumber>666</RecordNumber>
  </myws:DeleteOne>
</soapenv:Body>
```
Response:
```
<SOAP-ENV:Body>
  <ns1:AddOneResponse>ok</ns1:AddOneResponse>
</SOAP-ENV:Body>
```

### Types
* int1to99999
  xsd:positiveInteger, min 1, max 99999  
* token255
  xsd:token, min 1, max 255  
* pc1000to9999, Belgian style postal codes
  xsd:int, min 1000, max 9999  
* pnBEtype, Belgian style phone numbers
  xsd:string, multi-regex patterns  
  * "(0)(4)\d\d\s\d\d\s\d\d\s\d\d" , e.g. 0477 11 11 11
  * "(0)\d{1,9}\d\s\d\d\s\d\d\s\d\d" , e.g. 045 11 11 11
  * "(0)\d{1,9}\d\s\d\d\d\s\d\d\d" , e.g. 045 111 111
  * "(0)\d{1,9}\s\d\d\s\d\d\s\d\d" , e.g. 03 11 11 11
  * "(0)\d{1,9}\s\d\d\d\s\d\d\d" , e.g. 03 111 111
* oneRecord, what 1 record looks like, both in request as in response
  * id, int1to99999, mandatory, max 1 occurance
  * firstName, token255, mandatory, max 1 occurance
  * lastName, token255, mandatory, max 1 occurance
  * streetName, token255, mandatory, max 1 occurance
  * houseNumber, int1to99999, mandatory, max 1 occurance
  * postalCode, pc1000to9999, mandatory, max 1 occurance
  * cityName, token255, mandatory, max 1 occurance
  * phoneNumber, pnBEtype, optional, max 1 occurance

## Database
During this development I used a MySql database. Not sure if the queries will work on other databases.
I also include an export of the MySql database containing a table with 1000 fake addresses.

## Installation
Just put the WSDL and PHP together in a directory on a web server, import the database file and voila. (I used XAMPP)

## Configuration
* Servername
  If your web server is NOT 'localhost' you will have to edit the WSDL.  
  Near the bottom you'll find the line:  
```
<soap:address location="http://localhost/adreslist.php" />
```
  Change this to the URL of your server:  
```
<soap:address location="http://<my_server_url>/adreslist.php" />
```
* Database logon
  The PHP will try to contact the database 'adreslist' (or name your own) on 'localhost' (no need to change this).  
  The database is contacted using a user 'adreslist', password 'adreslist', and this user only has access to database 'adreslist'.  
  Figure out your own MySql settings and replace the line:  
```
$my_DB_obj = new mysqli('localhost', 'adreslist', 'adreslist', 'adreslist');
```
  With:  
```
$my_DB_obj = new mysqli('localhost', 'my_user', 'my_password', 'my_db');
```


Good luck.
LLAP.
