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
    //button value transfer to input autofill field handler
    $("#js_item_search_results_target").on(
        "click",
        ".item-search-button",
        function() {
            $("#name").val($(this).val());
        }
    );
    //market order autofill fields handler
    /*
        The following is a list of the array indexes and their corresponding values
        itemPropertyArray[0] = $marketOrder->order_id,
        itemPropertyArray[1] = $marketOrder->typeName,
        itemPropertyArray[2] = $marketOrder->price,
        itemPropertyArray[3] = $marketOrder->volume_remain,
        itemPropertyArray[4] = $marketOrder->volume_total,
        itemPropertyArray[5] = $marketOrder->locationName,

    */
    $("#js-market-order-target").on(
        "click",
        ".market-order-id-select",
        function() {
            //console.log($(this).prop("checked"));
            if ($(this).prop("checked") == true) {
                //console.log($(this).val());
                let itemPropertyArray = $(this)
                    .val()
                    .split(",");
                $("#name").val(itemPropertyArray[1]);
                $("#sell_price").val(itemPropertyArray[2]);
                $("#amount").val(itemPropertyArray[4]);
                $("#current_location").val(itemPropertyArray[5]);
                //console.log(itemPropertyArray);
            }
        }
    );
});
//$(this).val()
//$("#name").val($(this).val());
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
