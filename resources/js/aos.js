import AOS from 'aos';

let initialized = false;

const synchronizeAos = () => {
    const isHomepage = document.body?.classList.contains('homepage-without-aos') ?? false;

    if (isHomepage) {
        return;
    }

    if (!initialized) {
        AOS.init();
        initialized = true;

        return;
    }

    AOS.refreshHard();
};

synchronizeAos();

document.addEventListener('livewire:navigated', synchronizeAos);
