import {Controller} from '@hotwired/stimulus';
import Swal from "sweetalert2";

export default class extends Controller {
    static values = {
        title: String,
        text: String,
        icon: String,
        confirmationButtonText: String,
        urlDelete: String,
    }

    connect() {
        this.element.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            this.onSubmit().bind(this);
        });
    }

    onSubmit() {
        Swal.fire({
            title: this.titleValue || null,
            text: this.textValue || null,
            icon: this.iconValue || null,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: this.confirmationButtonTextValue || 'Yes',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return this.delete();
            }
        });
    }

    delete() {
        fetch(this.urlDeleteValue, {
            method: 'DELETE',
        }).then(response => {
            if (response.ok) {
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                ).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire(
                    'Error!',
                    'Your file has not been deleted.',
                    'error'
                );
            }
        });
    }
}
