export const formatNewsDate = (value) => {
    if (!value) return '-';

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return '-';
    }

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    }).format(date);
};

export const stripHtml = (value) => value?.replace(/<[^>]*>/g, '') ?? '';

export const getExcerpt = (value, limit = 110, fallback = '') => {
    const plain = stripHtml(value).trim();

    if (!plain) {
        return fallback;
    }

    return plain.length > limit ? `${plain.slice(0, limit)}...` : plain;
};

export const buildNewsFilterParams = (filters = {}) => {
    const params = {};

    if (filters.search?.trim()) {
        params.search = filters.search.trim();
    }

    if (filters.category?.trim()) {
        params.category = filters.category.trim();
    }

    if (filters.page) {
        params.page = filters.page;
    }

    return params;
};
