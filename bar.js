async function getColors(){
    return fetch('./getColor.php')
        .then(response => response.json())
        .then(data => {return data});
}
async function getPoints(){
    return fetch('./getPoints.php')
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
        //eval('var div' + i + '= document.createElement("div")' )
        let percentage = (parseFloat(points[i][1],10)/sum)*100;
        eval('console.log("Percentage'+ i +': ",percentage)')

        var newDiv = document.createElement("div");
        newDiv.setAttribute("id",eval('"bar' + i + '"'))
        let styleString = "height: 100%; min-height: 100%;";
        styleString += "width: " + percentage + "%; min-width: " + percentage + "%;";
        styleString += "background-color: " + colors[i][1];

        newDiv.setAttribute("style",styleString);

        newDiv.setAttribute("class","sub_bar");

        document.getElementById("bar").appendChild(newDiv);
    }
}

    showBar();