# 5Gewinnt

5Gewinnt ist das neue, knallharte und gnadenlose Multiplayer Game. Hier geht selbst der härteste in die Knie.
Projekt in **development**

## Common Vars

```
+-----------+--------------------------+
| var name  |  database item / session |
+-----------+--------------------------+
| userid    |  id                      |
| username  |  username                |
| passwd    |  passwort                |
| email     |  email                   |
| ip        |  ip_v4                   |
| reg_date  |  reg_date                |
| banned    |  banned                  |
| s_id      |  series_id               |
| rem_tok   |  remember_token          |
| expires   |  expires                 |
+-----------+--------------------------+
```

### USERNAME CONVENTION

```
- Consists of 3-16 characters
- Upper-case letters
- Lower-case letters
- Numbers from 0-9
- Underscore

```

### DATABASE

```
IP:    
USER: 	
PASS:	
```

# [WHATS DONE]

## [FRONTEND]

-   [x] Login
-   [x] Register
-   [x] Dark-Mode / Light-Mode

## [BACKEND]

-   [x] Database
-   [x] Structure
-   [x] Webserver

# [TODO]

## [Frontend]

-   [x] Lobby Page
-   [x] Profile Page
-   [ ] Game Page
-   [x] Wining / Losing / Draw / Timout Screen

## [Game Logic]

-   [x] Win
-   [x] Lose
-   [x] Quit
-   [x] Equal
-   [x] No move made (timout)

## [Multiplayer]

-   [x] Sessions
-   [x] Store Game
-   [x] Create Game
-   [x] Delete Game
-   [x] Join Game
-   [x] Share Game
-   [x] Cancel Game

## [User]

-   [x] Login
-   [x] Register
-   [ ] Profile Settings
-   [ ] Delete Account
-   [ ] Permissions
-   [ ] Created Games (so you can delete them later)

## [Security]

-   [x] IP Check
-   [x] Already Registered
-   [ ] Can't create / join / cancel... games without valid token
-   [x] Login Spam Protection
-   [ ] Can't access server files


# [UPDATE] : Die letzte Woche

> Hier ist eine (unvollständige) Liste der noch ausstehenden Tasks mit den zuständigen Personen.
> Ihr könnt gerne meine Vorschläge editieren und neue Tasks hinzufügen.
> Bitte schaut auch noch auf die Anforderungen von Herrn ******, mir liegen diese momentan nicht vor.

-   [x] Alert-Boxen bei fehlgeschlagenem Login entfernen (stattdessen rot umrandete Input-Felder) -> Simon? (hab ein errorfield eingebaut -Marvin)
-   [ ] Sicherstellen, dass alle Eingaben gefiltert werden (z.B. auch GET/POST-Parameter) -> Lucas
-   [ ] JavaScript-Dateien auslagern (was meint ihr dazu?) -> Lukas
-   [x] Fertigstellung des Spielfeld-Designs (game.php) -> Marvin
-   [x] Vereinigung von game.php und play.php -> Marvin / Lukas / Florian
-   [x] Verschiedene Skins für Spielsteine bei Erstellen eines Spiels festlegen lassen und in der game DB speichern -> Lukas / Florian
-   [x] Verschiedene Skins für Spielsteine in play.php verwenden -> Marvin / Lukas / Florian
-   [ ] Kommentare des Codes -> Alle, Tatjana liest gegen
-   [x] Checkbox für private Challenges entfernen -> Marvin / Lukas / Florian
-   [x] Schwarz/Weiß Checkbox in Togglebutton umwandeln -> Marvin
-   [x] Im Titel der Herausforderung den Startspieler klarmachen -> Marvin / Lukas / Florian
-   [ ] Weiterleitungsformulare in HTML statt JS schreiben
-   [x] "Back to the lobby"-Link für Wettkämpfer erst bei Spielbeendigung anzeigen
-   [x] Cross-Browser-Testing für play.php -> Marvin (chrome & firefox gehen. edge & IE sind geblockt.)
-   [x] "Are u sure?"-Button bei Profillöschung und Spielaufgabe nur einmal erzeugen -> Marvin / Lukas / Florian
-   [x] Spielstatus und Clocks an das Design anpassen -> Marvin
-   [x] Zeit rot hervorheben, wenn diese z.B. 20s unterstreitet -> Marvin / Lukas / Florian

# [DEADLINE] : **[23.08.2019]**
