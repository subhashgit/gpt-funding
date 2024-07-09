import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["button"]

    preventMultipleSubmits(event) {
        event.preventDefault();

        // Disable the button
        this.buttonTarget.disabled = true;

        // Submit the form
        event.target.form.submit();
    }
}
