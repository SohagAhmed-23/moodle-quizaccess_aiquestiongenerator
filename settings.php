<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage(
        'quizaccess_aiquestiongenerator',
        get_string('pluginname', 'quizaccess_aiquestiongenerator')
    );

    // Description (no heading).
    $settings->add(new admin_setting_heading(
        'quizaccess_aiquestiongenerator/description',
        '',
        get_string('plugindescription', 'quizaccess_aiquestiongenerator')
    ));


    // Enable/Disable.
    $settings->add(new admin_setting_configcheckbox(
        'quizaccess_aiquestiongenerator/enable',
        get_string('enable', 'quizaccess_aiquestiongenerator'),
        get_string('enable_desc', 'quizaccess_aiquestiongenerator'),
        0
    ));

    // API provider select.
    $settings->add(new admin_setting_configselect(
        'quizaccess_aiquestiongenerator/apiprovider',
        get_string('apiprovider', 'quizaccess_aiquestiongenerator'),
        get_string('apiprovider_desc', 'quizaccess_aiquestiongenerator'),
        'openai',
        [
            'openai'    => 'OpenAI',
            'anthropic' => 'Claude',
            'cohere'    => 'Cohere',
        ]
    ));

    // OpenAI Secret.
    $settings->add(new admin_setting_configpasswordunmask(
        'quizaccess_aiquestiongenerator/openai_secret',
        get_string('openai_secret', 'quizaccess_aiquestiongenerator'),
        get_string('openai_secret_desc', 'quizaccess_aiquestiongenerator'),
        ''
    ));

    $settings->add(new admin_setting_configtext('quizaccess_aiquestiongenerator/awskey',
        get_string('setting:aws_key', 'quizaccess_aiquestiongenerator'),
        get_string('setting:aws_keydesc', 'quizaccess_aiquestiongenerator'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('quizaccess_aiquestiongenerator/awssecret',
        get_string('setting:aws_secret', 'quizaccess_aiquestiongenerator'),
        get_string('setting:aws_secretdesc', 'quizaccess_aiquestiongenerator'), '', PARAM_TEXT));


}
