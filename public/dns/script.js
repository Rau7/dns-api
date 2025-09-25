"use strict";

// Author: Alp Toker
$(document).ready(function () {
    //hide result and error divs at the beginning
    $("#result").hide();
    $(".error").hide();

    //helper functions
    const showError = (message) => {
        $(".error").text(message).show();
        $("#result").hide();
    };

    const hideError = () => {
        $(".error").hide();
    };

    const showResult = (records) => {
        hideError();

        // A Records
        if (records.A && records.A.length > 0) {
            let aList = $("#a-records ul");
            aList.empty();

            $.each(records.A, function (index, a) {
                aList.append("<li>" + a + "</li>");
            });

            $("#a-records").show();
        } else {
            $("#a-records ul").html("<li>No A records found</li>");
            $("#a-records").show();
        }

        // AAAA Records
        if (records.AAAA && records.AAAA.length > 0) {
            let aaaaList = $("#aaaa-records ul");
            aaaaList.empty();

            $.each(records.AAAA, function (index, aaaa) {
                aaaaList.append("<li>" + aaaa + "</li>");
            });

            $("#aaaa-records").show();
        } else {
            $("#aaaa-records ul").html("<li>No AAAA records found</li>");
            $("#aaaa-records").show();
        }

        // MX Records
        if (records.MX && records.MX.length > 0) {
            let mxList = $("#mx-records ul");
            mxList.empty();

            $.each(records.MX, function (index, mx) {
                mxList.append(
                    "<li>" + mx.host + " (priority " + mx.priority + ")</li>"
                );
            });

            $("#mx-records").show();
        } else {
            $("#mx-records ul").html("<li>No MX records found</li>");
            $("#mx-records").show();
        }

        $("#result").show();
    };

    $("#check").on("click", function () {
        var domain = $("#check-input").val().trim();

        //checkforempty domain
        if (!domain) {
            //showError("Please enter a domain name");
            return;
        }

        // Loading state
        //$(this).prop("disabled", true).text("Checking...");
        hideError();
        $("#result").hide();

        // AJAX çağrısı
        $.ajax({
            url: "/api/dns/" + encodeURIComponent(domain),
            type: "GET",
            dataType: "json",
            success: function (data) {
                console.log(data);
                if (data.success) {
                    showResult(data.records);
                } else {
                    showError("Failed to get DNS records");
                }
            },
            error: function (xhr, status, error) {
                var errorMsg = "Error: " + error;
                showError(errorMsg);
            },
            complete: function () {
                $("#check").prop("disabled", false).text("Check");
            },
        });
    });
});
