<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemap generado</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <table align="center" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius: 8px; overflow:hidden; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
        <tr>
            <td style="background:#2563eb; color:#ffffff; text-align:center; padding:20px;">
                <h2 style="margin:0;">✅ Sitemap generado con éxito</h2>
            </td>
        </tr>
        <tr>
            <td style="padding:20px; color:#333333;">
                <p>Hola <strong>'Administrador'</strong>,</p>

                <p>Te informamos que el <strong>Sitemap</strong> de tu sitio web se ha creado correctamente en la fecha:</p>

                <p style="text-align:center; font-size:16px; font-weight:bold; color:#2563eb;">
                    {{ now()->format('d/m/Y H:i') }}
                </p>

                <p>Puedes consultarlo en el siguiente enlace:</p>

                <p style="text-align:center;">
                    <a href="{{ $sitemapUrl ?? url('/sitemap.xml') }}"
                       style="background:#2563eb; color:#ffffff; text-decoration:none; padding:10px 20px; border-radius:5px; display:inline-block;">
                        Ver Sitemap
                    </a>
                </p>

                <p>Si no solicitaste esta acción, puedes ignorar este mensaje.</p>

                <p style="margin-top:30px;">Saludos,<br>
                <strong>El equipo de {{ config('app.name') }}</strong></p>
            </td>
        </tr>
        <tr>
            <td style="background:#f3f4f6; text-align:center; padding:10px; font-size:12px; color:#6b7280;">
                © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
            </td>
        </tr>
    </table>
</body>
</html>
