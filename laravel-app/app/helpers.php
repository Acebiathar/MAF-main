<?php

/**
 * Application Helper Functions
 *
 * This file contains various helper functions used throughout the application.
 */

use Illuminate\Support\Facades\DB;

if (!function_exists('currentUser')) {
    /**
     * Get the currently authenticated user from session
     *
     * @return \stdClass|null
     */
    function currentUser()
    {
        $userId = session('user_id');
        return $userId ? DB::table('users')->where('id', $userId)->first() : null;
    }
}

if (!function_exists('flash')) {
    /**
     * Flash a message to the session
     *
     * @param string $category
     * @param string $message
     * @return void
     */
    function flash($category, $message)
    {
        session()->push('alerts', [$category, $message]);
    }
}

if (!function_exists('renderView')) {
    /**
     * Render a view with common data
     *
     * @param string $view
     * @param array $data
     * @return \Illuminate\View\View
     */
    function renderView(string $view, array $data = [])
    {
        $data['currentUser'] = currentUser();
        $data['alerts'] = session()->pull('alerts', []);
        return view($view, $data);
    }
}

if (!function_exists('formatPrice')) {
    /**
     * Format a price value
     *
     * @param float $price
     * @param string $currency
     * @return string
     */
    function formatPrice($price, $currency = 'UGX')
    {
        return number_format($price, 0, '.', ',') . ' ' . $currency;
    }
}

if (!function_exists('isActiveRoute')) {
    /**
     * Check if the current route matches the given route name
     *
     * @param string $routeName
     * @return string
     */
    function isActiveRoute($routeName)
    {
        return request()->routeIs($routeName) ? 'active' : '';
    }
}

if (!function_exists('getMedicineCategories')) {
    /**
     * Get all medicine categories
     *
     * @return \Illuminate\Support\Collection
     */
    function getMedicineCategories()
    {
        return DB::table('medicines')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->get()
            ->pluck('category');
    }
}

if (!function_exists('getApprovedPharmacies')) {
    /**
     * Get all approved pharmacies
     *
     * @return \Illuminate\Support\Collection
     */
    function getApprovedPharmacies()
    {
        return DB::table('pharmacies')
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();
    }
}

if (!function_exists('truncateText')) {
    /**
     * Truncate text to a specified length
     *
     * @param string $text
     * @param int $length
     * @param string $suffix
     * @return string
     */
    function truncateText($text, $length = 100, $suffix = '...')
    {
        return strlen($text) > $length ? substr($text, 0, $length) . $suffix : $text;
    }
}

if (!function_exists('generateSlug')) {
    /**
     * Generate a URL-friendly slug from a string
     *
     * @param string $text
     * @return string
     */
    function generateSlug($text)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
    }
}

if (!function_exists('getUserRole')) {
    /**
     * Get the role of the current user
     *
     * @return string|null
     */
    function getUserRole()
    {
        $user = currentUser();
        return $user ? $user->role : null;
    }
}

if (!function_exists('isAdmin')) {
    /**
     * Check if current user is an admin
     *
     * @return bool
     */
    function isAdmin()
    {
        return getUserRole() === 'admin';
    }
}

if (!function_exists('isPharmacist')) {
    /**
     * Check if current user is a pharmacist
     *
     * @return bool
     */
    function isPharmacist()
    {
        return getUserRole() === 'pharmacist';
    }
}

if (!function_exists('isPatient')) {
    /**
     * Check if current user is a patient
     *
     * @return bool
     */
    function isPatient()
    {
        return getUserRole() === 'patient';
    }
}
