import './bootstrap';
import Alpine from 'alpinejs';

// تهيئة Alpine.js
window.Alpine = Alpine;
Alpine.start();

// استيراد SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// استيراد Chart.js
import Chart from 'chart.js/auto';
window.Chart = Chart;

// استيراد GSAP للرسوم المتحركة
import gsap from 'gsap';
window.gsap = gsap;

// استيراد Howler للمؤثرات الصوتية
import { Howl } from 'howler';
window.Howl = Howl;

// إعدادات عامة
document.addEventListener('DOMContentLoaded', () => {
    // تفعيل Tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', () => {
            // إنشاء tooltip
        });
    });

    // تفعيل Dark Mode
    const darkModeToggle = document.querySelector('[data-dark-mode-toggle]');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark);
        });
    }
});

// تصدير الوظائف العامة
export {
    Swal,
    Chart,
    gsap,
    Howl
};