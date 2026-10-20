document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.passwordToggle);

        if (!input) {
            return;
        }

        const isPasswordVisible = input.type === 'password';

        input.type = isPasswordVisible ? 'text' : 'password';
        button.setAttribute('aria-label', isPasswordVisible ? 'Sembunyikan password' : 'Tampilkan password');
        button.setAttribute('aria-pressed', String(isPasswordVisible));
        button.querySelector('[data-eye]')?.classList.toggle('hidden', isPasswordVisible);
        button.querySelector('[data-eye-off]')?.classList.toggle('hidden', !isPasswordVisible);
    });
});
