import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["people", "table", "submit", "error", "capacityDisplay"];
    static values = {
        capacities: Object
    }

    connect() {
        console.log("Reservation validation controller connected");
        this.updateValidation();
        
        this.peopleTarget.addEventListener("change", () => this.updateValidation());
        this.peopleTarget.addEventListener("input", () => this.updateValidation());
        this.tableTarget.addEventListener("change", () => {
            this.updateCapacityDisplay();
            this.updateValidation();
        });
        
        this.updateCapacityDisplay();
    }

    updateCapacityDisplay() {
        const tableId = this.tableTarget.value;
        if (tableId && this.capacitiesValue[tableId]) {
            const capacity = this.capacitiesValue[tableId];
            this.capacityDisplayTarget.textContent = "Capacity: " + capacity + " people";
            this.capacityDisplayTarget.classList.remove("d-none", "text-danger");
            this.capacityDisplayTarget.classList.add("text-success", "small", "mt-1");
        } else {
            this.capacityDisplayTarget.textContent = "Select a table to see capacity";
            this.capacityDisplayTarget.classList.remove("text-success");
            this.capacityDisplayTarget.classList.add("text-muted", "small", "mt-1");
        }
    }

    updateValidation() {
        const people = parseInt(this.peopleTarget.value) || 0;
        const tableId = this.tableTarget.value;
        
        if (!tableId) {
            this.clearError();
            this.disableSubmit();
            this.capacityDisplayTarget.textContent = "Please select a table first";
            this.capacityDisplayTarget.classList.remove("text-success");
            this.capacityDisplayTarget.classList.add("text-warning");
            return;
        }

        if (people === 0) {
            this.showError("Please select number of people");
            this.disableSubmit();
            return;
        }

        const capacity = this.capacitiesValue[tableId];
        
        if (!capacity) {
            this.clearError();
            this.enableSubmit();
            return;
        }

        if (people > capacity) {
            this.showError("Error: This table only has capacity for " + capacity + " people (you selected " + people + ")");
            this.disableSubmit();
        } else {
            this.clearError();
            this.enableSubmit();
        }
    }

    showError(message) {
        this.errorTarget.textContent = message;
        this.errorTarget.classList.remove("d-none");
        this.errorTarget.classList.add("alert", "alert-danger", "mt-2", "p-2");
    }

    clearError() {
        this.errorTarget.textContent = "";
        this.errorTarget.classList.add("d-none");
        this.errorTarget.classList.remove("alert", "alert-danger", "mt-2", "p-2");
    }

    disableSubmit() {
        this.submitTarget.disabled = true;
        this.submitTarget.classList.add("btn-secondary");
        this.submitTarget.classList.remove("btn-primary");
        this.submitTarget.innerHTML = "Cannot Submit";
    }

    enableSubmit() {
        this.submitTarget.disabled = false;
        this.submitTarget.classList.add("btn-primary");
        this.submitTarget.classList.remove("btn-secondary");
        this.submitTarget.innerHTML = "Make Reservation";
    }
}
