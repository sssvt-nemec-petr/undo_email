•	Vytvořit složku undoEmail a do ní vlož obsah celého git repozitáře undoEmail -> C:\xampp\htdocs\roundcubemail\plugins

•	Do arraye s pluginy připsat undoEmail -> C:\xampp\htdocs\roundcubemail\config\config.inc.php

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


•	Případně přepsat nastavení db v configu, hlavní je existence tabulky unsentemails, bez ní plugin nemá šanci fungovat.


Tabulka unsentemails, kterou zde definuji, je zastaralá a s pluginem nekompatibilní, aktualizuji. 