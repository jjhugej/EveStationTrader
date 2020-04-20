$(document).ready(function() {
    $("#submit_btn").click(function() {
        $("#transaction_table input:checkbox:checked").each(function() {
            var selectedArray = $(this)
                .parent()
                .siblings()
                .map(function() {
                    return $(this)
                        .text()
                        .trim();
                })
                .get();
            //console.log(selectedArray);

            $.ajax({
                url: "http://evestationtrader.test/inventory/create/",
                data: JSON.stringify(selectedArray),
                type: "POST"
            }).done(function(data) {
                console.log(data);
            });
        });
    });
});
