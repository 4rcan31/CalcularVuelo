<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>
<body>
<div class="container" style="margin-top: 100px;">
<div class="jumbotron">
    <h1 style="margin-bottom: 50px;">Esto es tan solo una prueba en php</h1>
    <form method="GET">
    <div class="form-group">
        <label><b>Que dia saldra del pais</b></label>
        <input type="date" class="form-control" name="Day">
    </div>
    <div class="form-group" style="margin-top: 30px;">
        <label><b>Que hora es su vuelo</b></label>
        <input type="time" class="form-control" name="hour">
        <small>Ingrese la hora, luego los minutos y de ultimo pm o am => 2:03:pm</small>
    </div>
    <button type="submit" class="btn btn-primary" style="margin-top: 20px;" name="CalcularTime">Calcular</button>
   
    </form>
    <form method="POST" style="display: inline !important;">
        <button type="submit" class="btn btn-primary" style="margin-top: 20px;" name="Actualizar">Actualizar</button>
    </form>
</div>
</div>
</body>
</html>

<?php
function Redireccionar($url, $time = 0){
    if(is_numeric($time)){
        $time = strval($time);
    }
    $Page = '<META HTTP-EQUIV="REFRESH" CONTENT="'.$time.';URL='.$url.'">';
    echo $Page;
}
function calcMinutes($minutes){
    $time = ['days' => 0, 'hours' => 0, 'minutes' => 0];
    while ($minutes >= 60) {
        if ($minutes >= 1440) {
            $time['days']++;
            $minutes = $minutes - 1440;
        } else if ($minutes >= 60) {
            $time['hours']++;
            $minutes = $minutes - 60;
        }
    }
    $time['minutes'] = $minutes;
    return $time;
}
function TimeInMinuts( $Day = 0 , $Hour = 0, $Minutos =0){
    return  ($Day* 1440) + ($Hour * 60) + ($Minutos); 
}
$timeZone = "America/El_Salvador";
$StyleStart = "<div style ='margin-top: 20px;'class='container jumbotron'><b>";
$StyleEnd = "<b></div>";
date_default_timezone_set($timeZone);
    if(isset($_GET['CalcularTime'])){
        if($_GET['Day'] != "" && $_GET['hour'] != ""){
            $Hour = explode(':', $_GET['hour']);
            $Date = explode("-", $_GET['Day']);
            $DateToday = explode("-", date('d-m-Y'));
            $FechaElegida = TimeInMinuts($Date[2], $Hour[0], $Hour[1]);
            $FechaAhora = TimeInMinuts((getdate()['mday']), (getdate()['hours']), (getdate()['minutes']) );
            $Diferencia =  $FechaElegida - $FechaAhora;
            $Resultado = calcMinutes($Diferencia);
            $DataDate = [$Resultado['days'], ($Date[1] - $DateToday[1]),($Date[0] - $DateToday[2])];
            $DataTime = [$Resultado['hours'], $Resultado['minutes']];
            echo $StyleStart."La fecha de tu vuelo es el ".$Date[0]."-".$Date[1]."-".$Date[2]." a las ".$_GET['hour'].$StyleEnd."<br>";
            $DataDate[2] == 0 ? $e = false : $e = true;
            $DataDate[1] == 0 ? $r = false : $r = true;
            $DataDate[1] == 1 ? $m = " mes" : $m = " meses";
            $DataDate[0] == 1 ? $d = " dia" : $d = " dias";
            if($e){
                $Mensaje = "Faltan ".$DataDate[2]." aos, ".$DataDate[1].$m.", ".$DataDate[0].$d." con ".$DataTime[0]." horas y ".$DataTime[1]." minutos";
            }else{
                if($r){
                    $Mensaje = "Faltan ".$DataDate[1].$m.", ".$DataDate[0].$d." con ".$DataTime[0]." horas y ".$DataTime[1]." minutos";
                }else{
                    $Mensaje = "Faltan ".$DataDate[0].$d." con ".$DataTime[0]." horas y ".$DataTime[1]." minutos";
                }
            }
            echo $StyleStart.$Mensaje.$StyleEnd;
            $url = strtolower((explode('/', $_SERVER['SERVER_PROTOCOL']))[0])."://".$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI'];
            $get = "?Day=".$_GET['Day']."&hour=".$_GET['hour'];
                if(isset($_POST['Actualizar'])){
                    //echo $url.$get;
                    Redireccionar($url, 0);
                }
        }else{
            echo $StyleStart."Los campos no estan llenos".$StyleEnd;
        }
        
    }

?>