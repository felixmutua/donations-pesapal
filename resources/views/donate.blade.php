<!DOCTYPE html>
<html lang="en">
<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8" />

    <title>Donations</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/css/style.css" rel="stylesheet" type="text/css" />
    <link href="/css/donate.css" rel="stylesheet" type="text/css" />

</head>
<body>

<div id="grid">

    <div id="title">
        <h1>Donate</h1>
    </div>

    <div id="menu">
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/donate">Donate</a></li>
        </ul>
    </div>

    <div id="donate-fields">


        <h2>Please donate now</h2>

        <label for="formName">Name</label>
        <input type="text" value="" id="formName" />

        <label for="formEmail">Email address</label>
        <input type="text" value="" id="formEmail" />

        <label for="phoneNumber">Phone Number</label>
        <input type="text" value="" id="phoneNumber" />


        <label for="donationAmount">Donation amount</label>
        <input type="text"  id="donationAmount" />

        <input type="button" value="Donate" id="donateButton" />


    </div>

    <div id="donate-results">
    </div>

    <div id="don1">
        <h2>So far, your generosity has helped collect</h2>
        <p class="big">KES 1,420,792</p>
        <p>worldwide</p>
    </div>

    <div id="don2">
        <h2>Donate Money</h2>
        <p>Even a small donation can save lives!</p>
        <p><a href="/donate" class="button">Donate now</a></p>
    </div>

    <div id="don3">
        <h2>Become a Volunteer</h2>
        <p>We are in need of volunteers to distribute aid and help with rehabilitation.</p>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous">
</script>
<script src="js/donate.js"></script>

</body>
</html>
