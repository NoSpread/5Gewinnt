# 5Gewinnt

5Gewinnt ist das neue, knallharte und gnadenlose Multiplayer Game. Hier geht selbst der härteste in die Knie.
Projekt in **development**

## Common Vars

Ich bitte um eine **einheitliche** Variablennamen!

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
IP:     5.230.148.224
USER: 	5gewinnt
PASS:	LE53T4MeQ7NEDopipOBIqu6Oz7vu8u
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

## [Clean Up]

-   [ ] Finish all Tasks
-   [ ] Remove messy code
-   [ ] Dump Database for easy import

# [Assignment]

| Name    |            Task            |   Deadline |
| :------ | :------------------------: | ---------: |
| Marvin  |          Frontend          | 19.08.2019 |
| Florian |  Game Logic / Multiplayer  | 12.08.2019 |
| Lukas   |  Game Logic / Multiplayer  | 12.08.2019 |
| Simon   |            User            | 12.08.2019 |
| Tatjana |          Clean Up          | 19.08.2019 |
| Lucas   | Security / Project Manager | 19.08.2019 |

# [Comments]

> Wir müssen wirklich ranklotzen, das wird etwas stressig
> Bei Rückfragen und Fragen wie wir etwas managen stehe ich gerne zur Verfügung

> Bitte beachtet die einheitliche Benutzung von Variablen
> Jeder kann jedem Helfen, und man kann die Zuteilung ja noch ändern, aber das waren erstmal meine Ideen

> Marvin hat länger Zeit als Florian und Lukas, da dieser das Design für die Lobby und für das Game erst richtig erstellen kann wenn dies so einigermaßen steht

> Wir schaffen das :)

# [UPDATE] : Die letzte Woche

> Hier ist eine (unvollständige) Liste der noch ausstehenden Tasks mit den zuständigen Personen.
> Ihr könnt gerne meine Vorschläge editieren und neue Tasks hinzufügen.
> Bitte schaut auch noch auf die Anforderungen von Herrn Kolling, mir liegen diese momentan nicht vor.

-   [x] Alert-Boxen bei fehlgeschlagenem Login entfernen (stattdessen rot umrandete Input-Felder) -> Simon? (hab ein errorfield eingebaut -Marvin)
-   [ ] Sicherstellen, dass alle Eingaben gefiltert werden (z.B. auch GET/POST-Parameter) -> Lucas
-   [ ] GET-Parameter per Formular übergeben -> Lukas / Florian
-   [ ] JavaScript-Dateien auslagern (was meint ihr dazu?) -> Lukas
-   [ ] Fertigstellung des Spielfeld-Designs (game.php) -> Marvin
-   [ ] Vereinigung von game.php und play.php -> Marvin / Lukas / Florian
-   [ ] Verschiedene Skins für Spielsteine bei Erstellen eines Spiels festlegen lassen und in der game DB speichern -> Lukas / Florian
-   [ ] Verschiedene Skins für Spielsteine in game.php verwenden -> Marvin / Lukas / Florian
-   [ ] Kommentare des Codes -> Alle, Tatjana liest gegen
-   [x] Checkbox für private Challenges entfernen -> Marvin / Lukas / Florian
-   [x] Schwarz/Weiß Checkbox in Togglebutton umwandeln -> Marvin
-   [x] Im Titel der Herausforderung den Startspieler klarmachen -> Marvin / Lukas / Florian
-   [ ] Profilbild anzeigen -> Simon

# [DEADLINE] : **[23.08.2019]**

> **ES MUSS ALLES FERTIG SEIN!**
