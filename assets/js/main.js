// document.querySelector('.global').addEventListener('click', function () {
//     const currentLang = '<?= $lang ?>';
//     const newLang = currentLang === 'es' ? 'en' : 'es';
//     window.location.href = window.location.pathname + '?lang=' + newLang;
// });

    document.getElementById('langForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = this;
        const params = new URLSearchParams(window.location.search);
        const newLang = form.querySelector('input[name="lang"]').value;

        params.set('lang', newLang);
        window.location.search = params.toString(); // actualiza solo el parámetro 'lang' y recarga
    });

