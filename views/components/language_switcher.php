<?php
$currentLanguage = Helper::getCurrentLanguage();
$availableLanguages = AVAILABLE_LANGUAGES;
?>

<div class="dropdown">
    <button class="btn btn-link nav-link dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="fas fa-globe me-1"></i>
        <?php echo strtoupper($currentLanguage); ?>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
        <?php foreach ($availableLanguages as $lang): ?>
            <li>
                <a class="dropdown-item <?php echo $lang === $currentLanguage ? 'active' : ''; ?>"
                    href="?lang=<?php echo $lang; ?>">
                    <?php
                    switch ($lang) {
                        case 'en':
                            echo '<i class="fas fa-flag-usa me-2"></i> English';
                            break;
                        case 'ar':
                            echo '<i class="fas fa-flag me-2"></i> العربية';
                            break;
                    }
                    ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle language selection
        const languageLinks = document.querySelectorAll('.dropdown-item');
        languageLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const lang = this.getAttribute('href').split('=')[1];

                // Update form translations
                fetch('/appointments/get-translations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ language: lang })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update form labels and placeholders
                            Object.keys(data.translations).forEach(key => {
                                const element = document.querySelector(`[data-translate="${key}"]`);
                                if (element) {
                                    element.textContent = data.translations[key];
                                }
                            });
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>