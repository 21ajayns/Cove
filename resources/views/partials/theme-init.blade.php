<script>
    // Dark is the default. Light is used only after the person switches to it with the sun/moon button.
    (function () {
        var root = document.documentElement;

        function stored() {
            try { return localStorage.getItem('theme'); } catch (e) { return null; }
        }

        function apply() {
            var theme = stored() === 'light' ? 'light' : 'dark';
            root.setAttribute('data-theme', theme);
            var meta = document.querySelector('meta[name="theme-color"]');
            if (meta) meta.setAttribute('content', theme === 'light' ? '#f0f3f1' : '#0f1214');
        }

        window.toggleTheme = function () {
            var next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            try { localStorage.setItem('theme', next); } catch (e) {}
            apply();
        };

        apply();
    })();
</script>
