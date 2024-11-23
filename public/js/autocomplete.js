class Autocomplete {
    constructor(inputSelector, listSelector) {
        this.input = document.querySelector(inputSelector);
        this.list = document.querySelector(listSelector);
        this.items = Array.from(this.list.querySelectorAll('option'));

        this.addEventListeners();
    }

    filterOptions(query) {
        this.items.forEach((item) => {
            const isVisible = item.textContent.toLowerCase().includes(query.toLowerCase());
            item.style.display = isVisible ? 'block' : 'none';
            if (isVisible) {
                item.innerHTML = this.highlightMatch(item.textContent, query);
            } else {
                item.innerHTML = item.textContent;
            }
        });
    }

    highlightMatch(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<span class="highlight">$1</span>');
    }

    addEventListeners() {
        this.input.addEventListener('input', () => {
            this.filterOptions(this.input.value);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new Autocomplete('#rolesInput', '#rolesList');
});
