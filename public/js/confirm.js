document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete_button');

    deleteButtons.forEach((button) => {
        button.addEventListener('click', (event) => {
            const confirmed = confirm('Are you sure you want to delete this employee?');
            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
});
