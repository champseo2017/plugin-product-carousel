<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Tailwind CSS ผ่าน CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="wrap max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Product Carousel Settings</h1>
        <!-- Explanatory card about the importance of domain settings -->
        <div class="bg-white p-4 shadow-md rounded mb-4">
            <h2 class="text-lg font-semibold mb-2">Why Domain Settings Matter?</h2>
            <p class="text-gray-600">
                Domain settings are crucial for ensuring that only authorized sources can access and interact with your plugin. By specifying trusted domains, you enhance security and prevent unauthorized use. This control helps in managing how and where your plugin is utilized, safeguarding your content and user experience.
            </p>
        </div>

        <form method="post" action="options.php" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <?php settings_fields( 'plugin-settings-group' ); ?>
            <?php do_settings_sections( 'plugin-settings-group' ); ?>
            <div id="domains-container" class="mb-4">
                <!-- รายการ domains ที่มีอยู่จะถูกแสดงที่นี่ -->
                <?php
                $domains = get_option('allowed_domains');
                if (!empty($domains)) {
                    foreach ($domains as $domain) {
                        echo '<div class="domain-entry mb-2"><input type="text" name="allowed_domains[]" value="'. esc_attr($domain) .'" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><button type="button" onclick="removeDomain(this)" class="text-red-500">Remove</button></div>';
                    }
                }
                ?>
            </div>
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="addDomain()">Add Domain</button>
            <?php submit_button('', 'primary', 'submit', true, ['id' => 'submit-button']); ?>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            updateSubmitButtonState();
        });
        function addDomain() {
            var container = document.getElementById('domains-container');
            var newDomain = document.createElement('div');
            newDomain.classList.add('domain-entry', 'mb-2');
            newDomain.innerHTML = '<input type="text" name="allowed_domains[]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><button type="button" onclick="removeDomain(this)" class="text-red-500">Remove</button>';
            container.appendChild(newDomain);
            var newInput = newDomain.querySelector('input');
            newInput.addEventListener('input', updateSubmitButtonState);
            updateSubmitButtonState();
        }

        document.addEventListener('DOMContentLoaded', function() {
            var domainInputs = document.querySelectorAll('input[name="allowed_domains[]"]');
            domainInputs.forEach(input => input.addEventListener('input', updateSubmitButtonState));
            updateSubmitButtonState();
        });


        function removeDomain(button) {
            var domainEntry = button.parentNode;
            domainEntry.remove();
        }

        function updateSubmitButtonState() {
            var domains = document.querySelectorAll('input[name="allowed_domains[]"]');
            var isAnyDomainFilled = Array.from(domains).some(input => input.value.trim() !== '');
            document.getElementById('submit-button').disabled = !isAnyDomainFilled;
        }
    </script>
</body>
</html>
