import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import Welcome from '../../resources/js/Pages/Welcome.vue';

// Mock Inertia components
vi.mock('@inertiajs/vue3', () => ({
    Head: {
        render: () => null
    },
    Link: {
        render: (slots) => slots.default ? slots.default() : null
    }
}));

describe('Welcome.vue', () => {
    it('renders the project title', () => {
        const wrapper = mount(Welcome, {
            props: {
                laravelVersion: '11.0.0',
                phpVersion: '8.3.0'
            }
        });
        expect(wrapper.text()).toContain('Kp-Bungas Website');
    });

    it('renders the versions correctly', () => {
        const wrapper = mount(Welcome, {
            props: {
                laravelVersion: '11.0.0',
                phpVersion: '8.3.0'
            }
        });
        expect(wrapper.text()).toContain('Laravel v11.0.0');
        expect(wrapper.text()).toContain('(PHP v8.3.0)');
    });
});
