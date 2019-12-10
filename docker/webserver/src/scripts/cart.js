let cart = [];
let cartTotal = 0;
let pizzaID = 0;
let defaultCartSize = 4;

/* Creates a pizza object. */
function Pizza(pizzaName, pizzaPrice) {
    "use strict";

    this.name = pizzaName;
    this.price = pizzaPrice;
    this.id = pizzaID++;
}

/* Add selected pizzas to the cart[] and calculate the total cart price. */
function addToCart(pizzaName, pizzaPrice) {
    "use strict";

    cart.push(new Pizza(pizzaName, pizzaPrice));
    cartTotal += pizzaPrice;

    createCart();
}

/* Renders the cart with DOM manipulation. */
function createCart() {
    "use strict";

    // Get <select> node.
    let selectNode = document.getElementById("cart");

    // Remove all child nodes from <select>.
    while (selectNode.firstChild) {
        selectNode.removeChild(selectNode.firstChild);
    }

    // Create cart items.
    cart.forEach(function(pizza) {

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
    priceNode.setAttribute("data-price-total", cartTotal.toFixed(2));

    // Create and append text node for <p> node.
    let textNode = document.createTextNode("Gesamter Preis: " + cartTotal.toFixed(2) + " €");
    priceNode.appendChild(textNode);

    checkSelectSize();
}

/* Set by all <option> nodes the selected attribute. */
function selectAllOptions() {
    "use strict";

    let selectNode = document.getElementById("cart");

    for (let i=0; i < selectNode.options.length; i++) {
        selectNode.options[i].selected = true;
    }
}

/* Remove the selected <option> node from the cart[] and recalculate the price. */
function deleteSelectedOptions() {
    "use strict";

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

/* Reset cart[], cartTotal and pizzaID. */
function deleteAllOptions() {
    "use strict";

    cart.length = 0;
    cartTotal = 0;
    pizzaID = 0;

    createCart();
}

/* Change the size from the <select> Node on the <option> Nodes. */
function checkSelectSize() {
    "use strict";

    let selectNode = document.getElementById("cart");
    let optionsLength = selectNode.options.length;
    
    // Check if size is smaller than length.
    if (selectNode.size <= optionsLength) {
        selectNode.size = optionsLength;
    }

    // Check if all options are deleted.
    if (optionsLength === 0) {
        selectNode.size = defaultCartSize;
    }

    // Check if one option are deleted.
    if (optionsLength === selectNode.size - 1 && optionsLength + 1 !== defaultCartSize) {
        selectNode.size = selectNode.size -1;
    }
}