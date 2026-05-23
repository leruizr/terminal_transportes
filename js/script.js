// script.js
// Funciones del lado cliente para el buscador del index

function capitalizar(texto) {
    if(!texto) return "";
    return texto.charAt(0).toUpperCase() + texto.slice(1);
}

// BUSCADOR (usado en index.php)
function buscarRutas(){
    const origen = document.getElementById("origen").value;
    const destino = document.getElementById("destino").value;
    const fecha = document.getElementById("fecha").value;

    if(!origen || !destino || !fecha){
        alert("Complete todos los campos.");
        return;
    }
    if(origen === destino){
        alert("Origen y destino no pueden ser iguales.");
        return;
    }
    window.location.href = `resultados.php?origen=${origen}&destino=${destino}&fecha=${fecha}`;
}

console.log("Script JS cargado.");
