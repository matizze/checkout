import { http } from "./http";

export function polling(
    url,
    interval = 5000,
    options = {},
    initialData = null,
) {
    return {
        data: initialData,
        loading: false,
        error: null,
        intervalId: null,
        isDestroyed: false,

        async init() {
            if (this.isDestroyed) return;
            if (!this.data) {
                await this.fetch();
            }
            this.start();
        },

        async fetch() {
            if (this.loading || this.isDestroyed) return;
            this.loading = true;
            this.error = null;

            try {
                const response = await http(url);

                if (!response || typeof response !== "object") {
                    console.warn("Invalid response, ignoring:", response);
                    return;
                }

                this.data = response;

                // Callback seguro quando atualizar
                if (
                    options.onUpdate &&
                    typeof options.onUpdate === "function"
                ) {
                    options.onUpdate(response);
                }

                // Parar quando condição for verdadeira
                if (options.stopWhen && options.stopWhen(response)) {
                    this.stop();
                    if (options.onComplete) {
                        options.onComplete(response);
                    }
                }
            } catch (err) {
                this.error = err.message;
                if (options.onError && typeof options.onError === "function") {
                    options.onError(err);
                }
            } finally {
                this.loading = false;
            }
        },

        start() {
            if (!this.intervalId && !this.isDestroyed) {
                this.intervalId = setInterval(() => this.fetch(), interval);
            }
        },

        stop() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        },

        destroy() {
            this.isDestroyed = true;
            this.stop();
        },
    };
}
