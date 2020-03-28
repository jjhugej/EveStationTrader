$(document).ready(function() {
    console.log("fgsth");
    //search field keyup event and ajax call
    $("#name").keyup(function() {
        $.get("http://evestationtrader.test/inventory/itemsearch", {
            searchRequest: $("#name").val()
        }).done(function(data) {
            $("#js_item_search_results_target").empty();
            $("#js_item_search_results_target").append(data);
        });
    });
    //button value transfer to input field handler
    $("#js_item_search_results_target").on(
        "click",
        ".item-search-button",
        function() {
            $("#name").val($(this).val());
        }
    );
});
//$(this).val()
/*
                $.get("http://evestationtrader.test/inventory/itemsearch", function(
                    data
                ) {
                    $("#js_item_search_results_target").empty();
                    $("#js_item_search_results_target").append(data);
                });
*/
//inputField = document.getElementById("name");
//console.log(inputField.value);

/*
function fetchItemSearchResults() {
    fetch("itemSearch")
        .then(response => response.text())
        .then(html => {
            document.querySelector(
                "#js_item_search_results_target"
            ).innerHTML = html;
        });
}
*/

//inputField.addEventListener("keyup", console.log(inputField.value));
