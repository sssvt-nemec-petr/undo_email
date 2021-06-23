# Configuration



1. For the roundcube plugin to work, create a folder named <b>undoEmail</b> in the Roundcube plugins folder, place all contents of the undoEmail repository into it.

2. After creating the folder, you must modify the configuration in **config.inc.php**. Find the plugins array and add undoEmail into it.

3. Create a table named unsentEmails in your roundcube database. 

   ```
   Table definition: create table unsent_emails
   ```

   ​             (

   ​               email_id   int auto_increment primary key,

   ​               receiver_mail text not null,

   ​               sender_mail  text not null,

   ​               html_body   text null,

   ​               mail_body   text null,

   ​               subject   text null

   ​             );

4. Use **config.ini** to configure the plugin.





# undoEmail/undoEmail.php



* classes that provide a link to a database to delete or insert content.

----

#### DATABASE FUNCTIONALITY

If the user sends an e-mail, he has a few seconds to cancel the message. This can be set in config.ini

We can use hooks to attach other source code or provide plug-in functions. In the meantime, the message is temporarily stored in a database with the unsent emails table, which is linked to the email id, recipient and sender, where the message header is also assigned, and body.

----

Prepared expressions can be stored in $<u>stmt</u>

<b>$stmt = ('DELETE FROM unsent_emails ORDER BY email_id DESC LIMIT 1')</b>

----

When sending an email to a recipient, a connection to the database takes place based on the host name, login name, password and database name.

An associative field is then created from the information already inserted in the pre-prepared table.

----

If an email is sent to a recipient, the information about that email is deleted from this table that serves to keep the information before the actual sending due to the possible cancellation of the sending by the sender to discard all information associated with a given ID and discard the email from the queue. 

----

After this step, the connection is closed with database. 



# undoEmail/buttons.js

- creates buttons that appear when you send an email

----

1. After sending the email to the queue, the set timer starts counting down.
2. The buttons with the set properties appear
3. The sending can then be confirmed  or the email returned with the <i><u>Undo button</u></i>

----
