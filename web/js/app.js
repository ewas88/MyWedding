document.addEventListener("DOMContentLoaded", function () {

    $("#datepicker").datepicker();

});


$(document).ready(function () {
        $("#myTable").tablesorter();
    }
);

document.addEventListener("DOMContentLoaded", function () {

    var chosen = document.querySelectorAll("#chosen");
    var hidden = document.querySelectorAll("#hidden");

    for (var j = 0; j < hidden.length; j++) {
        hidden[j].style.display = "none";
    }


    for (var i = 0; i < chosen.length; i++) {
        chosen[i].addEventListener("click", function () {
            var parent = this.parentElement;
            var uncle = parent.nextElementSibling;
            if (uncle.style.display === "none") {
                uncle.style.display = "block";
            } else {
                uncle.style.display = "none";
            }
        });
    }

});

document.addEventListener("DOMContentLoaded", function () {

    var check = document.querySelectorAll("#choice");
    var hide = document.querySelectorAll("#date");

    for (var j = 0; j < hide.length; j++) {
        hide[j].style.display = "none";
    }


    for (var i = 0; i < check.length; i++) {
        check[i].addEventListener("click", function () {
            var parent = this.parentElement.parentElement;
            var uncle = parent.nextElementSibling;
            if (this.checked) {
                uncle.style.display = "block";
            } else {
                uncle.style.display = "none";
            }
        });
    }

});
