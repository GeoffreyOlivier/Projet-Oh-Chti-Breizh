function openMenu() {
    var nav = document.getElementById("top-nav");
    var burger = document.getElementById("burger");
    if (nav.className === "nav-bar") {
        nav.className += " nav-bar--open";
        burger.className += " burger-menu--active";
    } else {
        nav.className = "nav-bar";
        burger.className = "burger-menu";
    }
}