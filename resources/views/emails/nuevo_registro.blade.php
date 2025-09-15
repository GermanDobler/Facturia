<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Recibida</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 28px;
        }

        .header p {
            font-size: 16px;
            color: #7f8c8d;
        }

        .order-details {
            margin-top: 20px;
        }

        .order-details h3 {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .order-details p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .product-list {
            margin-top: 20px;
        }

        .product-list ul {
            list-style-type: none;
            padding: 0;
        }

        .product-list li {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0;
        }

        .product-list img {
            max-width: 80px;
            margin-right: 15px;
        }

        .product-list .product-info {
            display: flex;
            flex-direction: column;
        }

        .product-list .product-info span {
            font-size: 14px;
            color: #7f8c8d;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #7f8c8d;
        }

        .footer p {
            margin: 5px 0;
        }

        .cta-button {
            background-color: #ffab00;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
            margin-top: 20px;
        }

        .cta-button:hover {
            background-color: #cf8b03;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Nuevo registro de usuario</h2>
<p>Se ha registrado un nuevo usuario:</p>
<ul>
    <li>Nombre: {{ $usuario->name }}</li>
    <li>Email: {{ $usuario->email }}</li>
    {{-- <li>Password: {{ $usuario->password }}</li> --}}
</ul>

        <div class="footer">
            <p> Correo automaticomente generado</p>
        </div>
    </div>
</body>

</html>
