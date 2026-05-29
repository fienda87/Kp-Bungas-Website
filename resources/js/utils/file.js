const formatAcceptedType = (token) => {
    if (token.startsWith('.')) {
        return token.slice(1).toUpperCase();
    }

    if (token.endsWith('/*')) {
        return token.replace('/*', '').toUpperCase();
    }

    return token.split('/').pop()?.toUpperCase() ?? token.toUpperCase();
};

export const describeAcceptedTypes = (accept = '') => {
    if (!accept) return 'Semua file didukung.';

    return accept
        .split(',')
        .map((token) => formatAcceptedType(token.trim()))
        .join(', ');
};

export const validateSelectedFile = (file, accept = '', maxSizeMb = 10) => {
    if (!file) {
        return '';
    }

    if (maxSizeMb && file.size > maxSizeMb * 1024 * 1024) {
        return `Ukuran file maksimal ${maxSizeMb}MB.`;
    }

    if (!accept) {
        return '';
    }

    const rules = accept.split(',').map((token) => token.trim()).filter(Boolean);
    const fileName = file.name.toLowerCase();
    const mimeType = (file.type || '').toLowerCase();

    const isValid = rules.some((rule) => {
        const normalizedRule = rule.toLowerCase();

        if (normalizedRule.startsWith('.')) {
            return fileName.endsWith(normalizedRule);
        }

        if (normalizedRule.endsWith('/*')) {
            const prefix = normalizedRule.replace('/*', '/');
            return mimeType.startsWith(prefix);
        }

        return mimeType === normalizedRule;
    });

    if (!isValid) {
        return `Tipe file tidak didukung. Gunakan ${describeAcceptedTypes(accept)}.`;
    }

    return '';
};
