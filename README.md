# QR-Captcha
Captcha generator using QR code

# How to use
Create a login form where a username and password are requested. If the validation is true include the file **QRcaptcha.php** to perform a second validation using a QR captcha.


Example:

```php
<?php
session_start();

if (isset($_POST['ajax']) && isset($_SESSION["id_usuario"])) {
    include("QRcaptcha.php");
    exit; // Detenemos la ejecución para que solo devuelva el JSON
}

$token = $_SESSION["token"] = md5(uniqid(mt_rand(), true));

echo ("
<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>QRCaptcha</title>
        <!-- Importar jQuery -->
        <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
    </head>
    <body>
");

if (isset($_SESSION["id_usuario"])) {
    include("QRcaptcha.php");
    echo ("
        <form method='POST' action='sesion.php?accion=logout&token=$_SESSION[token]'>
            <button>Salir</button>
        </form>
    ");
} else {
    echo ("
        <center>
            <form method='POST' action='sesion.php?accion=login'>
                <table>
                    <tr><td><input type='text' name='user_name' placeholder='Usuario'/></td></tr>
                    <tr><td><input type='password' name='clave' placeholder='Clave'/></td></tr>
                    <tr><td align=center>
                        <input type='submit' value='Autenticar'/>
                        <input type='hidden' name='token' value='$token'>
                    </td></tr>
                </table>
            </form>
        </center>
    ");
}

echo ("
    </body>
</html>
");
?>
```
