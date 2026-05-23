<?php
defined('MOODLE_INTERNAL') || die();

// Load correct base class depending on Moodle version.
if (class_exists('\mod_quiz\local\access_rule_base')) {
    // Moodle 4.2 or higher.
    class_alias('\mod_quiz\local\access_rule_base', 'quizaccess_aiquestiongenerator_parent');
    class_alias('\mod_quiz\form\preflight_check_form', 'quizaccess_aiquestiongenerator_preflight_form_alias');
} else {
    // Older Moodle versions.
    require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');
    class_alias('\quiz_access_rule_base', 'quizaccess_aiquestiongenerator_parent');
    class_alias('\mod_quiz_preflight_check_form', 'quizaccess_aiquestiongenerator_preflight_form_alias');
}
class quizaccess_aiquestiongenerator extends quizaccess_aiquestiongenerator_parent {
    /**
     * Determine if the access rule should be applied to the quiz.
     *
     * @param quiz $quizobj Quiz object.
     * @param int $timenow Current timestamp.
     * @param bool $canignoretimelimits Flag to check if time limits can be ignored.
     * @return quiz_access_rule_base|null Returns an instance of the rule or null.
     */
    public static function make($quizobj, $timenow, $canignoretimelimits) {
        return new self($quizobj, $timenow);
    }

    public function description() {
        global $OUTPUT, $PAGE;
        echo $OUTPUT->render_from_template('quizaccess_aiquestiongenerator/modal', []);

        // Load the JavaScript module that handles the modal interaction.
        $PAGE->requires->js_call_amd('quizaccess_aiquestiongenerator/modalhandler', 'init');
    
        // Button for the quiz view page.
        $button = [
            $this->get_download_config_button(),
        ];

        return $button;
    }

    /**
     * Optionally show a button to download configuration.
     *
     * @return string HTML for a button.
     */
    private function get_download_config_button(): string {
        global $OUTPUT, $USER, $PAGE;

        $context = context_module::instance($this->quiz->cmid, MUST_EXIST);
        if (has_capability('quizaccess/aiquestiongenerator:generatequestions', $context, $USER->id)) {

            // Return a single button linking to the report.
            return '<button type="button" class="btn btn-outline-secondary" id="openAIModal">
                <i class="fa fa-wand-magic-sparkles" aria-hidden="true"></i> ' . get_string('aiquestiongenerator', 'quizaccess_aiquestiongenerator') . '
            </button>';
        }

        return false;

    }

    // Add other methods like make(), description(), etc. if needed.
}
