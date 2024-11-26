document.getElementById('crearProductoForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('accion', 'crear');
    fetch('/Kisma/HTML/Restaurante/productos.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            alert(data);
            cargarProductos();
        })
        .catch(error => console.error('Error:', error));
});

function cargarProductos() {
    fetch('/Kisma/HTML/Restaurante/productos.php?accion=listar')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.status}`);
            }
            return response.json().catch(() => {
                throw new Error("La respuesta no es un JSON válido");
            });
        })
        .then(data => {
            const lista = document.getElementById('productosLista');
            lista.innerHTML = '';

            if (data.length === 0) {
                lista.textContent = 'No hay productos disponibles.';
                return;
            }

            data.forEach(producto => {
                const item = document.createElement('div');
                item.textContent = `${producto.nombre_producto} - $${producto.precio}`;
                lista.appendChild(item);
            });
        })
        .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', cargarProductos);
