$(document).ready(function() {
    console.log("ready!");
    $.get("getdashboardstats").done(function(data) {
        $("#loading").remove();
        $("#contentSection").append(data);
    });
});

//ajax loading stack overflow post: https://stackoverflow.com/questions/55793857/display-loading-icon-once-while-waiting-for-ajax-call

/*

$.ajax({
        method: "GET",
        url: "/getdashboardstats",
        dataType: "json",
        success: function(data) {
            console.log(data);
        },
        error: function(data) {
            console.log(data);
        }
    });

*/
