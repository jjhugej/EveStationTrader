// This file contains various functions to improve UI performance on the shopping list details page
$(document).ready(function() {
    $("#status").change(function() {
        let status = $("#status")
            .find(":selected")
            .text();
        if (status == "Purchased") {
            $("#inventoryCheckBoxWrapper")
                .removeClass("d-none")
                .addClass("d-block");
        } else if (status == "Not Purchased") {
            $("#inventoryCheckBoxWrapper")
                .removeClass("d-block")
                .addClass("d-none");
        }
    });
});
