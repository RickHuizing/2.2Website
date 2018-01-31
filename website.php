<!DOCTYPE html>
<!--suppress ALL -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script type="text/javascript" src=https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js></script>
</head>
<body>


<script>

    //temperature in celcius (moet automatisch uit de text bestandjes komen)
    var temperature = 43;

    //deze temperatuur omgezet in fahrenheit
    var t = temperature * 1.8 + 32;

    // humidity in procent(moet automatisch uit de text bestandjes komen)
    var h = 40;

    //maakt een heatindex variabele aan die in de if else statements wordt omgezet naar de juiste vorm ervan.
    var newHeatIndex;

    //heatindex in fahrenheit
    var heat = -42.379 + 2.04901523*t + 10.14333127*h - .22475541*t*h - .00683783*t*t - .05481717*h*h + .00122874*t*t*h + .00085282*t*h*h - .00000199*t*t*h*h;

    //heatindex terug in celcius
    var heatIndex = (heat - 32) * 5 / 9 ;

    //heatindex uitgerekend op bepaalde temperaturen (bij bepaalde temperaturen gelden andere regels).
    if (h < 13 && t > 80 && t < 112) {
        var x = ((13 - h)/4) * (Math.sqrt(17-Math.abs(t-95)/17));
        newHeatIndex = heatIndex - x;
    } else if (h > 85 && t > 80 && t < 87) {
        var y = ((h - 85)/10)*((87-t)/5);
        newHeatIndex = heatIndex + y;
    } else {
        newHeatIndex = heatIndex;
    }

</script>
<input type="file" name="file" id="file">
<canvas id ="lijn-Grafiek" style= "width: 512px; height: 256px;"  > </canvas>
<script>

    //maakt een array voor de temperaturen aan.
    temperatuurArray = [];


    <?php
    //haalt alle files uit deze folder. moet veranderen in de geselecteerde weerstation.
    $files = glob("temp/2018-01-25/*.txt");

    //makes a array with only filenames without .txt in the for loop
    $filename = [];

    //makes an array set to utf8 code in the for loop
    $php_array = [];

    //makes every file in a folder part of an array
    for($i = 0; $i < sizeof($files); $i++){
        $snoep[$i] = basename($files[$i], '.txt');
        $php_array[$i] = utf8_encode($snoep[$i]);

    }
    //makes the php array a javascript array
    $js_array = json_encode($php_array);
    ?>

    //stores the php array in a javascript array
    secondenArray = <?php echo $js_array ?>;

    //laat je een bestand kiezen (denk ik), het is beter dat dit veranderd in de bestandjes die je in de php code selecteerd.
    document.getElementById('file').addEventListener('change', function (event) {

        //maakt een file array aan met de geselecteerde file (denk ik)
        files = event.target.files;

        //nieuwe filereader
        reader = new FileReader();

        //een functie die een bepaald text bestandje laad en hiermee een grafiek maakt (denk ik)
        reader.onload = function(event) {

            //de text kan alleen worden omgezet in ascii
            textInAscii = new Int8Array(event.target.result);

            //laat het aantal berichten in een file en zet het daarna om in een string en daarna om in een integer
            aantalBerichtenAscii = textInAscii.slice(0, 2);
            aantalBerichtenString = String.fromCharCode.apply(null, aantalBerichtenAscii);
            aantalBerichten = parseInt(aantalBerichtenString);

            //pakt voor een bestandje elke temperatuur en zet deze in de temperatuurarray
            for (i = 0; i < aantalBerichten; i++) {

                startIndex = i * 55 + 3;
                temperatuurLocatie = textInAscii.slice(startIndex, startIndex + 5);
                temper = String.fromCharCode.apply(null, temperatuurLocatie);
                temperatuurArray[i] = parseFloat(temper);


            };

            //pakt voor een bestandje elke humidity en zet deze in de humidityarray(die ik nog moet maken)
            for (i = 0; i < aantalBerichten; i++) {
                startIndex = i * 55 + 8;
                temperatuurLocatie = textInAscii.slice(startIndex, startIndex + 5);
                douwPunt = String.fromCharCode.apply(null, temperatuurLocatie);
            };

            //maakt de grafiek.
            new Chart(document.getElementById("lijn-Grafiek"), {
                type: 'line',
                data: {
                    labels: secondenArray,
                    datasets: [{
                        data: temperatuurArray,
                        label: "Zuudloarn",
                        borderColor: "#FF0000",
                        fill: false
                    }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'temperatuur'
                    }
                }
            });

        };

        //heeft iets te maken met het laden van de geselecteerde files. maar geen idee verder. haal het maar niet weg
        reader.readAsArrayBuffer(files[0]);

        //geen idee maar wanneer je het weghaalt doet de code het niet
    }, false);


</script>

</body>
</html>
