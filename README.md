# MyNetflix - Plataforma de Vídeo en Streaming 🎬

## Autores: 
- **Oriol Godoy** 👨‍💻
- **Eric Alcázar** 👨‍💻

## Descripción 📝

MyNetflix es una plataforma de vídeo en streaming donde los usuarios pueden ver contenidos, dar "likes" a películas, y filtrarlas según diferentes criterios. La plataforma tiene dos tipos de usuarios:

- **Cliente**: Puede interactuar con el catálogo, dar likes y filtrar contenido.
- **Administrador**: Tiene acceso a la gestión de usuarios y películas, incluyendo validación de registros, activación/desactivación de usuarios y edición de catálogos de películas.

## Objetivos 🎯

- Crear una plataforma de vídeo en streaming con un diseño responsive.
- Implementar una parte pública y una privada.
- Gestionar usuarios y películas en la parte privada.
- Utilizar AJAX para crear usuarios y trabajar con filtros y "likes".
- Subir pósters (carátulas) de las películas a un servidor.

## Funcionalidades 🚀

### 1. Parte Pública

- **TOP5 de Películas**: Visualización de las películas más destacadas de la plataforma.
- **Catálogo de Películas**: Se muestra un catálogo con las películas ordenadas por popularidad (número de likes).
- **Login/Registro**: Los usuarios registrados pueden acceder a su cuenta. Los nuevos usuarios deben ser validados por un administrador.

### 2. Parte Privada

#### 2.1 **Administrador**:
- Validar usuarios que se registren.
- Activar o desactivar usuarios registrados.
- Ver el catálogo de películas ordenado por nombre o likes.
- Añadir, eliminar y modificar películas del catálogo.
- Acceder a un filtro rápido de películas.

#### 2.2 **Cliente**:
- Ver todo el catálogo de películas.
- Dar "like" a las películas.
- Filtrar las películas por "like" del propio usuario.
- Acceder a un buscador avanzado con múltiples filtros simultáneos.

## Estructura de la Base de Datos 📊

La base de datos está estructurada para gestionar tanto los usuarios como las películas. Incluye tablas para usuarios, películas, géneros, likes y carteleras.

### Tablas:
1. **Usuarios**: Gestiona los datos de los usuarios (nombre, correo, rol, estado, etc.).
2. **Películas**: Información sobre las películas (título, descripción, director, etc.).
3. **Likes**: Relaciona a los usuarios con las películas que han marcado como favoritas.
4. **Géneros**: Clasifica las películas por géneros (acción, comedia, etc.).
5. **Carteleras**: Información sobre las películas disponibles en la plataforma.

## Características Técnicas 🛠️

- **Lenguaje Backend**: PHP
- **Base de Datos**: MySQL
- **Conexiones seguras**: PDO (Protección contra inyección SQL y uso de BindParams).
- **AJAX**: Para la creación de usuarios, filtrado y gestión de "likes".
- **Subida de Archivos**: Implementación de subida de imágenes (pósters) de las películas.

## Diseño y Prototipo 🎨

La interfaz es **mobile-first** y adaptativa. El diseño se basa en un esquema libre, sin plantillas predefinidas. La página incluye un **TOP5** de películas en una fila, un catálogo de películas en formato **grid** y una barra de navegación intuitiva.

### **Responsive**
El layout es completamente adaptable a diferentes tamaños de pantalla (móviles, tablets, escritorios).
