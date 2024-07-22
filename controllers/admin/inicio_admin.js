// Constantes para completar la ruta de la API.
const AUTORES_API = 'services/admin/autores.php';
const CLASIFICACION_API = 'services/admin/clasificacion.php';
const EDITORIALES_API = 'services/admin/editoriales.php';
const LIBRO_API = 'services/admin/libros.php'

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a las funciones que generan los gráficos en la página web.
    graficoBarrasAutor();
    graficoBarrasClasificacion();
    graficoBarrasEditorial ();
    graficoBarrasLibrosMasVendidos ();
});

const graficoBarrasAutor = async () => {
    // Petición para obtener los datos del gráfico.
    const DATA = await fetchData(AUTORES_API, 'cantidadLibrosAutor');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let autores = [];
        let cantidades = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            autores.push(row.nombre);
            cantidades.push(row.cantidad);
        });
        barGraph('chart1', autores, cantidades, 'Cantidad de libros', 'Cantidad de libros por autor');
    } else {
        document.getElementById('chart1').remove();
        console.log(DATA.error);
    }
};

const graficoBarrasClasificacion = async () => {
    // Petición para obtener los datos del gráfico.
    const DATA = await fetchData(CLASIFICACION_API, 'cantidadLibrosClasificacion');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let clasificaciones = [];
        let cantidades = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            clasificaciones.push(row.nombre);
            cantidades.push(row.cantidad);
        });
        pieGraph('chart2', clasificaciones, cantidades, 'Cantidad de libros por categoria', 'Cantidad de libros por clasificación');
    } else {
        document.getElementById('chart2').remove();
        console.log(DATA.error);
    }

};

const graficoBarrasEditorial = async () => {
    try {
        // Petición para obtener los datos del gráfico.
        const DATA = await fetchData(EDITORIALES_API, 'cantidadLibrosEditorial');
        console.log(DATA); // Verificar el contenido de DATA
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
        if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a graficar.
            let editoriales = [];
            let cantidades = [];
            // Se recorre el conjunto de registros fila por fila a través del objeto row.
            DATA.dataset.forEach(row => {
                // Se agregan los datos a los arreglos.
                editoriales.push(row.nombre);
                cantidades.push(row.cantidad);
            });
            radarGraph('chart3', editoriales, cantidades, 'Cantidad de libros', 'Cantidad de libros por editorial');
        } else {
            document.getElementById('chart3').remove();
            console.log(DATA.error);
        }
    } catch (error) {
        console.error('Error fetching data for editorial chart:', error);
    }
};
const graficoBarrasLibrosMasVendidos = async () => {
    try {
        // Petición para obtener los datos del gráfico.
        const DATA = await fetchData(LIBRO_API, 'getLibrosMasVendidos');
        console.log(DATA); // Verificar el contenido de DATA
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
        if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a graficar.
            let titulos = [];
            let cantidades = [];
            // Se recorre el conjunto de registros fila por fila a través del objeto row.
            DATA.dataset.forEach(row => {
                // Se agregan los datos a los arreglos.
                titulos.push(row.titulo);
                cantidades.push(row.total_vendido);
            });
            lineGraph('chartLibrosMasVendidos', titulos, cantidades, 'Total Vendido', 'Libros Más Vendidos');
        } else {
            document.getElementById('chartLibrosMasVendidos').remove();
            console.log(DATA.error);
        }
    } catch (error) {
        console.error('Error fetching data for top selling books chart:', error);
    }
};
