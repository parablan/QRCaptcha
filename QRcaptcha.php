<?php
// c:\xampp\htdocs\MAIN2\QRcaptcha.php

require_once 'vendor/autoload.php';
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

if (isset($_SESSION)) {
    $options = new QROptions([
        'version' => 2,
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
        'eccLevel' => QRCode::ECC_L,
        'imageBase64' => true,
    ]);

    $captcha = rand(1000, 9999);
    $qr_data = (new QRCode($options))->render($captcha);

    // Si es una petición AJAX, devolvemos SOLO el JSON limpio
    if (isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'captcha' => $captcha,
            'qr' => $qr_data
        ]);
        exit;
    }

    ?>
    <script>
        function actualizarQR() {
            $.ajax({
                url: 'index.php', // Apunta a index.php que es quien tiene la sesión
                type: 'POST',
                data: { ajax: 1 },
                dataType: 'json',
                success: function (data) {
                    // Actualizamos la imagen y el campo oculto
                    $('#qr').attr('src', data.qr);
                    $('#captcha').val(data.captcha);
                    console.log("QR Actualizado: " + data.captcha);
                },
                error: function (err) {
                    console.error("Error al actualizar QR", err);
                }
            });
        }

        $(document).ready(function () {
            // Ejecutar cada 30000ms (30 segundos)
            setInterval(actualizarQR, 30000);
        });
    </script>

    <center>
        <div style='margin-bottom: 20px;'>
            <h2 style='font-size: 20px; color: #333;'>Escanea el QR y digita el código</h2>
            <small style='color: #666;'>El código se actualiza cada 30 segundos</small>
        </div>

        <div>
            <img src="<?php echo $qr_data; ?>" id="qr" style="border: 1px solid #ccc; padding: 10px;" />
        </div>

        <div style='margin-top: 20px;'>
            <form action='validar.php' method='post'>
                <input type='hidden' id='captcha' name='captcha' value='<?php echo $captcha; ?>'>
                <input type='text' placeholder='****' id='captcha_digitado' name='captcha_digitado'
                    style='text-align:center; width:110px; font-weight:bold; font-size:20px; letter-spacing: 3px'
                    maxlength='4' autocomplete='off' required>
                <br><br>
                <button type='submit' style='cursor: pointer;'>Enviar</button>
            </form>
        </div>
    </center>
    <?php
}
?>
