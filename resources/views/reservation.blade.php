
<html>
<head>
    <title>Форма бронювання</title>

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>

    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

</head>

<body style="background: #fff0;">

<div id="app2"></div>


<script type="text/javascript">
    window.tmpl_data = JSON.parse('<?=addslashes(json_encode($tmpl_data)) ?>');
</script>


<script src="{{ asset(mix('js/app.js')) }}" defer></script>

</body>
</html>


