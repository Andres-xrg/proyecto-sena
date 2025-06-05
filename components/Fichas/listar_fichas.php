<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichas - Tecnólogo/Técnico</title>
     <link rel="stylesheet" href="assets/css/listar_fichas.css">
     <link rel="stylesheet" href="assets/css/header.css">
     <link rel="stylesheet" href="assets/css/footer.css">
</head>
<body>

    <div class="container">
        <div class="titulo">
            <h1 class="title">Fichas ( Tecnólogo/ Técnico)</h1>
        </div>

        <!-- Controls -->
        <div class="controls">
            <!-- Búsqueda -->
            <div class="search-box">
                <input type="text" placeholder="Consultar..." id="searchInput">
                <span class="search-icon">🔍</span>
            </div>

            <!-- Dropdown -->
            <div class="dropdown-container">
                <div class="dropdown" onclick="toggleDropdown()">
                    <span>Selecciona el Horario De Jornada...</span>
                    <span class="arrow">▼</span>
                </div>
                <div class="dropdown-options" id="dropdownOptions">
                    <div class="option">Mañana</div>
                    <div class="option">Mixta</div>
                    <div class="option">Nocturna</div>
                </div>
            </div>
        </div>

                    <!-- Grid de Fichas -->
            <div class="fichas-grid">
                <!-- Ejemplo de varias fichas -->
                <div class="ficha-card">
                    <div class="card-header">
                        <span class="numero">2845655</span>
                        <div class="sena-logo">⚙</div>
                    </div>
                    <button class="btn-ver-ficha" onclick="verFicha()">Ver ficha</button>
                    <button class="btn-deshabilitar" onclick="toggleFicha(this)">Deshabilitar</button>
                </div>

                <div class="ficha-card">
                    <div class="card-header">
                        <span class="numero">2845655</span>
                        <div class="sena-logo">⚙</div>
                    </div>
                    <button class="btn-ver-ficha" onclick="verFicha()">Ver ficha</button>
                    <button class="btn-deshabilitar" onclick="toggleFicha(this)">Deshabilitar</button>
                </div>

                <div class="ficha-card ">
                    <div class="card-header">
                        <span class="numero">2845655</span>
                        <div class="sena-logo">⚙</div>
                    </div>
                    <button class="btn-ver-ficha" onclick="verFicha()">Ver ficha</button>
                    <button class="btn-deshabilitar" onclick="toggleFicha(this)">Deshabilitar</button>
                </div>

                <div class="ficha-card">
                    <div class="card-header">
                        <span class="numero">2845655</span>
                        <div class="sena-logo">⚙</div>
                    </div>
                    <button class="btn-ver-ficha" onclick="verFicha()">Ver ficha</button>
                    <button class="btn-deshabilitar" onclick="toggleFicha(this)">Deshabilitar</button>
                </div>

                <div class="ficha-card">
                    <div class="card-header">
                        <span class="numero">2845655</span>
                        <div class="sena-logo">⚙</div>
                    </div>
                    <button class="btn-ver-ficha" onclick="verFicha()">Ver ficha</button>
                    <button class="btn-deshabilitar" onclick="toggleFicha(this)">Deshabilitar</button>
                </div>

                <div class="ficha-card disabled">
                    <div class="card-header">
                        <span class="numero">2845655</span>
                        <div class="sena-logo">⚙</div>
                    </div>
                    <button class="btn-ver-ficha disabled">Ver ficha</button>
                    <button class="btn-habilitar" onclick="toggleFicha(this)">Habilitar</button>
                </div>
                <div class="ficha-card">
                    <div class="card-header">
                        <span class="numero">2845655</span>
                        <div class="sena-logo">⚙</div>
                    </div>
                    <button class="btn-ver-ficha" onclick="verFicha()">Ver ficha</button>
                    <button class="btn-deshabilitar" onclick="toggleFicha(this)">Deshabilitar</button>
                </div>

                <div class="ficha-card">
                    <div class="card-header">
                        <span class="numero">2845655</span>
                        <div class="sena-logo">⚙</div>
                    </div>
                    <button class="btn-ver-ficha" onclick="verFicha()">Ver ficha</button>
                    <button class="btn-deshabilitar" onclick="toggleFicha(this)">Deshabilitar</button>
                </div>

                <div class="ficha-card disabled">
                    <div class="card-header">
                        <span class="numero">2845655</span>
                        <div class="sena-logo">⚙</div>
                    </div>
                    <button class="btn-ver-ficha disabled">Ver ficha</button>
                    <button class="btn-habilitar" onclick="toggleFicha(this)">Habilitar</button>
                </div>

                <div class="ficha-card disabled">
                    <div class="card-header">
                        <span class="numero">2845655</span>
                        <div class="sena-logo">⚙</div>
                    </div>
                    <button class="btn-ver-ficha disabled">Ver ficha</button>
                    <button class="btn-habilitar" onclick="toggleFicha(this)">Habilitar</button>
                </div>
            </div>
            
    </div>

    <script src="assets/js/listar_fichas.js"></script>
</body>
</html>