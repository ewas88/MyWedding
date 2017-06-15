
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