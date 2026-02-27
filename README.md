# QR-Captcha
Captcha generator using QR code

# How to use
Create a login form where a username and password are requested. If the validation is true, include the file QRcaptcha\QRcaptcha.php to perform a second validation using a QR captcha.


Example:

```php
<?PHP
session_start();
$token = $_SESSION["token"] = md5(uniqid(mt_rand(), true));
echo ("
<!DOCUMENT TYPE='es'>
<html lang='es'>
    <head>
        <title>QRCaptcha</title>
    </head>
    <body>
");
if (isset($_SESSION["id_usuario"])) {
    include_once("QRcaptcha.php");
    echo ("
        <form method='POST' action='sesion.php?accion=logout&token=$_SESSION[token]'>
        <button>Salir</button>
        </form>
    ");
} else {
    echo ("
                <center>
                    <table border=0 width=100%>
                        <tr>
                        <td align=center>
                            <form method='POST' action='sesion.php?accion=login'>
                                <table>
                                <td><input type='text' id='user_name' name='user_name' placeholder='Usuario'/>
                                <tr>
                                <td><input type='password' id='clave' name='clave' placeholder='Clave'/>
                                <tr>
                                <td align=center><input type='submit' value='Autenticar'/>
                                <input type='hidden' id='token' name='token' value='" . $token . "'>
                                </table>
                            </form>
                        <tr>
                        <td colspan=2 align='center'>
                    </table>
                </center>
            </div>
        </div>
    </body>
</html>
");
}
?>
```
