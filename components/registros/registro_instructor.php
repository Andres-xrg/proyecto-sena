<?php
if (!ACCESO_PERMITIDO){
    header("Location: /proyecto-sena/components/principales/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/registro_instructor.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <title><?= $translations['register_instructor'] ?> - SENA</title>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <main class="main-content">
        <div class="form-container">
            <h1 class="form-title"><?= $translations['register_instructor'] ?></h1>

            <form method="post" action="functions/functions_registro_instructor.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre"><?= $translations['name'] ?></label>
                        <input type="text" id="nombre" name="nombre" required placeholder="<?= $translations['user_name'] ?>"
                               pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios"
                               oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')">
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido"><?= $translations['lastname'] ?></label>
                        <input type="text" id="apellido" name="apellido" required placeholder="<?= $translations['user_lastname'] ?>"
                               pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios"
                               oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tipoDocumento"><?= $translations['document_type'] ?></label>
                        <select id="tipoDocumento" name="tipoDocumento" required>
                            <option value=""><?= $translations['select_doc_type'] ?></option>
                            <option value="CC">Cédula</option>
                            <option value="CE">Cédula Extranjera</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="numeroDocumento"><?= $translations['document_number'] ?></label>
                        <input type="number" id="numeroDocumento" name="numeroDocumento" required 
                               placeholder="<?= $translations['document_number'] ?>" min="1" 
                               oninput="validarNoNegativos(this)">
                    </div>
                    <div class="form-group">
                        <label for="instructor"><?= $translations['instructors'] ?></label>
                        <select id="instructor" name="instructor" required onchange="toggleFechasContrato()">
                            <option value="">Seleccione tipo instructor</option>
                            <option value="contratista">Contratista</option>
                            <option value="planta">Instructor planta</option>
                        </select>
                    </div>
                </div>

                <!-- 📌 Campo: Rol del instructor -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="rol_instructor">Rol del Instructor</label>
                        <select id="rol_instructor" name="rol_instructor" required>
                            <option value="">Seleccione un rol</option>
                            <option value="clave">Clave</option>
                            <option value="transversal">Transversal</option>
                            <option value="tecnico">Técnico</option>
                        </select>
                    </div>
                </div>

                <div class="form-row" id="fechas-contrato" style="display:none;">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de inicio del contrato</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio">
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin">Fecha de fin del contrato</label>
                        <input type="date" id="fecha_fin" name="fecha_fin">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono"><?= $translations['phone'] ?></label>
                        <input type="number" id="telefono" name="telefono" required 
                               placeholder="<?= $translations['user_phone'] ?>" min="1"
                               oninput="validarNoNegativos(this)">
                    </div>
                    <div class="form-group">
                        <label for="correo"><?= $translations['email'] ?></label>
                        <input type="email" id="correo" name="Email" required placeholder="<?= $translations['access_email'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="contraseña"><?= $translations['password'] ?? 'Contraseña' ?></label>
                        <input type="password" id="contraseña" name="contraseña" required placeholder="<?= $translations['password'] ?? 'Ingrese su contraseña' ?>">
                    </div>
                </div>

                <button type="submit" class="register-btn"><?= $translations['register'] ?></button>
            </form>
        </div>
    </main>

    <script>
    function toggleFechasContrato() {
        const tipo = document.getElementById('instructor').value;
        const fechas = document.getElementById('fechas-contrato');
        fechas.style.display = tipo === 'contratista' ? 'flex' : 'none';
    }

    // 🔴 Validar que no se ingresen negativos
    function validarNoNegativos(input) {
        if (input.value < 0) {
            input.value = "";
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se permiten valores negativos en este campo.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        }
    }

    // 📌 Mostrar alertas de errores desde PHP
    <?php if (isset($_GET['estado']) && $_GET['estado'] === "error" && isset($_GET['mensajes'])): 
        $mensajes = explode('|', $_GET['mensajes']); ?>
        const errores = <?= json_encode($mensajes) ?>;
        errores.forEach(msg => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg,
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        });
    <?php endif; ?>

    // 📌 Mostrar alerta de éxito
    <?php if (isset($_GET['estado']) && $_GET['estado'] === "success" && isset($_GET['mensaje'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '<?= htmlspecialchars($_GET['mensaje']) ?>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#28a745'
        });
    <?php endif; ?>

    if (window.history.replaceState) {
        const url = new URL(window.location.href);
        url.searchParams.delete("estado");
        url.searchParams.delete("mensajes");
        url.searchParams.delete("mensaje");
        window.history.replaceState({}, document.title, url.toString());
    }
    </script>
</body>
</html>
