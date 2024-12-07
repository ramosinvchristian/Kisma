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
                item.classList.add('producto-item');
                item.innerHTML = `
                    <div class="producto-info">
                        <h3>${producto.nombre_producto}</h3>
                        <p>${producto.descripcion}</p>
                        <p><strong>Precio:</strong> $${producto.precio}</p>
                        <p><strong>Disponible:</strong> ${producto.disponible ? 'Sí' : 'No'}</p>
                    </div>
                    <div class="acciones">
                        <button class="editar" data-id="${producto.id_producto}">Editar</button>
                        <button class="eliminar" data-id="${producto.id_producto}">Eliminar</button>
                    </div>
                `;
                lista.appendChild(item);
            });

            document.querySelectorAll('.editar').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    editarProducto(id);
                });
            });

            document.querySelectorAll('.eliminar').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    eliminarProducto(id);
                });
            });
        })
        .catch(error => console.error('Error:', error));
}

function editarProducto(id) {
    alert(`Editar producto con ID: ${id}`);
}

function eliminarProducto(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        fetch(`/Kisma/HTML/Restaurante/productos.php?accion=eliminar&id_producto=${id}`, {
            method: 'GET'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error al eliminar producto: ${response.status}`);
                }
                return response.text();
            })
            .then(data => {
                alert(data);
                cargarProductos();
            })
            .catch(error => console.error('Error:', error));
    }
}

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

document.addEventListener('DOMContentLoaded', cargarProductos);
