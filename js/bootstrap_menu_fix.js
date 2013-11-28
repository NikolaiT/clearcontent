/* 
 * Sets the original navheader bootstrap menu for tablets and smartphones.
 */
var intViewportWidth = window.innerWidth;
if (intViewportWidth < 992) {
    e = document.querySelectorAll('[class="dropdown-toggle"]');
    for (var i = 0; i < e.length; i++) {
        e[i].setAttribute("data-toggle", "dropdown");
    }
}