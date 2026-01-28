<?php
require_once __DIR__ . '/../config/db.php';

class Producto {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // LISTAR TODOS (Para el admin, mostramos activos e inactivos)
    public function obtenerTodos() {
        $stmt = $this->pdo->query("
            SELECT p.*, c.nombre as categoria 
            FROM productos p
            JOIN categorias c ON p.id_categoria = c.id_categoria
            ORDER BY p.activo DESC, p.nombre ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // LISTAR PARA VENTA (Solo los activos)
    public function obtenerActivos() {
        $stmt = $this->pdo->query("SELECT * FROM productos WHERE stock > 0 AND activo = 1 ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // OBTENER UNO SOLO (Para cuando le das a "Editar", necesitamos llenar el formulario)
    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE id_producto = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CREAR (Igual que antes)
    public function crear($nombre, $costo, $precio, $stock, $categoria) {
        $sql = "INSERT INTO productos (nombre, precio_costo, precio_venta, stock, id_categoria, activo) 
                VALUES (:nom, :cost, :prec, :stk, :cat, 1)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nom' => $nombre, 'cost' => $costo, 'prec' => $precio, 'stk' => $stock, 'cat' => $categoria
        ]);
    }

    // ACTUALIZAR (El UPDATE que faltaba)
    public function actualizar($id, $nombre, $costo, $precio, $stock, $categoria) {
        $sql = "UPDATE productos SET 
                nombre = :nom, 
                precio_costo = :cost, 
                precio_venta = :prec, 
                stock = :stk, 
                id_categoria = :cat 
                WHERE id_producto = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nom' => $nombre, 'cost' => $costo, 'prec' => $precio, 'stk' => $stock, 'cat' => $categoria, 'id' => $id
        ]);
    }

    // BAJA LÓGICA (Soft Delete)
    public function darDeBaja($id) {
        // En lugar de DELETE, hacemos UPDATE del estado
        $stmt = $this->pdo->prepare("UPDATE productos SET activo = 0 WHERE id_producto = :id");
        return $stmt->execute(['id' => $id]);
    }

    // ALTA LÓGICA (Por si te arrepentís y querés reactivarlo)
    public function reactivar($id) {
        $stmt = $this->pdo->prepare("UPDATE productos SET activo = 1 WHERE id_producto = :id");
        return $stmt->execute(['id' => $id]);
    }

    // ... (otros métodos) ...

    // BUSCADOR: Trae productos activos que coincidan con el nombre
    public function buscarActivos($termino) {
        // Usamos los comodines % para buscar en cualquier parte del texto
        $termino = "%" . $termino . "%";

        $sql = "SELECT * FROM productos 
                WHERE stock > 0 
                AND activo = 1 
                AND nombre LIKE :termino 
                ORDER BY nombre";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['termino' => $termino]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>