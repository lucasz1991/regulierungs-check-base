<div class="app-shell app-page app-auth-layout grid items-start gap-9 py-10 lg:grid-cols-[.85fr_1.15fr] lg:gap-20 lg:py-16">
    <section class="app-auth-intro min-w-0 lg:sticky lg:top-32">
        <div class="mb-7 w-14 lg:mb-10 lg:w-20">
            <x-authentication-card-logo />
        </div>
        <h1 class="app-heading">{{ $title }}</h1>
        <p class="app-muted mt-5 max-w-lg text-sm leading-8 lg:text-base">{{ $description }}</p>
    </section>

    <div class="app-auth-card app-panel min-w-0 p-6 sm:p-8 lg:p-10">
        {{ $form }}
    </div>
</div>
