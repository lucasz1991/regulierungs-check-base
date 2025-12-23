import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import mask from '@alpinejs/mask';
import resize from '@alpinejs/resize';
import intersect from '@alpinejs/intersect';
import anchor from '@alpinejs/anchor';
import masonry from 'alpinejs-masonry';
import persist from '@alpinejs/persist'



// Alpine Plugins
Alpine.plugin(collapse);
Alpine.plugin(mask);
Alpine.plugin(resize);
Alpine.plugin(intersect);
Alpine.plugin(anchor);
Alpine.plugin(masonry);
Alpine.plugin(persist);

(function () {
    const isMobile = window.matchMedia('(max-width: 768px)').matches;
    if (!isMobile) return;

    let tries = 0;
    const maxTries = 50; 

    const interval = setInterval(() => {
        tries++;

        const ucRoot = document.querySelector('#usercentrics-cmp-ui');
        if (!ucRoot || !ucRoot.shadowRoot) {
            if (tries >= maxTries) clearInterval(interval);
            return;
        }

        const dialog = ucRoot.shadowRoot.querySelector('#uc-main-dialog');
        if (!dialog) {
            if (tries >= maxTries) clearInterval(interval);
            return;
        }

        dialog.style.bottom = '80px';
        dialog.style.top = 'auto';

        clearInterval(interval);
    }, 100);
})();