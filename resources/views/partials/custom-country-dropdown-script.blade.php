@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-country-dropdown]').forEach((dropdown) => {
            const trigger = dropdown.querySelector('.custom-dropdown__trigger');
            const hiddenInput = dropdown.querySelector('[data-country-input]');
            const form = dropdown.closest('form');

            if (! trigger || ! hiddenInput || ! form) {
                return;
            }

            trigger.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                document.querySelectorAll('[data-country-dropdown].open').forEach((openDropdown) => {
                    if (openDropdown !== dropdown) {
                        openDropdown.classList.remove('open');
                    }
                });

                dropdown.classList.toggle('open');
            });

            dropdown.querySelectorAll('.custom-dropdown__option').forEach((option) => {
                option.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();

                    hiddenInput.value = option.dataset.value;
                    dropdown.classList.remove('open');
                    form.submit();
                });
            });
        });

        document.addEventListener('click', (event) => {
            if (! event.target.closest('[data-country-dropdown]')) {
                document.querySelectorAll('[data-country-dropdown].open').forEach((dropdown) => {
                    dropdown.classList.remove('open');
                });
            }
        });
    });
</script>
@endpush
@endonce
