<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
        <h1>Dominio</h1>
        <input type="text" id="Inputdominio">
        <button id="btnenviar">Enviar</button>

        <h1 id="VencimientoSSL"></h1>
        <h1 id="VencimientoDominio"></h1>
</body>
<script>
    $(document).ready(function () {
        $('#btnenviar').click(function (e) { 
            e.preventDefault();

            var Dominio = $('#Inputdominio').val();

                fetch('WebScriping.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'Dominio=' + encodeURIComponent(Dominio)
            })
            .then(response => response.json())
            .then(data => {
                $('#VencimientoDominio').text('El dominio se vence el: ' + data.VencimientoDominio);
                $('#VencimientoSSL').text('El SSL se vence el:' + data.VencimientoSSL);
                console.log(data);
            });
            
        });
    });
</script>
</html>