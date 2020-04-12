<?php
$servername = "localhost";
$username = "admin";
$password = "pajarito";
$database = "logPiWater";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    $sqlTuples = "SELECT * FROM logs ORDER BY id DESC";
    $resultTuples = $conn->query($sqlTuples);
    $result=mysqli_query($conn,"SELECT COUNT(*) as total FROM logs WHERE watered = 1;");
    $data=mysqli_fetch_assoc($result);
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <link rel="stylesheet" type="text/css" href="./styles.css">
        </head>
    <body>

    <h1>Logs de riego automático</h1>
    <p># de veces regadas: <?php echo $data['total']; ?><p>

    <table class="greyGridTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha y hora</th>
                <th>Resultado</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while($tupla = mysqli_fetch_array($resultTuples, MYSQLI_ASSOC) ){
            ?>
                <tr>
                    <td><?php echo $tupla['id'];?></td>
                    <td><?php echo $tupla['time'];?></td>
                    <td><?php echo $tupla['watered'];?></td>
                </tr>
            <?php
        }
        ?>
        </tbody>
    </table>


    </body>
    </html> 

    <?php
}

?>