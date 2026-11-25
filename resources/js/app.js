import 'flowbite';
import { initPopovers } from 'flowbite';

// Expose Flowbite's initializer for server-rendered fragments loaded over AJAX.
window.initQjCompanyPopovers = initPopovers;
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();