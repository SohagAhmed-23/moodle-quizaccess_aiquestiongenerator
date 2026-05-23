<?php

namespace quizaccess_aiquestiongenerator\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/question/editlib.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');

use external_function_parameters;
use external_value;
use external_single_structure;
use external_api;
use context_course;
use question_bank;
use stdClass;

class add_question extends external_api {

    public static function execute_parameters() {
        return new external_function_parameters([
            'qtype'     => new external_value(PARAM_TEXT, 'Question type (multichoice, truefalse, shortanswer)'),
            'question'  => new external_value(PARAM_TEXT, 'Question text'),
            'options'   => new external_value(PARAM_RAW, 'JSON encoded array of options or answers'),
            'answer'    => new external_value(PARAM_RAW, 'Correct answer index or text'),
            'courseid'  => new external_value(PARAM_INT, 'Course ID'),
            'quizid'    => new external_value(PARAM_INT, 'Quiz ID'),
        ]);
    }

    public static function execute($qtype, $question, $options, $answer, $courseid, $quizid) {
        global $USER, $DB;

        self::validate_parameters(self::execute_parameters(), compact('qtype', 'question', 'options', 'answer', 'courseid', 'quizid'));

        $context = context_course::instance($courseid);
        $category = question_get_default_category($context->id);

        $formdata = new stdClass();
        $formdata->category = $category->id;
        $formdata->name = $question;
        $formdata->questiontext = ['text' => $question, 'format' => FORMAT_HTML];
        $formdata->generalfeedback = ['text' => '', 'format' => FORMAT_HTML];
        $formdata->qtype = $qtype;
        $formdata->defaultmark = 1;
        $formdata->penalty = 0;
        $formdata->status = 'ready';

        $formdata->correctfeedback = ['text' => '', 'format' => FORMAT_HTML];
        $formdata->partiallycorrectfeedback = ['text' => '', 'format' => FORMAT_HTML];
        $formdata->incorrectfeedback = ['text' => '', 'format' => FORMAT_HTML];

        $decodedOptions = json_decode($options, true);

        switch ($qtype) {
            case 'multichoice':
                $formdata->single = 1;
                $formdata->shuffleanswers = 1;
                $formdata->answernumbering = 'abc';

                $formdata->answer = [];
                $formdata->fraction = [];
                $formdata->feedback = [];

                foreach ($decodedOptions as $i => $opt) {
                    $formdata->answer[] = ['text' => $opt, 'format' => FORMAT_HTML];
                    $formdata->fraction[] = ((int)$i === (int)$answer) ? 1.0 : 0.0;
                    $formdata->feedback[] = ['text' => '', 'format' => FORMAT_HTML];
                }

                break;

            case 'truefalse':
                $formdata->correctanswer = strtolower($answer) === 'true' ? 1 : 0;
                $formdata->feedbacktrue = ['text' => '', 'format' => FORMAT_HTML];
                $formdata->feedbackfalse = ['text' => '', 'format' => FORMAT_HTML];
                break;

            case 'shortanswer':
                $formdata->usecase = false;
                $formdata->answer = [$answer];
                $formdata->fraction = ['1.0'];
                $formdata->feedback = [['text' => '', 'format' => FORMAT_HTML]];
                break;

            default:
                throw new \moodle_exception('Unsupported question type: ' . $qtype);
        }

        $newquestion = new stdClass();
        $newquestion->category = $formdata->category;
        $newquestion->qtype = $formdata->qtype;
        $newquestion->createdby = $USER->id;

        $qtypeobj = question_bank::get_qtype($qtype);
        $questionobj = $qtypeobj->save_question($newquestion, $formdata);
        $questionid = $questionobj->id;

        // Add to quiz
        $quiz = $DB->get_record('quiz', ['id' => $quizid], '*', MUST_EXIST);
        quiz_add_quiz_question($questionid, $quiz, $page = 0, $maxmark = 1.0 );
        quiz_update_sumgrades($quiz);


        return ['status' => "Question '{$questionobj->name}' added successfully with ID: $questionid"];
    }

    public static function execute_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Status message')
        ]);
    }
}
