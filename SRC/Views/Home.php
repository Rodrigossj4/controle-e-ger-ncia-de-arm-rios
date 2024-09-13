<?php require_once __DIR__ . "/topo.php";
if (isset($_SESSION['usuario'])) {
?>
    <div id="saudacao" class="saudacao">
        <h3>Bem-vindo(a) <?php echo $_SESSION['usuario'][0]["nomeusuario"] ?></h3>
        <?php var_dump($_SESSION) ?>
    </div>
<?php }
require_once __DIR__ . "/rodape.php"
?>