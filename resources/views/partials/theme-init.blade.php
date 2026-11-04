<script>
    // Light is the default. Dark is used only after the person switches to it with the sun/moon button.
    (function () {
        var root = document.documentElement;

        function stored() {
            try { return localStorage.getItem('theme'); } catch (e) { return null; }
        }

        function apply() {
            var theme = stored() === 'dark' ? 'dark' : 'light';
            root.setAttribute('data-theme', theme);
            var meta = document.querySelector('meta[name="theme-color"]');
            if (meta) meta.setAttribute('content', theme === 'light' ? '#f7fbfa' : '#120e0a');
        }

        window.toggleTheme = function () {
            var next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            try { localStorage.setItem('theme', next); } catch (e) {}
            apply();
        };

        apply();
    })();
</script>
