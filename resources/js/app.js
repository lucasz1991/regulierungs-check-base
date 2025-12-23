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

    function applyUcBottomOffset() {
        const ucRoot = document.querySelector('#usercentrics-cmp-ui');

        const button = ucRoot.shadowRoot.querySelector(
            '#uc-main-dialog'
        );

        button.style.bottom = '80px';
    }
    setTimeout(() => {applyUcBottomOffset();}, 100);
})();