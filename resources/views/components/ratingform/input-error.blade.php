<!-- Component ratingform.input-error  -->
@props(['for'])

<div class="w-full">
    <span
        x-data="{ hasError: '{{ $errors->get($for)[0] ?? '' }}' }"
        x-init="
            $watch('hasError', (value) => {
                if (!value) return;
                const errorDiv = document.getElementsByClassName('invalid-feedback')[0];
                if (errorDiv) {
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
                }
            })
        "
    >
        @error($for)
            <div
                {{ $attributes->merge([
                    'class' => '
                        invalid-feedback
                        flex items-start gap-3
                        rounded-xl
                        border border-red-300
                        bg-red-50
                        px-4 py-3
                        text-sm text-red-700
                    '
                ]) }}
                role="alert"
            >
                <!-- Icon -->
                <div class="shrink-0 pt-0.5">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-red-500"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.451 11.474c.75 1.334-.213 2.997-1.742 2.997H3.548c-1.53 0-2.492-1.663-1.742-2.997L8.257 3.099zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-.993.883L9 8v4a1 1 0 001.993.117L11 12V8a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>

                <!-- Text -->
                <div class="leading-snug">
                    {{ $message }}
                </div>
            </div>
        @enderror
    </span>
</div>
