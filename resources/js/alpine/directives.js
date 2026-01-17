import notifications from "./components/notifications";

export default function (Alpine) {
    Alpine.data("notifications", notifications);

    Alpine.magic('clipboard', () => (subject) => {
        return new Promise((resolve, reject) => {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(subject).then(resolve).catch(reject);
            } else {
                // Fallback for older browsers or non-secure contexts
                const textArea = document.createElement('textarea');
                textArea.value = subject;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    const success = document.execCommand('copy');
                    if (success) {
                        resolve();
                    } else {
                        reject(new Error('Copy failed'));
                    }
                } catch (err) {
                    reject(err);
                } finally {
                    document.body.removeChild(textArea);
                }
            }
        });
    });
}
