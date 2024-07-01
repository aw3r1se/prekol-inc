const configuration = {
    fetchLimit: 15,
};

export function config (key, def) {
    if (!key) {
        return configuration;
    }

    return configuration[key] ?? def ?? null;
}
