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
| email     | email                    |
| ip        | ip_v4                    |
| reg_date  |  reg_date                |
| banned    | banned                   |
| s_id      |  series_id               |
| rem_tok   |  remember_token          |
| expires   |  expires                 |
+-----------+--------------------------+
```


## UML Login

Basic Login Structure

```mermaid
graph LR
A{index.php} --> D((auth_validate.php))
B -- register --> E(register.php)
B -- login--> C((auth.php))
C -. fail .-> B
C -. success .-> A
D -- Not logged in --> B(login.php)
D -- Already logged in --> A
E -. success .-> B
E -. fail .-> E

```

## UML Game
TBD
