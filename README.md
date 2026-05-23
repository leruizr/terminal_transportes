# Terminal de Transportes

Sistema web para la gestión y consulta de rutas de transporte terrestre intermunicipal. Desarrollado con PHP + MySQL sobre AppServ.

**Repositorio:** https://github.com/leruizr/terminal_transportes.git

---

## Requisitos

- **AppServ** (Apache + PHP + MySQL + phpMyAdmin) instalado y en ejecución
- **Git** instalado (solo si se obtiene el proyecto clonando el repositorio)
- Navegador moderno

---

## Instalación

### 1. Obtener el proyecto

Tienes dos formas de obtener el proyecto. En ambos casos debe quedar en `C:\AppServ\www\terminal_completo`.

**Opción A — Clonar el repositorio (recomendado)**

Abrir una terminal en `C:\AppServ\www\` y ejecutar:

```bash
git clone https://github.com/leruizr/terminal_transportes.git terminal_completo
```

**Opción B — Descargar ZIP**

Entrar al repositorio, presionar **Code → Download ZIP**, descomprimir y copiar la carpeta resultante a `C:\AppServ\www\terminal_completo`.

### 2. Crear la base de datos

Abrir phpMyAdmin (`http://localhost:8888/phpmyadmin`) → pestaña **Importar** → seleccionar `database/schema.sql` → **Continuar**.

Esto crea la base de datos `terminal_transportes` con todas las tablas y datos iniciales (empresas, ciudades, tipos de vehículo y rutas).

### 3. Configurar credenciales

Las credenciales NO están en el código fuente, se manejan con un archivo `.env` (excluido del repositorio mediante `.gitignore`). Por eso, al clonar o descargar el proyecto este archivo **no existe** y hay que crearlo:

1. En la raíz del proyecto, copiar `.env.example` y renombrar la copia a `.env`.
2. Editar `.env` con tus datos de MySQL:

   ```
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=tu_contraseña_de_mysql
   DB_NAME=terminal_transportes
   ```

> El loader `app/includes/env.php` lee este archivo y carga las variables que `app/includes/db.php` consume con `getenv()`.

### 4. Abrir el proyecto

```
http://localhost:8888/terminal_completo/app/index.php
```

---

## Acceso al panel de administración

Desde el menú principal → enlace **Admin** → `login.php`.

| Usuario | Contraseña |
|---------|------------|
| `admin` | `admin1234` |

Desde el panel se pueden **crear, editar y eliminar vehículos y rutas**. Los cambios se reflejan en tiempo real en las páginas públicas. La sesión se cierra con el botón **"Cerrar sesión"**.

> Si se intenta abrir `admin.php` sin sesión activa, el sistema redirige a `login.php`.

---

## Páginas del sitio

| Página | Descripción |
|--------|-------------|
| `index.php` | Inicio con buscador de rutas |
| `nosotros.php` | Historia, misión, visión y servicios |
| `empresas.php` | Empresas de transporte vinculadas |
| `vehiculos.php` | Tipos de vehículo con capacidad y comodidades |
| `rutas.php` | Destinos agrupados por región |
| `horarios.php` | Horarios de salida por ciudad |
| `disponibilidad.php` | Asientos disponibles por destino/fecha |
| `cotizacion.php` | Cálculo de tarifas y compra de tiquetes |
| `resultados.php` | Resultados de búsqueda del inicio |
| `contacto.php` | Formulario de contacto |
| `login.php` / `admin.php` | Panel administrativo (privado) |

---

## Estructura del proyecto

```
terminal_completo/
├── .env.example        # Plantilla de variables de entorno
├── .gitignore          # Excluye .env, IDE, logs, etc.
├── app/
│   ├── includes/
│   │   ├── env.php     # Carga variables desde .env
│   │   ├── db.php      # Conexión a MySQL (lee credenciales con getenv)
│   │   ├── header.php  # Header + metaetiquetas SEO y Open Graph
│   │   └── footer.php
│   ├── index.php, nosotros.php, empresas.php, vehiculos.php,
│   ├── rutas.php, horarios.php, disponibilidad.php, cotizacion.php,
│   ├── resultados.php, contacto.php, login.php, admin.php
├── css/styles.css      # Estilos + media query responsive
├── database/schema.sql # Script de BD + datos iniciales
├── documentacion/      # Anexos del proyecto
├── sustentacion/       # Video y presentación PowerPoint de la sustentación final
├── img/                # Imágenes
├── js/script.js        # JS del buscador
├── robots.txt          # Reglas para motores de búsqueda
├── sitemap.xml         # Mapa del sitio (SEO)
└── README.md
```

---

## Base de datos

5 tablas: `empresas` (7 registros), `ciudades` (15), `tipos_vehiculo` (7), `rutas` (30) y `tiquetes` (compras realizadas).

---

## SEO

- Metaetiquetas (`description`, `keywords`, Open Graph, Twitter Card) configurables por página desde `app/includes/header.php`.
- Diseño responsive con media query (`@media (max-width: 768px)`) en `css/styles.css`.
- `robots.txt` y `sitemap.xml` en la raíz del proyecto. El panel admin está excluido del rastreo.

---

## Sustentación

La carpeta `sustentacion/` contiene los materiales finales del proyecto:

- **Video de sustentación** — grabación con la presentación del proyecto y la demostración de su funcionamiento.
- **Presentación PowerPoint** — diapositivas usadas como guion durante la sustentación.

---

## Seguridad

- Credenciales de BD en `.env` (fuera del código y del repositorio).
- Sesiones PHP para proteger el panel administrativo.
- **Prepared statements** con `bind_param` en todas las consultas del admin (previene SQL injection).
- **Escape de salida** con `htmlspecialchars` en todas las vistas (previene XSS).
- Cascada FK en la BD: eliminar un vehículo/empresa/ciudad elimina sus rutas asociadas.
