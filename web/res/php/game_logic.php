<?php

abstract class Color {
    const NONE = 1;
    const WHITE = 2;
    const BLACK = 3;
}

class Disc {
    public $color, $marked;
    public function __construct($color) {
        $this->color = $color;
        $this->marked = false;
    }
}

class Grid {
    public $lines, $height, $width;
    public function __construct($lines) {
        $this->lines = $lines;
        $this->height = count($lines);
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

    public function mirrorud() {
        return new Grid(array_reverse($this->lines));
    }

    public function mirrorlr() {
        return new Grid(array_map('array_reverse', $this->lines));
    }

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

class Game {
    public $width, $height, $grid, $winner, $finished;
    public function __construct($width = 7, $height = 6) {
        $this->height = $height;
        $this->width = $width;
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

    public function getFreeColumns() {
        $result = Array();
        for ($x = 0; $x < $this->width; $x++) {
            if ($this->grid->lines[0][$x]->color == Color::NONE) {
                $result[] = $x;
            }
        }

        return $result;
    }

    public static function markWinners($grid) {
        $result = Array();

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

    public function checkWinner() {
        if ($this->winner == Color::NONE) {
            $transforms = Array(
                $this->grid,
                $this->grid->flip(),
                $this->grid->slant()->flip(),
                $this->grid->mirrorud()->slant()->flip()
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

    public function checkFinished() {
        $this->checkWinner();
        if ((count($this->getFreeColumns()) == 0)
                || ($this->winner != Color::NONE)) {
            $this->finished = true;
        }
    }

    public function addDisc($column) {
        if (!$this->finished && in_array($column, $this->getFreeColumns())) {
            $y = $this->height - 1;
            while ($this->grid->lines[$y][$column]->color != Color::NONE) {
                $y--;
            }
            $this->grid->lines[$y][$column]->color = $this->player;

            $this->switchPlayers();

            $this->checkFinished();
        }
    }

    public function switchPlayers() {
        if ($this->player == Color::WHITE) {
            $this->player = Color::BLACK;
        } else {
            $this->player = Color::WHITE;
        }
    }

    public function resign() {
        $this->checkFinished();
        if (!$this->finished) {
            $this->switchPlayers();
            $this->winner = $this->player;
        }
    }
}

?>
