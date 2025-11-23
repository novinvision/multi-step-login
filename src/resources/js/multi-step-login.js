$(document).ready(function () {
    $('form#multi-step-login').submit(e => {
        let submitButton = $(this).find('[type="submit"]');
        // $(submitButton).attr('disabled', 'disabled');
        // $(submitButton).addClass('loading');
    });

    document.querySelectorAll('[data-length-submit]').forEach(input => {
        input.addEventListener('input', () => {
            const max = parseInt(input.getAttribute('data-length-submit'), 10);
            if (max > 0 && input.value.length >= max) {
                const btn = input.form.querySelector('[type="submit"]');
                if (btn) btn.disabled = true;

                input.form.submit();
                input.disabled = true;
            }
        });
    });

    if (window && 'OTPCredential' in window) {
        window.addEventListener('DOMContentLoaded', () => {
            const input = document.querySelector('[data-has-otp="true"]');
            if (!input) return;

            navigator.credentials.get({
                otp: {transport: ['sms']},
                signal: new AbortController().signal
            }).then(otp => {
                if (otp && otp.code) {
                    input.value = otp.code;
                    input.dispatchEvent(new Event('input', {bubbles: true}));
                }
            }).catch(() => {
            });
        });
    }

    document.querySelectorAll('[data-count-down]').forEach(el => {
        let time = parseInt(el.getAttribute('data-count-down'));
        const text = el.getAttribute('data-count-down-text') || '';
        const finishText = el.getAttribute('data-count-down-finish-text') || '';

        const timer = setInterval(() => {
            if (time > 0) {
                el.setAttribute('disabled', 'disabled');
                el.classList.add('disabled');
                el.innerHTML = '<i class="fa-solid fa-rotate-right align-middle me-1"></i>' + text.replace(':sec', time);
                time--;
            } else {
                el.removeAttribute('disabled');
                el.classList.remove('disabled');
                clearInterval(timer);
                el.innerHTML = '<i class="fa-solid fa-rotate-right align-middle me-1"></i>' + finishText;
            }
        }, 1000);
    });
});

