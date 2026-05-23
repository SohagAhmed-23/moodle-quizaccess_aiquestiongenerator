<?php
defined('MOODLE_INTERNAL') || die();
$capabilities = [
    'quizaccess/aiquestiongenerator:generatequestions' => [
        'captype'           => 'write',
        'contextlevel'      => CONTEXT_MODULE,
        'archetypes'        => [
            'editingteacher'    => CAP_ALLOW,
            'manager'           => CAP_ALLOW,
        ],
    ],
    'quizaccess/aiquestiongenerator:addquestions' => [
        'captype'           => 'write',
        'contextlevel'      => CONTEXT_MODULE,
        'archetypes'        => [
            'editingteacher'    => CAP_ALLOW,
            'manager'           => CAP_ALLOW,
        ],
    ],
    
];