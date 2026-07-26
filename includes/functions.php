<?php
/** Escape output safely */
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/** Format a date like "12 Jan 2025" */
function fdate($date) {
    if (!$date) return '';
    return date('d M Y', strtotime($date));
}

/** Truncate text to N words */
function excerpt($text, $words = 20) {
    $arr = explode(' ', strip_tags($text ?? ''));
    if (count($arr) <= $words) return strip_tags($text ?? '');
    return implode(' ', array_slice($arr, 0, $words)) . '…';
}
