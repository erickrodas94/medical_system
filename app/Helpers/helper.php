<?php
// Archivo: helpers.php

/**
 * Verifica si el usuario actual tiene un permiso específico en su sesión.
 */
function hasPermission($permissionKey) {
    if (!isset($_SESSION['user']['permissions'])) {
        return false;
    }
    
    $permissions = $_SESSION['user']['permissions'];

    // Si tiene la llave maestra, siempre devolvemos true
    if (isset($permissions['all_access']) && $permissions['all_access'] === true) {
        return true;
    }
    
    return isset($permissions[$permissionKey]) && $permissions[$permissionKey] === true;
}

/**
 * Formatea una fecha según la configuración de la clínica actual
 */
function format_date($date_string, $include_time = false) {
    if (empty($date_string)) return '---';

    try {
        $date = new DateTime($date_string);
        
        // 1. Buscamos el formato de la clínica (si no hay, usamos LATAM por defecto)
        $base_format = $_SESSION['clinic']['date_format'] ?? 'd/m/Y';
        
        // 2. Agregamos la hora si se solicita
        $format = $include_time ? $base_format . ' h:i A' : $base_format;
        
        return $date->format($format);
    } catch (Exception $e) {
        return $date_string; 
    }
}