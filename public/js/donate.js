// Backend database to GET and POST donations to
const BACKEND = "/api/v1/transactions";

// Get current donations on page load
//$(document).ready(getDonations());

// When the website user clicks the donate button, send their details
// to the backend and update the page
$("#donateButton").click(function () {

    var donateJSON = {
        fundraising_event: "China Floods",
        name: $('#formName').val(),
        email: $('#formEmail').val(),
        phoneNumber: $('#phoneNumber').val(),
        amount: $('#donationAmount').val()
    };

    console.log("Sending : " + JSON.stringify(donateJSON));

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: BACKEND + "",
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(donateJSON),
        success: function (msg) {
            $("#donate-fields").html(`    <iframe id="iFrame" src="" width="100%" height="700px"  scrolling="no" frameBorder="0">
        <p>Browser unable to load iFrame</p>
    </iframe>`);
            document.getElementById('iFrame').src = msg;


            // Change the button text
            $("#donateButton").prop('value', 'Thank you!');

            // Clear the fields
            $('#formName').prop('value', '');
            $('#formEmail').prop('value', '');
            $('#phoneNumber').prop('value', '');
            $('#donationAmount').prop('value', '');

            // Scroll down to show the donation results
            setTimeout(function () {
                document.querySelector('#iFrame').scrollIntoView({
                    behavior: 'smooth'
                });

                // Change the donation button back
                $("#donateButton").prop('value', 'Donate');
            }, 1000);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("Error : " + textStatus + "Error Thrown : " + errorThrown);
            $("#donate-results").html(`<p class="zero">Something went wrong!</p>`);

        }
    });
});


// Fetch the donations from the backend database and insert it into
// the donations page.
function getDonations() {
    $.ajax({
        type: "GET",
        url: BACKEND + "",
        dataType: "json",
        success: function (msg) {
            console.log("Success : " + JSON.stringify(msg));

            // No new donations, try again in a few seconds
            if (msg.length == 0) {
                console.log("Backend DB empty");
                $("#donate-results").html(`<p class="zero">Zero donations: be the first!</p>`);
                return;
            }

            var donatorList = msg.reverse();
            console.log("Success. Retrieved " + msg.length + " records from backend");

            // Chopping it up so we only show latest 5 contributions
            // console.log(donatorList.slice(0, (donatorList.length < 5 ? donatorList.length : 5)));
            var donator = "";
            $.each(donatorList, function (index, value) {

                donator = donator + `<div class="donation">
                    <p class="name">` + value.name + `</p>
                    <p class="donation-amount">` + value.contribution + `</p>
                    </div>`;

            });
            $("#donate-results").html(donator);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("Error : " + textStatus + " Threw : " + errorThrown);
        }
    });
}
