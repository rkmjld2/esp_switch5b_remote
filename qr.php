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

/*
 * QR Code image
 */
$qr_url =
    'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=' .
    urlencode($controller_url);

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

.qr-image {
    width: 300px;
    height: 300px;
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

/*
 * When printing, hide the buttons and URL.
 */
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

<img
    id="qrImage"
    class="qr-image"
    src="<?= htmlspecialchars(
        $qr_url,
        ENT_QUOTES,
        'UTF-8'
    ) ?>"
    alt="QR Code"
>

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
    onclick="window.open(
        '<?= htmlspecialchars(
            $controller_url,
            ENT_QUOTES,
            'UTF-8'
        ) ?>',
        '_blank'
    )"
>
OPEN CONTROLLER
</button>

</div>

</div>


<script>

/*
 * Download QR code as PNG
 */
function downloadQR()
{
    const image =
        document.getElementById("qrImage");

    const controller =
        <?= json_encode($controller_id) ?>;

    /*
     * Fetch the QR image and convert it
     * into a downloadable PNG.
     */
    fetch(image.src)
        .then(response => response.blob())
        .then(blob => {

            const url =
                URL.createObjectURL(blob);

            const link =
                document.createElement("a");

            link.href = url;

            link.download =
                controller + "_QR.png";

            document.body.appendChild(link);

            link.click();

            document.body.removeChild(link);

            URL.revokeObjectURL(url);

        })
        .catch(error => {

            alert(
                "Unable to download QR code. " +
                "Please try again."
            );

            console.error(error);

        });
}

</script>

</body>

</html>
```
