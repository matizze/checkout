export async function http(url, options = {}) {
    const defaultOptions = {
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                ?.content,
        },
        credentials: "same-origin",
        ...options,
    };

    try {
        const response = await fetch(url, defaultOptions);

        if (
            !response.ok &&
            response.status !== 302 &&
            response.status !== 204
        ) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        // For 204 No Content - return null without error
        if (response.status === 204) {
            return null;
        }

        // Handle redirects - reload page if URL changed
        if (response.redirected || response.type === "opaqueredirect") {
            window.location.reload();
            return null;
        }

        // Check if response is JSON before parsing
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.includes("application/json")) {
            return await response.json();
        }

        // For 302 redirects that didn't get followed automatically
        if (response.status === 302) {
            window.location.reload();
            return null;
        }

        // Return null for non-JSON responses
        return null;
    } catch (error) {
        console.error("HTTP Error:", error);
        throw error;
    }
}
