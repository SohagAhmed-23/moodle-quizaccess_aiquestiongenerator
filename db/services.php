<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


defined('MOODLE_INTERNAL') || die();

$functions = [
    'quizaccess_aiquestiongenerator_generate_questions' => [
        'classname'   => 'quizaccess_aiquestiongenerator\external\generate_questions',
        'methodname'  => 'execute',
        'classpath'   => '',
        'description' => 'Generate quiz questions using OpenAI based on description',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities' => 'quizaccess/aiquestiongenerator:generatequestions',
        'services'     => [MOODLE_OFFICIAL_MOBILE_SERVICE],
        'loginrequired' => true,
    ],
    'quizaccess_aiquestiongenerator_add_question' => [
        'classname'   => 'quizaccess_aiquestiongenerator\external\add_question',
        'methodname'  => 'execute',
        'classpath'   => '',
        'description' => 'Add a question to the quiz',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities' => 'quizaccess/aiquestiongenerator:addquestions',
        'services'     => [MOODLE_OFFICIAL_MOBILE_SERVICE],
        'loginrequired' => true,
    ],
];
