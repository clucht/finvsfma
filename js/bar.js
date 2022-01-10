async function getColors(){
    return fetch('./getPHP/getColor.php')
        .then(response => response.json())
        .then(data => {return data});
}
async function getPoints(){
    return fetch('./getPHP/getPoints.php')
        .then(response => response.json())
        .then(data => {return data});
}

async function showBar(){
    let colorsObject = await getColors();
    let colors = Object.entries(colorsObject);
    console.log(colors);

    let pointsObject = await getPoints();
    let points = Object.entries(pointsObject);
    console.log(points);

    let sum = 0;
    for (let i = 0; i < points.length; i++){
        sum = sum + parseInt(points[i][1],10);
    }
    console.log("Sum: ",sum)

    for (let i = 0; i < points.length; i++){
        let percentage = (parseFloat(points[i][1],10)/sum)*100;
        eval('console.log("Percentage'+ i +': ",percentage)')

        if (percentage > 0){ //don't show if team has 0 points
            var newDiv = document.createElement("div");
            newDiv.setAttribute("id",eval('"bar' + i + '"'))
            let styleString = "height: 100%; min-height: 100%;"; //build string for style because style is one attribute
            styleString += "width: " + percentage + "%; min-width: " + percentage + "%;";
            styleString += "background-color: " + colors[i][1] + ";";
            styleString += "font-size: min(" + percentage*0.7*0.5 + "vw, 4vh);";

            let red = parseInt(colors[i][1].substring(1,3),16) //decide if text color should be black or white
            let green = parseInt(colors[i][1].substring(3,5),16)
            let blue = parseInt(colors[i][1].substring(5,7),16)
            if ((red*0.299 + green*0.587 + blue*0.114) > 186){
                styleString += "color: #000000;"
            }
            else {
                styleString += "color: #ffffff;"
            }

            newDiv.setAttribute("style",styleString);

            newDiv.setAttribute("class","sub_bar");



            newDiv.innerHTML = points[i][0];

            document.getElementById("bar").appendChild(newDiv);
        }


    }
}
function reset(){ //clear bar
    console.log("Resetting")
    document.getElementById("bar").innerHTML = "";
}

function Sleep(milliseconds) {
    return new Promise(resolve => setTimeout(resolve, milliseconds));
}

async function doCycle(){
    await showBar();
    while (true){
        await Sleep(60000); //might add setting for that
        reset()
        await showBar()
    }
}

doCycle();
