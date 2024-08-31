# Přehled oscarů
Přehled oscarů za nejlepší mužskou a ženskou hlavní roli. 
Zpracování dvou paralerních .CSV souborů, následné spojení dat v jedné array.
Na základě zpracovaných dat, se zobrazí dvě tabulky s řazením "Dle roku" a "Dle filmu".

## Jak spustit aplikaci

Stáhněte si tento repo:
```shell
git clone https://github.com/Poweringg/csv-oscars.git
```

Přesuňte se do složky s projektem
```shell
cd csv-oscars
```

Pomocí Dockeru můžete spustit aplikaci příkazem:
```shell
docker compose up
```

Aplikace následně bude dostupná na lokální adrese [http://localhost:8080](http://localhost:8080)

## Používání aplikace
Po otevření hlavní strany, se očekává vstup dvou .CSV souborů, přičemž jeden by měl obsahovat v názvu souboru *male* a druhý *female*, je jedno kde.
Po nahrání se objeví dvě tlačítka, kterými si lze zobrazit potřebnou tabulku, nebo obě naráz.
