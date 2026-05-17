import { defineStore } from 'pinia';

export const useUIStore = defineStore('ui', {
    state: () => ({
        isNavbarOpen: false,
        activeSection: 'home',
    }),
    actions: {
        toggleNavbar() {
            this.isNavbarOpen = !this.isNavbarOpen;
        },
        setActiveSection(section) {
            this.activeSection = section;
        },
    },
});
