
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


// // Create a "close" button and append it to each list item
// var myNodelist = document.getElementsByTagName("LI");
// var i;
// for (i = 0; i < myNodelist.length; i++) {
//     var span = document.createElement("SPAN");
//     var txt = document.createTextNode("\u00D7");
//     span.className = "close";
//     span.appendChild(txt);
//     myNodelist[i].appendChild(span);
// }
//
// // Click on a close button to hide the current list item
// var close = document.getElementsByClassName("close");
// var i;
// for (i = 0; i < close.length; i++) {
//     close[i].onclick = function() {
//         var div = this.parentElement;
//         div.style.display = "none";
//     }
// }
//
// // Add a "checked" symbol when clicking on a list item
// var list = document.querySelector('ul');
// console.log(list.length);
// list.addEventListener('click', function(ev) {
//     if (ev.target.tagName === 'LI') {
//         ev.target.classList.toggle('checked');
//     }
// }, false);
//
// // Create a new list item when clicking on the "Add" button
// function newElement() {
//     var li = document.createElement("li");
//     var inputValue = document.getElementById("myInput").value;
//     var t = document.createTextNode(inputValue);
//     li.appendChild(t);
//     if (inputValue === '') {
//         alert("You must write something!");
//     } else {
//         document.getElementById("myUL").appendChild(li);
//     }
//     document.getElementById("myInput").value = "";
//
//     var span = document.createElement("SPAN");
//     var txt = document.createTextNode("\u00D7");
//     span.className = "close";
//     span.appendChild(txt);
//     li.appendChild(span);
//
//     for (i = 0; i < close.length; i++) {
//         close[i].onclick = function() {
//             var div = this.parentElement;
//             div.style.display = "none";
//         }
//     }
// }