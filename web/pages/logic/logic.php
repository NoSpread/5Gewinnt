<?php

abstract class Color {
    const NONE = 1;
    const WHITE = 2;
    const BLACK = 3;
}

class Field {
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
                $line[] = new Field(Color::NONE);
            }
        }
    }

    public function mirrorud() {
        return new Grid(array_reverse($this->lines));
    }

    public function mirrorlr() {
        return new Grid(array_map("array_reverse", $this->lines));
    }

    public function flip() {
        $result = array();
        for ($x = 0; $x < $this->width; $x++) {
            $result[] = array();

            foreach ($this->lines as $line) {
                $result[$x][] = $line[$x];
            }
        }

        return new Grid($result);
    }

    public function slant() {
        $result = array();
        for ($y = 0; $y < $this->height; $y++) {
            $result[] = array_merge(
                array_fill(0, $y, new Field(Color::NONE)),
                $this->lines[$y]
            );
        }

        return new Grid($result);
    }
}

class Game {
    public $width, $height, $grid, $resigned;
    public function __construct($width = 7, $height = 6) {
        $this->height = $height;
        $this->width = $width;
        $this->player = Color::WHITE;

        $lines = array();
        for ($y = 0; $y < $height; $y++) {
            $lines[] = array();
            for ($x = 0; $x < $width; $x++) {
                $lines[$y][] = new Field(Color::NONE);
            }
        }

        $this->grid = new Grid($lines);
        $this->resigned = false;
    }

    public function getFreeColumns() {
        $result = array();
        for ($x = 0; $x < $this->width; $x++) {
            if ($this->grid->lines[0][$x]->color == Color::NONE) {
                $result[] = $x;
            }
        }

        return $result;
    }

    public static function markWinners($grid) {
        $result = array();

        foreach ($grid->lines as $line) {
            $streak = 0;
            $player = Color::NONE;

            for ($x = 0; $x < $grid->width; $x++) {
                $field = $line[$x];

                switch ($field->color) {
                    case Color::NONE:
                        $player = Color::NONE;
                        $streak = 0;
                        break;
                    case $player:
                        $streak++;
                        break;
                    default:
                        $player = $field->color;
                        $streak = 1;
                }

                if ($streak == 4) {
                    for ($offset = 0; $offset < 4; $offset++) {
                        $winner = $line[$x - $offset];
                        $winner->marked = true;
                        $result[] = $winner;
                    }
                } else if ($streak > 4) {
                    $field->marked = true;
                    $result[] = $field;
                }
            }
        }

        return $result;
    }

    public function getWinner() {
        if ($this->resigned) {
            return $this->player;
        }

        $transforms = array(
            $this->grid,
            $this->grid->flip(),
            $this->grid->slant()->flip(),
            $this->grid->mirrorud()->slant()->flip()
        );

        $result = array();

        foreach ($transforms as $transform) {
            $result = array_merge($result, Game::markWinners($transform));
        }

        if (count($result) == 0) {
            return Color::NONE;
        } else {
            return $result[0]->color;
        }
    }

    public function isFinished() {
        return (count($this->getFreeColumns()) == 0)
                || ($this->getWinner() != Color::NONE)
                || $this->resigned;
    }

    public function addPiece($column) {
        if (!$this->isFinished() && in_array($column, $this->getFreeColumns())) {
            $y = $this->height - 1;
            while ($this->grid->lines[$y][$column]->color != Color::NONE) {
                $y--;
            }
            $this->grid->lines[$y][$column]->color = $this->player;

            $this->switchPlayers();
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
        if (!$this->isFinished()) {
            $this->resigned = true;
            $this->switchPlayers();
        }
    }
}

function showGame($game) {
    echo "<table>";
    for ($y = 0; $y < $game->height; $y++) {
        $linestr = "<tr>";
        for ($x = 0; $x < $game->width; $x++) {
            if ($game->grid->lines[$y][$x]->marked) {
                $linestr .= "<td style='background-color: #ff0000'>";
            } else {
                $linestr .= "<td>";
            }

            switch ($game->grid->lines[$y][$x]->color) {
                case Color::WHITE:
                    $linestr .= "X";
                    break;
                case Color::BLACK:
                    $linestr .= "O";
                    break;
                case Color::NONE:
                    $linestr .= "-";
                    break;
            }
            $linestr .= "</td>";
        }
        $linestr .= "</tr>";
        echo $linestr;
    }
    echo "</table>";
}

function playRandomGame($width = 7, $height = 6) {
    $game = new Game($width, $height);
    while (!$game->isFinished()) {
        $possible = $game->getFreeColumns();
        $choice = rand(0, count($possible) - 1);
        $game->addPiece($possible[$choice]);
    }

    showGame($game);

    switch ($game->getWinner()) {
        case Color::WHITE:
            echo "White won!";
            break;
        case Color::BLACK:
            echo "Black won!";
            break;
        case Color::NONE:
            echo "It's a tie.";
            break;
    }

    return $game;
}

$game = playRandomGame();

?>
