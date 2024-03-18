function openModal(switchId, page) {
    console.log('Modal abierta para el switch con ID:', switchId);
    
    // Obtener el contenedor de la modal
    var modalContainer = document.getElementById('modal-container');
    
    // Crear el contenido HTML de la modal
    var modalContent = `
        <div class="modal">
            <div class="modal-header">
                <span class="close-button" onclick="closeModal()">&times;</span>
                <h2>Encender Switch</h2>
            </div>
            <div class="modal-body">
                <form action="index.php?controller=switchs&amp;action=changeStatus&amp;status=true&amp;redirect=${page}" method="post" id="time-form">
                    <input type="hidden" id="switchId" name="id" value="${switchId}">
                    <label for="timeModal" name="tiempo">Tiempo (minutos):</label>
                    <input type="number" id="timeModal" name="timeOff" required>
                    <button type="submit">Confirmar</button>
                </form>
            </div>
        </div>
    `;

    // Agregar el contenido HTML al contenedor de la modal
    modalContainer.innerHTML = modalContent;
    
    // Mostrar la modal
    modalContainer.style.display = 'block';
}

function closeModal() {
    var modalContainer = document.getElementById('modal-container');
    modalContainer.style.display = 'none';
}
