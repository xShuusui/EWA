function request(){
    "use strict";

    //Global xmlhttp object
    let xmlhttp = new XMLHttpRequest();

    //Global AJAX response handler
    xmlhttp.onreadystatechange = function (){
        if(this.readyState == 4 && this.status == 200){
            process(this.responseText);
        }
    }

    xmlhttp.open("GET", "KundenStatus.php", true);
    xmlhttp.send();

}

window.onload=function(){
    this.request();
    this.setInterval(window.request, 2000);
}

function process(json) {
    "use strict";

    let body = document.getElementsByClassName("customer");

    let pizzen = JSON.parse(json);

    pizzen.forEach(function(pizza){  

        let row = document.createElement("div");
        row.innerHTML = "Hat geklappt";
        body[0].appendChild(row);
    });
}