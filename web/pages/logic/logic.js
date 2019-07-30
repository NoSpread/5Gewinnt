const colors = {
    NONE: 0,
    WHITE: 1,
    BLACK: 2
};

class Field {
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
        for (let i = 0; i < lines.length; i++) {
            this.width = Math.max(this.width, lines[i].length);
        }
        for (let i = 0; i < lines.length; i++) {
            while (lines[i].length < this.width) {
                lines[i].push(new Field(colors.NONE));
            }
        }
    }

    flip() {
        var result = new Array(this.width);
        for (let i = 0; i < result.length; i++) {
            result[i] = new Array();
        }

        for (let x = 0; x < this.width; x++) {
            for (let y = 0; y < this.height; y++) {
                result[x].push(this.lines[y][x]);
            }
        }

        return new Grid(result);
    }

    slant() {
        var result = new Array(this.width + this.height - 1);
        for (let i = 0; i < result.length; i++) {
            result[i] = new Array();
        }

        for (let x = 0; x < this.width; x++) {
            for (let y = 0; y < this.height; y++) {
                result[x + y].push(this.lines[y][x]);
            }
        }

        return new Grid(result);
    }
}

class Game {
    constructor(width = 7, height = 6) {
        this.height = height;
        this.width = width;
        this.player = colors.WHITE;

        var lines = new Array(height);
        for (let y = 0; y < height; y++) {
            lines[y] = new Array();
            for (let x = 0; x < width; x++) {
                lines[y].push(new Field(colors.NONE));
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

    static markLineWinners(line) {
        var streak = 0;
        var player = colors.NONE;
        var result = new Array();

        for (let i = 0; i < line.length; i++) {
            let field = line[i];

            switch (field.color) {
                case colors.NONE:
                    player = colors.NONE;
                    streak = 0;
                    break;
                case player:
                    streak++;
                    break;
                default:
                    player = field.color;
                    streak = 1;
            }

            if (streak == 4) {
                for (let j = 0; j < 4; j++) {
                    let winner = line[i - j];
                    winner.marked = true;
                    result.push(winner);
                }
            } else if (streak > 4) {
                field.marked = true;
                result.push(field);
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
            this.grid.slant(),
            this.grid.flip().slant()
        ];

        var result = new Array();

        for (let i = 0; i < transforms.length; i++) {
            for (let j = 0; j < transforms[i].height; j++) {
                result = result.concat(Game.markLineWinners(transforms[i].lines[j]));
            }
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

    addPiece(column) {
        if (!this.isFinished() && this.getFreeColumns().includes(column)) {
            for (let y = this.height - 1; y >= 0; y--) {
                if (this.grid.lines[y][column].color == colors.NONE) {
                    this.grid.lines[y][column].color = this.player;
                    break;
                }
            }

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
    for (let i = 0; i < game.height; i++) {
        var linestr = "";
        for (let j = 0; j < game.width; j++) {
            switch (game.grid.lines[i][j].color) {
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

        game.addPiece(possible[choice]);
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
