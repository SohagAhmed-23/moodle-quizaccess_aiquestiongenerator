/* eslint-disable @babel/object-curly-spacing */
/* eslint-disable capitalized-comments */

define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {
    return {
        init: function() {

            function formatQuizText(text) {
                const questions = text.trim().split(/\n(?=\d+\.)/); // Split by question numbers
                let html = '';

                questions.forEach(q => {
                    const lines = q.trim().split('\n');
                    const question = lines[0];
                    const options = lines.slice(1, 5);
                    const answer = lines.find(line => line.toLowerCase().startsWith('answer:'));

                    html += '<div class="card mb-3">';
                    html += '<div class="card-body">';
                    html += `<p class="fw-bold">${question}</p>`;
                    html += '<ul class="list-group list-group-flush mb-2">';
                    options.forEach(opt => {
                        html += `<li class="list-group-item">${opt}</li>`;
                    });
                    html += '</ul>';
                    if (answer) {
                        html += `<p class="text-success"><strong>${answer}</strong></p>`;
                    }
                    html += '</div></div>';
                });

                return html;
            }
            function formatSlides(text) {
    const questions = text.trim().split(/\n(?=\d+\.)/); // Split by question numbers
    let html = '<div id="quizSlides" class="position-relative">';

    questions.forEach((q, index) => {
        const lines = q.trim().split('\n');
        const question = lines[0];
        const options = lines.slice(1, 5);
        const answer = lines.find(line => line.toLowerCase().startsWith('answer:'));

        html += `<div class="quiz-slide card mb-3" data-slide="${index}" style="${index === 0 ? '' : 'display: none;'}">`;
        html += '<div class="card-body">';
        html += `<p class="fw-bold">${question}</p>`;
        html += '<ul class="list-group list-group-flush mb-2">';
        options.forEach(opt => {
            html += `<li class="list-group-item">${opt}</li>`;
        });
        html += '</ul>';
        if (answer) {
            html += `<p class="text-success"><strong>${answer}</strong></p>`;
        }
        html += '</div></div>';
    });

    // Add navigation buttons
    html += `
        <div class="d-flex justify-content-between mt-3">
            <button id="prevSlide" class="btn btn-secondary" disabled>Previous</button>
            <button id="addToQuiz" class="btn btn-success mx-3">Add this to quiz</button>
            <button id="nextSlide" class="btn btn-primary">Next</button>
        </div>
    </div>`;

    return html;
}


function initSlideNavigation() {
    let currentSlide = 0;
    const slides = $('.quiz-slide');
    const totalSlides = slides.length;

    $('#prevSlide').on('click', function() {
        if (currentSlide > 0) {
            slides.eq(currentSlide).hide();
            currentSlide--;
            slides.eq(currentSlide).show();
            toggleButtons();
        }
    });

    $('#nextSlide').on('click', function() {
        if (currentSlide < totalSlides - 1) {
            slides.eq(currentSlide).hide();
            currentSlide++;
            slides.eq(currentSlide).show();
            toggleButtons();
        }
    });

    $('#addToQuiz').on('click', function() {
        const $btn = $(this);
        const current = slides.eq(currentSlide);

        const question = current.find('p.fw-bold').text().trim();
        const options = current.find('li').map(function() {
            return $(this).text().trim();
        }).get();
        const answerText = current.find('p.text-success').text().replace(/^Answer:\s*/i, '').trim();

        // AJAX call to Moodle webservice
        require(['core/ajax', 'core/notification'], function(Ajax, Notification) {
            Ajax.call([{
                methodname: 'quizaccess_aiquestiongenerator_add_question',
                args: {
                    question: question,
                    options: JSON.stringify(options),
                    answer: answerText,
                    courseid: 2,
                    quizid: 5, // Replace with actual quiz ID
                    qtype: 'multichoice' // Assuming multichoice type
                }
            }])[0].done(function(response) {
                // Update the button
                console.log(response);
                $btn.text('Question Added');
                $btn.prop('disabled', true);
            }).fail(Notification.exception);
        });
    });

    function toggleButtons() {
        $('#prevSlide').prop('disabled', currentSlide === 0);
        $('#nextSlide').prop('disabled', currentSlide === totalSlides - 1);
        $('#addToQuiz').prop('disabled', false).text('Add this to quiz'); // Reset the Add button
    }
}




            // Open the modal when button is clicked.
            $('#openAIModal').on('click', function() {
                $('#aiModal').modal('show');
            });

            // Handle submit button inside modal.
            $('#submitAI').on('click', function() {
                const description = $('#aiDescription').val().trim();
                if (!description) {
                    alert('Please enter a description.');
                    return;
                }

                const $btn = $(this);
                const originalText = $btn.html();

                // Disable the button and show loader
                $btn.prop('disabled', true);
                const loadingText = $btn.data('loadingtext') || 'Loading...';
                // eslint-disable-next-line max-len
                $btn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> ' + loadingText);

                // Clear previous response
                $('#aiResponse').hide().text('');

                // Make AJAX call to your web service
                Ajax.call([{
                    methodname: 'quizaccess_aiquestiongenerator_generate_questions',
                    args: { description: description }
                }])[0].done(function(response) {
                    console.log(response);
                        const rawText = response.questions;
                        const slidesHtml = formatSlides(rawText);

                        $('#aiResponse').html(slidesHtml).show();
                        initSlideNavigation();
                }).fail(Notification.exception).always(function() {
                    // Re-enable button and restore text
                    $btn.prop('disabled', false);
                    $btn.html(originalText);
                });
            });
        }
    };
});
