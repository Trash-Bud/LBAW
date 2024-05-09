<!DOCTYPE html>
<html lang = "pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto Slab');
        body{
            font-family: 'Roboto Slab';
            text-align: center;
            text-justify: auto;
            display: flex;
            flex-direction: column;
        }
        #title {
            background-color: #8C2E14;
            color: white;
            text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
            font-size: 35px;
            padding: 1%;
            text-align: center;
            float: left;
            width: 100%;
            box-sizing: content-box;

        }
        #title >a {
            color: white;
            text-decoration:none;
        }
        .content{
            background-color: rgb(214, 214, 214);
            padding: 2%;
        }

    </style>
</head>
<body>
    <header>
        <h1 id = title><a href = {{env('APP_URL')}}>Feup-Tech</a></h1>
    </header>

    <div class = content>
        <div>{{$details['body']}}</div>
        <br>
        <a href = {{$details['link']}}>Muda a sua password!</a>
    </div>

</body>
</html>
