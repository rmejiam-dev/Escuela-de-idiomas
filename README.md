# Sistema de Gestión de Trámites - Escuela de Idiomas

## Propósito

Plataforma integral para la gestión de trámites académicos, diseñada para automatizar y agilizar los procesos de certificación en una institución educativa.

---

## ¿Qué resuelve?

- Digitalización completa del flujo de trámites
- Procesos automatizados con seguimiento en tiempo real
- Validaciones y registro de historial de acciones
- Dashboard con gráficas y estados claros
- Generación automática de certificados con hash de verificación

---

## Flujo del Sistema

### Estudiante
- Crea solicitud de certificado
- Visualiza estado de sus trámites

### Secretaría
- Revisa documentación del estudiante
- Aprueba o envía a observación

### Finanzas
- Verifica comprobante de pago
- Registra monto y método de pago

### Revisión Académica
- Registra notas mediante archivo Excel
- Calcula promedio final

### Firmas Digitales
- Agrega firmas de autoridades
- Sube imagen de firma con hash de verificación

### Completado
- Genera certificado en PDF
- Descarga del documento oficial

---

## Características Técnicas

- **Framework:** Laravel 11
- **Interfaz dinámica:** Livewire 3
- **Estilos:** Tailwind CSS
- **Base de datos:** MySQL
- **Generación de PDF:** DomPDF
- **QR Code:** QuickChart API
- **Autenticación:** Laravel Auth
- **Roles y Permisos:** Spatie Permission

---

## Módulos Principales

- Gestión de usuarios (CRUD con roles)
- Gestión de trámites (creación, edición, workflow)
- Verificación de pagos (sencillo)
- Carga de notas desde Excel (PhpSpreadsheet)
- Firmas digitales (imagen + hash)
- Dashboard con métricas y gráficas
- Historial de movimientos por trámite
- Preinscripción pública
- Reportes y estadísticas

---

## Permisos del Sistema

| Permiso | Descripción |
|---------|-------------|
| view dashboard | Acceso al panel principal |
| view own procedures | Ver solo sus trámites |
| view all procedures | Ver todos los trámites |
| create procedures | Crear nuevos trámites |
| review procedures | Revisar y aprobar trámites |
| edit procedures | Editar cualquier trámite |
| edit own procedures | Editar sus propios trámites |
| sign procedures | Firmar documentos |
| manage users | Gestionar usuarios |
| manage roles | Gestionar roles y permisos |
| verify payments | Verificar pagos |
| view reports | Ver reportes y estadísticas |
| manage pre_enrollments | Gestionar preinscripciones |

---

## Instalación

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve