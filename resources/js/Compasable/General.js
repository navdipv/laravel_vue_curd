import { inject, ref, watch } from "vue";

export function useGeneral() {

    const asset = (path) => {
        const base_path = window._asset || '';
        return base_path + path;
    }

    const base_url = (path) => {
        const base_path = window._BASE_URL || '';
        return base_path + path;
    }

    const formatDate = (date) => {
        const d = new Date(date);
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        const year = d.getFullYear();
        return [day, month, year].join('-');
    }

    const formatDateTime = (date) => {
        const d = new Date(date);
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        const year = d.getFullYear();
        const hour = String(d.getHours()).padStart(2, '0');
        const minute = String(d.getMinutes()).padStart(2, '0');
        const second = String(d.getSeconds()).padStart(2, '0');
        const dateText = [day, month, year].join('-');
        const timeText = [hour, minute, second].join(':');
        return `${dateText} ${timeText}`;
    }


    const confirmDelete = (route) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0872BA',
            cancelButtonColor: '#ea5455',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {

            }
        });
    }


    return {
        asset,
        base_url,
        formatDate,
        formatDateTime,
        confirmDelete
    };
}
