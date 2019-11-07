var cart = [];
var cartTotal = 0;
var pizzaID = 0;

function addToCart(pizzaName, pizzaPrice) {
    "use strict";

    // Add selected pizzas to the cart array and calculate total cart price.
    cart.push(createPizza(pizzaName, pizzaPrice));
    cartTotal += pizzaPrice;

    createCart();
}

function createPizza(pizzaName, pizzaPrice) {
    "use strict"

    // Create pizza object.
    var pizza = {
        name : pizzaName,
        price : pizzaPrice,
        id : pizzaID
    };

    pizzaID++;

    return pizza;
}

function createCart() {
    "use strict"

    // Get parent node.
    let selectNode = document.getElementById("cart");

    // Remove all child nodes.
    while (selectNode.firstChild) {
        selectNode.removeChild(selectNode.firstChild);
    }

    // Create cart items.
    cart.forEach(pizza => {

        // Create <option> node and set his attribute nodes.
        let optionNode = document.createElement("option");
        optionNode.setAttribute("value", pizza.name);
        optionNode.setAttribute("selected", true);

        // Create and append text node for <option> node.
        let textNode = document.createTextNode("Pizza " + pizza.name);
        optionNode.appendChild(textNode);
      
        // Append <option> node to his parent node.
        selectNode.appendChild(optionNode);
    });

    // Get paragraph-element
    var priceNode = document.getElementById("totalPrice");

    //Remove all child nodes.
    while(priceNode.firstChild){
        priceNode.removeChild(priceNode.firstChild);
    }

    // Create <p> node and set his attribute nodes.
    priceNode.setAttribute("data-price-total", cartTotal);

    // Create and append text node for <p> node.
    let textNode = document.createTextNode("Gesamter Preis: " + cartTotal + " €");
    priceNode.appendChild(textNode);

    // Get <input type="hidden"> and set his attribute nodes.
    let hiddenPriceNode = document.getElementById("totalPriceHidden");
    hiddenPriceNode.setAttribute("name", "cartTotal");
    hiddenPriceNode.setAttribute("value", cartTotal);
}
