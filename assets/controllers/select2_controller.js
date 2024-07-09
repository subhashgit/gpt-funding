import { Controller } from "@hotwired/stimulus";
import 'select2';
import 'select2/dist/css/select2.css';

export default class extends Controller {
    connect() {
        $(this.element).select2(this.options);
    }

    disconnect() {
        $(this.element).select2('destroy');
    }

    get options() {
        // Customize this method to suit your needs
        return {
            placeholder: "Select an option",
            allowClear: true
        };
    }
}
