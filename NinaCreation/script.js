function addfav(index) {
    var image = document.getElementById("imgfav" + index);
    var idprod = document.getElementById("idprod" + index);
    if (image.src.endsWith("nonfav.png")) {
        image.src = "./img/fav.png";
        window.location.href = "controleur.php?action=addfav&idprod=" + idprod.value;
    } else {
        image.src = "./img/nonfav.png";
        window.location.href = "controleur.php?action=delfav&idprod=" + idprod.value;
    }
}