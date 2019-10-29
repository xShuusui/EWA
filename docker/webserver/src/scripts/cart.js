var warenkorb = [];
var cartTotal = 0;

function addToCart(pizzaName, pizzaPrice){
    "use strict";

    warenkorb.push(createPizza(pizzaName, pizzaPrice));
    cartTotal += pizzaPrice;
}

function createPizza(pizzaName, pizzaPrice) {
    "use strict"

    var pizza = {
        name : pizzaName,
        price : pizzaPrice
    };

    return pizza;
}

function print() {
    "use strict"

    warenkorb.forEach(element => {
        console.log(element);
    });
}