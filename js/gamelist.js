async function getGames(){
    return fetch('./getPHP/getGames.php')
        .then(response => response.json())
        .then(data => {return data});
}

async function showGamelist(){
    let gamesObject = await getGames();
    let games = Object.entries(gamesObject);
    console.log(games);
}

showGamelist();