# undoEmail/undoEmail.php



* classes that provide a link to a database to delete or insert content.

----

#### DATABASE FUNCTIONALITY

If the user sends an e-mail, he has a few seconds to cancel the message. This can be set in config.ini

<img align="left" src="C:\Users\Admin\AppData\Roaming\Typora\typora-user-images\image-20210621173151115.png" alt="image-20210621173151115"  />

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

<img align="left" src="C:\Users\Admin\AppData\Roaming\Typora\typora-user-images\image-20210621175450732.png" alt="image-20210621175450732"  />

