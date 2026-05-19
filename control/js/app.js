document.addEventListener('DOMContentLoaded', function(){
    const btn_eliminar = document.getElementsByClassName("btn-eliminar");
    const btn_eliminar_color =document.getElementsByClassName("btn-eliminar-color");

    Array.from(btn_eliminar).forEach(btn => {
        btn.addEventListener('click', function(){
            const data_id = this.getAttribute('data-id');
            if (confirm('¿Deseas Eliminar los datos?')){
                window.location.href = `inventario.php?borrar=${data_id}`;
                //console.log("...");
            }
        });
    });

});
