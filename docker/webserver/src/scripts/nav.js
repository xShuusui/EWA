
// Get <ul> Node.
let ulNode = document.getElementById("navbar");

// Get <a> Nodes in <nav> Node.
let aNodes = ulNode.getElementsByTagName("a");

// Iterate through <a> Nodes.
for (let i=0; i < aNodes.length; i++) {

    // Check if href in <a> is the same as the current URL.
    if (document.location.href === aNodes[i].href) {
          
        aNodes[i].parentNode.className = "active";
    }
}
