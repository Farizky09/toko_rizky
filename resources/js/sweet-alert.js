const Alert = {
    success(message = "Proses berhasil dilakukan", title = "Berhasil") {
        Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true
        });
    },

    error(message = "Terjadi kesalahan", title = "Gagal") {
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            timer: 2500,
            showConfirmButton: true,
        });
    },

    info(message = "Informasi penting", title = "Info") {
        Swal.fire({
            icon: 'info',
            title: title,
            text: message,
            timer: 2500,
            showConfirmButton: true,
        });
    },

    confirm(message = "Apakah Anda yakin?", title = "Konfirmasi", callback = null) {
        Swal.fire({
            title: title,
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed && typeof callback === 'function') {
                callback();
            }
        });
    }
};
