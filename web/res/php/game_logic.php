<?php

// Simuliertes enum der Spielfarben
// Color::NONE kann ein Unentschieden oder einen leeren Slot im Spielfeld signalisieren
abstract class Color {
    const NONE = 1;
    const WHITE = 2;
    const BLACK = 3;
}

/**
  * Klasse für Spielsteine
  * @var color Die Farbe des Spielsteines (Color::NONE signalisiert hier ein leeres Feld)
  * @var marked Wird gesetzt, wenn vier Spielsteine einer Farbe am Stück liegen.
  */
class Disc {
    public $color, $marked;
    public function __construct($color) {
        $this->color = $color;
        $this->marked = FALSE;
    }
}

/**
  * Klasse für das Spielfeld
  * Wird hauptsächlich zur Durchführung von Transformationen des Spielfelds verwendet, um Gewinne mit geringerem Code-Aufwand zu erkennen.
  * @var lines Ein zwei-dimensionales Array, das Spielsteine enthält. (Dieses muss nicht rechteckig sein.)
  * @var height Die Höhe des Spielfeldes
  * @var width Die Breite des Spielfeldes
  */
class Grid {
    public $lines, $height, $width;
    public function __construct($lines) {
        $this->lines = $lines;
        $this->height = count($lines);
        // Wir bestimmen die längste Zeile von $lines und füllen alle kürzeren Zeilen mit Leerfeldern auf.
        // Dadurch erhalten wir eine rechteckige Matrix.
        $this->width = 0;
        foreach ($lines as $line) {
            $this->width = max($this->width, count($line));
        }
        foreach ($this->lines as &$line) {
            while (count($line) < $this->width) {
                $line[] = new Disc(Color::NONE);
            }
        }
    }

    /**
      * @return Grid Ein neues Spielfeld, das an der horizontalen Achse gespiegelt wurde
      */
    public function mirrorud() {
        return new Grid(array_reverse($this->lines));
    }

    /**
      * @return Grid Ein neues Spielfeld, das an der vertikalen Achse gespiegelt wurde
      */
    public function mirrorlr() {
        return new Grid(array_map('array_reverse', $this->lines));
    }

    /**
      * @return Grid Ein neues Spielfeld, das an der Hauptdiagonalen gespiegelt wurde
      */
    public function flip() {
        $result = Array();
        for ($x = 0; $x < $this->width; $x++) {
            $result[] = Array();

            foreach ($this->lines as $line) {
                $result[$x][] = $line[$x];
            }
        }

        return new Grid($result);
    }

    /**
      * @return Grid Ein neues Spielfeld, das stufenartig verschoben wurde.
      * Beispiel:
      * ABC    ABC..
      * DEF -> .DEF.
      * GHI    ..GHI
      */
    public function slant() {
        $result = Array();
        for ($y = 0; $y < $this->height; $y++) {
            $result[] = array_merge(
                array_fill(0, $y, new Disc(Color::NONE)),
                $this->lines[$y]
            );
        }

        return new Grid($result);
    }
}

/**
  * Spiel-Klasse
  * @param width Breite des Spielfeldes
  * @param height Höhe des Spielfeldes
  * @var player Der aktuelle Spieler
  * @var grid Das Spielfeld
  * @var winner Der Gewinner (auf Color::NONE gesetzt, wenn es ein Unentschieden ist)
  * @var finished Signalisiert, ob das Spiel beendet ist oder nicht
  */
class Game {
    public $grid, $winner, $finished;
    public function __construct($width = 7, $height = 6) {
        $this->player = Color::WHITE;

        $lines = Array();
        for ($y = 0; $y < $height; $y++) {
            $lines[] = Array();
            for ($x = 0; $x < $width; $x++) {
                $lines[$y][] = new Disc(Color::NONE);
            }
        }

        $this->grid = new Grid($lines);
        $this->winner = Color::NONE;
        $this->finished = FALSE;
    }

    /**
      * @return Array Ein Array mit den noch freien Spaltennummern
      */
    public function getFreeColumns() {
        // Es wird über die erste Zeile des Spielfelds iteriert und erfasst, welche Spalten dort noch frei sind.
        $result = Array();
        for ($x = 0; $x < $this->grid->width; $x++) {
            if ($this->grid->lines[0][$x]->color == Color::NONE) {
                $result[] = $x;
            }
        }

        return $result;
    }

    /**
      * @param grid Das Spielfeld mit den zu markierenden Spielsteinen
      * @return Array Ein Array mit allen Spielsteinen, welche zu der Vier-am-Stück-Kombination gehören.
      */
    public static function markWinners($grid) {
        $result = Array();

        // Es wird über alle Spielstein iteriert und die aktuelle Serie von aufeinanderfolgenden Spielsteinen der selben Farbe im Auge behalten.
        foreach ($grid->lines as $line) {
            $streak = 0;
            $player = Color::NONE;

            for ($x = 0; $x < $grid->width; $x++) {
                $disc = $line[$x];

                switch ($disc->color) {
                    case Color::NONE:
                        $player = Color::NONE;
                        $streak = 0;
                        break;
                    case $player:
                        $streak++;
                        break;
                    default:
                        $player = $disc->color;
                        $streak = 1;
                }

                // Die zur Vier-in-einer-Reihe-Kombination gehörenden Spielsteine werden markiert, wenn die Serie eine Länge von vier oder mehr Steinen erreicht.
                if ($streak == 4) {
                    for ($offset = 0; $offset < 4; $offset++) {
                        $winner = $line[$x - $offset];
                        $winner->marked = TRUE;
                        $result[] = $winner;
                    }
                } else if ($streak > 4) {
                    $disc->marked = TRUE;
                    $result[] = $disc;
                }
            }
        }

        return $result;
    }

    // Das Spielfeld wird auf eine Vier-am-Stück-Serie untersucht und das "finished"-Attribut gesetzt, sollte eine gefunden werden.
    public function checkWinner() {
        if ($this->winner == Color::NONE) { // Vermeidung unnötiger Berechnungen
            $transforms = Array(
                $this->grid, // Zeilen
                $this->grid->flip(), // Spalten
                $this->grid->slant()->flip(), // "unten-links nach oben-rechts"-Diagonalen
                $this->grid->mirrorud()->slant()->flip() // "oben-links nach unten-rechts"-Diagonalen
            );

            $result = Array();

            // Es wird über alle Spielfeld-Transformationen iteriert und nach Vier-am-Stück-Kombinationen gesucht. Diese werden gesammelt.
            foreach ($transforms as $transform) {
                $result = array_merge($result, Game::markWinners($transform));
            }

            if (count($result) != 0) {
                $this->winner = $result[0]->color;
            }
        }
    }

    // Es wird überprüft, ob das Spiel beendet ist und in diesem Fall das "finished"-Attribut gesetzt
    public function checkFinished() {
        if (!$this->finished) { // Vermeidung unnötiger Berechnungen
            $this->checkWinner();
            if ((count($this->getFreeColumns()) == 0) // Unentschieden
                    || ($this->winner != Color::NONE)) { // Ein Spieler hat gewonnen
                $this->finished = TRUE;
            }
        }
    }

    // Ein Spielstein wird der ausgewählten Spalte hinzugefügt, falls dies möglich ist.
    // Anschließend wird der aktive Spieler gewechselt und überprüft, ob das Spiel beendet ist.
    public function addDisc($column) {
        if (!$this->finished && in_array($column, $this->getFreeColumns())) {
            // Wir iterieren über die gegebene Spalte von unten nach oben, bis wir einen freien Platz finden.
            $y = $this->grid->height - 1;
            while ($this->grid->lines[$y][$column]->color != Color::NONE) {
                $y--;
            }
            $this->grid->lines[$y][$column]->color = $this->player;

            $this->switchPlayers();

            $this->checkFinished();
        }
    }

    // Wechsel des aktiven Spielers
    public function switchPlayers() {
        if ($this->player == Color::WHITE) {
            $this->player = Color::BLACK;
        } else {
            $this->player = Color::WHITE;
        }
    }

    // Das Spiel wird aufgegeben, falls dies möglich ist.
    /**
      * @param player Der aufgebende Spieler
      */
    public function resign($player) {
        if (!$this->finished) {
            $this->player = $player;
            $this->switchPlayers();
            $this->winner = $this->player;
            $this->finished = TRUE;
        }
    }
}

?>
