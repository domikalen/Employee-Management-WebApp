class Modal {
    constructor(triggerSelector, modalSelector, confirmCallback = null) {
        this.triggerElements = document.querySelectorAll(triggerSelector);
        this.modal = document.querySelector(modalSelector);
        this.closeButton = this.modal.querySelector('.modal-close');
        this.confirmButton = this.modal.querySelector('.modal-confirm');
        this.confirmCallback = confirmCallback;

        this.addEventListeners();
    }

    openModal() {
        this.modal.classList.add('open');
    }

    closeModal() {
        this.modal.classList.remove('open');
    }

    addEventListeners() {
        this.triggerElements.forEach((trigger) => {
            trigger.addEventListener('click', (event) => {
                event.preventDefault();
                this.openModal();
            });
        });

        this.closeButton.addEventListener('click', () => {
            this.closeModal();
        });

        if (this.confirmButton && this.confirmCallback) {
            this.confirmButton.addEventListener('click', () => {
                this.confirmCallback();
                this.closeModal();
            });
        }

        window.addEventListener('click', (event) => {
            if (event.target === this.modal) {
                this.closeModal();
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const deleteModal = document.getElementById('delete-confirm-modal');
    const closeModalButtons = document.querySelectorAll('.modal-close');
    const deleteButtons = document.querySelectorAll('.trigger-delete-modal');

    deleteButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            modal.classList.add('open');
        });
    });

    closeModalButtons.forEach((button) => {
        button.addEventListener('click', () => {
            deleteModal.classList.remove('open');
        });
    });

    window.addEventListener('click', (event) => {
        if (event.target === deleteModal) {
            deleteModal.classList.remove('open');
        }
    });
});

