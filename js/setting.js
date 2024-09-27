let toggleSettings = false;

function toggleSetting(){
    let sideBar = document.querySelector(".nav-sidebar");
    let getMain = document.querySelector("main");
    let getUpload = document.querySelector(".upload_img");
    let getTitle = document.querySelector(".title");
    let getDisplay = document.querySelector(".display_img");

    if(toggleSettings === false){
        document.querySelector(".nav-sidebar").setAttribute("style", "visibility: visible;");
        document.querySelector("main").setAttribute("style", "opacity: 0;");
        document.querySelector(".upload_img").setAttribute("style", "opacity: 0;");
        document.querySelector(".title").setAttribute("style", "opacity: 0;");
        document.querySelector(".display_img").setAttribute("style", "opacity: 0;");


        toggleSettings = true;
    }else if(toggleSettings === true){
        document.querySelector(".nav-sidebar").setAttribute("style", "visibility: hidden;");
        document.querySelector("main").setAttribute("style", "opacity: 1;");
        document.querySelector(".upload_img").setAttribute("style", "opacity: 1;");
        document.querySelector(".title").setAttribute("style", "opacity: 1;");
        document.querySelector(".display_img").setAttribute("style", "opacity: 1;");


        toggleSettings = false;
    }
}

function calc(){
    let a = parseInt(document.querySelector("#value1").value);
    let b = parseInt(document.querySelector("#value2").value);
    let op = document.querySelector("#oparator").value;

    let calc;

    if(op == "add"){
        calc = a + b;
    }else if(op == "sub"){
        calc = a - b;
    }else if(op == "mult"){
        calc = a * b;
    }else if(op == "div"){
        calc = a / b;
    }

    document.querySelector("#result").innerHTML = calc;
}
/*----------------------------------------------------------------------------------------*/

let getTime = document.querySelector("#time");
let newElement = document.createElement("h1");

let date = new Date();
let hour = date.getHours();

let msg;

if(hour >= 4 && hour < 10){
    msg = "Good Morning!";
}else if(hour >= 10 && hour < 12){
    msg = "Good Day!";
}else if(hour >= 12 && hour < 18){
    msg = "Good Afternoon!";
}else if(hour >= 18 && hour < 22){
    msg = "Good Evening!";
}else if(hour >= 22 && hour < 4){
    msg = "Good Night!";
}else{
    msg = "No way!";
}

let newNode = document.createTextNode(msg);
newElement.appendChild(newNode);
getTime.appendChild(newElement);
document.querySelector("h1").setAttribute("style", "font-size: 50px; color: white;");

/*---------------------------------------------------------------------------------------------*/

document.addEventListener('keydown', function(event) {
    // Check if the Escape key was pressed
    if (event.key === 'Escape') {
        event.preventDefault(); // Prevent default browser behavior if necessary
        toggleSetting(); // Call the function to toggle the menu
    }
});
