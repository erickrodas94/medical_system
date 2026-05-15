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

/**
 * Calcula la edad de forma dinámica con precisión para pediatría.
 */
function calculate_age($birth_date_string) {
    if (empty($birth_date_string)) return '---';

    try {
        $birthDate = new DateTime($birth_date_string);
        $now = new DateTime();
        $diff = $birthDate->diff($now);

        // 1. Caso: 5 años o más (Solo años)
        if ($diff->y >= 5) {
            return $diff->y . ' ' . __('lbl_years');
        } 
        
        // 2. Caso: Entre 1 y 5 años (Años y Meses)
        if ($diff->y >= 1) {
            $yearsLabel = ($diff->y == 1) ? __('lbl_year') : __('lbl_years');
            $age = $diff->y . ' ' . $yearsLabel;
            
            if ($diff->m > 0) {
                $monthsLabel = ($diff->m == 1) ? __('lbl_month') : __('lbl_months');
                $age .= ' ' . __('lbl_and') . ' ' . $diff->m . ' ' . $monthsLabel;
            }
            return $age;
        } 
        
        // 3. Caso: Menos de 1 año, pero 1 mes o más
        if ($diff->m > 0) {
            return $diff->m . ' ' . (($diff->m == 1) ? __('lbl_month') : __('lbl_months'));
        } 
        
        // 4. Caso: Menos de 1 mes (Semanas o Días)
        if ($diff->d >= 7) {
            $weeks = floor($diff->d / 7);
            return $weeks . ' ' . (($weeks == 1) ? __('lbl_week') : __('lbl_weeks'));
        } 
        
        return $diff->d . ' ' . (($diff->d == 1) ? __('lbl_day') : __('lbl_days'));

    } catch (Exception $e) {
        return '---';
    }
}