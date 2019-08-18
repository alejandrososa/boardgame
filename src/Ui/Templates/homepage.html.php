<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Game board</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .wrapper {
            background-color: white;
        }

        #form-start-game {
            width: 35%;
            margin: 0px auto;
        }

        .row {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            width: auto;
        }

        .column {
            display: flex;
            flex-direction: column;
            flex-basis: 100%;
            flex: 1;
            color: white;
        }

        .border {
            border: 1px solid #6e6e6e !important;
        }

        .align-center {
            align-items: center !important;
        }

        .white-column {
            background-color: #fff;
            height: 100px;
        }

        .blue-column {
            background-color: #007bff;
            height: 100px;
        }

        .red-column {
            background-color: #dc3545;
            height: 100px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="text-center mb-4 mt-4">
        <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72"
             height="72">
        <h1 class="h3 mb-3 font-weight-normal">Boardgame</h1>
        <p>Welcome to play a simple board game. There will be 2 players, playing in the same browser. </p>
        <p>The player who completes all the fields with his color will be the winner. To enjoy and play!!!</p>
    </div>

    <form id="form-start-game" class="m-5 ml-auto mr-auto">
        <div class="form-label-group mb-3">
            <input type="text" id="txt-player-one" name="player-one" class="form-control" placeholder="Name player two"
                   required="" autofocus="">
        </div>

        <div class="form-label-group mb-3">
            <input type="text" id="txt-player-two" name="player-two" class="form-control" placeholder="Name player two"
                   required="">
        </div>

        <button id="btn-start-game" class="btn btn-lg btn-primary btn-block" type="button">Start Game</button>
    </form>

    <div id="gameboard" class='wrapper m-2'>
        <h4 id="current-player">Current player: </h4>
        <div id="panel" class='row'></div>
    </div>

    <div id="gameboard-info" class='gameboard-info mt-4'>
        <div class='row'>
            <div class='column align-center'>
                <h1><span id="bdg-planer-one" class="badge badge-pill badge-primary">Primary 1</span></h1>
            </div>
            <div class='column align-center'>
                <h1><span id="bdg-planer-two" class="badge badge-pill badge-danger">Player 2</span></h1>
            </div>
        </div>
    </div>

    <footer><p class="mt-5 mb-3 text-muted text-center">© Mayordomo Gameboard 2019</p></footer>

</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

<script>
    var gameBoard = {
        init: function (settings) {
            gameBoard.config = {
                formBoard: $("#form-start-game"),
                namePlayerOne: $("#txt-player-one"),
                namePlayerTwo: $("#txt-player-two"),
                btnStartGame: $("#btn-start-game"),

                containerBoard: $("#gameboard"),
                panelSize: 10,
                panel: $("#panel"),
                currentPlayer: $("#current-player"),
                fieldWhite: $("<div class='column border field'><div class='white-column'></div></div>"),
                fieldBlue: $("<div class='column border field'><div class='blue-column'></div></div>"),
                fieldRed: $("<div class='column border field'><div class='red-column'></div></div>"),
                fieldSelector: ".column.border.field",

                containerInfo: $("#gameboard-info"),
                bdgPlayerOne: $("#bdg-planer-one"),
                bdgPlayerTwo: $("#bdg-planer-two"),

                apiUrlBase: "http://localhost:8080/api"
            };

            $.extend(gameBoard.config, settings);

            gameBoard.setup();
        },

        setup: function () {
            gameBoard.startStepOne();
            gameBoard.enableBtnStartGame();
            gameBoard.listenToStartGame();
        },

        enableBtnStartGame: function () {
            var namePlayerOne = gameBoard.config.namePlayerOne;
            var namePlayerTwo = gameBoard.config.namePlayerTwo;
            var btnStart = gameBoard.config.btnStartGame;

            namePlayerOne.on('keyup', function () {
                if (namePlayerOne.val() !== '' && namePlayerTwo.val() !== '') {
                    btnStart.attr('disabled', false);
                } else {
                    btnStart.attr('disabled', true);
                }
            });

            namePlayerTwo.on('keyup', function () {
                if (namePlayerOne.val() !== '' && namePlayerTwo.val() !== '') {
                    btnStart.attr('disabled', false);
                } else {
                    btnStart.attr('disabled', true);
                }
            });
        },

        listenToStartGame: function () {
            var btnStart = gameBoard.config.btnStartGame;
            var urlApi = gameBoard.config.apiUrlBase;

            btnStart.on("click", function () {
                const fd = new FormData();
                fd.append("player_one", gameBoard.config.namePlayerOne.val());
                fd.append("player_two", gameBoard.config.namePlayerTwo.val());
                fd.append("board_size", gameBoard.config.panelSize);

                const request = new Request(`${urlApi}/start-game`, {method: 'POST', body: fd});

                fetch(request)
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        const {player_one, player_two, board, current_player} = data;
                        gameBoard.goStepTwo();
                        gameBoard.initInfoPlayer(player_one, gameBoard.config.bdgPlayerOne);
                        gameBoard.initInfoPlayer(player_two, gameBoard.config.bdgPlayerTwo);
                        gameBoard.createBoard(board);
                        gameBoard.changeCurrentPlayer(current_player)
                    })
                    .catch(error => console.error('Error:', error))

            })
        },

        startStepOne: function () {
            gameBoard.config.containerBoard.hide();
            gameBoard.config.containerInfo.hide();
            gameBoard.config.btnStartGame.attr('disabled', true);
        },

        goStepTwo: function () {
            gameBoard.config.formBoard.hide();
            gameBoard.config.containerBoard.show();
            gameBoard.config.containerInfo.show();
        },

        initInfoPlayer: function (player, bdg) {
            const { name } = player;
            bdg.text(gameBoard.capitalizeName(name));
        },

        capitalizeName: function(name) {
            if (typeof name !== 'string') return '';
            return name.charAt(0).toUpperCase() + name.slice(1)
        },

        changeCurrentPlayer: function ({name}) {
            const playerName = gameBoard.capitalizeName(name);
            gameBoard.config.currentPlayer.text(`Current player: ${playerName}`);
        },
        showWinner: function (player_win) {
            if(player_win !== 'none'){
                const playerName = gameBoard.capitalizeName(player_win);
                gameBoard.config.currentPlayer.text(`Player Winner: ${playerName}`);
                gameBoard.disableMove();
                gameBoard.showBtnStarGame();
            }
        },

        createBoard: function ({panel}) {
            var board = gameBoard.config.panel;
            var fieldWhite = gameBoard.config.fieldWhite;
            var fieldBlue = gameBoard.config.fieldBlue;
            var fieldRed = gameBoard.config.fieldRed;

            board.empty();

            panel.map(function(field) {
                if(field === 0) { fieldWhite.clone().bind("click", gameBoard.moveField).appendTo(board); }
                if(field === 1) { fieldBlue.clone().bind("click", gameBoard.moveField).appendTo(board); }
                if(field === 2) { fieldRed.clone().bind("click", gameBoard.moveField).appendTo(board); }
            });
        },

        moveField: function (e) {
            e.preventDefault();

            const fieldSelected = $(this).index();
            var urlApi = gameBoard.config.apiUrlBase;
            const fd = new FormData();
            fd.append("field", fieldSelected);
            const request = new Request(`${urlApi}/play-next-turn`, {method: 'POST', body: fd});

                fetch(request)
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        console.log(data);
                        const {player_win, board, current_player} = data;
                        console.log(data.board.panel);
                        gameBoard.createBoard(board);
                        gameBoard.changeCurrentPlayer(current_player);
                        gameBoard.showWinner(player_win);

                    })
                    .catch(error => console.error('Error:', error))
        },

        disableMove: function () {
            var board = gameBoard.config.panel;
            board.children().map(function() {
                $(this).unbind("click", gameBoard.moveField);
                $(this).bind("click", function () { alert('You must start a new game!!!') });
            });
        },

        showBtnStarGame: function () {
            gameBoard.config.formBoard.show();
            gameBoard.config.namePlayerOne.hide();
            gameBoard.config.namePlayerTwo.hide();
        }

    };

    $(document).ready(gameBoard.init);

</script>
</body>
</html>