```php
<?php

$controller_id = isset($_GET['controller_id'])
    ? trim($_GET['controller_id'])
    : '';

if ($controller_id === '') {
    die('Controller ID missing.');
}

/* Allow only safe controller IDs */
if (!preg_match('/^[A-Za-z0-9_-]+$/', $controller_id)) {
    die('Invalid Controller ID.');
}

/*
 * ESP-SWITCH5B customer URL
 */
$base_url = 'https://esp-switch5b-remote.onrender.com/c/';

$controller_url =
    $base_url . rawurlencode($controller_id);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Controller QR Code</title>

<style>

body {
    font-family: Arial, sans-serif;
    text-align: center;
    margin: 0;
    padding: 30px;
    background: #f2f2f2;
}

.box {
    display: inline-block;
    padding: 30px;
    background: white;
    border: 1px solid #ccc;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.15);
}

h2 {
    margin-top: 0;
    margin-bottom: 20px;
}

.controller {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 20px;
}

#qrcode {
    width: 300px;
    min-height: 300px;
    margin: auto;
}

#qrcode img,
#qrcode canvas {
    display: block;
    margin: auto;
}

.url {
    margin-top: 18px;
    margin-bottom: 20px;
    word-break: break-all;
    font-size: 15px;
}

.url a {
    color: #007bff;
    text-decoration: none;
}

.url a:hover {
    text-decoration: underline;
}

.buttons {
    margin-top: 20px;
}

button {
    border: none;
    border-radius: 6px;
    padding: 12px 20px;
    margin: 5px;
    font-size: 15px;
    cursor: pointer;
}

.download-button {
    background: #28a745;
    color: white;
}

.print-button {
    background: #007bff;
    color: white;
}

.open-button {
    background: #6c757d;
    color: white;
}

button:hover {
    opacity: 0.85;
}

@media print {

    body {
        background: white;
        padding: 0;
    }

    .box {
        border: none;
        box-shadow: none;
    }

    .buttons,
    .url {
        display: none;
    }

}

</style>

</head>

<body>

<div class="box">

<h2>
ESP-SWITCH5B
</h2>

<div class="controller">

Controller:
<?= htmlspecialchars(
    $controller_id,
    ENT_QUOTES,
    'UTF-8'
) ?>

</div>

<!-- QR CODE -->

<div id="qrcode"></div>

<div class="url">

<a
    href="<?= htmlspecialchars(
        $controller_url,
        ENT_QUOTES,
        'UTF-8'
    ) ?>"
    target="_blank"
>
<?= htmlspecialchars(
    $controller_url,
    ENT_QUOTES,
    'UTF-8'
) ?>
</a>

</div>

<div class="buttons">

<button
    type="button"
    class="download-button"
    onclick="downloadQR()"
>
DOWNLOAD QR CODE
</button>

<button
    type="button"
    class="print-button"
    onclick="window.print()"
>
PRINT QR CODE
</button>

<button
    type="button"
    class="open-button"
    onclick="openController()"
>
OPEN CONTROLLER
</button>

</div>

</div>


<!-- QR CODE JAVASCRIPT LIBRARY -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>


<script>

/*
 * Controller URL
 */

const controllerURL =
    <?= json_encode($controller_url) ?>;

const controllerID =
    <?= json_encode($controller_id) ?>;


/*
 * Generate QR code locally
 */

const qrContainer =
    document.getElementById("qrcode");

const qr =
    new QRCode(
        qrContainer,
        {
            text: controllerURL,

            width: 300,

            height: 300,

            correctLevel:
                QRCode.CorrectLevel.H
        }
    );


/*
 * DOWNLOAD QR CODE
 */

function downloadQR()
{

    /*
     * QRCodeJS normally creates a canvas.
     */

    const canvas =
        qrContainer.querySelector("canvas");

    if (canvas)
    {

        const link =
            document.createElement("a");

        link.download =
            controllerID + "_QR.png";

        link.href =
            canvas.toDataURL("image/png");

        document.body.appendChild(link);

        link.click();

        document.body.removeChild(link);

        return;
    }


    /*
     * Fallback if an image was created.
     */

    const image =
        qrContainer.querySelector("img");

    if (image)
    {

        const canvas =
            document.createElement("canvas");

        canvas.width = 300;

        canvas.height = 300;

        const context =
            canvas.getContext("2d");

        context.drawImage(
            image,
            0,
            0,
            300,
            300
        );

        const link =
            document.createElement("a");

        link.download =
            controllerID + "_QR.png";

        link.href =
            canvas.toDataURL("image/png");

        document.body.appendChild(link);

        link.click();

        document.body.removeChild(link);

        return;
    }


    alert(
        "QR code is not ready yet. Please wait a moment and try again."
    );
}


/*
 * OPEN CONTROLLER
 */

function openController()
{
    window.open(
        controllerURL,
        "_blank"
    );
}

</script>

</body>

</html>
```
