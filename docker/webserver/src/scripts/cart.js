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
    let formNode = document.getElementById("cart");

    // Remove all child nodes.
    while (formNode.firstChild) {
        formNode.removeChild(formNode.firstChild);
    }

    // Create <select> node and set his attribute nodes.
    let selectNode = document.createElement("select");
    selectNode.setAttribute("name", "cart[]");
    selectNode.setAttribute("size", "5");

    // Append <select> node to his parent node.
    formNode.appendChild(selectNode);

    // Create cart items.
    cart.forEach(pizza => {

        // Create <option> node and set his attribute nodes.
        let optionNode = document.createElement("option");
        optionNode.setAttribute("value", pizza.price);

        // Create and append text node for <option> node.
        let textNode = document.createTextNode("Pizza " + pizza.name);
        optionNode.appendChild(textNode);
      
        // Append <option> node to his parent node.
        selectNode.appendChild(optionNode);

    });

    // Create <p> node and set his attribute nodes.
    let priceNode = document.createElement("p");
    priceNode.setAttribute("data-price-total", cartTotal);

    // Create and append text node for <p> node.
    let textNode = document.createTextNode("Gesamter Preis: " + cartTotal + " €");
    priceNode.appendChild(textNode);

    // Append <p> node to his parent node.
    formNode.appendChild(priceNode);

}