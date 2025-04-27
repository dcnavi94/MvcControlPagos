<?php

/**
 * Rutas limpias del sistema
 * Formato: '/ruta' => ['Controlador', 'metodo']
 */

return [

    // 🌐 Páginas públicas
    '/'                     => ['AuthController', 'index'],
    '/login'                => ['AuthController', 'login'],
    '/login/auth'           => ['AuthController', 'authenticate'],
    '/logout'               => ['AuthController', 'logout'],

    // 👤 Alumno
   '/alumno/pagos' => ['AlumnoController', 'pagos'],
'/alumno/perfil' => ['AlumnoController', 'perfil'],
    '/alumno/guardarPerfil' => ['AlumnoController', 'guardarPerfil'],

           // opcional
    '/alumno/comprobante'   => ['AlumnoController', 'comprobante'],      // opcional

    // 🛠 Panel Admin
    '/dashboard'                => ['DashboardController', 'index'],
    '/dashboard/admin'                => ['DashboardController', 'index'],
    '/dashboard/usuarios'       => ['DashboardController', 'usuarios'],
    '/dashboard/crearusuario'   => ['DashboardController', 'crear'],
    '/dashboard/guardarusuario' => ['DashboardController', 'guardar'],
    '/dashboard/editarUsuario'  => ['DashboardController', 'editar'],
    '/dashboard/eliminarUsuario'=> ['DashboardController', 'eliminar'],

    // 📊 Reportes
    '/dashboard/reportes'       => ['DashboardController', 'reportes'],
    '/dashboard/generarReporte' => ['ReporteController', 'generar'],
    '/dashboard/generar-reporte' => ['DashboardController', 'generarReporte'],

    // 💵 Pagos (admin o tesorería)
    '/dashboard/pagos'              => ['DashboardController', 'pagos'],
    '/dashboard/pagos/registrar'    => ['PagoController', 'registrar'],
    '/dashboard/pagos/ver'          => ['PagoController', 'ver'],
    '/dashboard/pagos/eliminar'     => ['PagoController', 'eliminar'],

    // ⚙️ Configuración o módulos adicionales
    '/dashboard/carreras'           => ['CarreraController', 'index'],
    '/dashboard/grupos'             => ['GrupoController', 'index'],

    // 🧪 Test / Ejemplo
    '/test'                     => ['TestController', 'index'],
];
