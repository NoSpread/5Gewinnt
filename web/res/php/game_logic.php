<?php

// Auflistung der Farben zur Zuordnung der Spieler und deren Spielsteinen
// Color::NONE wird zur Signalisierung eines Unentschieden oder eines leeren Slots auf dem Spielfeld verwendet.
abstract class Color {
    const NONE = 1;
    const WHITE = 2;
    const BLACK = 3;
}

/**
  * Klasse für Spielsteine
  * @var color Die Farbe des Spielsteines (Color::NONE signalisiert einen leeren bzw. Platzhalter-Spielstein)
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
  * Wird hauptsächlich zur Durchführung von Transformationen verwendet, damit Gewinne leichter identifiziert werden können. 
  * @var lines Ein zwei-dimensionales viereckiges Feld, das Spielsteine enthält.
  * @var height Die Höhe des Spielfeldes
  * @var width Die Breite des Spielfeldes
  */
class Grid {
    public $lines, $height, $width;
    public function __construct($lines) {
        $this->lines = $lines;
        $this->height = count($lines);
        // Determine the longest row and buffer to a rectangular matrix (Note for Naatz: Please correct the translation)
        // Bestimmen der längsten Reihe und Zwischenspeichern in einer quadratischen Matrix
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
      * @return Grid Ein neues Spielfeld mit den Linien des Spielfeldes an der horizontalen Achse gespiegelt
      */
    public function mirrorud() {
        return new Grid(array_reverse($this->lines));
    }

    /**
      * @return Grid Ein neues Spielfeld mit den Linien des Spielfeldes an der vertikalen Achse gespiegelt
      */
    public function mirrorlr() {
        return new Grid(array_map('array_reverse', $this->lines));
    }

    /**
      * @return Grid Ein neues Spielfeld mit den Linien des Spielfeldes an der Hauptdiagonalen gespiegelt
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
      * @return Grid Ein neues Spielfeld mit den Linien des Spielfeldes, welche stufenartig verschoben werden.
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
      * @return Array An array with all column numbers that result in legal moves Ein Array mit allen möglichen Spielzügen (Note for Naatz: Please correct the translation)
      */
    public function getFreeColumns() {
        // Es wird über die erste Zeile iteriert und alle Spalten erfasst, die mit einer leeren Position beginnen
        $result = Array();
        for ($x = 0; $x < $this->grid->width; $x++) {
            if ($this->grid->lines[0][$x]->color == Color::NONE) {
                $result[] = $x;
            }
        }

        return $result;
    }

    /**
      * @param grid Das Spielfeld mit allen Spielsteinen, welche markiert werden.
      * @return Array Ein Array mit allen Spielsteinen, welche zu der Vier-am-Stück-Kombination gehören.
      */
    public static function markWinners($grid) {
        // Iterate over all lines
        $result = Array();

        foreach ($grid->lines as $line) {
            // Es wird über alle Spielstein iteriert und die aktuelle Serie von aufeinanderfolgenden Spielsteinen der selben Farbe im Auge behalten.
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

                // Die zur Vier-in-einer-Reihe-Kombination gehörenden Spielsteine werden markiert, wenn die Serie die vier aneinander erreicht.
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

    // Das Spielfeld wird nach einer Vier-am-Stück-Serie untersucht und das "finished"-Attribut gesetzt, sollte eine gefunden werden.
    public function checkWinner() {
        // Es wird über alle Spielfeld-Transformationen iteriert und nach Vier-am-Stück-Kombinationen gesucht. Dieses werden erfasst.
        if ($this->winner == Color::NONE) { // Vermeidung unnötiger Berechnungen
            $transforms = Array(
                $this->grid, // Zeilen
                $this->grid->flip(), // Spalten
                $this->grid->slant()->flip(), // unten-links nach oben-rechts Diagonalen
                $this->grid->mirrorud()->slant()->flip() // oben-links nach unten-rechts Diagonalen
            );

            $result = Array();

            foreach ($transforms as $transform) {
                $result = array_merge($result, Game::markWinners($transform));
            }

            if (count($result) != 0) {
                $this->winner = $result[0]->color;
            }
        }
    }

    // Es wird überprüft, ob das Spiel beendet ist und das "finished"-Attribut gesetzt
    public function checkFinished() {
        if (!$this->finished) { // Vermeidung unnötiger berechnungen
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
            // Iterate over specified column from bottom to top until there is a non-empty slot
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

    // Das Spiel wird beendet, falls dies möglich ist und der andere Spieler ist der Gewinner.
    public function resign() {
        if (!$this->finished) {
            $this->switchPlayers();
            $this->winner = $this->player;
            $this->finished = TRUE;
        }
    }
}

?>
