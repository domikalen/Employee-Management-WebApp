class Dropdown {
    constructor(dropdownSelector) {
        this.dropdown = document.querySelector(dropdownSelector);
        this.toggle = this.dropdown.querySelector('.dropdown-toggle');
        this.menu = this.dropdown.querySelector('.dropdown-menu');

        this.addEventListeners();
    }

    toggleMenu() {
        this.menu.classList.toggle('open');
    }

    addEventListeners() {
        this.toggle.addEventListener('click', () => {
            this.toggleMenu();
        });

        document.addEventListener('click', (event) => {
            if (!this.dropdown.contains(event.target)) {
                this.menu.classList.remove('open');
            }
        });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const dropdownTrigger = document.querySelector(".dropdown-trigger");
    const dropdownMenu = document.querySelector(".dropdown-menu");

    if (dropdownTrigger) {
        dropdownTrigger.addEventListener("click", function (event) {
            event.stopPropagation();
            dropdownMenu.classList.toggle("visible");
        });

        document.addEventListener("click", function (event) {
            if (!dropdownTrigger.contains(event.target)) {
                dropdownMenu.classList.remove("visible");
            }
        });
    }
});

