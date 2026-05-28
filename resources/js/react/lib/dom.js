export function readJsonScript(id) {
    const element = document.getElementById(id);

    if (!element?.textContent) {
        return null;
    }

    try {
        return JSON.parse(element.textContent);
    } catch (error) {
        console.error(`Could not parse JSON from #${id}`, error);
        return null;
    }
}

export function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

export async function fetchJson(url, options = {}) {
    const response = await fetch(url, options);
    let errorBody = null;

    if (!response.ok) {
        try {
            errorBody = await response.json();
        } catch {
            errorBody = null;
        }

        const message = errorBody?.message ?? `HTTP ${response.status}`;
        const error = new Error(message);
        error.status = response.status;
        error.body = errorBody;
        throw error;
    }

    const contentType = response.headers.get('content-type') ?? '';

    if (!contentType.includes('application/json')) {
        const error = new Error('Unexpected server response (expected JSON).');
        error.status = response.status;
        throw error;
    }

    return response.json();
}
