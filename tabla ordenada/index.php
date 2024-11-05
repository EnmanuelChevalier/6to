<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" >
    <meta name="author" content="Enman">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almacenar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<main class="container py-4 text-center">
    <h2>Empleados</h2>
    <div class="row g-4 align-items-center">
        <div class="col-auto">
            <label for="num_registros" class="col-form-label">Mostrar: </label>
        </div>
        <div class="col-auto">
            <select name="num_registros" id="num_registros" class="form-select">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="col-auto">
            <label for="num_registros" class="col-form-label">registros</label>
        </div>
        <div class="col-md-4 col-xl-5"></div>
        <div class="col-auto text-end">
            <label for="campo" class="col-form-label">Buscar: </label>
        </div>
        <div class="col-auto">
            <input type="text" name="campo" id="campo" class="form-control">
        </div>
    </div>
    <div class="row py-4">
        <div class="col">
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="sort" onclick="ordenar(0)">Num. empleado</th>
                        <th class="sort" onclick="ordenar(1)">Nombre</th>
                        <th class="sort" onclick="ordenar(2)">Apellido</th>
                        <th class="sort" onclick="ordenar(3)">Fecha nacimiento</th>
                        <th class="sort" onclick="ordenar(4)">Fecha ingreso</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="content">
                    <!-- Datos cargados dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="row justify-content-between">
        <div class="col-12 col-md-4">
            <label id="lbl-total"></label>
        </div>
        <div class="col-12 col-md-4" id="nav-paginacion"></div>
        <input type="hidden" id="pagina" value="1">
        <input type="hidden" id="orderCol" value="0">
        <input type="hidden" id="orderType" value="asc">
    </div>
</main>
<script>
document.addEventListener("DOMContentLoaded", () => {
    getData();

    // Asignar eventos a los elementos de la página
    document.getElementById("campo").addEventListener("keyup", getData);
    document.getElementById("num_registros").addEventListener("change", getData);
});

function getData() {
    let campo = document.getElementById("campo").value;
    let num_registros = document.getElementById("num_registros").value;
    let pagina = document.getElementById("pagina").value || 1;
    let orderCol = document.getElementById("orderCol").value;
    let orderType = document.getElementById("orderType").value;

    let formaData = new FormData();
    formaData.append('campo', campo);
    formaData.append('registros', num_registros);
    formaData.append('pagina', pagina);
    formaData.append('orderCol', orderCol);
    formaData.append('orderType', orderType);

    fetch("load.php", {
        method: "POST",
        body: formaData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("content").innerHTML = data.data;
        document.getElementById("lbl-total").innerHTML = `Mostrando ${data.totalFiltro} de ${data.totalRegistros} registros`;
        document.getElementById("nav-paginacion").innerHTML = data.paginacion;

        // Verificare si hay resultados y reiniciar la página si no hay
        if (data.data.includes('Sin resultados') && parseInt(pagina) !== 1) {
            nextPage(1);
        }
    })
    .catch(err => console.log(err));
}

function nextPage(pagina) {
    document.getElementById('pagina').value = pagina;
    getData();
}

function ordenar(index) {
    let orderCol = document.getElementById('orderCol');
    let orderType = document.getElementById('orderType');
    let currentType = orderCol.value == index ? orderType.value : null;
    orderType.value = currentType === 'asc' ? 'desc' : 'asc';
    orderCol.value = index;
    getData();
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
