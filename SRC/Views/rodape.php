<?php
?>
<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/jquery-mask.js"></script>
<script src="../../scripts/scripts.js" ?<?php echo time(); ?>></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        var url = window.location.pathname;
        $('nav.menu ul li.nav-item a[href="' + url + '"]').addClass('menuAtivo');
    });
</script>

<script>
    $("#menuAdmin").click(function() {
        $("#submenu").toggle();;
        //$("#submenu").toggle();
    });
</script>