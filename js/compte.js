// script.js

$(document).ready(function() {
    $('.modifier-question').click(function() {
        const questionId = $(this).data('id');
        const question = $(this).data('question');
        const answer = $(this).data('answer');

        $('#modal_question_id').val(questionId);
        $('#modal_question').val(question);
        $('#modal_answer').val(answer);
    });
});

function toggleAccountBox() {
    var accountBox = document.querySelector('.account-box');
    accountBox.classList.toggle('show');
}

document.addEventListener('DOMContentLoaded', function() {
    var accountBtn = document.querySelector('.account-btn');
    accountBtn.addEventListener('click', toggleAccountBox);
});
