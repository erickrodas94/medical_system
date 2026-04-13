const showAlert = (type, title, message, timer = null) => {
    const customClassMap = {
        'error': 'swal-custom-error',
        'warning': 'swal-custom-warning',
        'success': 'swal-custom-success',
        'info': 'swal-custom-info'
    };

    const selectedClass = customClassMap[type] || '';

    Swal.fire({
        icon: type,
        title: title,
        text: message,
        timer: timer,
        showConfirmButton: !timer,
        confirmButtonColor: '#2563eb',
        background: '#ffffff',
        // borderRadius: '1.25rem', <-- ELIMINA ESTA LÍNEA, ES EL ERROR
        customClass: {
            popup: selectedClass
        },
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    });
};

// En tu archivo JS
function confirmation_page_change(urlBase, location, title, message) {
    Swal.fire({
        title: title,          // Usamos el diccionario JS
        text: message,         // Usamos el diccionario JS
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: i18n.confirm_btn, // Usamos el diccionario JS
        cancelButtonText: i18n.cancel_btn,    // Usamos el diccionario JS

        heightAuto: false,        // Evita que rompa el h-screen
        scrollbarPadding: false   // Evita que empuje el diseño hacia la izquierda

    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = urlBase + location;
        }
    });
}

// En tu archivo JS
// function logout(urlBase) {
//     Swal.fire({
//         title: i18n.logout_title,          // Usamos el diccionario JS
//         text: i18n.logout_confirm,         // Usamos el diccionario JS
//         icon: 'question',
//         showCancelButton: true,
//         confirmButtonColor: '#ef4444',
//         cancelButtonColor: '#64748b',
//         confirmButtonText: i18n.confirm_btn, // Usamos el diccionario JS
//         cancelButtonText: i18n.cancel_btn    // Usamos el diccionario JS
//     }).then((result) => {
//         if (result.isConfirmed) {
//             window.location.href = urlBase + 'logout';
//         }
//     });
// }

