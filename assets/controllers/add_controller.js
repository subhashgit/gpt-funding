import {Controller} from "@hotwired/stimulus";
import 'select2';
import 'select2/dist/css/select2.css';

export default class extends Controller {
    connect() {
        document
            .querySelectorAll('.add_item_link')
            .forEach(btn => {
                btn.addEventListener("click", this.addFormToCollection)
            });

        document
            .querySelectorAll('ul.tags li')
            .forEach((tag) => {
                this.addTagFormDeleteLink(tag)
            });

        const btn  = document.querySelector('.add_item_link');

        btn.click();
    }

    disconnect() {

    }

     addFormToCollection = (e) => {
        const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

        const item = document.createElement('li');

        item.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );

        collectionHolder.appendChild(item);
        this.addTagFormDeleteLink(item);


        collectionHolder.dataset.index++;
    };

     addTagFormDeleteLink = (item) => {
        const removeFormButton = document.createElement('button');
        removeFormButton.innerText = 'Delete this item';

        item.append(removeFormButton);

        removeFormButton.addEventListener('click', (e) => {
            e.preventDefault();
            // remove the li for the tag form
            item.remove();
        });
    }


}
