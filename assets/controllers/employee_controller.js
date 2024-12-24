import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["searchInput", "list", "pagination"];
    static values = { url: String };

    connect() {
        console.log("EmployeeController connected");
    }

    search(event) {
        event.preventDefault();
        const query = this.searchInputTarget.value;
        const url = new URL(this.urlValue);
        url.searchParams.set("search", query);

        fetch(url)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Error fetching employees: ${response.statusText}`);
                }
                return response.text();
            })
            .then((html) => {
                this.listTarget.innerHTML = html;
            })
            .catch((error) => console.error("Error fetching employees:", error));
    }

    paginate(event) {
        event.preventDefault();
        const url = event.target.getAttribute("href");

        fetch(url)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Error fetching page: ${response.statusText}`);
                }
                return response.text();
            })
            .then((html) => {
                this.listTarget.innerHTML = html;
            })
            .catch((error) => console.error("Error fetching page:", error));
    }
}
