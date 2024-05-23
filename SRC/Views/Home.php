<?php require_once __DIR__ . "/topo.php";
if (isset($_SESSION['usuario'])) {
?>
    <div id="saudacao" class="saudacao">
        <h3>Bem vinda(o) <?php echo $_SESSION['usuario'][0]["nomeusuario"] ?></h3>
    </div>
<?php }
require_once __DIR__ . "/rodape.php"
?>