const colors = {
    NONE: 0,
    WHITE: 1,
    BLACK: 2
};

class Disc {
    constructor(color) {
        this.color = color;
        this.marked = false;
    }
}

class Grid {
    constructor(lines) {
        this.lines = lines;
        this.height = lines.length;
        this.width = 0;
        for (let y = 0; y < lines.length; y++) {
            this.width = Math.max(this.width, lines[y].length);
        }
        for (let y = 0; y < lines.length; y++) {
            while (this.lines[y].length < this.width) {
                this.lines[y].push(new Disc(colors.NONE));
            }
        }
    }

    mirror() {
        return new Grid(this.lines.slice().reverse());
    }

    flip() {
        var result = new Array();
        for (let x = 0; x < this.width; x++) {
            result.push(new Array());

            for (let y = 0; y < this.height; y++) {
                result[x].push(this.lines[y][x]);
            }
        }

        return new Grid(result);
    }

    slant() {
        var result = new Array();
        for (let y = 0; y < this.height; y++) {
            result.push(new Array());
            for (let buffer = 0; buffer < y; buffer++) {
                result[y].push(new Disc(colors.NONE));
            }
            result[y] = result[y].concat(this.lines[y])
        }

        return new Grid(result);
    }
}

class Game {
    constructor(width = 7, height = 6) {
        this.height = height;
        this.width = width;
        this.player = colors.WHITE;

        var lines = new Array();
        for (let y = 0; y < height; y++) {
            lines.push(new Array());
            for (let x = 0; x < width; x++) {
                lines[y].push(new Disc(colors.NONE));
            }
        }

        this.grid = new Grid(lines);
        this.resigned = false;
    }

    getFreeColumns() {
        var result = [];
        for (let x = 0; x < this.width; x++) {
            if (this.grid.lines[0][x].color == colors.NONE) {
                result.push(x);
            }
        }
        return result;
    }

    static markWinners(grid) {
        var result = new Array();

        for (let y = 0; y < grid.height; y++) {
            let streak = 0;
            let player = colors.NONE;

            for (let x = 0; x < grid.width; x++) {
                let disc = grid.lines[y][x];

                switch (disc.color) {
                    case colors.NONE:
                        player = colors.NONE;
                        streak = 0;
                        break;
                    case player:
                        streak++;
                        break;
                    default:
                        player = disc.color;
                        streak = 1;
                }

                if (streak == 4) {
                    for (let offset = 0; offset < 4; offset++) {
                        let winner = grid.lines[y][x - offset];
                        winner.marked = true;
                        result.push(winner);
                    }
                } else if (streak > 4) {
                    disc.marked = true;
                    result.push(disc);
                }
            }
        }

        return result;
    }

    getWinner() {
        if (this.resigned) {
            return this.player;
        }

        var transforms = [
            this.grid,
            this.grid.flip(),
            this.grid.slant().flip(),
            this.grid.mirror().slant().flip()
        ];

        var result = new Array();

        for (let i = 0; i < transforms.length; i++) {
            result = result.concat(Game.markWinners(transforms[i]));
        }

        if (result.length == 0) {
            return colors.NONE;
        } else {
            return result[0].color;
        }
    }

    isFinished() {
        return (this.getFreeColumns().length == 0)
                || (this.getWinner() != colors.NONE)
                || this.resigned;
    }

    addDisc(column) {
        if (!this.isFinished() && this.getFreeColumns().includes(column)) {
            let y = this.height - 1;
            while (this.grid.lines[y][column].color != colors.NONE) {
                y--;
            }
            this.grid.lines[y][column].color = this.player;

            this.switchPlayers();
        }
    }

    switchPlayers() {
        if (this.player == colors.WHITE) {
            this.player = colors.BLACK;
        } else {
            this.player = colors.WHITE;
        }
    }

    resign() {
        if (!this.isFinished()) {
            this.resigned = true;
            this.switchPlayers();
        }
    }
}

function showGame(game) {
    for (let y = 0; y < game.height; y++) {
        var linestr = "";
        for (let x = 0; x < game.width; x++) {
            switch (game.grid.lines[y][x].color) {
                case colors.WHITE:
                    linestr += "X";
                    break;
                case colors.BLACK:
                    linestr += "O";
                    break;
                case colors.NONE:
                    linestr += ".";
                    break;
            }
        }
        console.log(linestr);
    }
}

function playRandomGame(width = 7, height = 6) {
    var game = new Game(width, height);
    while (!game.isFinished()) {
        let possible = game.getFreeColumns();
        let choice = Math.floor(Math.random() * possible.length);

        game.addDisc(possible[choice]);
    }

    showGame(game);

    switch (game.getWinner()) {
        case colors.WHITE:
            console.log("White won!");
            break;
        case colors.BLACK:
            console.log("Black won!");
            break;
        case colors.NONE:
            console.log("It's a tie.");
            break;
    }

    return game;
}
