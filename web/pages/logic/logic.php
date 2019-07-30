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
        for ($y = 0; $y < count($lines); $y++) {
            $this->width = max($this->width, count($lines[$y]));
        }
        for ($y = 0; $y < count($lines); $y++) {
            while (count($this->lines[$y]) < $this->width) {
                $this->lines[$y][] = new Field(Color::NONE);
            }
        }
    }

    public function mirror() {
        return new Grid(array_reverse($this->lines));
    }

    public function flip() {
        $result = array();
        for ($x = 0; $x < $this->width; $x++) {
            $result[] = array();

            for ($y = 0; $y < $this->height; $y++) {
                if (!isset($this->lines[$y][$x])) {
                    print_r($this->lines[$y]);
                    print_r($this->width);
                }
                $result[$x][] = $this->lines[$y][$x];
            }
        }

        return new Grid($result);
    }

    public function slant() {
        $result = array();
        for ($y = 0; $y < $this->height; $y++) {
            $result[] = array();
            for ($buffer = 0; $buffer < $y; $buffer++) {
                $result[$y][] = new Field(Color::NONE);
            }
            $result[$y] = array_merge($result[$y], $this->lines[$y]);
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

        for ($y = 0; $y < $grid->height; $y++) {
            $streak = 0;
            $player = Color::NONE;

            for ($x = 0; $x < $grid->width; $x++) {
                $field = $grid->lines[$y][$x];

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
                        $winner = $grid->lines[$y][$x - $offset];
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
            $this->grid->mirror()->slant()->flip()
        );

        $result = array();

        for ($i = 0; $i < count($transforms); $i++) {
            $result = array_merge($result, Game::markWinners($transforms[$i]));
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
            for ($y = $this->height - 1; $y >= 0; $y--) {
                if ($this->grid->lines[$y][$column]->color == Color::NONE) {
                    $this->grid->lines[$y][$column]->color = $this->player;
                    break;
                }
            }

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
