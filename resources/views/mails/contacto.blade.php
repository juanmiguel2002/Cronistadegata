<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo mensaje de contacto</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border: 1px solid #dddddd;">
        <tr>
            <td align="center">
                <img src="{{ asset('front/img/periodic1.jpg') }}" alt="Logo" style="max-height: 50px; margin-bottom: 20px;">
            </td>
        </tr>
        <tr>
            <td>
                <h2 style="color: #333; text-align: center;">Nuevo mensaje desde el formulario de contacto</h2>
                <p><strong>Nombre:</strong> {{ $datos['name'] }}</p>
                <p><strong>Email:</strong> {{ $datos['email'] }}</p>
                <p><strong>Mensaje:</strong></p>
                <div style="background-color: #f1f1f1; padding: 10px; border-radius: 5px;">
                    {!! nl2br(e($datos['message'])) !!}
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 30px; font-size: 12px; color: #777; text-align: center;">
                Este mensaje ha sido enviado desde Cronista de Gata .
            </td>
        </tr>
    </table>
</body>
</html>
