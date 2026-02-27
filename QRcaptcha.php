<?php
/*

Powered by parablan
Hector Alejandro Parada Blanco
Captcha con código QR

*/

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

    // Si es una petición AJAX, devolvemos JSON
    if (isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'captcha' => $captcha,
            'qr' => $qr_data
        ]);
        exit;
    }

    // Mostrar como página HTML
    header('Content-Type: text/html');

    echo ("<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
<script>
        function actualizarQR(){
            $.ajax({
                url: 'QRcaptcha.php',
                type: 'POST',
                data: { ajax: 1 }, 
                dataType: 'json',
                success: function(data){
                    $('#qr').attr('src', data.qr);
                    $('#captcha').val(data.captcha);
                }
            });
        }

        $(document).ready(function(){
            setInterval(actualizarQR, 30000); 
        });
    </script>
<center>
<div style='width: 300px; height: 150px; display: flex; align-items: center; justify-content: center; flex-direction: column;'>
    <h2 style='font-size: 20px; color: #333333;'>
        Escanea el código QR y digita el número captcha
    </h2>
    <small style='font-size: 15px; color: #333333;'>
        El código QR se actualiza cada 30 segundos
    </small>
</div>
<div style='width: 150px; height: 150px; display: flex; align-items: center; justify-content: center; flex-direction: column;'>
    <img src='" . $qr_data . "' id='qr' />
</div>
<div style='width: 150px; height: 150px; display: flex; align-items: center; justify-content: center; flex-direction: column;'>
    <form action='validar.php' method='post'>
        <input type='hidden' id='captcha' name='captcha' value='" . $captcha . "'>
        <input type='text' placeholder='****' id='captcha_digitado' name='captcha_digitado' style='text-align:center; width:110px; font-weight:bold; font-size:20px; color:#328bd7; background:transparent; letter-spacing: 3px' maxlength='4' autocomplete='off' required>
        </br>
        </br>
        <button type='submit' style='width: 110px; height: 30px; font-weight: bold; font-size: 15px; color: #328bd7; cursor: pointer;'>Enviar</button>
    </form>
</div>
</center>");
} else {
    // Sesión no iniciada
}



