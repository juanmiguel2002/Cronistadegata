<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En mantenimiento | Cronista de Gata de Gorgos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f4f9;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        .container {
            background: #fff;
            padding: 3rem 2rem;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #007BFF;
        }

        p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .spinner {
            margin: 1rem auto;
            border: 6px solid #eee;
            border-top: 6px solid #007BFF;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        footer {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚙️ Estamos en mantenimiento</h1>
        <p>El sitio del <strong>Cronista de Gata de Gorgos</strong> está temporalmente en mantenimiento.<br>
        Vuelve a intentarlo más tarde.</p>

        <div class="spinner"></div>

        <footer>
            &copy; {{ date('Y') }} Cronista de Gata de Gorgos
        </footer>
    </div>
</body>
</html>
