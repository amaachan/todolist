<script>
    const subtaskForm = document.querySelectorAll('form[action=""]'); // Adjust selector to the subtask form
    subtaskForm.forEach(function(form) {form.addEventListener('submit', function (e) {
        const subtaskInput = form.querySelector('input[name="subtask"]');

        if (subtaskInput.value.trim() === '') {
            alert('Subtask cannot be empty!');
            e.preventDefault(); // Prevent form submission
        }
    })};
    );
</script>;
