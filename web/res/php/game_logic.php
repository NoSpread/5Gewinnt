<?php

/**
  * Color enum for labeling players and coloring disks
  * Color::NONE is used to signal empty slots or a tie
  */
abstract class Color {
    const NONE = 1;
    const WHITE = 2;
    const BLACK = 3;
}

/**
  * Class for game pieces
  * @var color The disc's color (Color::NONE signals an empty/placeholder disc)
  * @var marked Set when the disc contributes to a four-in-a-row
  */
class Disc {
    public $color, $marked;
    public function __construct($color) {
        $this->color = $color;
        $this->marked = false;
    }
}

/**
  * Class for the game grid
  * Mainly used for performing transformations to detect wins more elegantly
  * @var lines A two-dimensional rectangular array that holds discs
  * @var height The grid's height
  * @var width The grid's width
  */
class Grid {
    public $lines, $height, $width;
    public function __construct($lines) {
        $this->lines = $lines;
        $this->height = count($lines);
        // Determine the longest row and buffer to a rectangular matrix
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
      * @return A new grid with this grid's lines mirrored along a horizontal axis
      */
    public function mirrorud() {
        return new Grid(array_reverse($this->lines));
    }

    /**
      * @return A new grid with this grid's lines mirrored along a vertical axis
      */
    public function mirrorlr() {
        return new Grid(array_map('array_reverse', $this->lines));
    }

    /**
      * @return A new grid with this grid's lines mirrored along the main diagonal
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
      * @return A new grid with this grid's lines shifted in a staircase-like fashion
      * Example:
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
  * The game class
  * @param width The game grid's width
  * @param height The game grid's height
  * @var player The current player
  * @var grid The game grid
  * @var winner The game's winner (set to Color::NONE if it's a tie)
  * @var finished Signals whether the game has finished or not
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
        $this->finished = false;
    }

    /**
      * @return An array with all column numbers that result in legal moves
      */
    public function getFreeColumns() {
        // Iterate over the first line and record all columns that start with an empty slot
        $result = Array();
        for ($x = 0; $x < $this->grid->width; $x++) {
            if ($this->grid->lines[0][$x]->color == Color::NONE) {
                $result[] = $x;
            }
        }

        return $result;
    }

    /**
      * @param grid The game grid with the discs to be marked
      * @return An array of all disc contributing to a four-in-a-row
      */
    public static function markWinners($grid) {
        // Iterate over all lines
        $result = Array();

        foreach ($grid->lines as $line) {
            // Iterate over all discs and keep track of the current streak of consecutive discs with the same color
            $streak = 0;
            $player = Color::NONE;

            for ($x = 0; $x < $grid->grid->width; $x++) {
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

                // Mark the corresponding discs if the streak reaches/exceedes 4
                if ($streak == 4) {
                    for ($offset = 0; $offset < 4; $offset++) {
                        $winner = $line[$x - $offset];
                        $winner->marked = true;
                        $result[] = $winner;
                    }
                } else if ($streak > 4) {
                    $disc->marked = true;
                    $result[] = $disc;
                }
            }
        }

        return $result;
    }

    /**
      * Checks the game grid for a four-in-a-row and sets the 'finished' attribute if it finds one
      */
    public function checkWinner() {
        // Iterate over all grid transformation and search them for four-in-a-rows and record them
        if ($this->winner == Color::NONE) { // avoid unnecessary calculations
            $transforms = Array(
                $this->grid, // lines
                $this->grid->flip(), // columns
                $this->grid->slant()->flip(), // bottom-left to top-right diagonals
                $this->grid->mirrorud()->slant()->flip() // top-left to bottom-right diagonals
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

    /**
      * Checks whether the game has finished or not and sets the 'finished' attribute
      */
    public function checkFinished() {
        $this->checkWinner();
        if ((count($this->getFreeColumns()) == 0) // finished by tie
                || ($this->winner != Color::NONE)) { // finished by win
            $this->finished = true;
        }
    }

    /**
      * Adds a disc to the specified column if possible, switches players and checks whether the game has finished
      */
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

    /**
      * Switches the players
      */
    public function switchPlayers() {
        if ($this->player == Color::WHITE) {
            $this->player = Color::BLACK;
        } else {
            $this->player = Color::WHITE;
        }
    }

    /**
      * Finishes the game if possible and sets other the player as winner
      */
    public function resign() {
        $this->checkFinished();
        if (!$this->finished) {
            $this->switchPlayers();
            $this->winner = $this->player;
            $this->finished = true;
        }
    }
}

?>
