<body>
    <?php

    $is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
    ?>

    <ul class="nav-links">
        <li><a href="../../../projeto/index.php">Home</a></li>
        <li><a href="../../../projeto/view/team/manage.php">Times</a></li>
        <li><a href="../../../projeto/view/match/manage.php">Partidas</a></li>
        <li><a href="../../../projeto/view/user/perfil.php">Perfil</a></li>
    </ul>
</body>

</html>