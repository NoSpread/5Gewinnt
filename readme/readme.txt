██████╗ ███████╗ █████╗ ██████╗ ███╗   ███╗███████╗
██╔══██╗██╔════╝██╔══██╗██╔══██╗████╗ ████║██╔════╝
██████╔╝█████╗  ███████║██║  ██║██╔████╔██║█████╗  
██╔══██╗██╔══╝  ██╔══██║██║  ██║██║╚██╔╝██║██╔══╝  
██║  ██║███████╗██║  ██║██████╔╝██║ ╚═╝ ██║███████╗
╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝╚═════╝ ╚═╝     ╚═╝╚══════╝
                                                   
                                        

+-+-+-+-+-+
|Anleitung|
+-+-+-+-+-+                                               
                                                               
1. Den gesamten Ordner in das htdocs-Verzeichnis Ihres Xampp-Pakets einfügen.

2. In der Datei (htdocs)\web\res\php\config.php die allgemeine Datenbank-Konfiguration prüfen.

3. Mit einer Anwendung zur Administration von SQL-Datenbanken (empfohlen: HeidiSQL) die Datei (htdocs)\database\all.sql in der Anwendung ausführen.

4. Mithilfe des Xampp-Control Panels den lokalen Apache-Server und das MySQL Modul starten.

5. Browser Ihrer Wahl öffnen (empfohlen Chrome oder Firefox) und localhost/web/pages aufrufen.



+-+-+-+-+-+-+
|Good-2-Know|
+-+-+-+-+-+-+

1. Die E-Mail-Versendung ist theoretisch implementiert (Mailserver jedoch nicht konfiguriert).
	Möchten Sie den E-Mail-Versandt testen, so verfahren Sie gemäß dieser Anleitung: https://www.homeconstructor.net/de/email-versand-unter-xampp-einrichten.

2. Die clientseitige Fehlerausgabe wurde unsererseits deaktiviert. 
	Sollten Sie trotzdem auf ausgegebene Fehler stoßen, so hängt dies von Ihrer php.ini-Konfiguration (xampp\php\php.ini) ab.