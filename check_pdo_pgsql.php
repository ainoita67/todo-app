<?php
if (extension_loaded('pdo_pgsql')) {
    echo "La extensión pdo_pgsql está cargada correctamente.";
} else {
    echo "La extensión pdo_pgsql NO está cargada.";
}