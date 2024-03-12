<?php require_once __DIR__ . "/topo.php";
if (isset($_SESSION['usuario'])) {
?>
    <h3>Bem vinda(o) <?php echo $_SESSION['usuario'][0]["nomeusuario"] ?></h3>

<?php } ?>