<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.4.0/css/bulma.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/app.css">
        
        <script>
            window.Laravel =  <?= json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
        
        <title>LV FileManager</title>

    </head>
    <body>

        <div id="app" class="panel">

            <div class="panel-heading">
                <p>File manager. Laravel + Vue.js</p>
            </div>

            <div class="panel-block">
                <files></files>  
            </div>

        </div>
        
        <script src="js/app.js"></script>
        
    </body>
</html>