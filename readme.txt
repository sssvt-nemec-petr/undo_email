•	Vytvořit složku undoEmail a do ní vložit soubor undoEmail.php -> C:\xampp\htdocs\roundcubemail\plugins

•	Do arraye s pluginy na ř. 91 připsat undoEmail -> C:\xampp\htdocs\roundcubemail\config\config.inc.php

•	V db pro roundcube vytvořit tabulku unsentemails:

			create table unsentemails
			(
			    emailID      int auto_increment
				primary key,
			    receiverMail text not null,
			    senderMail   text not null,
			    htmlBody     text null,
			    mailBody     text  null,
			    subject      text null
			);


•	Případně přepsat nastavení db v configu, hlavní je existence tabulky unsentemails
