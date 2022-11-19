REDAXO-AddOn:  lus_cleverreach
==================================

REDAXO 5 Addon für cleverreach Anbindung

Dieses Addon ermöglicht es E-Mail-Adressen mit dem E-Mail-Versand-Anbieter [cleverreach](http://www.cleverreach.de/frontend/?rk=12968pvmjlnca" target="_blank") zu synchronisieren.
Dazu benutzt man ein XFORM-Formular, in das man das von diesem Addon bereitgestellte Action-Element "cr_recipient" einsetzt. Das Element hat die folgende XFORM Syntax:
	
Mindestvoraussetzungen
----------------------

* REDAXO 5.6
* YFORM 3.0

Verwendung
----------

<pre>action|cr_recipient|emailfield|0/1/actionfield|anrede,titel,vorname,nachname,firma|errormsg</pre>

bzw. per PHP:

<pre> &lt;?php $yform->setActionField('cr_recipient', array('emailfield', 1, 'anrede,titel,vorname,nachname,firma','Fehler bei der Registrierung')); ?></pre>

Parameter
----------

* `emailfeld` - gibt das Feld im Formular an, das die E-Mail-Adresse enthält.
*  `0/1/actionfeld`	- Hier wird die durchzuführende Aktion gewählt ( 0 = Abmelden, 1 = Anmelden) 
bzw. ein Feld im Formular angegeben, das die entsprechenden Werte liefert, also z.B. ein Radio Feld 
zur Auswahl durch den Benutzer.
* `anrede,titel,vorname,nachname,firma`
Eine komma-separierte Liste von Formularfeldern, die an cleverreach übermittelt werden soll.
* `errormsg` - Hier können die Fehlermeldungen der **cleverreach-API** mit einer Endnutzerfreundlichen Variante überschrieben werden
* `checkboxfeld` - optional ein Checkbox-Feld, das die Zustimmung zum Newsletter enthält. Wenn angegeben, wird die Aktion nur ausgeführt, wenn die checkbox den Wert 1 hat


Einstellungen:
--------------

Einstellungen müssen gemacht werden, die die Schnittstelle zu cleverreach benötigt.
Das sind:

1. API - Key (Schlüssel)
2. Eine Adressengruppe
3. Ein An- / Abmeldeformular
4. Ein Text, an dem man später erkennen kann, dass die Adressen über diese Website eingetragen wurden (optional)
	

Nach Eingabe des API-Schlüssels ( den Sie bei <a href="http://www.cleverreach.de/frontend/?rk=12968pvmjlnca" target="_blank">cleverreach</a> unter "Account --> Extras --> SOAP API mit Druck auf den Knopf "API Key erstellen" erzeugen können ) 
und einem Klick auf "aktualisieren", erscheint im Feld darunter eine Liste mit allen bei cleverreach angelegten Empfängergruppen.
Wir diese Auswahl der Grupper wiederum gespeichert, erscheint im Feld darunter eine Liste mit allen bei cleverreach angelegten An- / Abmeldeformularen. 
Dies ist notwendig, da das Formular die Opt-in E-Mail erzeugt.
	
