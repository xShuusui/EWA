let cart = [];
let cartTotal = 0;
let pizzaID = 0;

function addToCart(pizzaName, pizzaPrice) {
    "use strict";

    // Add selected pizzas to the cart array and calculate total cart price.
    cart.push(createPizza(pizzaName, pizzaPrice));
    cartTotal += pizzaPrice;

    createCart();
}

function createPizza(pizzaName, pizzaPrice) {
    "use strict";

    // Create pizza object.
    let pizza = {
        name : pizzaName,
        price : pizzaPrice,
        id : pizzaID
    };

    pizzaID++;

    return pizza;
}

function createCart() {
    "use strict";

    // Get <select> node.
    let selectNode = document.getElementById("cart");

    // Remove all child nodes from <select>.
    while (selectNode.firstChild) {
        selectNode.removeChild(selectNode.firstChild);
    }

    // Create cart items.
    cart.forEach(pizza => {

        // Create <option> node and set his attribute nodes.
        let optionNode = document.createElement("option");
        optionNode.setAttribute("value", pizza.name);
        optionNode.id = pizza.id;

        // Create and append text node to <option> node.
        let textNode = document.createTextNode("Pizza " + pizza.name);
        optionNode.appendChild(textNode);
      
        // Append <option> node to his parent node.
        selectNode.appendChild(optionNode);
    });

    // Get <p> node.
    let priceNode = document.getElementById("totalPrice");

    // Remove all child nodes from <p>.
    while (priceNode.firstChild) {
        priceNode.removeChild(priceNode.firstChild);
    }

    // Set his attribute nodes.
    priceNode.setAttribute("data-price-total", cartTotal);

    // Create and append text node for <p> node.
    let textNode = document.createTextNode("Gesamter Preis: " + cartTotal + " €");
    priceNode.appendChild(textNode);
}

function selectAllOptions() {
    "use strict";

    // Get <select> node.
    let selectNode = document.getElementById("cart");

    // Set by all <option> node the selected.
    for (let i=0; i < selectNode.options.length; i++) {
        selectNode.options[i].selected = true;
    }
}

function deleteSelectedOptions() {
    "use strict";

    // Get <select> node.
    let selectNode = document.getElementById("cart");

    for (let i=0; i < selectNode.options.length; i++) {

        // Check if <option> is selected and calculate total price.
        let optionNode = selectNode.options[i];
        if (optionNode.selected == true) {

            cartTotal -= cart[optionNode.id].price;
            delete cart[optionNode.id];
        }
    }

    createCart();
}

function deleteAllOptions() {
    "use strict";

    // Reset cart[], cartTotal and pizzaID.
    cart.length = 0;
    cartTotal = 0;
    pizzaID = 0;

    createCart();
}