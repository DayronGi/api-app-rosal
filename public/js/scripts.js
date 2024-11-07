window.addEventListener("DOMContentLoaded", (event) => {
    function filterList(value, listId) {
        var listItems = document.getElementById(listId + '_list').querySelectorAll('li');
        for (var i = 0; i < listItems.length; i++) {
            var text = listItems[i].textContent.toLowerCase();
            if (text.indexOf(value.toLowerCase()) >= 0) {
                listItems[i].style.display = 'block';

                listItems[i].addEventListener('click', (event) => {
                    var selectedItem = event.target;
                    var allItems = document.getElementById(listId + '_list').querySelectorAll('li');
                    for (var j = 0; j < allItems.length; j++) {
                        allItems[j].classList.remove('selected');
                    }
                    document.getElementById(listId + '_id').value = selectedItem.dataset.value;
                    selectedItem.classList.add('selected');
                    document.getElementById(listId + '_name_visible').value = selectedItem.textContent;

                    event.target.style.display = 'none';

                    document.getElementById(listId + '_list').style.display = 'none';
                });
            }
            else {
                listItems[i].style.display = 'none';
            }
        }
        document.getElementById(listId + '_list').style.display = 'block';
    }

    if (document.getElementById('worker_name_visible')) {
        document.getElementById('worker_name_visible').addEventListener('keyup', (event) => {
            filterList(event.target.value, 'worker');
        });
    }

    if (document.getElementById('job_name_visible')) {
        document.getElementById('job_name_visible').addEventListener('keyup', (event) => {
        filterList(event.target.value, 'job');
        });
    }

    if (document.getElementById('plant_name_visible')) {
        document.getElementById('plant_name_visible').addEventListener('keyup', (event) => {
            filterList(event.target.value, 'plant');
        });
    }

    if (document.getElementById('plant_from_name_visible')) {
        document.getElementById('plant_from_name_visible').addEventListener('keyup', (event) => {
            filterList(event.target.value, 'plant_from');
        });
    }

    $(document).ready(function() {
        $("#job_list li").click(function() {
          const textoSeleccionado = $(this).text();

          const indiceInicioPrecio = textoSeleccionado.indexOf("V.U. $") + 6;
          const indiceFinPrecio = textoSeleccionado.length;
          const textoPrecio = textoSeleccionado.substring(indiceInicioPrecio, indiceFinPrecio);

          const precio = parseFloat(textoPrecio.trim());

          $("#precio_unidad").val(precio);
        });
      });
});