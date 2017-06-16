
document.addEventListener("DOMContentLoaded", function () {

    var obj1 = document.querySelectorAll("#selectItem");

    for (var i = 0; i < obj1.length; i++) {
        obj1[i].addEventListener("click", function () {
            this.nextElementSibling.innerText = '2';

        })
    }

$( "#datepicker" ).datepicker();

});



$(document).ready(function()
    {
        $("#myTable").tablesorter();
    }
);

// document.addEventListener("DOMContentLoaded", function () {
//
//     var check = document.querySelector("#choice");
//     var hide = document.querySelector("#date");
//     hide.style.display = "none";
//
//     check.addEventListener("click", function () {
//         if (check.checked) {
//             hide.style.display = "block";
//         } else {
//             hide.style.display = "none";
//         }
//     });
//
// });

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