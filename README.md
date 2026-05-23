# AI Question Generator for Moodle Quiz

A Moodle plugin that provides intelligent quiz question generation using AI language models.

## About

This plugin is a quiz access rule that integrates AI-powered question generation into Moodle quizzes. It allows educators to automatically generate quiz questions based on topic descriptions using multiple AI providers including OpenAI, Anthropic Claude, and Cohere.

## Features

* Generate quiz questions automatically using AI
* Support for multiple AI providers (OpenAI, Claude, Cohere)
* Modal-based interface for easy question generation
* Direct integration with quiz questions
* Capability-based access control
* AWS integration support

## Installation

1. Download or clone this plugin into `mod/quiz/accessrule/aiquestiongenerator/`

```bash
cd /path/to/moodle/mod/quiz/accessrule
git clone https://github.com/SohagAhmed-23/moodle-quizaccess_aiquestiongenerator.git aiquestiongenerator
```

2. Log in as administrator and go to **Notifications** to install
3. Go to **Site administration > Plugins > Quiz access rules > AI Question Generator**
4. Configure your API provider and credentials

## Requirements

* Moodle 3.8 (2019052000) or later
* PHP 7.2 or higher
* cURL and OpenSSL extensions
* Valid API key from one of the supported AI providers:
  - OpenAI API key
  - Anthropic API key
  - Cohere API key

## Configuration

1. Navigate to **Site administration > Plugins > Quiz access rules > AI Question Generator**
2. Enable the plugin
3. Select your preferred AI provider (OpenAI, Claude, or Cohere)
4. Enter your API key for the selected provider
5. Optionally configure AWS credentials
6. Save settings

## Usage

### For Educators

1. Go to your quiz
2. Click the **"✨ AI Question Generator"** button
3. Enter a description or topic for the questions
4. Specify the number of questions and other preferences
5. Click "Generate" to create questions
6. Review the generated questions
7. Click "Add" to add selected questions to your quiz

### Permissions

The following capabilities are required:
* `quizaccess/aiquestiongenerator:generatequestions` - Generate new questions
* `quizaccess/aiquestiongenerator:addquestions` - Add questions to quizzes

## Supported AI Providers

### OpenAI
* Models: GPT-4, GPT-3.5-turbo
* Get API key: https://platform.openai.com

### Anthropic Claude
* Models: Claude 3 family
* Get API key: https://console.anthropic.com

### Cohere
* Models: Command R, Command R+
* Get API key: https://dashboard.cohere.com

## Web Services

The plugin provides the following AJAX-enabled web services:

### quizaccess_aiquestiongenerator_generate_questions
Generates quiz questions based on a description.

**Capabilities required:** quizaccess/aiquestiongenerator:generatequestions

### quizaccess_aiquestiongenerator_add_question
Adds a question to a quiz.

**Capabilities required:** quizaccess/aiquestiongenerator:addquestions

## Security

* API keys are stored securely in Moodle configuration
* Access is controlled via Moodle capabilities
* All API communication uses HTTPS
* Only authenticated users with appropriate capabilities can generate questions

## Support

For bugs, issues, or feature requests, please visit the GitHub repository:
https://github.com/SohagAhmed-23/moodle-quizaccess_aiquestiongenerator

## Changelog

### Version 1.0.0 (2026-05-23)
* Initial release
* Support for OpenAI, Claude, and Cohere providers
* Basic question generation and addition functionality

## License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.

## Author

Sohag Ahmed

## Maintained by

Visit the plugin repository for updates and support:
https://github.com/SohagAhmed-23/moodle-quizaccess_aiquestiongenerator