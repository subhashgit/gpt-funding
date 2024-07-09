import {Controller} from "@hotwired/stimulus";
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
            allowClear: true,
            ajax: {
                url: '/bid-writer/postcode',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term,
                }),
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    const result = data.data??[];

                    return {
                        results: result.map(item => ({
                                id: item,
                                text: item
                            })
                        )
                    };
                },
                minimumInputLength: 3,
            }
        };
    }
}
