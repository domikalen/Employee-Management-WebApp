document.addEventListener("DOMContentLoaded", () => {
    const accountsTable = document.querySelector("#accounts-table tbody");
    const addAccountContainer = document.querySelector("#add-account-container");
    const addAccountForm = document.querySelector("#add-account-form");
    const editContainer = document.querySelector("#edit-account-container");
    const editForm = document.querySelector("#edit-account-form");

    function addAccountRow(account) {
        const row = document.createElement("tr");
        row.setAttribute("data-id", account.id);
        row.innerHTML = `
            <td>${account.name}</td>
            <td>${account.type}</td>
            <td>${account.expiration || "Permanent"}</td>
            <td><button class="button edit_button" data-id="${account.id}"></button></td>
            <td><button class="button delete_button" data-id="${account.id}"></button></td>
        `;
        accountsTable.appendChild(row);
    }

    document.querySelector(".add_button").addEventListener("click", () => {
        addAccountContainer.style.display = "block";
    });

    addAccountForm.querySelector(".delete_button").addEventListener("click", () => {
        addAccountContainer.style.display = "none";
        addAccountForm.reset();
    });

    addAccountForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(addAccountForm);
        const jsonData = Object.fromEntries(formData);

        fetch(addAccountForm.dataset.createUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(jsonData),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Error creating account: ${response.statusText}`);
                }
                return response.json();
            })
            .then((account) => {
                addAccountRow(account);
                addAccountForm.reset();
                addAccountContainer.style.display = "none";
            })
            .catch((error) => {
                console.error("Error creating account:", error);
            });
    });

    accountsTable.addEventListener("click", (e) => {
        if (e.target.classList.contains("edit_button")) {
            const accountId = e.target.dataset.id;

            fetch(`/api/accounts/${accountId}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`Error fetching account: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then((account) => {
                    editForm.querySelector('[name="name"]').value = account.name;
                    editForm.querySelector('[name="type"]').value = account.type;
                    editForm.querySelector('[name="expiration"]').value = account.expiration || "";
                    editForm.dataset.accountId = account.id;
                    editContainer.style.display = "block";
                })
                .catch((error) => console.error("Error fetching account data:", error));
        }
    });

    editForm.querySelector(".delete_button").addEventListener("click", () => {
        editContainer.style.display = "none";
        editForm.reset();
    });

    editForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const accountId = editForm.dataset.accountId;
        const formData = new FormData(editForm);
        const jsonData = Object.fromEntries(formData);

        fetch(`/api/accounts/${accountId}`, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(jsonData),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Error updating account: ${response.statusText}`);
                }
                return response.json();
            })
            .then((updatedAccount) => {
                const row = document.querySelector(`tr[data-id="${updatedAccount.id}"]`);
                row.innerHTML = `
                    <td>${updatedAccount.name}</td>
                    <td>${updatedAccount.type}</td>
                    <td>${updatedAccount.expiration || "Permanent"}</td>
                    <td><button class="button edit_button" data-id="${updatedAccount.id}"></button></td>
                    <td><button class="button delete_button" data-id="${updatedAccount.id}"></button></td>
                `;
                editContainer.style.display = "none";
            })
            .catch((error) => console.error("Error updating account:", error));
    });

    accountsTable.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete_button")) {
            const accountId = e.target.dataset.id;

            fetch(`/api/accounts/${accountId}`, {
                method: "DELETE",
            })
                .then(() => {
                    const row = document.querySelector(`tr[data-id="${accountId}"]`);
                    row.remove();
                })
                .catch((error) => console.error("Error deleting account:", error));
        }
    });
});
