<?php
namespace quizaccess_aiquestiongenerator\external;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;
use Aws\BedrockRuntime\BedrockRuntimeClient;

class generate_questions extends external_api {

    public static function execute_parameters() {
        return new external_function_parameters([
            'description' => new external_value(PARAM_TEXT, 'Content description for generating questions')
        ]);
    }

    public static function execute($description) {

        $params = self::validate_parameters(self::execute_parameters(), ['description' => $description]);

        $apiprovider = get_config('quizaccess_aiquestiongenerator', 'apiprovider');

        // echo '<div class="alert alert-info">Using API provider: ' . $apiprovider . '</div>';
        // die;

        if($apiprovider === 'openai') {
            $apisecret = get_config('quizaccess_aiquestiongenerator', 'openai_secret');
            $prompt = "Generate 5 multiple-choice questions with answers based on:\n\n" . $params['description'];
            $response = self::call_openai_api($prompt, $apisecret);
        } else if($apiprovider === 'anthropic') {
            // Placeholder for Anthropic API call
            $response = self::call_claude_api($params['description']);
            // $response = 'Anthropic API is not yet implemented.';
        } else if($apiprovider === 'cohere') {
            // Placeholder for Cohere API call
            $response = 'Cohere API is not yet implemented.';
        } else {
            throw new \moodle_exception('Unsupported API provider: ' . $apiprovider);
        }

        // if (empty($apisecret)) {
        //     throw new \moodle_exception('API is not set. Please configure the plugin settings.');
        // }
        // // Validate the API key.
        // if (!preg_match('/^[a-zA-Z0-9]{32,}$/', $apisecret)) {
        //     throw new \moodle_exception('Invalid API key format. Please check your settings.');
        // }

        return ['questions' => $response];
    }

    public static function execute_returns() {
        return new external_single_structure([
            'questions' => new external_value(PARAM_RAW, 'Generated questions and answers')
        ]);
    }



    private static function call_claude_api($description) {
        $awskey     = get_config('quizaccess_aiquestiongenerator', 'awskey');
        $awssecret  = get_config('quizaccess_aiquestiongenerator', 'awssecret');

        if (empty($awskey) || empty($awssecret)) {
            return ['error' => get_string('key_invalid', 'quizaccess_proctoring')];
        }

        $client = new BedrockRuntimeClient([
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
                'key'    => $awskey,
                'secret' => $awssecret,
            ],
        ]);

        $prompt = "Human: Based on the following content, generate 5 multiple-choice questions. 
        Each question should have exactly 4 options labeled a), b), c), and d). 
        Only one option should be correct. At the end of each question, include the correct answer in the format: 'Answer: <option letter>'.

        Return the result in the following format:

        1. Question text?
        a) Option A  
        b) Option B  
        c) Option C  
        d) Option D  
        Answer: b

        Here is the content to generate questions from:
        \n\n" . trim($description) . "\n\nAssistant:";

        try {
            $result = $client->invokeModel([
                'modelId'      => 'anthropic.claude-v2:1',
                'contentType'  => 'application/json',
                'accept'       => 'application/json',
                'body'         => json_encode([
                    'prompt' => $prompt,
                    'max_tokens_to_sample' => 500
                ]),
            ]);

            $responseBody = $result['body']->getContents();
            $decoded = json_decode($responseBody, true);
            return $decoded['completion'] ?? 'No completion received.';

        } catch (\Exception $e) {
            debugging('Claude API error: ' . $e->getMessage(), DEBUG_DEVELOPER);
            return ['error' => 'Claude API call failed: ' . $e->getMessage()];
        }
    }



    private static function call_openai_api($prompt, $apisecret) {
    /// this is for now


    $response = <<<EOT

                1. What is an algorithm?
                a) A type of hardware
                b) A step-by-step procedure to solve a problem
                c) A programming language
                d) A computer virus
                Answer: b

                2. Which of the following is a characteristic of a good algorithm?
                a) Ambiguity
                b) Infinite steps
                c) Well-defined inputs and outputs
                d) Requires no logic
                Answer: c

                3. Which algorithm is used for sorting?
                a) Dijkstra's algorithm
                b) Binary search
                c) Quick sort
                d) Depth-first search
                Answer: c

                4. What does Big-O notation represent?
                a) Output quality
                b) Time complexity
                c) Programming language version
                d) File size
                Answer: b
                EOT;
            return $response;



            // tesing end here






















        $logdir = __DIR__; // Or use sys_get_temp_dir()
        file_put_contents($logdir . '/debug_marker.log', 'Function entered\n');
        file_put_contents($logdir . '/debug_marker.log', "API Token: $apisecret\n", FILE_APPEND);


        $ch = curl_init('https://api.openai.com/v1/chat/completions');

        $postData = json_encode([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an AI quiz generator.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7
        ]);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apisecret
            ],
            CURLOPT_POSTFIELDS => $postData
        ]);

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        file_put_contents($logdir . '/openai_postdata.json', $postData);
        file_put_contents($logdir . '/openai_response.log', $result);

        if ($err) {
            file_put_contents($logdir . '/openai_error.log', 'cURL Error: ' . $err);
            return 'Error: ' . $err;
        }

        $data = json_decode($result, true);
        if (!isset($data['choices'][0]['message']['content'])) {
            file_put_contents($logdir . '/openai_debug.log', print_r($data, true));
            // return 'No response.';
            $response = <<<EOT

                1. What is an algorithm?
                a) A type of hardware
                b) A step-by-step procedure to solve a problem
                c) A programming language
                d) A computer virus
                Answer: b

                2. Which of the following is a characteristic of a good algorithm?
                a) Ambiguity
                b) Infinite steps
                c) Well-defined inputs and outputs
                d) Requires no logic
                Answer: c

                3. Which algorithm is used for sorting?
                a) Dijkstra's algorithm
                b) Binary search
                c) Quick sort
                d) Depth-first search
                Answer: c

                4. What does Big-O notation represent?
                a) Output quality
                b) Time complexity
                c) Programming language version
                d) File size
                Answer: b
                EOT;
            return $response;
        }

        return $data['choices'][0]['message']['content'];
    }


}
