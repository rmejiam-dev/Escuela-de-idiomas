import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import persist from '@alpinejs/persist';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
Alpine.plugin(collapse);
Alpine.plugin(persist);
Alpine.start();

// Configuración global de Toast personalizada
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    customClass: {
        popup: 'custom-toast',
        title: 'custom-toast-title',
        icon: 'custom-toast-icon',
        timerProgressBar: 'custom-toast-progress'
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

// Colores personalizados
const toastColors = {
    success: {
        background: '#101828',
        title: '#ffffff',
        progress: '#ffffff'
    },
    error: {
        background: '#b00020',
        title: '#ffffff',
        progress: '#dc2626'
    },
    warning: {
        background: '#f59e0b',
        title: '#ffffff',
        progress: '#d97706'
    },
    info: {
        background: '#0025ba',
        title: '#ffffff',
        progress: '#2563eb'
    }
};

// Función para mostrar toast personalizado
window.showToast = (type, message) => {
    const colors = toastColors[type] || toastColors.info;

    Toast.fire({
        icon: type,
        title: message,
        background: colors.background,
        color: colors.title,
        iconColor: colors.title,
        timerProgressBar: true,
    });
};

// Escuchar eventos de Livewire
document.addEventListener('livewire:initialized', () => {
    // Evento para toast simple
    Livewire.on('toast', (data) => {
        const payload = data[0] || data;
        showToast(payload.type, payload.message);
    });

    // Evento para toast con redirección
    Livewire.on('toast-and-redirect', (data) => {
        const payload = data[0] || data;
        const { type, message, route, timer = 2000 } = payload;
        
        const colors = toastColors[type] || toastColors.info;
        
        Toast.fire({
            icon: type,
            title: message,
            background: colors.background,
            color: colors.title,
            iconColor: colors.title,
            timerProgressBar: true,
        });
        
        setTimeout(() => {
            window.location.href = route;
        }, timer);
    });

    // Evento show-toast (para compatibilidad)
    Livewire.on('show-toast', (data) => {
        const payload = data[0] || data;
        showToast(payload.type, payload.message);
    });
});