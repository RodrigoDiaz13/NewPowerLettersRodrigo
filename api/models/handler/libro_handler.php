<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
 * Clase para manejar el comportamiento de los datos de la tabla PRODUCTO.
 */
class LibroHandler
{
    /*
     * Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $titulo = null;
    protected $autor = null;
    protected $precio = null;
    protected $descripcion = null;
    protected $imagen = null;
    protected $clasificacion = null;
    protected $editorial = null;
    protected $existencias = null;
    protected $genero = null;

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../images/libros/';

    /*
     * Métodos para realizar las operaciones CRUD (crear, leer, actualizar y eliminar).
     */

    /*
     * Método para buscar registros en la tabla tb_libros.
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT
            l.id_libro,
            l.titulo AS titulo_libro,
            l.descripcion AS descripcion_libro,
            l.precio,
            l.imagen,
            a.nombre AS nombre_autor,
            c.nombre AS nombre_clasificacion,
            e.nombre AS nombre_editorial,
            g.nombre AS nombre_genero,
            l.existencias
        FROM
            tb_libros AS l
        INNER JOIN
            tb_autores AS a ON l.id_autor = a.id_autor
        INNER JOIN
            tb_clasificaciones AS c ON l.id_clasificacion = c.id_clasificacion
        INNER JOIN
            tb_editoriales AS e ON l.id_editorial = e.id_editorial
        INNER JOIN
            tb_generos AS g ON l.id_genero = g.id_genero
        WHERE
            l.titulo LIKE ? OR
            l.descripcion LIKE ?
        ORDER BY
            l.titulo;';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    /*
     * Método para crear un nuevo registro en la tabla tb_libros.
     */
    public function createRow()
    {
        $sql = 'INSERT INTO tb_libros(titulo, id_autor, precio, descripcion, imagen, id_clasificacion, id_editorial, existencias, id_genero)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->titulo, $this->autor, $this->precio, $this->descripcion, $this->imagen, $this->clasificacion, $this->editorial, $this->existencias, $this->genero);
        return Database::executeRow($sql, $params);
    }

    /*
     * Método para leer todos los registros de la tabla tb_libros.
     */
    public function readAll()
    {
        $sql = 'SELECT
            l.id_libro,
            l.titulo AS titulo_libro,
            l.descripcion AS descripcion_libro,
            l.precio,
            l.imagen,
            a.id_autor,
            a.nombre AS nombre_autor,
            c.id_clasificacion,
            c.nombre AS nombre_clasificacion,
            e.id_editorial,
            e.nombre AS nombre_editorial,
            g.id_genero,
            g.nombre AS nombre_genero,
            l.existencias
        FROM
            tb_libros AS l
        INNER JOIN
            tb_autores AS a ON l.id_autor = a.id_autor
        INNER JOIN
            tb_clasificaciones AS c ON l.id_clasificacion = c.id_clasificacion
        INNER JOIN
            tb_editoriales AS e ON l.id_editorial = e.id_editorial
        INNER JOIN
            tb_generos AS g ON l.id_genero = g.id_genero
        ORDER BY
            l.titulo;';
        return Database::getRows($sql);
    }

    /*
     * Método para leer un registro específico de la tabla tb_libros por id.
     */
    public function readOne()
    {
        $sql = 'SELECT
            l.id_libro,
            l.titulo AS titulo_libro,
            l.descripcion AS descripcion_libro,
            l.precio,
            l.imagen,
            a.id_autor,
            a.nombre AS nombre_autor,
            c.id_clasificacion,
            c.nombre AS nombre_clasificacion,
            e.id_editorial,
            e.nombre AS nombre_editorial,
            g.id_genero,
            g.nombre AS nombre_genero,
            l.existencias
        FROM
            tb_libros AS l
        INNER JOIN
            tb_autores AS a ON l.id_autor = a.id_autor
        INNER JOIN
            tb_clasificaciones AS c ON l.id_clasificacion = c.id_clasificacion
        INNER JOIN
            tb_editoriales AS e ON l.id_editorial = e.id_editorial
        INNER JOIN
            tb_generos AS g ON l.id_genero = g.id_genero
        WHERE id_libro = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    /*
     * Método para leer el nombre de archivo de la imagen de un libro.
     */
    public function readFilename()
    {
        $sql = 'SELECT imagen FROM tb_libros WHERE id_libro = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    /*
     * Método para actualizar un registro específico de la tabla tb_libros por id.
     */
    public function updateRow()
    {
        $sql = 'UPDATE tb_libros
            SET imagen = ?, titulo = ?, descripcion = ?, precio = ?, existencias = ?, id_autor = ?, id_clasificacion = ?, id_editorial = ?, id_genero = ?
            WHERE id_libro = ?';
        $params = array($this->imagen, $this->titulo, $this->descripcion, $this->precio, $this->existencias, $this->autor, $this->clasificacion, $this->editorial, $this->genero, $this->id);
        return Database::executeRow($sql, $params);
    }
    /*
     * Método para eliminar un registro específico de la tabla tb_libros por id.
     */
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_libros
            WHERE id_libro = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    /*
     * Método para actualizar existencias de la tabla tb_libros.
     */
    public function updateExistencias()
    {
        $sql = 'UPDATE tb_libros SET existencias = existencias - ? WHERE id_libro = ? AND existencias >= ?';
        $params = array($this->existencias, $this->id, $this->existencias);
        return Database::executeRow($sql, $params);
    }
    /*
     * Método para verificar existencias de la tabla tb_libros.
     */
    public function getExistencias()
    {
        $sql = 'SELECT existencias FROM tb_libros WHERE id_libro = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        if ($data) {
            return $data['existencias'];
        } else {
            return false;
        }
    }
    /*
     * Funcion para graficar
     */
    public function getLibrosMasVendidos($limit = 5)
    {
        $sql = 'SELECT tb_libros.titulo, SUM(tb_detalle_pedidos.cantidad) AS total_vendido
                FROM tb_detalle_pedidos
                INNER JOIN tb_libros ON tb_detalle_pedidos.id_libro = tb_libros.id_libro
                GROUP BY tb_libros.id_libro
                ORDER BY total_vendido DESC
                LIMIT 5';
        return Database::getRows($sql);
    }



    public function getLibrosEnExistencia()
    {
        // Consulta para obtener los libros con más existencias
        $sql = 'SELECT tb_libros.titulo, tb_libros.existencias, tb_libros.precio
                FROM tb_libros
                ORDER BY tb_libros.existencias DESC
                ';

        // Ejecuta la consulta y pasa el parámetro de límite
        return Database::getRows($sql);
    }

    // Método para obtener los datos de un libro específico por su ID
    public function getReporteLibros($id_libro)
    {
        
        $sql = 'SELECT l.id_libro, l.titulo, a.nombre AS autor, e.nombre AS editorial, g.nombre AS genero, c.nombre AS clasificacion, l.existencias, l.precio
                    FROM tb_libros l
                    INNER JOIN tb_autores a ON l.id_autor = a.id_autor
                    INNER JOIN tb_editoriales e ON l.id_editorial = e.id_editorial
                    INNER JOIN tb_generos g ON l.id_genero = g.id_genero
                    INNER JOIN tb_clasificaciones c ON l.id_clasificacion = c.id_clasificacion
                    WHERE l.id_libro = ?';
        $params = array($id_libro);
        return Database::getRows($sql, $params);
    }
      /*
 *   Métodos para generar reportes por editorial.
 */
public function getLibrosPorEditorial($id_editorial) {
    $sql = 'SELECT tb_libros.titulo, tb_autores.nombre AS autor, tb_libros.precio, tb_libros.existencias
            FROM tb_libros
            INNER JOIN tb_autores ON tb_libros.id_autor = tb_autores.id_autor
            WHERE tb_libros.id_editorial = ?
            ORDER BY tb_libros.titulo ASC';
    $params = array($id_editorial);
    return Database::getRows($sql, $params);
}
}
